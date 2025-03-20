<?php

namespace App\Http\Services;

use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Tag\TagCreate;
use App\Http\Requests\Tag\TagUpdate;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\Tag\TagResource;
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
            'tags' => TagResource::collection($result['items']->get()),
        ];
    }

    /**
     * Сохранение тега
     * @param TagCreate $request
     */
    public static function save(TagCreate $request)
    {
        $tags = array_map(fn ($tagName) => ['name' => trim($tagName)], $request->validated()['tags']);
        Tag::upsert($tags, ['name']);

        return ['message' => 'Тег создан'];
    }

    /**
     * Обновление тега
     * @param string $uuid
     * @param TagUpdate $request
     */
    public static function update(string $uuid, TagUpdate $request)
    {
        Tag::findOrFail($uuid)->update($request->validated());
        return ['message' => 'Тег обновлен'];
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
