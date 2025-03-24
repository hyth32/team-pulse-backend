<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Requests\Test\TestSolution;
use App\Http\Requests\Test\TestSolve;
use App\Http\Requests\User\SubjectStats;
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

    public function userTests(string $userUuid, BaseListRequest $request)
    {
        return TestService::listUserTests($userUuid, $request);
    }

    public function subjectUserStats(SubjectStats $request)
    {
        return TestService::listSubjectStats($request);
    }

    /**
     * @OA\Post(path="/api/v1/tests/assign",
     *      tags={"Test"},
     *      summary="Назначить шаблон",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/AssignTestRequest"),
     *      ),
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function assign(TemplateAssign $request)
    {
        return (new TestService)->assign($request);
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
     * @OA\Get(path="/api/v1/tests/{uuid}/{topicUuid}",
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

    public function solve(TestSolve $request)
    {
        return (new TestService)->saveSolution($request);
    }

    public function getSolution(TestSolution $request)
    {
        return TestService::solution($request);
    }
}
