<?php

namespace App\Http\Services;

use App\Enums\User\UserRole;
use App\Http\Requests\Auth\Login;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Вход в систему
     * @param Login $request
     */
    public function login(Login $request)
    {
        $data = $request->validated();

        $user = User::where('login', $data['login'])->first();
        if (!$user) {
            throw new AuthorizationException('Пользователь не существует');
        }

        $isPasswordValid = Hash::check($request['password'], $user->password);
        if (!$isPasswordValid) {
            throw new AuthorizationException('Неправильный пароль');
        }

        $user->tokens()->delete();
        $expirationDate = Carbon::now()->addHour();
        $token = $user->createToken('access_token', ['*'], $expirationDate)->plainTextToken;

        return [
            'token' => $token,
            'expirationDate' => $expirationDate->timestamp,
            'id' => $user->id,
            'role' => UserRole::getLabelFromValue($user->role),
        ];
    }
}
