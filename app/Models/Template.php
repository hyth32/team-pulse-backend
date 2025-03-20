<?php

namespace App\Models;

class Template extends BaseModel
{
    public function questions()
    {
        $this->belongsToMany(Question::class, 'template_questions');
    }
}
