<?php

namespace App\Models;

class Topic extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'topic_questions');
    }
}
