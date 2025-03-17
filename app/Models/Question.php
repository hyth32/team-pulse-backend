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
 *      @OA\Property(property="topic_id", type="string", format="uuid", description="ID темы вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class Question extends BaseModel
{
    protected $fillable = [
        'text',
        'type',
        'topic_id',
    ];

    public function topic(): HasOne
    {
        return $this->hasOne(QuestionTopic::class, 'topic_id');
    }

    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'question_tags', 'question_id', 'tag_id');
    }
}
