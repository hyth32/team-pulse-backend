<?php

namespace App\Models;

use App\Enums\User\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasUuids, SoftDeletes;

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
        return ['password' => 'hashed'];
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, UserRole::adminRoles());
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    public function tests()
    {
        return $this->belongsToMany(AssignedTest::class, 'assigned_test_users')->distinct();
    }

    public function assignedTests()
    {
        return $this->belongsToMany(
            AssignedTest::class,
            UserTestCompletion::class,
        )
        ->distinct()
        ->whereHas('topicCompletions', function ($topicCompletionsQuery) {
            $topicCompletionsQuery->where(['user_id' => $this->id]);
        });
    }
}
