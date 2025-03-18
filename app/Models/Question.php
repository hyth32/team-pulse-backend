<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(schema="Question", description="Вопрос", properties={
 *      @OA\Property(property="text", type="text", description="Текст вопроса"),
 *      @OA\Property(property="type", type="integer", description="Тип ответа", ref="#/components/schemas/AnswerType"),
 * })
 *
 * @OA\Schema(schema="QuestionsResponse", description="Вопрос", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID вопроса"),
 *      @OA\Property(property="text", type="string", description="Текст вопроса"),
 *      @OA\Property(property="type", type="integer", description="Тип ответа", ref="#/components/schemas/AnswerType"),
 *      @OA\Property(property="tags", type="array", description="Теги вопроса", @OA\Items(ref="#/components/schemas/Tag")),
 *      @OA\Property(property="answers", type="array", description="Теги вопроса",
 *          @OA\Items(
 *              @OA\Property(property="id", type="string", format="uuid", description="ID ответа"),
 *              @OA\Property(property="text", type="string", description="Текст ответа"),
 *              @OA\Property(property="tagPoints", type="array", @OA\Items(ref="#/components/schemas/AnswerTagShort")),
 *          ),
 *      ),
 * })
 */
class Question extends BaseModel
{
    protected $fillable = [
        'text',
        'type',
    ];

    public function topics()
    {
        return $this->belongsToMany(Topic::class, TestQuestion::class, 'question_id', 'topic_id')->distinct();
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, TestQuestion::class, 'question_id', 'test_id')->distinct();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, QuestionTag::class);
    }
}
