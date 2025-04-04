<?php

namespace App\Models;

/**
 * @OA\Schema(schema="Topic", description="Тема вопроса", properties={
 *      @OA\Property(property="id", type="string", description="ID темы вопроса"),
 *      @OA\Property(property="name", type="string", description="Название темы вопроса"),
 * })
 */
class Topic extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function tests()
    {
        $this->belongsToMany(Test::class, TestQuestion::class, 'topic_id', 'test_id');
    }

    public function questions()
    {
        $this->belongsToMany(Question::class, TestQuestion::class, 'topic_id', 'question_id');
    }
}
