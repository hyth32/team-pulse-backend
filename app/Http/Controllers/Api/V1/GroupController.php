<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

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
    public function list()
    {
        //
    }

    /**
     * @OA\Post(path="/api/v1/groups",
     *     tags={"Group"},
     *     summary="Создать группу",
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function create()
    {
        //
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
    public function update(string $uuid)
    {
        //
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
        //
    }
}
