<?php

namespace App\Models;

class Topic extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
