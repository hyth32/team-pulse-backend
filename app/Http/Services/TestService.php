<?php

namespace App\Http\Services;

use App\Enums\EntityStatus;
use App\Enums\Test\TestCompletionStatus;
use App\Enums\Test\TestStatus;
use App\Enums\User\UserRole;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Test\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListAssignedGroupsRequest;
use App\Http\Requests\Test\ListAssignedUsersRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Resources\GroupShortResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\TestShortResource;
use App\Http\Resources\TestTemplateShortResource;
use App\Http\Resources\TestViewResource;
use App\Http\Resources\UserTestCompletionResource;
use App\Models\Answer;
use App\Models\AnswerTagPoints;
use App\Models\Group;
use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\Test;
use App\Models\TestGroup;
use App\Models\TestQuestion;
use App\Models\User;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nette\NotImplementedException;

class TestService extends BaseService
{
    /**
     * Получение списка тестов
     * @param ListTestRequest $request
     */
    public static function list(ListTestRequest $request)
    {
        $user = $request->user();

        $query = in_array($user->role, UserRole::adminRoles())
            ? Test::withoutGlobalScopes()->whereHas('assignedUsers')
            : $user->tests();

        $query->where(['status' => EntityStatus::Active->value()]);

        $result = self::paginateQuery($query, $request);
        $tests = $result['items']->orderBy('created_at', 'desc');

        return [
            'total' => $result['total'],
            'tests' => TestShortResource::collection($tests->get()),
        ];
    }

    /**
     * Получение списка шаблонов
     * @param ListTestRequest $request
     */
    public static function templateList(ListTestRequest $request)
    {
        $query = Test::query()->where(['status' => EntityStatus::Active->value()]);

        $result = self::paginateQuery($query, $request);
        $tests = $result['items']->orderBy('created_at', 'desc');

        return [
            'total' => $result['total'],
            'tests' => TestTemplateShortResource::collection($tests->get()),
        ];
    }

