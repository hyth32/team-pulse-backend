<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\ListGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Services\GroupService;

class GroupController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/groups",
     *     tags={"Group"},
     *     summary="Список групп",
     *     @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *     @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *     @OA\Response(response = 200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="groups", type="array", @OA\Items(ref="#/components/schemas/Group"))
     *             ),
     *         ),
     *     ),
     * )
     */
    public function list(ListGroupRequest $request)
    {
        return GroupService::list($request);
    }

    /**
     * @OA\Post(path="/api/v1/groups",
     *     tags={"Group"},
     *     summary="Создать группу",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/CreateGroupRequest"),
     *     ),
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function create(CreateGroupRequest $request)
    {
        return (new GroupService)->save($request);
    }

    /**
     * @OA\Put(path="/api/v1/groups/{uuid}",
     *     tags={"Group"},
     *     summary="Обновить группу",
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function update(string $uuid, UpdateGroupRequest $request)
    {
        return (new GroupService)->update($uuid, $request);
    }

    /**
     * @OA\Delete(path="/api/v1/groups/{uuid}",
     *     tags={"Group"},
     *     summary="Удалить группу",
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function delete(string $uuid)
    {
        return (new GroupController)->delete($uuid);
    }
}
