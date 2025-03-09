<?php

namespace App\Http\Services;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;

class TagService
{
    /**
     * Получение списка тегов
     */
    public static function list(ListTagRequest $request) {}

    /**
     * Сохранение тега
     */
    public static function save(CreateTagRequest $request) {}

    /**
     * Обновление тега
     */
    public static function update(string $uuid, UpdateTagRequest $request) {}

    /**
     * Удаление тега
     */
    public static function delete(string $uuid) {}
}
