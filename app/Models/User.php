<?php

namespace App\Models;

use App\Enums\User\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
 *
 * @OA\Schema(schema="UserProfile", description="Профиль пользователя", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID пользователя", example="123e4567-e89b-12d3-a456-426614174000"),
 *      @OA\Property(property="name", type="string", description="Имя"),
 *      @OA\Property(property="lastname", type="string", description="Фамилия"),
 *      @OA\Property(property="login", type="string", description="Логин"),
 *      @OA\Property(property="email", type="string", description="Email"),
 *      @OA\Property(property="role", type="string", description="Роль"),
 *      @OA\Property(property="groups", type="array", @OA\Items(ref="#/components/schemas/Group"))
 * })
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            $model->{$model->getUpdatedAtColumn()} = now();
        });
    }

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'login',
        'password',
        'role',
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
        return $this->belongsToMany(Test::class, UserTest::class)->withPivot(['assigner_id', 'completion_status', 'topic_id']);
    }

    public function notifications()
    {
        return $this->hasMany(UserCreateNotification::class);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, UserRole::adminRoles());
    }
}
