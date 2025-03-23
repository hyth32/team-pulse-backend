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
}
