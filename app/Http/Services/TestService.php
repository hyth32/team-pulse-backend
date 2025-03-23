<?php

namespace App\Http\Services;

use App\Enums\Answer\AnswerType;
use App\Enums\Test\TopicCompletionStatus;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Requests\Test\TestSolution;
use App\Http\Requests\Test\TestSolve;
use App\Http\Resources\AssignedTest\AssignedTestResource;
use App\Http\Resources\Topic\TopicResource;
use App\Http\Resources\User\TestCompletionResource;
use App\Models\AssignedTest;
use App\Models\Question;
use App\Models\Template;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserTestCompletion;
use App\Models\UserTopicCompletion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TestService extends BaseService
{
    /**
     * Получение списка тестов
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        if ($isAdmin) {
            $query = AssignedTest::query();
        } else {
            $query = $user->assignedTests()
                ->where('start_date', '<', now())
                ->wherePivot('completion_status', '!=', 2);
        }

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'tests' => AssignedTestResource::collection($result['items']->get()),
        ];
    }

    /**
     * Получение списка назначенных пользователей
     * @param BaseListRequest $request
     */
    public static function listAssignedUsers(string $uuid, BaseListRequest $request)
    {
        $test = AssignedTest::findOrFail($uuid)->first();

        $query = $test->users();

        $result = self::paginateQuery($query, $request);

        $users = $result['items']->get()->map(function ($user) use ($uuid) {
            return new TestCompletionResource($user, $uuid);
        });

        return [
            'total' => $result['total'],
            'users' => TestCompletionResource::collection($users),
        ];
    }

    /**
     * Получение вопросов по ID топика
     * @param string $uuid
     * @param string $topicUuid
     * @param BaseListRequest $request
     */
    public static function listTopicQuestions(string $uuid, string $topicUuid, BaseListRequest $request)
    {
        $test = AssignedTest::where(['id' => $uuid])->first();

        if (!$test) {
            abort(400, 'Тест не найден');
        }

        $topic = $test->template->topics()->where(['topics.id' => $topicUuid]);
        if (!$topic->exists()) {
            abort(400, 'Тема не найдена');
        }

        return ['topic' => TopicResource::make($topic->first()),];
    }

    /**
     * Назначение шаблона
     * @param TemplateAssign $request
     */
    public function assign(TemplateAssign $request)
    {
        $data = $request->validated();

        try {
            $template = Template::findOrFail($data['templateId']);

            $test = AssignedTest::firstOrCreate([
                'template_id' => $template->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'frequency' => $data['frequency'],
                'start_date' => Carbon::parse($data['startDate']),
                'end_date' => Carbon::parse($data['endDate']) ?? null,
                'subject_id' => $data['subjectId'] ?? null,
                'is_anonymous' => $data['isAnonymous'],
                'late_result' => $data['lateResult'],
                'assigner_id' => $request->user()->id,
                'test_status' => TopicCompletionStatus::NotPassed->value(),
            ]);
    
            $usersQuery = User::query();
    
            if (!$data['assignToAll']) {
                $usersQuery = $usersQuery
                    ->when(isset($data['groupIds']) && filled($data['groupIds']), function ($q) use ($data) {
                        $q->whereHas('groups', fn ($q) => $q->whereIn('id', $data['groupIds']));
                    })
                    ->when(isset($data['employeeIds']) && filled($data['employeeIds']), function ($q) use ($data) {
                        $q->whereIn('id', $data['employeeIds']);
                    });
            }
    
            $users = $usersQuery->get();
            
            foreach ($users as $user) {
                $topicIds = $template->topics()->pluck('id')->toArray();
            
                foreach ($topicIds as $topicId) {
                    UserTopicCompletion::updateOrCreate([
                            'user_id' => $user->id,
                            'assigned_test_id' => $test->id,
                            'topic_id' => $topicId,
                        ],
                        ['completion_status' => TopicCompletionStatus::NotPassed->value()]
                    );
                }
            }
    
            $test->users()->sync($users, ['completion_status' => TopicCompletionStatus::NotPassed->value()]);

            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => $e,
            ];
        }
    }

    public function saveSolution(TestSolve $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $test = $user->assignedTests()->where(['id' => $data['testId']])->first();

        foreach ($data['questions'] as $questionData) {
            $question = Question::where(['id' => $questionData['questionId']])->first();
            $userAnswersData = collect($questionData['answer'])
                ->map(function ($answerText) use ($test, $user, $question) {
                    return [
                        'assigned_test_id' => $test->id,
                        'user_id' => $user->id,
                        'question_id' => $question->id,
                        'answer' => $answerText,
                    ];
                })->toArray();

            foreach ($userAnswersData as $userAnswerData) {
                UserAnswer::updateOrCreate($userAnswerData);
            }
        }

        $userTopics = $test->topicCompletions()->where(['user_id' => $request->user()->id]);
        $userTopicsCount = $userTopics->count();
    
        UserTopicCompletion::query()
            ->where([
                'user_id' => $request->user()->id,
                'assigned_test_id' => $test->id,
                'topic_id' => $data['topicId'],
            ])
            ->update(['completion_status' => TopicCompletionStatus::Passed->value()]);
        
        $completedTopicsCount = $userTopics->where(['completion_status' => TopicCompletionStatus::Passed->value()])->count();

        $testCompletionStatus = $userTopicsCount === $completedTopicsCount 
            ? TopicCompletionStatus::Passed
            : TopicCompletionStatus::InProgress;

        UserTestCompletion::query()
            ->where([
                'user_id' => $request->user()->id,
                'assigned_test_id' => $test->id,
            ])
            ->update(['completion_status' => $testCompletionStatus->value()]);

        return ['message' => 'Результаты сохранены'];
    }

    public static function solution(TestSolution $request)
    {
        $data = $request->validated();
        
        $test = AssignedTest::where(['id' => $data['testId']])->first();
        $user = User::where(['id' => $data['userId']])->first();

        $userAnswers = UserAnswer::where([
            'assigned_test_id' => $test->id,
            'user_id' => $user->id,
        ])->get();

        $answerPoints = collect($userAnswers)
            ->map(function ($answerData) {
                $question = Question::where(['id' => $answerData['question_id']])->first();
                $topicName = $question->topic->name;

                $answers = $question->userAnswers()->pluck('answer')->toArray();

                if (in_array($question->answer_type, [AnswerType::SingleChoice->value(), AnswerType::MultipleChoice])) {
                    $answers = collect($answers)->map(function ($answerText) use ($question) {
                        $answer = $question->answers()->where(['text' => $answerText])->first();
                        $answerTagPoints = collect($answer?->tags()->get())
                            ->map(function ($tag) {
                                return [
                                    'name' => $tag?->name,
                                    'points' => $tag?->pivot->point_count,
                                ];
                            });
                        return [
                            'text' => $answer->text,
                            'points' => $answerTagPoints,
                        ];
                    })->toArray();
                } else {
                    $answers = collect($answers)->map(fn ($answerText) => ['text' => $answerText]);
                }

                return [
                    'name' => $topicName,
                    'questions' => [[
                        'text' => $question->text,
                        'tags' => $question?->tags()?->pluck('name')->toArray(),
                        'answers' => $answers,
                    ]],
                ];
            })
            ->groupBy('name')
            ->map(function ($groupedData, $topicName) {
                $questions = $groupedData->flatMap(function ($item) {
                    return $item['questions'];
                });

                return [
                    'name' => $topicName,
                    'questions' => $questions->values()->all(),
                ];
            })
            ->values()
            ->all();

        $solutionData = [
            'name' => $test->name,
            'description' => $test->description,
            'topics' => $answerPoints,
        ];

        return ['solution' => $solutionData];
    }
}
