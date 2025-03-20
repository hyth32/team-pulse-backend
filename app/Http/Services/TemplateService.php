<?php

namespace App\Http\Services;

use App\Enums\Test\TemplateStatus;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateCreate;
use App\Http\Requests\Template\TemplateUpdate;
use App\Http\Resources\Template\TemplateResource;
use App\Models\Tag;
use App\Models\Template;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TemplateService extends BaseService
{
    /**
     * Получение списка шаблонов
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $query = Template::query();

        $result = self::paginateQuery($query, $request);
        $tests = $result['items']->orderBy('created_at', 'desc');

        return [
            'total' => $result['total'],
            'tests' => TemplateResource::collection($tests->get()),
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
        $test = Template::where(['id' => $uuid])->first();

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
     * Создание шаблона
     * @param TemplateCreate $request
     */
    public function save(TemplateCreate $request)
    {
        $data = $request->validated();

        $test = Template::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => TemplateStatus::getValueFromLabel($data['status']),
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

        return ['message' => 'Шаблон создан'];
    }

    /**
     * Обновление шаблона
     * @param UpdateTestRequest $request
     * @param Request $request
     */
    public function update(string $uuid, TemplateUpdate $request)
    {
        $data = $request->validated();

        $test = Template::findOrFail($uuid);

        if ($test->start_date && Carbon::parse($test->start_date) < now()) {
            abort(403, 'Тест уже начался');
        }

        $test->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'test_status' => TemplateStatus::getValueFromLabel($data['status']),
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
     * Получение шаблона по id
     * @param string $uuid
     * @param Request $request
     */
    public static function view(string $uuid, Request $request)
    {
        $test = Template::findOrFail($uuid);

        if (!$request->user()->tests()->where(['tests.id' => $test->id])->exists()) {
            abort(403, 'Тест недоступен для прохождения');
        }

        return ['test' => TestViewResource::make($test)];
    }

    /**
     * Удаление шаблона по id
     * @param string $uuid
     * @param Request $request
     */
    public function delete(string $uuid, Request $request)
    {
        $test = Template::findOrFail($uuid);

        if (Carbon::parse($test->start_date) < now()) {
            abort(403, 'Тест уже начался');
        }

        $test->delete();

        return ['message' => 'Тест удален'];
    }
}
