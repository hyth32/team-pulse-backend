<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedTest extends Model
{
    use SoftDeletes;

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'assigned_test_users');
    }

    public function subject()
    {
        return $this->hasOne(User::class, 'subject_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'assigned_test_users')->distinct();
    }
}
