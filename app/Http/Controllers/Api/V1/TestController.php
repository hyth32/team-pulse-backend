<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Test\AssignTestRequest;
use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListAssignedGroupsRequest;
use App\Http\Requests\Test\ListAssignedUsersRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
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
     *                  @OA\Property(property="tests", type="array", @OA\Items(ref="#/components/schemas/TestShortResource"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function list(ListTestRequest $request)
    {
        return TestService::list($request);
    }

    /**
     * @OA\Get(path="/api/v1/tests/templates",
     *      tags={"Test"},
     *      summary="Список шаблонов",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                 @OA\Property(property="tests", type="array", @OA\Items(ref="#/components/schemas/TestTemplateShortResource"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function templateList(ListTestRequest $request)
    {
        return TestService::templateList($request);
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
    public function assignedUsers(string $uuid, ListAssignedUsersRequest $request)
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
    public function assignedGroups(string $uuid, ListAssignedGroupsRequest $request)
    {
        return TestService::listAssignedGroups($uuid, $request);
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
        return (new TestService)->save($request);
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
        return (new TestService)->update($uuid, $request);
    }

    /**
     * @OA\Get(path="/api/v1/tests/{uuid}",
     *      tags={"Test"},
     *      summary="Получить тест",
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="test", type="object", ref="#/components/schemas/TestView")
     *              ),
     *          ),
     *      ),
     * )
     */
    public function view(string $uuid, Request $request)
    {
        return TestService::view($uuid, $request);
    }

    /**
     * @OA\Get(path="/api/v1/tests/{uuid}/topics/{topicUuid}",
     *      tags={"Test"},
     *      summary="Получить вопросы теста по ID топика",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                  @OA\Property(property="questions", type="array", @OA\Items(ref="#/components/schemas/QuestionsResponse"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function topicQuestions(string $uuid, string $topicUuid, BaseListRequest $request)
    {
        return TestService::listTopicQuestions($uuid, $topicUuid, $request);
    }

    public function solve(string $uuid, Request $request)
    {
        return (new TestService)->solve($uuid, $request);
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
        return (new TestService)->delete($uuid);
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
    public function assign(string $uuid, AssignTestRequest $request)
    {
        return (new TestService)->assign($uuid, $request);
    }
}
