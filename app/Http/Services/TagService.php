<?php

namespace App\Http\Services;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\TagShortResource;
use App\Models\Tag;

class TagService
{
    /**
     * Получение списка тегов
     * @param ListTagRequest $request
     */
    public static function list(ListTagRequest $request)
    {
        $total = Tag::count();
        $tags = Tag::skip($request['offset'])->take($request['limit'])->get();
        return [
            'total' => $total,
            'tags' => TagShortResource::collection($tags),
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
     */
    public static function delete(string $uuid)
    {
        Tag::findOrFail($uuid)->delete();
        return ['message' => 'Тег удален'];
    }
}
