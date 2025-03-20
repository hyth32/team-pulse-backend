<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class TagUpdate extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }
}
