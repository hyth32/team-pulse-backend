<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(schema="UserTest", description="Тесты пользователя", properties={
 *      @OA\Property(property="user_id", type="string", format="uuid", description="ID пользователя", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="test_id", type="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="assigner_id", type="string", format="uuid", description="ID пользователя, назначившего тест", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="completion_status", type="string", ref="#/components/schemas/TestCompletionStatus"),
 * })
 */
class UserTest extends BaseModel
{
    public $fillable = [
        'user_id',
        'test_id',
        'assigner_id',
        'completion_status',
    ];

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }
}
