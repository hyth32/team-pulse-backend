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
            return [
                'success' => false,
                'error' => 'username',
            ];
        }

        $isPasswordValid = Hash::check($request['password'], $user->password);
        if (!$isPasswordValid) {
            return [
                'success' => false,
                'error' => 'password',
            ];
        }

        $user->tokens()->delete();
        $expirationDate = Carbon::now()->addDay();
        $token = $user->createToken('access_token', ['*'], $expirationDate)->plainTextToken;

        return [
            'success' => true,
            'data' => [
                'token' => $token,
                'expirationDate' => $expirationDate->timestamp,
                'role' => UserRole::getLabelFromValue($user->role),
            ],
        ];
    }
}
