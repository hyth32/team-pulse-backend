<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="UserGroup", description="Группы пользователя", properties={
 *      @OA\Property(property="user_id", type="integer", description="ID пользователя", example="1"),
 *      @OA\Property(property="group_id", type="uuid", description="ID группы", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class UserGroup extends BaseModel
{
    protected $fillable = [
        'user_id',
        'group_id',
    ];
}
