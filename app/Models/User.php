<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\Schema(schema="User", description="Пользователь", properties={
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="description", type="text", description="Описание теста"),
 *      @OA\Property(property="frequency", type="integer", ref="#/components/schemas/TestFrequency"),
 *      @OA\Property(property="start_date", type="datetime", description="Дата начала теста"),
 *      @OA\Property(property="end_date", type="datetime", description="Дата окончания теста"),
 *      @OA\Property(property="assignee_id", type="string", format="uuid", description="ID пользователя, назначившего тест", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public function avatar(): HasOne
    {
        return $this->hasOne(File::class, 'image_id');
    }

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'login',
        'password',
        'role',
        'status',
        'image_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups', 'user_id', 'group_id');
    }
}
