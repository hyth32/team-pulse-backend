<?php

namespace App\Http\Services;

use App\Enums\Test\TestCompletionStatus;
use App\Enums\Test\TestStatus;
use App\Enums\User\UserRole;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Requests\Test\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Resources\GroupShortResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestTemplateResource;
use App\Http\Resources\TestViewResource;
use App\Http\Resources\UserTestCompletionResource;
use App\Models\AssignedTest;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\Test;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

class TestService extends BaseService
{
    /**
     * Получение списка тестов
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $user = $request->user();

        $query = in_array($user->role, UserRole::adminRoles())
            ? AssignedTest::withoutGlobalScopes()->whereHas('assignedUsers')
            : $user->tests();
        $query->orderBy('created_at', 'desc');

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'tests' => TestResource::collection($result['items']->get()),
        ];
    }

    /**
     * Получение списка назначенных пользователей
     * @param BaseListRequest $request
     */
    public static function listAssignedUsers(string $uuid, BaseListRequest $request)
    {
        $test = AssignedTest::findOrFail($uuid);
        $query = $test->assignedUsers()->whereHas('tests', fn ($q) => $q->where(['test_id' => $test->id]));

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'users' => UserTestCompletionResource::collection($result['items']->get()),
        ];
    }

    /**
     * Получение списка назначенных групп
     * @param BaseListRequest $request
     */
    public static function listAssignedGroups(string $uuid, BaseListRequest $request)
    {
        $test = AssignedTest::findOrFail($uuid);
        $query = $test->groups();

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'groups' => GroupShortResource::collection($result['items']->get()),
        ];
    }

    /**
     * Назначение шаблона
     * @param string $uuid
     * @param TemplateAssign $request
     */
    public function assign(string $uuid, TemplateAssign $request)
    {
        $test = AssignedTest::findOrFail($uuid);
        $data = $request->validated();

        $test->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $data['frequency'],
            'start_date' => Carbon::parse($data['startDate']),
            'end_date' => Carbon::parse($data['endDate']) ?? null,
            'subject_id' => $data['subjectId'] ?? null,
            'is_anonymous' => $data['isAnonymous'],
        ]);

        if ($data['assignToAll']) {
            $usersQuery = User::where(['role' => UserRole::Employee->value()]);
        } else {
            $usersQuery = User::query()
                ->when(isset($data['groups']) && filled($data['groups']), function ($q) use ($data) {
                    $q->whereHas('groups', fn ($q) => $q->whereIn('id', $data['groups']));
                })
                ->when(isset($data['employees']) && filled($data['employees']), function ($q) use ($data) {
                    $q->whereIn('id', $data['employees']);
                });
        }

        $userIds = $usersQuery->pluck('id')->toArray();
        $topicIds = $test->topics()->pluck('id')->toArray();

        $syncData = [];
        foreach ($userIds as $userId) {
            foreach ($topicIds as $topicId) {
                $syncData[] = [
                    'user_id' => $userId,
                    'assigner_id' => $request->user()->id,
                    'topic_id' => $topicId,
                    'completion_status' => TestCompletionStatus::NotPassed->value(),
                ];
            }
        }
        $test->assignedUsers()->sync($syncData);

        if (isset($data['groups']) && filled($data['groups']) && !$data['assignToAll']) {
            $test->groups()->sync($data['groups']);
        } else {
            $test->groups()->detach();
        }

        return ['message' => 'Тест назначен'];
    }
}
