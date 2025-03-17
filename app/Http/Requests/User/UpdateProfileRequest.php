<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *      schema="UpdateUserProfile",
 *      type="object",
 *      description="Обновление профиля пользователя", properties={
 *      @OA\Property(property="name", type="string", description="Имя"),
 *      @OA\Property(property="lastname", type="string", description="Фамилия"),
 *      @OA\Property(property="login", type="string", description="Логин"),
 *      @OA\Property(property="password", type="string", description="Пароль"),
 *      @OA\Property(property="email", type="string", description="Email"),
 *      @OA\Property(property="role", type="string", description="Роль"),
 *      @OA\Property(property="groups", type="array",
 *           @OA\Items(type="string", format="uuid", description="ID группы")
 *      ),
 * })
 */
class UpdateProfileRequest extends FormRequest
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
        $user = request()->user();

        return [
            'name' => 'nullable|string',
            'lastname' => 'nullable|string',
            'login' => ['nullable', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string',
            'email' => ['nullable', Rule::unique('users')->ignore($user->id)],
            'role' => ['nullable', Rule::in(UserRole::labels())],
            'groups' => 'nullable|array',
        ];
    }
}
