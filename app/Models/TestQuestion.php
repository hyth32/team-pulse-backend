<?php

namespace App\Models;

/**
 * @OA\Schema(schema="TestQuestion", description="Вопросы теста", properties={
 *      @OA\Property(property="test_id", type="string", format="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="question_id", type="string", format="uuid", description="ID вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class TestQuestion extends BaseModel
{
    //
}
