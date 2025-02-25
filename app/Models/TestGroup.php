<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="TestGroup", description="Группы, назначенные на тест", properties={
 *     @OA\Property(property="test_id", type="string", format="uuid", description="ID теста", example="123e4567-e89b-12d3-a456-426614174000"),
 *     @OA\Property(property="group_id", type="string", format="uuid", description="ID группы", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
*/
class TestGroup extends Model
{
    //
}
