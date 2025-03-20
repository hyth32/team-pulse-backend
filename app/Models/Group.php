<?php

namespace App\Models;

class Group extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }
}
