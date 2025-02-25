<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="Question", description="Вопрос", properties={
 *      @OA\Property(property="text", type="string", description="Текст вопроса"),
 *      @OA\Property(property="test_id", type="string", format="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class Question extends BaseModel
{
    //
}
