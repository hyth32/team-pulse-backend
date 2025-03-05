<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="TestTagPoints", description="Распределение баллов по тегам в тесте", properties={
 *      @OA\Property(property="test_id", type="string", format="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="question_id", type="string", format="uuid", description="ID вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="tag_id", type="string", format="uuid", description="ID тега", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="point_count", type="integer", description="Количество поинтов на тег"),
 * })
 */
class TestTagPoints extends Model
{
    //
}
