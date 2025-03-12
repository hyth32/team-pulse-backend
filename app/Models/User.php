<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @OA\Schema(schema="User", description="Пользователь", properties={
 *      @OA\Property(property="id", type="string", description="ID пользователя"),
 *      @OA\Property(property="name", type="string", description="Имя"),
 *      @OA\Property(property="lastname", type="string", description="Фамилия"),
 *      @OA\Property(property="login", type="string", description="Логин"),
 *      @OA\Property(property="email", type="string", description="Email"),
 *      @OA\Property(property="role", type="string", description="Роль"),
 *      @OA\Property(property="createdAt", type="string", description="Дата создания"),
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
