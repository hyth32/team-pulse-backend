<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(schema="Tag", description="Тег", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID тега"),
 *      @OA\Property(property="name", type="string", description="Название тега"),
 * })
 */
class Tag extends BaseModel
{
    protected $fillable = [
        'name',
        'priority',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, QuestionTag::class);
    }

    public function answers()
    {
        return $this->belongsToMany(Answer::class, AnswerTagPoints::class)->withPivot('point_count');
    }
}
