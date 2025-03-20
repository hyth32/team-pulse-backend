<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseListRequest;
use App\Http\Services\TestService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/tests",
     *      tags={"Test"},
     *      summary="Список тестов",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *              ),
     *          ),
     *      ),
     * )
     */
    public function list(BaseListRequest $request)
    {
        return TestService::list($request);
    }

    /**
     * @OA\Get(path="/api/v1/tests/{uuid}/users",
     *      tags={"Test"},
     *      summary="Список назначенных пользователей",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                 @OA\Property(property="users", type="array", @OA\Items(ref="#/components/schemas/User"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function assignedUsers(string $uuid, BaseListRequest $request)
    {
        return TestService::listAssignedUsers($uuid, $request);
    }

    /**
     * @OA\Get(path="/api/v1/tests/{uuid}/groups",
     *      tags={"Test"},
     *      summary="Список назначенных групп",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                 @OA\Property(property="groups", type="array", @OA\Items(ref="#/components/schemas/Group"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function assignedGroups(string $uuid, BaseListRequest $request)
    {
        return TestService::listAssignedGroups($uuid, $request);
    }
}
