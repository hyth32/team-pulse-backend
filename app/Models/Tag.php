<?php

namespace App\Models;

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
}
