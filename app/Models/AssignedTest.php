<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedTest extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'template_id',
        'name',
        'description',
        'frequency',
        'start_date',
        'end_date',
        'subject_id',
        'assigner_id',
        'is_anonymous',
        'late_result',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_test_completions')->withPivot(['completion_status', 'topic_id']);
    }

    public function subject()
    {
        return $this->hasOne(User::class, 'id', 'subject_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'assigned_test_groups');
    }

    public function assigner()
    {
        return $this->hasOne(User::class, 'id', 'assigner_id');
    }
}
