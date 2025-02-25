<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Test\CreateTestRequest;
use App\Models\Test;
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
     *                  @OA\Property(property="tests", type="array", @OA\Items(ref="#/components/schemas/Test"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function list(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $tests = Test::skip($offset)->take($limit)->get();

        return response()->json(['tests' => $tests]);
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
    public function create(CreateTestRequest $request)
    {
        $data = $request->validated();
        $test = Test::create($data);

        return response()->json($test, 201);
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
    public function update(CreateTestRequest $request, string $uuid)
    {
        $data = $request->validated();
        $test = Test::findOrFail($uuid);
        $test->update($data);
        
        return response()->json($test);
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
        $test = Test::findOrFail($uuid);

        return response()->json($test);
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
        $test = Test::findOrFail($uuid);
        $test->delete();

        return response()->json(['message' => 'Тест удален']);
    }
}
