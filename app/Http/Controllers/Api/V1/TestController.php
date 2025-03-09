<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Services\TestService;

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
    public function list(ListTestRequest $request)
    {
        $tests = TestService::list($request);
        return response()->json(['tests' => $tests]);
    }

    /**
     * @OA\Post(path="/api/v1/tests",
     *      tags={"Test"},
     *      summary="Создать тест",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/CreateTestRequest"),
     *      ),
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function create(CreateTestRequest $request)
    {
        $test = (new TestService)->save($request);
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
    public function update(string $uuid, UpdateTestRequest $request)
    {
        $test = (new TestService)->update($uuid, $request);
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
        $test = TestService::view($uuid);
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
        (new TestService)->delete($uuid);
        return response()->json(['message' => 'Тест удален']);
    }

    /**
     * @OA\Post(path="/api/v1/tests/{uuid}/assign",
     *      tags={"Test"},
     *      summary="Назначить тест",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/AssignTestRequest"),
     *      ),
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function assign(AssignTestRequest $request, string $uuid)
    {
        (new TestService)->assign($uuid, $request);
        return response()->json(['message' => 'Тест назначен']);
    }
}
