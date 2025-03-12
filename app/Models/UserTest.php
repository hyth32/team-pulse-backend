<?php

namespace App\Models;

/**
 * @OA\Schema(schema="UserTest", description="Тесты пользователя", properties={
 *      @OA\Property(property="user_id", type="integer", description="ID пользователя", example="1"),
 *      @OA\Property(property="test_id", type="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="assignee_id", type="integer", description="ID пользователя, назначившего тест", example="1"),
 * })
 */
class UserTest extends BaseModel
{
    public $fillable = [
        'user_id',
        'test_id',
        'assignee_id',
    ];
}
