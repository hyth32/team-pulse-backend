<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(schema="UserTest", description="Группы пользователя", properties={
 *      @OA\Property(property="user_id", type="uuid", description="ID пользователя", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="test_id", type="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class UserTest extends BaseModel
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_id');
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'test_id');
    }
}
