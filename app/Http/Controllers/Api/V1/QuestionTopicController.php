<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class QuestionTopicController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/question-topics",
     *      tags={"QuestionTopic"},
     *      summary="Список тем вопросов",
     *      @OA\Parameter(name="limit", @OA\Schema(type="integer"), description="Количество записей", in="query"),
     *      @OA\Parameter(name="offset", @OA\Schema(type="integer"), description="Смещение", in="query"),
     *      @OA\Response(response = 200, description="Ответ",
     *          @OA\MediaType(mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="questionTopics", type="array", @OA\Items(ref="#/components/schemas/QuestionTopic"))
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
     * @OA\Post(path="/api/v1/question-topics",
     *      tags={"QuestionTopic"},
     *      summary="Создать тему вопроса",
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
     * @OA\Put(path="/api/v1/question-topics/{uuid}",
     *      tags={"QuestionTopic"},
     *      summary="Обновить тему вопроса",
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
     * @OA\Delete(path="/api/v1/question-topics/{uuid}",
     *      tags={"QuestionTopic"},
     *      summary="Удалить тему вопроса",
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
