<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="LoginRequest",
 *      type="object",
 *      description="Тело запроса для назначения теста",
 *      @OA\Property(property="login", type="string", description="Логин"),
 *      @OA\Property(property="password", type="string", description="Пароль"),
 * )
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
