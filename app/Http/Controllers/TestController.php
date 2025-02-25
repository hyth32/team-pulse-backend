<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    /**
     * @OA\Get(path="/api/tests",
     *      tags={"Test"},
     *      summary="Список тестов",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description = "Ответ",
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
}
