<?php

namespace App\Models;

class Topic extends BaseModel
{
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_topics');
    }
}
