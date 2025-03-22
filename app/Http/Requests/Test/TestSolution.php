<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class TestSolution extends FormRequest
{
    public function rules(): array
    {
        return [
            'testId' => 'required|string',
            'topicId' => 'required|string',
            'questions' => 'required|array',
            'questions.*.questionId' => 'required|string',
            'questions.*.answer' => 'required|array',
        ];
    }
}
