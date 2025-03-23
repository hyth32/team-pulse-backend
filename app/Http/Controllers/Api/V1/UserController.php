<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\User\UpdateProfile;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UserCreate;
use App\Http\Requests\User\UserImport;
use App\Http\Services\UserService;
use Illuminate\Http\Request;

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
    public function list(BaseListRequest $request)
    {
        return UserService::list($request);
    }

    /**
     * @OA\Get(path="/api/v1/users/{uuid}",
     *     tags={"User"},
     *     summary="Профиль запрашиваемого пользователя",
     *     @OA\Response(response = 200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *             ),
     *         ),
     *     ),
     * )
     */
    public function profile(string $uuid, Request $request)
    {
        return UserService::profile($uuid, $request);
    }

    /**
     * @OA\Get(path="/api/v1/users/me",
     *     tags={"User"},
     *     summary="Профиль пользователя",
     *     @OA\Response(response = 200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user", type="object", ref="#/components/schemas/User")
     *             ),
     *         ),
     *     ),
     * )
     */
    public function me(Request $request)
    {
        return UserService::me($request);
    }

    /**
     * @OA\Put(path="/api/v1/users/{uuid}",
     *     tags={"User"},
     *     summary="Обновить профиль пользователя",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/UpdateGroupRequest"),
     *     ),
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function changeProfile(string $uuid, UpdateProfile $request)
    {
        return (new UserService)->changeProfile($uuid, $request);
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
    public function create(UserCreate $request)
    {
        return (new UserService)->save($request);
    }

    /**
     * @OA\Delete(path="/api/v1/users/{uuid}",
     *      tags={"User"},
     *      summary="Удалить пользователя",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function delete(string $uuid, Request $request)
    {
        return (new UserService)->delete($uuid, $request);
    }

    public function importUsers(UserImport $request)
    {
        return (new UserService)->import($request);
    }
}
