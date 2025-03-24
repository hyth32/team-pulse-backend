<?php

namespace App\Models;


class UserAnswer extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'assigned_test_id',
        'user_id',
        'question_id',
        'answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function test()
    {
        return $this->belongsTo(AssignedTest::class, 'assigned_test_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
