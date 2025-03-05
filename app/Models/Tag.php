<?php

namespace App\Models;

/**
 * @OA\Schema(schema="Tag", description="Тег", properties={
 *      @OA\Property(property="name", type="string", description="Название тега"),
 *      @OA\Property(property="priority", type="integer", description="Приоритет тега"),
 * })
 */
class Tag extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'priority',
    ];
}
