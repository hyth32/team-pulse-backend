<?php

namespace App\Http\Services;

use App\Enums\Test\TopicCompletionStatus;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Requests\Test\TestSolution;
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

        return [
            'total' => $result['total'],
            'users' => TestCompletionResource::collection($result['items']->get()),
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

        return ['message' => 'Тест назначен'];
    }

    public function saveSolution(TestSolution $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $test = $user->assignedTests()->where(['id' => $data['testId']])->first();

        foreach ($data['questions'] as $questionData) {
            $question = Question::where(['id' => $questionData['questionId']]);
            $userAnswerData = collect($questionData['answers'])
                ->map(function ($answerText) use ($test, $user, $question) {
                    return [
                        'assigned_test_id' => $test->id,
                        'user_id' => $user->id,
                        'question_id' => $question->id,
                        'answer' => $answerText,
                    ];
                });

            UserAnswer::upsert($userAnswerData);
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
}
