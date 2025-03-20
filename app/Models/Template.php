<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'author_id',
    ];

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'template_topics');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
