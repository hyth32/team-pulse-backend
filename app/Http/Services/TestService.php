<?php

namespace App\Http\Services;

use App\Enums\EntityStatus;
use App\Enums\Test\TestCompletionStatus;
use App\Enums\Test\TestStatus;
use App\Enums\User\UserRole;
use App\Http\Requests\Test\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListAssignedGroupsRequest;
use App\Http\Requests\Test\ListAssignedUsersRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Resources\GroupShortResource;
use App\Http\Resources\TestShortResource;
use App\Http\Resources\TestTemplateShortResource;
use App\Http\Resources\TestViewResource;
use App\Http\Resources\UserTestCompletionResource;
use App\Models\Answer;
use App\Models\AnswerTagPoints;
use App\Models\Group;
use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\QuestionTopic;
use App\Models\Tag;
use App\Models\Test;
use App\Models\TestGroup;
use App\Models\TestQuestion;
use App\Models\User;
use App\Models\UserTest;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestService
{
    /**
     * Получение списка тестов
     * @param ListTestRequest $request
     */
    public static function list(ListTestRequest $request)
    {
        $user = $request->user();

        $query = null;
        if (in_array($user->role, UserRole::adminRoles())) {
            $query = Test::withoutGlobalScopes()->whereHas('assignedUsers');
        } else {
            $query = $user->tests();
        }

        $total = $query->count();
        $tests = $query
            ->where(['status' => EntityStatus::Active->value()])
            ->offset($request['offset'])
            ->limit($request['limit'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'total' => $total,
            'tests' => TestShortResource::collection($tests),
        ];
    }

    /**
     * Получение списка шаблонов
     * @param ListTestRequest $request
     */
    public static function templateList(ListTestRequest $request)
    {
        $query = Test::query()->where(['status' => EntityStatus::Active->value()]);

        $total = $query->count();
        $tests = $query
            ->offset($request['offset'])
            ->limit($request['limit'])
            ->orderBy('created_at', 'desc');

        return [
            'total' => $total,
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

        $total = $query->count();
        $users = $query->offset($request['offset'])->limit([$request['limit']]);

        return [
            'total' => $total,
            'users' => UserTestCompletionResource::collection($users->get()->map(function ($user) use ($test) {
                return new UserTestCompletionResource($user, $test->id);
            })),
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

        $total = $query->count();
        $groups = $query->offset($request['offset'])->limit($request['limit']);

        return [
            'total' => $total,
            'groups' => GroupShortResource::collection($groups->get()),
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
                $topic = QuestionTopic::firstOrCreate([
                    'name' => $testData['topic'],
                ]);

                if (isset($testData['questions']) && count($testData['questions']) > 0) {
                    $questionsData = $testData['questions'];
                    foreach ($questionsData as $questionData) {
                        $question = Question::create([
                            'text' => $questionData['name'],
                            'type' => $questionData['type'],
                            'topic_id' => $topic->id,
                        ]);

                        TestQuestion::create([
                            'test_id' => $test->id,
                            'question_id' => $question->id,
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
     */
    public function update(string $uuid, UpdateTestRequest $request)
    {
        $data = $request->validated();

        $test = Test::findOrFail($uuid);

        if ($test->start_date->isPast()) {
            throw new HttpException('Тест уже начался', 403);
        }

        $test->update($data);

        return ['message' => 'Тест обновлен'];
    }

    /**
     * Получение теста по id
     * @param string $uuid
     */
    public static function view(string $uuid)
    {
        $test = Test::findOrFail($uuid);

        return ['test' => TestViewResource::make($test)];
    }

    /**
     * Удаление теста по id
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $test = Test::findOrFail($uuid);

        if ($test->start_date->isPast()) {
            throw new HttpException('Тест уже начался', 403);
        }

        $test->update(['status' => EntityStatus::Deleted->value()]);

        return ['message' => 'Тест удален'];
    }
}
