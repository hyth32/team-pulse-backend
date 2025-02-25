<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="Group", description="Группа пользователей", properties={
 *      @OA\Property(property="name", type="string", description="Название группы"),
 *      @OA\Property(property="priority", type="integer", description="Приоритет группы"),
 * })
 */
class Group extends Model
{
    //
}
