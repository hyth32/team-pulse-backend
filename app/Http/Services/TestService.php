<?php

namespace App\Http\Services;

use App\Enums\Test\TestCompletionStatus;
use App\Enums\Test\TestStatus;
use App\Enums\User\UserRole;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Test\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Resources\GroupShortResource;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\TestShortResource;
use App\Http\Resources\TestTemplateResource;
use App\Http\Resources\TestViewResource;
use App\Http\Resources\UserTestCompletionResource;
use App\Models\Topic;
use App\Models\Tag;
use App\Models\Test;
use App\Models\User;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            ? Test::withoutGlobalScopes()->whereHas('assignedUsers')
            : $user->tests();
        $query->orderBy('created_at', 'desc');

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'tests' => TestShortResource::collection($result['items']->get()),
        ];
    }

    /**
     * Получение списка шаблонов
     * @param BaseListRequest $request
     */
    public static function templateList(BaseListRequest $request)
    {
        $query = Test::query();

        $result = self::paginateQuery($query, $request);
        $tests = $result['items']->orderBy('created_at', 'desc');

        return [
            'total' => $result['total'],
            'tests' => TestTemplateResource::collection($tests->get()),
        ];
    }

    /**
     * Получение списка назначенных пользователей
     * @param BaseListRequest $request
     */
    public static function listAssignedUsers(string $uuid, BaseListRequest $request)
    {
        $test = Test::findOrFail($uuid);
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

        foreach ($data['tests'] as $testData) {
            $topic = Topic::firstOrCreate(['name' => $testData['topic']]);

            if (isset($testData['questions']) && filled($testData['questions'])) {
                foreach ($testData['questions'] as $questionData) {
                    $question = $test->questions()->create([
                        'text' => $questionData['name'],
                        'type' => $questionData['type'],
                    ], ['topic_id' => $topic->id]);

                    if (isset($questionData['tags']) && filled($questionData['tags'])) {
                        $tags = collect($questionData['tags'])->map(function ($tagName) {
                            return Tag::firstOrCreate(['name' => trim($tagName)]);
                        });

                        $question->tags()->sync($tags->pluck('id'));
                    }
                }

                if (isset($questionData['answers']) && filled($questionData['answers'])) {
                    $answers = collect($questionData['answers'])->map(function ($answerData) use ($question) {
                        return $question->answers()->create([
                            'text' => $answerData['text'],
                        ]);
                    });

                    $answers->each(function ($answer, $index) use ($questionData) {
                        $answerData = $questionData['answers'];
                        if (isset($answerData[$index]['points']) && filled($answerData[$index]['points'])) {
                            $points = collect($answerData[$index]['points'])
                                ->mapWithKeys(function ($pointData) {
                                    $tag = Tag::firstOrCreate(['name' => $pointData['name']]);
                                    return [$tag->id => ['point_count' => $pointData['points']]];
                                });

                            $answer->tags()->sync($points);
                        }
                    });
                }
            }
        }

        return ['message' => 'Тест создан'];
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

        if ($test->start_date && Carbon::parse($test->start_date) < now()) {
            abort(403, 'Тест уже начался');
        }

        $test->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'test_status' => TestStatus::getValueFromLabel($data['status']),
            'author_id' => $request->user()->id,
        ]);

        foreach ($data['tests'] as $testData) {
            $topic = Topic::firstOrCreate(['name' => $testData['topic']]);

            if (isset($testData['questions']) && filled($testData['questions'])) {
                foreach ($testData['questions'] as $questionData) {
                    $question = $test->questions()->findOrCreate([
                        'text' => $questionData['name'],
                        'type' => $questionData['type'],
                    ], ['topic_id' => $topic->id]);

                    if (isset($questionData['tags']) && filled($questionData['tags'])) {
                        $tags = collect($questionData['tags'])->map(function ($tagName) {
                            return Tag::firstOrCreate(['name' => trim($tagName)]);
                        });

                        $question->tags()->sync($tags->pluck('id'));
                    } else {
                        $question->tags()->detach();
                    }
                }

                if (isset($questionData['answers']) && filled($questionData['answers'])) {
                    $answers = collect($questionData['answers'])->map(function ($answerData) use ($question) {
                        return $question->answers()->findOrCreate([
                            'text' => $answerData['text'],
                        ]);
                    });

                    $answers->each(function ($answer, $index) use ($questionData) {
                        $answerData = $questionData['answers'];
                        if (isset($answerData[$index]['points']) && filled($answerData[$index]['points'])) {
                            $points = collect($answerData[$index]['points'])
                                ->mapWithKeys(function ($pointData) {
                                    $tag = Tag::firstOrCreate(['name' => $pointData['name']]);
                                    return [$tag->id => ['point_count' => $pointData['points']]];
                                });

                            $answer->tags()->sync($points);
                        } else {
                            $answer->tags()->detach();
                        }
                    });
                } else {
                    $question->answers()->detach();
                }
            }
        }

        return ['message' => 'Тест обновлен'];
    }

    /**
     * Назначение теста
     * @param string $uuid
     * @param AssignTestRequest $request
     */
    public function assign(string $uuid, AssignTestRequest $request)
    {
        $test = Test::findOrFail($uuid);
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

    /**
     * Получение теста по id
     * @param string $uuid
     * @param Request $request
     */
    public static function view(string $uuid, Request $request)
    {
        $test = Test::findOrFail($uuid);

        if (!$request->user()->tests()->where(['tests.id' => $test->id])->exists()) {
            abort(403, 'Тест недоступен для прохождения');
        }

        return ['test' => TestViewResource::make($test)];
    }

    /**
     * Получение вопросов по ID топика
     * @param string $uuid
     * @param string $topicUuid
     * @param BaseListRequest $request
     */
    public static function listTopicQuestions(string $uuid, string $topicUuid, BaseListRequest $request)
    {
        $test = Test::where(['id' => $uuid])->first();

        if (!$test) {
            abort(400, 'Тест не найден');
        }

        if (!$request->user()->tests()->where(['tests.id' => $test->id])->exists()) {
            abort(403, 'Тест недоступен для прохождения');
        }

        if (!$test->topics()->where(['topics.id' => $topicUuid])->exists()) {
            abort(400, 'Тема не найдена');
        }

        $query = $test->questions()
            ->whereHas('topics', fn ($query) => $query->where(['topics.id' => $topicUuid]));

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
     * @param Request $request
     */
    public function delete(string $uuid, Request $request)
    {
        $test = Test::findOrFail($uuid);

        if (Carbon::parse($test->start_date) < now()) {
            abort(403, 'Тест уже начался');
        }

        $test->delete();

        return ['message' => 'Тест удален'];
    }
}
