<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="Test", description="Тест", properties={
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="description", type="text", description="Описание теста"),
 *      @OA\Property(property="frequency", type="integer", ref="#/components/schemas/TestFrequency"),
 *      @OA\Property(property="start_date", type="datetime", description="Дата начала теста"),
 *      @OA\Property(property="end_date", type="datetime", description="Дата окончания теста"),
 *      @OA\Property(property="author_id", type="string", format="uuid", description="ID пользователя, создавшего тест", example="123e4567-e89b-12d3-a456-426614174000"),
*       @OA\Property(property="subject_id", type="string", format="uuid", description="ID пользователя, на оценку которого направлен тест"),
*       @OA\Property(property="is_anonymous", type="bool", description="Метка анонимности"),
 * })
 */
class Test extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'frequency',
        'start_date',
        'status',
        'end_date',
        'test_status',
        'author_id',
        'subject_id',
        'is_anonymous',
    ];
}
