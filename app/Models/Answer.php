<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(schema="Answer", description="Ответ", properties={
 *      @OA\Property(property="text", type="text", description="Текст ответа"),
 *      @OA\Property(property="image_id", type="string", format="uuid", description="ID изображения", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="question_id", type="string", format="uuid", description="ID вопроса", example="123e4567-e89b-12d3-a456-426614174000"),
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

    public function tagPoints(): HasMany
    {
        return $this->hasMany(AnswerTagPoints::class);
    }
}
