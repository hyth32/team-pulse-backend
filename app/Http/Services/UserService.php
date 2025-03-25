<?php

namespace App\Http\Services;

use App\Enums\User\UserRole;
use App\Http\Requests\BaseListRequest;
use App\Http\Requests\User\UpdateProfile;
use App\Http\Requests\User\UserCreate;
use App\Http\Requests\User\UserImport;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService
{
    /**
     * Получение списка пользователей
     * @param BaseListRequest $request
     */
    public static function list(BaseListRequest $request)
    {
        $searchQuery = $request->q;
        $query = User::query()
            ->when(isset($searchQuery), function ($query) use ($searchQuery) {
                $searchQuery = "%{$searchQuery}%";
                $query->where('name', 'ilike', $searchQuery)
                    ->orWhere('lastname', 'ilike', $searchQuery)
                    ->orWhere('login', 'ilike', $searchQuery);
            })
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
     * @param UpdateProfile $request
     */
    public function changeProfile(string $uuid, UpdateProfile $request)
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
     * @param UserCreate $request
     */
    public function save(UserCreate $request)
    {
        $data = $request->validated();
        $generatedPassword = User::generatePassword();

        $user = User::create([
            'name' => $data['fullName']['firstName'],
            'lastname' => $data['fullName']['lastName'],
            'email' => $data['email'],
            'login' => $data['login'],
            'password' => Hash::make($generatedPassword),
            'role' => UserRole::getValueFromLabel($data['role']),
        ]);

        if (isset($data['groups']) && filled($data['groups'])) {
            $user->groups()->sync($data['groups']);
        }
        
        self::sendNewUserMessage($user, $generatedPassword);

        return ['message' => 'Пользователь создан'];
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

    public function import(UserImport $request)
    {
        $data = $request->validated();

        $importData = collect($data['users'])->map(function ($user) {
            return [
                'name' => $user['name'],
                'lastname' => $user['lastname'],
                'login' => $user['login'],
                'email' => $user['email'],
                'role' => UserRole::getValueFromLabel($user['role']),
            ];
        });

        $errors = [];
        foreach ($importData as $userData) {
            $existingLogin = User::query()->where(['login' => $userData['login']])->exists();
            $existingEmail = User::query()->where(['email' => $userData['email']])->exists();

            if ($existingLogin || $existingEmail) {
                $errorField = $existingLogin ? 'login' : 'email';
                $errors[] = [
                    'error' => "Пользователь с таким {$errorField} уже существует",
                    'data' => $userData,
                ];
            } else {
                $generatedPassword = User::generatePassword();
                $userData['password'] = Hash::make($generatedPassword);

                $user = User::create($userData);

                self::sendNewUserMessage($user, $generatedPassword);   
            }
        }

        if (filled($errors)) {
            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        return ['success' => true];
    }

    public static function sendNewUserMessage(User $user, string $generatedPassword)
    {
        $newUserText = "Добавлен новый пользователь:\n{$user->name} {$user->lastname}\nЛогин: {$user->login}\nПароль: {$generatedPassword}";
        Http::post('http://localhost:1234/send-message', [
            'text' => $newUserText,
        ]);
    }
}
