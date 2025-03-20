<?php

namespace App\Models;

class Question extends BaseModel
{
    protected $fillable = [
        'text',
        'answer_type',
    ];

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topic_questions');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'question_tags')->distinct();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
