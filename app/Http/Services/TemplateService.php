<?php

namespace App\Http\Services;

use App\Enums\Test\TemplateStatus;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateCreate;
use App\Http\Resources\Template\TemplateResource;
use App\Models\Tag;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateService extends BaseService
{
    /**
     * Получение списка шаблонов
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $searchQuery = $request->q;
        $query = Template::query()
            ->when(isset($searchQuery) && $searchQuery != 'undefined', function ($query) use ($searchQuery) {
                $searchQuery = "%{$searchQuery}%";
                $query->where('name', 'ilike', $searchQuery);
            });

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

        try {
            DB::transaction(function () use ($data, $request) {
                $template = Template::create([
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => TemplateStatus::getValueFromLabel($data['status']),
                    'author_id' => $request->user()->id,
                ]);
        
                foreach ($data['topics'] as $topicData) {
                    $topic = $template->topics()->create(['name' => $topicData['name']]);
        
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
                                if (!empty($answerData['text'])) {
                                    $answer = $question->answers()->create([
                                        'text' => $answerData['text'],
                                        'isRight' => $answerData['isRight'],
                                    ]);
            
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
                }
            });

            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => $e->getMessage(),
            ];
        }
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
