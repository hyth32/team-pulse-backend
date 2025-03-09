<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="Question", description="Вопрос", properties={
 *      @OA\Property(property="text", type="text", description="Текст вопроса"),
 *      @OA\Property(property="type", type="integer", description="Тип ответа", ref="#/components/schemas/AnswerType"),
 *      @OA\Property(property="topic_id", type="string", format="uuid", description="ID темы вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class Question extends BaseModel
{
    protected $fillable = [
        'text',
        'type',
        'topic_id',
    ];
}
