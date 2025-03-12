<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="Group", description="Группа", properties={
 *      @OA\Property(property="name", type="string", description="Название группы"),
 * })
 */
class Group extends BaseModel
{
    protected $fillable = [
        'name',
        'priority',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups', 'group_id', 'user_id');
    }
}
