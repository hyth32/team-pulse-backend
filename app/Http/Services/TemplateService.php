<?php

namespace App\Http\Services;

use App\Enums\Test\TemplateStatus;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateCreate;
use App\Http\Requests\Template\TemplateUpdate;
use App\Http\Resources\Template\TemplateResource;
use App\Http\Resources\Topic\TopicResource;
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
        $templates = $result['items']->orderBy('created_at', 'desc');

        return [
            'total' => $result['total'],
            'templates' => TemplateResource::collection($templates->get()),
        ];
    }

    /**
     * Создание шаблона
     * @param TemplateCreate $request
     */
    public function save(TemplateCreate $request)
    {
        $data = $request->validated();

        $template = Template::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => TemplateStatus::getValueFromLabel($data['status']),
            'author_id' => $request->user()->id,
        ]);

        foreach ($data['topics'] as $topicData) {
            $topic = $template->topics()->firstOrCreate(['name' => $topicData['name']]);

            foreach ($topicData['questions'] as $questionData) {
                $question = $topic->questions()->create([
                    'text' => $questionData['text'],
                    'answer_type' => $questionData['answerType'],
                ]);

                if (isset($questionData['tags']) && filled($questionData['tags'])) {
                    $tags = collect($questionData['tags'])->map(fn ($tagName) => Tag::firstOrCreate(['name' => $tagName]));
                    $question->tags()->sync($tags->pluck('id'));
                }

                if (isset($questionData['answers']) && filled($questionData['answers'])) {
                    foreach ($questionData['answers'] as $answerData) {
                        $answer = $question->answers()->create(['text' => $answerData['text']]);

                        if (isset($answerData['points']) && filled($answerData['points'])) {
                            $tagData = [];
                            foreach ($answerData['points'] as $pointData) {
                                $tag = Tag::where(['name' => $pointData['name']])->first();
                                $tagData[$tag->id] = ['point_count' => $pointData['points']];
                            }
                            $answer->tags()->sync($tagData);
                        }
                    }
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
