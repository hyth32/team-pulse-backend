<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(schema="File", description="Файл", properties={
 *      @OA\Property(property="url", type="string", description="Локальный url файла"),
 *      @OA\Property(property="type", type="string", description="Тип файла"),
 *      @OA\Property(property="mime_type", type="string", description="Mime тип файла"),
 *      @OA\Property(property="original_name", type="string", description="Оригинальное имя файла")
 * })
 */
class File extends Model
{
    //
}