    /**
     * Получение списка назначенных пользователей
     * @param ListAssignedUsersRequest $request
     */
    public static function listAssignedUsers(string $uuid, ListAssignedUsersRequest $request)
    {
        $test = Test::findOrFail($uuid);
        $query = $test->assignedUsers();

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'users' => UserTestCompletionResource::collection($result['items']->get()
                ->map(fn ($user) => new UserTestCompletionResource($user, $test->id))),
        ];
    }

    /**
     * Получение списка назначенных групп
     * @param ListAssignedGroupsRequest $request
     */
    public static function listAssignedGroups(string $uuid, ListAssignedGroupsRequest $request)
    {
        $test = Test::findOrFail($uuid);
        $query = $test->groups();

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'groups' => GroupShortResource::collection($result['items']->get()),
        ];
    }

    /**
     * Сохранение теста
     * @param CreateTestRequest $request
     */
    public function save(CreateTestRequest $request)
    {
        $data = $request->validated();

        $test = Test::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'test_status' => TestStatus::getValueFromLabel($data['status']),
            'author_id' => $request->user()->id,
        ]);

        if (isset($data['tests'])) {
            $testsData = $data['tests'];

            foreach ($testsData as $testData) {
                $topic = Topic::firstOrCreate([
                    'name' => $testData['topic'],
                ]);

                if (isset($testData['questions']) && count($testData['questions']) > 0) {
                    $questionsData = $testData['questions'];
                    foreach ($questionsData as $questionData) {
                        $question = Question::create([
                            'text' => $questionData['name'],
                            'type' => $questionData['type'],
                        ]);

                        TestQuestion::create([
                            'test_id' => $test->id,
                            'question_id' => $question->id,
                            'topic_id' => $topic->id,
                        ]);

                        if (isset($questionData['tags']) && count($questionData['tags']) > 0) {
                            $questionTags = $questionData['tags'];
                            foreach ($questionTags as $tagName) {
                                $tag = Tag::firstOrCreate([
                                    'name' => trim($tagName),
                                ]);

                                QuestionTag::create([
                                    'question_id' => $question->id,
                                    'tag_id' => $tag->id,
                                ]);
                            }
                        }
                    }

                    if (isset($questionData['answers']) && count($questionData['answers']) > 0) {
                        $questionAnswers = $questionData['answers'];
                        foreach ($questionAnswers as $answerData) {
                            $answer = Answer::create([
                                'text' => $answerData['text'],
                                'question_id' => $question->id,
                            ]);

                            if (isset($answerData['points']) && count($answerData['points']) > 0) {
                                foreach ($answerData['points'] as $answerPointsData) {
                                    AnswerTagPoints::create([
                                        'answer_id' => $answer->id,
                                        'tag_id' => $tag->id,
                                        'point_count' => $answerPointsData['points'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return ['message' => 'Тест создан'];
    }

    /**
     * Назначение теста
     * @param string $uuid
     * @param AssignTestRequest $request
     */
    public function assign(string $uuid, AssignTestRequest $request)
    {
        $test = Test::findOrFail($uuid);
        if (!$test) {
            return ['message' => 'Тест не существует'];
        }

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

        $isAssignToAll = $data['assignToAll'];

        $usersToAssign = $isAssignToAll
            ? User::query()
                ->where(['status' => EntityStatus::Active->value()])
                ->whereIn('role', UserRole::adminRoles())
                ->pluck('id')
                ->toArray()
            : [];

        if (isset($data['groups']) && !$isAssignToAll) {
            $groups = $data['groups'];
            foreach ($groups as $groupId) {
                $group = Group::find($groupId);
                TestGroup::firstOrCreate([
                    'test_id' => $test->id,
                    'group_id' => $group->id,
                ]);

                foreach ($group->users as $user) {
                    $usersToAssign[] = $user->id;
                }
            }
        }

        if (isset($data['employees']) && !$isAssignToAll) {
            $employees = $data['employees'];
            foreach ($employees as $employeeId) {
                $usersToAssign[] = $employeeId;
            }
        }

        foreach ($usersToAssign as $userId) {
            UserTest::firstOrCreate([
                'user_id' => $userId,
                'test_id' => $test->id,
                'assigner_id' => $request->user()->id,
                'completion_status' => TestCompletionStatus::NotPassed->value(),
            ]);
        }

        return ['message' => 'Тест назначен'];
    }

    /**
     * Обновление теста
     * @param UpdateTestRequest $request
     * @param Request $request
     */
    public function update(string $uuid, UpdateTestRequest $request)
    {
        $data = $request->validated();

        $test = Test::findOrFail($uuid);

        if ($test->start_date->isPast()) {
            abort(403, 'Тест уже начался');
        }

        $test->update($data);

        return ['message' => 'Тест обновлен'];
    }

    /**
     * Получение теста по id
     * @param string $uuid
     */
    public static function view(string $uuid, Request $request)
    {
        $test = Test::findOrFail($uuid);
        $user = $request->user();

        if (!$user->tests()->where(['tests.id' => $test->id])->exists()) {
            abort(403, 'Тест недоступен для прохождения');
        }

        return ['test' => TestViewResource::make($test)];
    }

    /**
     * Получение вопросов по ID топика
     * @param string $uuid
     * @param string $topicUuid
     * @param Request $request
     */
    public static function listTopicQuestions(string $uuid, string $topicUuid, BaseListRequest $request)
    {
        $user = $request->user();
        $test = Test::where('id', $uuid)->first();

        if (!$test) {
            abort(400, 'Тест не найден');
        }

        if (!$user->tests()->where(['tests.id' => $test->id])->exists()) {
            abort(403, 'Тест недоступен для прохождения');
        }

        if (!$test->topics()->where(['topics.id' => $topicUuid])->exists()) {
            abort(400, 'Тема не найдена');
        }

        $query = $test->questions()
            ->whereHas('topics', function ($query) use ($topicUuid) {
                $query->where(['topics.id' => $topicUuid]);
            });

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'questions' => QuestionResource::collection($result['items']->get()),
        ];
    }

    /**
     * Прохождение теста
     * @param string $uuid
     * @param Request $request
     */
    public function solve(string $uuid, Request $request)
    {
        $test = Test::firstOrFail($uuid);
        $user = $request->user();

        if (!$user->tests()->where(['tests.id' => $test->id])->exists()) {
            abort(403, 'Тест недоступен для прохождения');
        }

        throw new NotImplementedException();
    }

    /**
     * Удаление теста по id
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $test = Test::findOrFail($uuid);

        if ($test->start_date->isPast()) {
            abort(403, 'Тест уже начался');
        }

        $test->update(['status' => EntityStatus::Deleted->value()]);

        return ['message' => 'Тест удален'];
    }
}
