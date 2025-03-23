<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class TestSolution extends FormRequest
{
    public function rules(): array
    {
        return [
            'testId' => 'required|string',
            'userId' => 'required|string',
        ];
    }
}
