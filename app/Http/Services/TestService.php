<?php

namespace App\Http\Services;

use App\Enums\Test\TopicCompletionStatus;
use App\Enums\User\UserRole;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Resources\AssignedTest\AssignedTestResource;
use App\Http\Resources\Topic\TopicResource;
use App\Http\Resources\User\UserTestCompletionResource;
use App\Models\AssignedTest;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;

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
            $query = $user->assignedTests();
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
        $test = AssignedTest::findOrFail($uuid);
        $query = $test->users();

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'users' => UserTestCompletionResource::collection($result['items']->get()),
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

        $topic = $test->template()->topics()->where(['topics.id' => $topicUuid]);
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
        ]);

        if ($data['assignToAll']) {
            $usersQuery = User::where(['role' => UserRole::Employee->value()]);
        } else {
            $usersQuery = User::query()
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
                $user->assignedTests()->attach($test->id, [
                    'topic_id' => $topicId,
                    'completion_status' => TopicCompletionStatus::NotPassed->value(),
                ]);
            }
        }

        return ['message' => 'Тест назначен'];
    }
}
