<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

class TagCreate extends FormRequest
{
    public function rules(): array
    {
        return [
            'tags' => 'required|array',
        ];
    }
}
