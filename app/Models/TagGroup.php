<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="TagGroup", description="Группа тегов", properties={
 *      @OA\Property(property="tag_id", type="uuid", description="ID тега", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="group_id", type="uuid", description="ID группы", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class TagGroup extends Model
{
    //
}
