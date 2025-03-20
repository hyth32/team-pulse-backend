<?php

namespace App\Models;

class Tag extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_tags');
    }
}
