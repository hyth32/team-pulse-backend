<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreate extends FormRequest
{
    public function rules(): array
    {
        $userId = request()->user()->id;

        return [
            'fullName' => 'required|array',
            'fullName.firstName' => 'required|string',
            'fullName.lastName' => 'required|string',
            'email' => ['required', Rule::unique('users', 'email')->ignore($userId)],
            'login' => ['required', Rule::unique('users', 'login')->ignore($userId)],
            'role' => ['required', Rule::in(UserRole::labels())],
        ];
    }
}
