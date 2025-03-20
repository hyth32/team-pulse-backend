<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends BaseModel
{
    use SoftDeletes;

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'template_questions');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'author_id');
    }
}
