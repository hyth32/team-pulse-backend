<?php

namespace App\Models;

/**
 * @OA\Schema(schema="TestGroup", description="Группы, назначенные на тест", properties={
 *      @OA\Property(property="test_id", type="uuid", description="ID теста"),
 *      @OA\Property(property="group_id", type="uuid", description="ID группы"),
 * })
 */
class TestGroup extends BaseModel
{
    protected $fillable = [
        'test_id',
        'group_id',
    ];
}
