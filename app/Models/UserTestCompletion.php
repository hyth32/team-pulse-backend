<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTestCompletion extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_test_id',
        'topic_id',
        'completion_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTest()
    {
        return $this->belongsTo(AssignedTest::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
