<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends BaseModel
{
    use SoftDeletes;

    public function questions()
    {
        $this->belongsToMany(Question::class, 'template_questions');
    }
}
