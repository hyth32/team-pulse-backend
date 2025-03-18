<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(schema="Question", description="Вопрос", properties={
 *      @OA\Property(property="text", type="text", description="Текст вопроса"),
 *      @OA\Property(property="type", type="integer", description="Тип ответа", ref="#/components/schemas/AnswerType"),
 * })
 */
class Question extends BaseModel
{
    protected $fillable = [
        'text',
        'type',
    ];

    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'question_tags', 'question_id', 'tag_id');
    }
}
