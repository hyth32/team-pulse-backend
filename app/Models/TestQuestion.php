<?php

namespace App\Models;

/**
 * @OA\Schema(schema="TestQuestion", description="Вопросы теста", properties={
 *      @OA\Property(property="test_id", type="string", format="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="question_id", type="string", format="uuid", description="ID вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="topic_id", type="string", format="uuid", description="ID темы", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class TestQuestion extends BaseModel
{
    protected $fillable = [
        'test_id',
        'question_id',
        'topic_id',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
