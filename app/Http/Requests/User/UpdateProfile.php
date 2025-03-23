<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfile extends FormRequest
{
    public function rules(): array
    {
        return [
            'fullName' => 'nullable|array',
            'fullName.firstName' => 'nullable|string',
            'fullName.lastName' => 'nullable|string',
            'email' => ['nullable', Rule::unique('users', 'email')->ignore(request()->user()->id)],
            'login' => ['nullable', Rule::unique('users', 'login')->ignore(request()->user()->id)],
            'role' => ['nullable', Rule::in(UserRole::labels())],
        ];
    }
}
