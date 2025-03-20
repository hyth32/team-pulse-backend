<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupCreate extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'employees' => 'nullable|array',
        ];
    }
}
