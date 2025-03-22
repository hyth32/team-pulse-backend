<?php

namespace App\Http\Requests\User;

use App\Enums\User\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserImport extends FormRequest
{
    public function rules(): array
    {
        return [
            'users' => 'required|array',
            'users.*.name' => 'required|string',
            'users.*.lastname' => 'required|string',
            'users.*.login' => 'required|string',
            'users.*.email' => 'required|string',
            'users.*.role' => [
                'required',
                Rule::in(UserRole::labels()),
            ],
        ];
    }
}
