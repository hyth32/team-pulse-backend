<?php

namespace App\Http\Services;

use App\Enums\EntityStatus;
use App\Enums\User\UserRole;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\ListUserRequest;
use App\Http\Resources\UserShortResource;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Получение списка пользователей
     * @param ListUserRequest $request
     */
    public static function list(ListUserRequest $request)
    {
        $users = User::query()
            ->where([
                'status' => EntityStatus::Active->value(),
                'role' => UserRole::Employee->value(),
            ])
            ->offset($request['offset'])
            ->limit($request['limit'])
            ->orderBy('created_at', 'desc')
            ->get();

        return ['users' => UserShortResource::collection($users)];
    }

    /**
     * Сохранение пользователя
     * @param CreateUserRequest $request
     */
    public function save(CreateUserRequest $request)
    {
        $data = $request->validated();
        $existingUser = User::where([
            'email' => $data['email'],
        ])->exists();

        $generatedPassword = Str::random(20);

        if (!$existingUser) {
            $user = User::create([
                'name' => $data['name'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'login' => $data['login'],
                'password' => Hash::make($generatedPassword),
                'role' => UserRole::getValueFromLabel($data['role']),
                'status' => EntityStatus::Active->value(),
            ]);

            if (isset($data['groups']) && count($data['groups']) > 0) {
                foreach ($data['groups'] as $groupId) {
                    $group = Group::find($groupId);

                    UserGroup::create([
                        'user_id' => $user->id,
                        'group_id' => $group->id,
                    ]);
                }
            }
        }

        return ['message' => $generatedPassword];
    }
}