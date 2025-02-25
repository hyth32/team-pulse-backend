<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

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
     *                  @OA\Property(property="tests", type="array", @OA\Items(ref="#/components/schemas/Test"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function list()
    {
        //
    }

    /**
     * @OA\Post(path="/api/v1/tests",
     *      tags={"Test"},
     *      summary="Создать тест",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Put(path="/api/v1/tests/{uuid}",
     *      tags={"Test"},
     *      summary="Обновить тест",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function update(string $uuid)
    {
        //
    }

    /**
     * @OA\Get(path="/api/v1/tests/{uuid}",
     *      tags={"Test"},
     *      summary="Получить тест",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function view(string $uuid)
    {
        //
    }

    /**
     * @OA\Delete(path="/api/v1/tests/{uuid}",
     *      tags={"Test"},
     *      summary="Удалить тест",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function delete(string $uuid)
    {
        //
    }
}
