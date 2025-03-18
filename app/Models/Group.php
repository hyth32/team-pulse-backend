<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(schema="Group", description="Группа", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID группы"),
 *      @OA\Property(property="name", type="string", description="Название группы"),
 * })
 */
class Group extends BaseModel
{
    protected $fillable = [
        'name',
        'priority',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups', 'group_id', 'user_id');
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, TestGroup::class);
    }
}
