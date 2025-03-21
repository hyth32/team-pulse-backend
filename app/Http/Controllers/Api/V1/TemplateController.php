<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\Template\TemplateAssign;
use App\Http\Requests\Template\TemplateCreate;
use App\Http\Requests\Template\TemplateUpdate;
use App\Http\Services\TemplateService;
use App\Http\Services\TestService;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/templates",
     *      tags={"Test"},
     *      summary="Список шаблонов",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="total", type="integer", description="Общее количество записей"),
     *                 @OA\Property(property="tests", type="array", @OA\Items(ref="#/components/schemas/TestTemplateResource"))
     *              ),
     *          ),
     *      ),
     * )
     */
    public function list(BaseListRequest $request)
    {
        return TemplateService::list($request);
    }

    /**
     * @OA\Post(path="/api/v1/templates",
     *      tags={"Test"},
     *      summary="Создать шаблон",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/CreateTestRequest"),
     *      ),
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function create(TemplateCreate $request)
    {
        return (new TemplateService)->save($request);
    }

    /**
     * @OA\Put(path="/api/v1/templates/{uuid}",
     *      tags={"Test"},
     *      summary="Обновить шаблон",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function update(string $uuid, TemplateUpdate $request)
    {
        return (new TemplateService)->update($uuid, $request);
    }

    /**
     * @OA\Get(path="/api/v1/templates/{uuid}/topics/{topicUuid}",
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
        return TemplateService::listTopicQuestions($uuid, $topicUuid, $request);
    }

    /**
     * @OA\Delete(path="/api/v1/templates/{uuid}",
     *      tags={"Test"},
     *      summary="Удалить тест",
     *      @OA\Response(response=200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json"),
     *      ),
     * )
     */
    public function delete(string $uuid, Request $request)
    {
        return (new TemplateService)->delete($uuid, $request);
    }
}
