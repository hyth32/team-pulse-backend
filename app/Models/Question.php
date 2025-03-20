<?php

namespace App\Models;

class Question extends BaseModel
{
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'question_topics');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'question_tags');
    }
}
