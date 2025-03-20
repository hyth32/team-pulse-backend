<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class GroupUpdate extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'nullable|string',
            'employees' => 'nullable|array',
        ];
    }
}
