<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
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
        $token = $user->createToken('access_token')->plainTextToken;

        return ['message' => $token];
    }
}
