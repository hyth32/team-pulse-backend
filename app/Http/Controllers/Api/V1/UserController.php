<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\ListUserRequest;
use App\Http\Services\UserService;

class UserController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/users",
     *     tags={"User"},
     *     summary="Список пользователей",
     *     @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *     @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *     @OA\Response(response = 200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                 @OA\Property(property="users", type="array", @OA\Items(ref="#/components/schemas/User"))
     *             ),
     *         ),
     *     ),
     * )
     */
    public function list(ListUserRequest $request)
    {
        return UserService::list($request);
    }

    /**
     * @OA\Post(path="/api/v1/users",
     *     tags={"User"},
     *     summary="Создать пользователя",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/CreateUserRequest"),
     *     ),
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function create(CreateUserRequest $request)
    {
        return (new UserService)->save($request);
    }
}
