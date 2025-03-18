<?php

namespace App\Http\Services;

use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\TagShortResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagService extends BaseService
{
    /**
     * Получение списка тегов
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $query = Tag::query();
        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'tags' => TagShortResource::collection($result['items']->get()),
        ];
    }

    /**
     * Сохранение тега
     * @param CreateTagRequest $request
     */
    public static function save(CreateTagRequest $request)
    {
        $data = $request->validated();
        foreach ($data['tags'] as $tagData) {
            Tag::firstOrCreate([
                'name' => trim($tagData['name']),
            ]);
        }

        return ['message' => 'Тег создан'];
    }

    /**
     * Обновление тега
     * @param string $uuid
     * @param UpdateTagRequest $request
     */
    public static function update(string $uuid, UpdateTagRequest $request)
    {
        $data = $request->validated();

        $tag = Tag::findOrFail($uuid);
        $tag->update($data);

        return $tag;
    }

    /**
     * Удаление тега
     * @param string $uuid
     * @param Request $request
     */
    public static function delete(string $uuid, Request $request)
    {
        Tag::findOrFail($uuid)->delete();
        return ['message' => 'Тег удален'];
    }
}
