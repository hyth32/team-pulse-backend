<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="Test", description="Тест", properties={
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="description", type="text", description="Описание теста"),
 *      @OA\Property(property="type", type="integer", ref="#/components/schemas/TestType"),
 *      @OA\Property(property="periodicity", type="string", format="uuid", description="ID периодичности", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="start_date", type="datetime", description="Дата начала теста"),
 *      @OA\Property(property="end_date", type="datetime", description="Дата окончания теста"),
 *      @OA\Property(property="assignee_id", type="string", format="uuid", description="ID пользователя, назначившего тест", example="123e4567-e89b-12d3-a456-426614174000"),
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
        'periodicity',
        'start_date',
        'end_date',
        'assignee_id',
    ];
}
