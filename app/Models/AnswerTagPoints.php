<?php

namespace App\Models;

/**
 * @OA\Schema(schema="AnswerTagPoints", description="Распределение баллов по тегам на ответы", properties={
 *      @OA\Property(property="answer_id", type="string", format="uuid", description="ID темы вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="tag_id", type="string", format="uuid", description="ID темы вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="point_count", type="integer", description="Количество баллов", example="1"),
 * })
 */
class AnswerTagPoints extends BaseModel
{
    protected $fillable = [
        'answer_id',
        'tag_id',
        'point_count',
    ];
}
