<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    public $primaryKey = ['assigned_test_id', 'user_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'assigned_test_id',
        'user_id',
        'question_id',
        'answer',
    ];
}
