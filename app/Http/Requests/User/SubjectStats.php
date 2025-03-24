<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SubjectStats extends FormRequest
{
    public function rules(): array
    {
        return [
            'subjectId' => 'required|string',
        ];
    }
}
