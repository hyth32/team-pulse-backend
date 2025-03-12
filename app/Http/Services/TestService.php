<?php

namespace App\Http\Services;

use App\Enums\EntityStatus;
use App\Http\Requests\Test\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Resources\TestTemplateShortResource;
use App\Models\Answer;
use App\Models\AnswerTagPoints;
use App\Models\Group;
use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\QuestionTopic;
use App\Models\Tag;
use App\Models\Test;
use App\Models\TestPeriodicity;
use App\Models\TestQuestion;
use App\Models\UserTest;
use Carbon\Carbon;

class TestService
{
    /**
     * Получение списка тестов
     * @param ListTestRequest $request
     */
    public static function list(ListTestRequest $request)
    {
        $tests = Test::query()
            ->where(['status' => EntityStatus::Active->value()])
            ->offset($request['offset'])
            ->limit($request['limit'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ['tests' => $tests];
    }

    /**
     * Получение списка шаблонов
     * @param ListTestRequest $request
     */
    public static function templateList(ListTestRequest $request)
    {
        $tests = Test::query()
            ->where(['status' => EntityStatus::Active->value()])
            ->offset($request['offset'])
            ->limit($request['limit'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ['tests' => TestTemplateShortResource::collection($tests)];
    }

    /**
     * Сохранение теста
     * @param CreateTestRequest $request
     */
    public function save(CreateTestRequest $request)
    {
        $data = $request->validated();

        $test = Test::create([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
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
            return response()->json(['message' => 'Тест не существует']);
        }

        $data = $request->validated();
        $periodicityTimeframe = null;
        if (isset($data['periodicity']['timeframe'])) {
            $periodicityTimeframe = $data['periodicity']['timeframe'];
            $periodicityFrom = $periodicityTimeframe['from'] ?? null;
            $periodicityTo = $periodicityTimeframe['to'] ?? null;
            $periodicityTimeframe = $periodicityTo - $periodicityFrom;
            // TODO: добавить логику вычисления таймфрейма периодичности
        }
        $periodicity = TestPeriodicity::firstOrCreate([
            'name' => $data['periodicity']['name'],
        ], ['timeframe' => $periodicityTimeframe]);

        $test->update([
            'periodicity' => $periodicity->id,
            'start_date' => Carbon::parse($data['start_date']) ?? null,
            'end_date' => Carbon::parse($data['end_date']) ?? null,
        ]);

        $usersToAssign = [];
        if (isset($data['groups'])) {
            $groups = $data['groups'];
            foreach ($groups as $groupData) {
                $group = Group::where(['name' => $groupData['name']])->first();
                foreach ($group->users as $user) {
                    $usersToAssign[] = $user->id;
                }
            }
        }

        if (isset($data['employees'])) {
            $employees = $data['employees'];
            foreach ($employees as $employee) {
                $employeeId = $employee['id'];
                $usersToAssign[] = $employeeId;
            }
        }

        foreach ($usersToAssign as $userId) {
            UserTest::firstOrCreate([
                'user_id' => $userId,
                'test_id' => $test->id,
                'assignee_id' => $request->user()->id ?? 1,
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
        $test->update($data);

        return $test;
    }

    /**
     * Получение теста по id
     * @param string $uuid
     */
    public static function view(string $uuid)
    {
        return Test::findOrFail($uuid);
    }

    /**
     * Удаление теста по id
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $test = Test::findOrFail($uuid);
        $test->update(['status' => EntityStatus::Deleted->value()]);

        return ['message' => 'Тест удален'];
    }
}
