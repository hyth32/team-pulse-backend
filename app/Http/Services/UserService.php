<?php

namespace App\Http\Services;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\ListUserRequest;

class UserService
{
    /**
     * Получение списка пользователей
     * @param ListUserRequest $request
     */
    public static function list(ListUserRequest $request)
    {

    }

    /**
     * Сохранение пользователя
     * @param CreateUserRequest $request
     */
    public function save(CreateUserRequest $request)
    {

    }
}