<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTestCompletion extends Model
{
    public $primaryKey = ['user_id', 'assigned_test_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'assigned_test_id',
        'completion_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(AssignedTest::class, 'assigned_test_id', 'id');
    }
}
