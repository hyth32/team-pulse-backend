<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Services\AuthService;

class AuthController extends Controller
{
    /**
     * @OA\Post(path="/api/v1/auth/login",
     *      tags={"Auth"},
     *      summary="Вход",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/LoginRequest"),
     *      ),
     *      @OA\Response(response = 200, description="Ответ",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="token", type="string", description="Access токен"),
     *                 @OA\Property(property="expirationDate", type="datetime", description="Дата экспирации токена"),
     *                 @OA\Property(property="role", type="string", description="Роль пользователя"),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        return (new AuthService)->login($request);
    }
}
