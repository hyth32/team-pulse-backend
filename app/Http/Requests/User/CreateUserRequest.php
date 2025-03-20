<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *      schema="CreateUserRequest",
 *      type="object",
 *      description="Тело запроса для создания группы",
 *      @OA\Property(property="name", type="string", description="Имя"),
 *      @OA\Property(property="lastname", type="string", description="Фамилия"),
 *      @OA\Property(property="login", type="string", description="Логин"),
 *      @OA\Property(property="email", type="string", description="Email"),
 *      @OA\Property(property="role", type="string", description="Роль", ref="#/components/schemas/UserRole"),
 *      @OA\Property(property="groups", type="array",
*           @OA\Items(type="string", format="uuid", description="ID группы")
*       ),
 * )
 */
class CreateUserRequest extends FormRequest
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
            'name' => 'required|string',
            'lastname' => 'required|string',
            'login' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'role' => ['required', Rule::in(UserRole::labels())],
            'groups' => 'nullable|array',
        ];
    }
}
