<?php

namespace App\Models;

class Answer extends BaseModel
{
    protected $fillable = [
        'text',
        'isRight',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'answer_tags')->withPivot('point_count');
    }
}
