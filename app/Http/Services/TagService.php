<?php

namespace App\Http\Services;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;

class TagService
{
    /**
     * Получение списка тегов
     * @param ListTagRequest $request
     */
    public static function list(ListTagRequest $request) {}

    /**
     * Сохранение тега
     * @param CreateTagRequest $request
     */
    public static function save(CreateTagRequest $request) {}

    /**
     * Обновление тега
     * @param string $uuid
     * @param UpdateTagRequest $request
     */
    public static function update(string $uuid, UpdateTagRequest $request) {}

    /**
     * Удаление тега
     * @param string $uuid
     */
    public static function delete(string $uuid) {}
}
