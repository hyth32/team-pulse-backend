<?php

namespace App\Models;

/**
 * @OA\Schema(schema="QuestionTopic", description="Тема вопроса", properties={
 *      @OA\Property(property="name", type="string", description="Название темы вопроса"),
 * })
 */
class QuestionTopic extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
    ];
}
