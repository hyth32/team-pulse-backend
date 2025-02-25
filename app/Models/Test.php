<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="Test", description="Тест", properties={
 *      @OA\Property(property="name", type="string", description="Название теста"),
 * })
 */
class Test extends Model
{
    //
}
