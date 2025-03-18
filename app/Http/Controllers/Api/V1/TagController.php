<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/tags",
     *     tags={"Tag"},
     *     summary="Список тегов",
     *     @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *     @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *     @OA\Response(response = 200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                 @OA\Property(property="tags", type="array", @OA\Items(ref="#/components/schemas/Tag"))
     *             ),
     *         ),
     *     ),
     * )
     */
    public function list(BaseListRequest $request) {
        return TagService::list($request);
    }

    /**
     * @OA\Post(path="/api/v1/tags",
     *     tags={"Tag"},
     *     summary="Создать тег",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/CreateTagRequest"),
     *     ),
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function create(CreateTagRequest $request) {
        return (new TagService)->save($request);
    }

    /**
     * @OA\Put(path="/api/v1/tags/{uuid}",
     *     tags={"Tag"},
     *     summary="Обновить тег",
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/UpdateTagRequest"),
     *     ),
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function update(string $uuid, UpdateTagRequest $request) {
        return (new TagService)->update($uuid, $request);
    }

    /**
     * @OA\Delete(path="/api/v1/tags/{uuid}",
     *     tags={"Tag"},
     *     summary="Удалить тег",
     *     @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *     ),
     * )
     */
    public function delete(string $uuid, Request $request) {
        return (new TagService)->delete($uuid, $request);
    }
}
