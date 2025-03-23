<?php

namespace App\Models;

class Question extends BaseModel
{
    protected $fillable = [
        'text',
        'answer_type',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'question_tags')->distinct();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
