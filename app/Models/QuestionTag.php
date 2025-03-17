<?php

namespace App\Models;

/**
 * @OA\Schema(schema="QuestionTag", description="Теги вопроса", properties={
 *      @OA\Property(property="question_id", type="uuid", description="ID вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="tag_id", type="uuid", description="ID тега", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class QuestionTag extends BaseModel
{
    protected $fillable = [
        'question_id',
        'tag_id',
    ];
}
