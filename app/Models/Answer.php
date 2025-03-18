<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(schema="Answer", description="Ответ", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID ответа"),
 *      @OA\Property(property="text", type="string", description="Текст ответа"),
 * })
 */
class Answer extends BaseModel
{
    protected $fillable = [
        'text',
        'image_id',
        'question_id',
    ];

    public function image(): HasOne
    {
        return $this->hasOne(File::class, 'image_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, AnswerTagPoints::class)->withPivot('point_count');
    }
}
