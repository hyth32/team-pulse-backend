<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="Tag", description="Тег", properties={
 *      @OA\Property(property="text", type="string", description="Название тега"),
 *      @OA\Property(property="priority", type="integer", description="Приоритет тега"),
 * })
 */
class Tag extends Model
{
    //
}
