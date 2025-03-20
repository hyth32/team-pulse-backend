<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreate extends FormRequest
{
    public function rules(): array
    {
        return [
            'fullName' => 'required|array',
            'fullName.firstName' => 'required|string',
            'fullName.lastName' => 'required|string',
            'email' => ['required', Rule::unique('users', 'email')],
            'login' => ['required', Rule::unique('users', 'login')],
            'role' => ['required', Rule::in(UserRole::labels())],
        ];
    }
}
