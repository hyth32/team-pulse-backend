<?php

namespace App\Http\Requests\Template;

use App\Enums\Answer\AnswerType;
use App\Enums\Test\TemplateStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateCreate extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(TemplateStatus::labels())],

            'topics' => 'required|array',
            'topics.*.name' => 'required|string',

            'topics.*.questions' => 'required|array',
            'topics.*.questions.*.text' => 'required|string',
            'topics.*.questions.*.answerType' => ['required', Rule::in(AnswerType::values())],

            'topics.*.questions.*.tags' => 'nullable|array',
            'topics.*.questions.*.answers' => 'nullable|array',
            'topics.*.questions.*.answers.*.text' => 'nullable|string',

            'topics.*.questions.*.answers.*.points' => 'nullable|array',
            'topics.*.questions.*.answers.*.points.*.name' => 'required|string',
            'topics.*.questions.*.answers.*.points.*.points' => 'required|numeric',
        ];
    }
}
