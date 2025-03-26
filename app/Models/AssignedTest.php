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
        'test_status',
        'min_percentage',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, UserTestCompletion::class)->withPivot('completion_status');
    }

    public function subject()
    {
        return $this->hasOne(User::class, 'id', 'subject_id');
    }

    public function assigner()
    {
        return $this->hasOne(User::class, 'id', 'assigner_id');
    }

    public function topicCompletions()
    {
        return $this->belongsToMany(
            Topic::class,
            UserTopicCompletion::class,
        )->withPivot(['user_id','completion_status']);
    }
}
