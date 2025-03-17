<?php

namespace App\Models;

use App\Enums\EntityStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

/**
 * @OA\Schema(schema="User", description="Пользователь", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID пользователя", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="name", type="string", description="Имя"),
 *      @OA\Property(property="lastname", type="string", description="Фамилия"),
 *      @OA\Property(property="login", type="string", description="Логин"),
 *      @OA\Property(property="email", type="string", description="Email"),
 *      @OA\Property(property="role", type="string", description="Роль"),
 *      @OA\Property(property="status", type="string", description="Статус сущности"),
 *      @OA\Property(property="createdAt", type="string", description="Дата создания"),
 * })
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::uuid()->toString();

            $model->{$model->getCreatedAtColumn()} = now();

            $model->{$model->getUpdatedAtColumn()} = now();
        });

        static::updating(function ($model) {
            $model->{$model->getUpdatedAtColumn()} = now();
        });
    }

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

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups', 'user_id', 'group_id');
    }

    public function tests(): BelongsToMany
    {
        return $this->belongsToMany(Test::class, 'user_tests', 'user_id', 'test_id');
    }

    /**
     * Определение статуса пользователя
     */
    public function isActive()
    {
        return $this->status == EntityStatus::Active->value();
    }

    public function notifications()
    {
        return $this->hasMany(UserCreateNotification::class);
    }
}
