<?php

namespace App\Http\Services;

use App\Enums\Answer\AnswerType;
use App\Enums\Test\TopicCompletionStatus;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Requests\Test\TestSolution;
use App\Http\Requests\Test\TestSolve;
use App\Http\Requests\User\SubjectStats;
use App\Http\Resources\AssignedTest\AssignedTestResource;
use App\Http\Resources\Topic\TopicResource;
use App\Http\Resources\User\TestCompletionResource;
use App\Http\Resources\User\UserTestListResource;
use App\Models\Answer;
use App\Models\AssignedTest;
use App\Models\Question;
use App\Models\Template;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserTestCompletion;
use App\Models\UserTopicCompletion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public static function listUserTests(string $userUuid, BaseListRequest $request)
    {
        $user = User::where(['id' => $userUuid])->first();
        $query = UserTestCompletion::query()
            ->where(['user_id' => $user->id])
            ->with('user')
            ->with('test');

        $result = self::paginateQuery($query, $request);
            
        return [
            'total' => $result['total'],
            'tests' => UserTestListResource::collection($result['items']->get()),
        ];
    }

    public static function listSubjectStats(SubjectStats $request)
    {
        $data = $request->validated();
        $subjectId = $data['subjectId'];

        $query = UserAnswer::query()
            ->with('question')
            ->with('test', fn ($testQuery) => $testQuery->where(['subject_id' => $subjectId]))
            ->whereHas('user', fn ($userQuery) => 
                    $userQuery->with('assignedTests', fn ($testQuery) => 
                        $testQuery->where(['subject_id' => $subjectId])
                            ->wherePivot(['completion_status' => TopicCompletionStatus::Passed->value()])));
        
        $topicQuestions = collect($query->get())->map(function ($userAnswer) {
            $question = $userAnswer->question;
            $topic = $question->topic->name;
            $tagPoints = [];

            if (in_array($question->answer_type, [AnswerType::SingleChoice->value(), AnswerType::MultipleChoice->value()])) {
                $answer = Answer::query()
                    ->where(['text' => $userAnswer->answer])
                    ->with('tags')
                    ->first();

                $tagPoints = collect($answer->tags()->get())
                    ->map(function ($tag) {
                        return [
                            'tagName' => $tag->name,
                            'points' => $tag->pivot->point_count,
                        ];
                    })
                    ->toArray();
            }

            return [
                'topic' => $topic,
                'tagPoints' => $tagPoints,
            ];
        });

        $groupedTopics = $topicQuestions->groupBy('topic')->map(function ($group) {
            $aggregatedTags = collect($group)->flatMap(fn ($item) => $item['tagPoints'])
                ->groupBy('tagName')
                ->map(function ($tagGroup) {
                    $points = $tagGroup->pluck('points');
                    return [
                        'tagName' => $tagGroup->first()['tagName'],
                        'minPoints' => (float)$points->min(),
                        'maxPoints' => (float)$points->max(),
                        'averagePoints' => (float)$points->avg(),
                    ];
                })->values()->toArray();
    
            return [
                'topic' => $group->first()['topic'],
                'tagPoints' => $aggregatedTags,
            ];
        })->values()->toArray();

        return ['stats' => $groupedTopics];
    }

    /**
     * Получение списка назначенных пользователей
     * @param BaseListRequest $request
     */
    public static function listAssignedUsers(string $uuid, BaseListRequest $request)
    {
        $testCompletions = UserTestCompletion::query()
            ->where(['assigned_test_id' => $uuid])
            ->with('user')
            ->get();

        $users = collect($testCompletions)->map(fn ($completion) => $completion->user)->unique('id');

        $users = $users->map(function ($user) use ($uuid) {
            return new TestCompletionResource($user, $uuid);
        });

        return [
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
            DB::transaction(function () use ($data, $request) {
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
            });

            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => $e->getMessage(),
            ];
        }
    }

    public function saveSolution(TestSolve $request)
    {
        $data = $request->validated();
        $user = $request->user();

        try {
            DB::transaction(function () use ($user, $data) {
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
                        UserAnswer::create([
                            'assigned_test_id' => $userAnswerData['assigned_test_id'],
                            'user_id' => $userAnswerData['user_id'],
                            'question_id' => $userAnswerData['question_id'],
                            'answer' => $userAnswerData['answer']
                        ]);
                    }
                }
        
                $userTopics = $test->topicCompletions()->where(['user_id' => $user->id]);
                $userTopicsCount = $userTopics->count();
            
                UserTopicCompletion::query()
                    ->where([
                        'user_id' => $user->id,
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
                        'user_id' => $user->id,
                        'assigned_test_id' => $test->id,
                    ])
                    ->update(['completion_status' => $testCompletionStatus->value()]);
            });
    
            return ['message' => 'Результаты сохранены'];
        } catch (\Exception $e) {
            return ['message' => 'Произошла ошибка: ' . $e->getMessage()];
        }
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

                if (in_array($question->answer_type, [AnswerType::SingleChoice->value(), AnswerType::MultipleChoice->value()])) {
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
                            'isRight' => $answer->isRight,
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
                })
                ->unique(fn ($question) => $question['text']);

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
