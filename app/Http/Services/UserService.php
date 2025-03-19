<?php

namespace App\Http\Services;

use App\Enums\User\UserRole;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    /**
     * Получение списка пользователей
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $query = User::query()
            ->where(['role' => UserRole::Employee->value()])
            ->orderBy('created_at', 'desc');

        $result = self::paginateQuery($query, $request);

        return [
            'total' => $result['total'],
            'users' => UserResource::collection($result['items']->get()),
        ];
    }

    /**
     * Получение профиля пользователя по ID
     * @param string $uuid
     * @param Request $request
     */
    public static function profile(string $uuid, Request $request)
    {
        $currentUser = $request->user();
        if ($uuid !== $currentUser->id && !in_array($currentUser->role, UserRole::adminRoles())) {
            abort(403, 'Страница недоступна');
        }

        $user = User::findOrFail($uuid);
        return ['user' => UserResource::make($user)];
    }

    /**
     * Получение профиля пользователя
     * @param Request $request
     */
    public static function me(Request $request)
    {
        $user = User::findOrFail($request->user()->id);
        return ['user' => UserResource::make($user)];
    }

    /**
     * Изменение профиля пользователя
     * @param string $uuid
     * @param UpdateProfileRequest $request
     */
    public function changeProfile(string $uuid, UpdateProfileRequest $request)
    {
        $user = User::findOrFail($uuid);
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['groups']) && filled($data['groups'])) {
            $user->groups()->sync($data['groups']);
            unset($data['groups']);
        }

        $user->update($data);

        return ['message' => 'Профиль обновлен'];
    }

    /**
     * Сохранение пользователя
     * @param CreateUserRequest $request
     */
    public function save(CreateUserRequest $request)
    {
        $data = $request->validated();

        $generatedPassword = Str::random(20);

        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'login' => $data['login'],
            'password' => Hash::make($generatedPassword),
            'role' => UserRole::getValueFromLabel($data['role']),
        ]);

        if (isset($data['groups']) && filled($data['groups'])) {
            $user->groups()->sync($data['groups']);
        }

        return ['message' => $generatedPassword];
    }

    /**
     * Удаление пользователя по id
     * @param int $id
     * @param Request $request
     */
    public function delete(string $uuid, Request $request)
    {
        User::findOrFail($uuid)->delete();
        return ['message' => 'Пользователь удален'];
    }
}
