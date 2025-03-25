<?php

namespace App\Http\Requests\Template;

use App\Enums\Answer\AnswerType;
use App\Enums\Test\TemplateStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TemplateCreate extends FormRequest
{
    public function rules(): array
    {
        $currentStatus = $this->input('status');

        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(TemplateStatus::labels())],

            'topics' => [
                'array',
                Rule::when(
                    $currentStatus == TemplateStatus::Done->label(),
                    ['required', 'min:1'],
                ),
            ],
            'topics.*.name' => 'required|string',

            'topics.*.questions' => [
                'array',
                Rule::when(
                    $currentStatus == TemplateStatus::Done->label(),
                    ['required', 'min:1'],
                ),
            ],
            'topics.*.questions.*.text' => 'required|string',
            'topics.*.questions.*.answerType' => ['required', Rule::in(AnswerType::values())],

            'topics.*.questions.*.tags' => 'nullable|array',
            'topics.*.questions.*.answers' => 'required|array',
            'topics.*.questions.*.answers.*.text' => [
                function ($attribute, $value, $fail) use ($currentStatus) {
                    $questionPath = implode('.', array_slice(explode('.', $attribute), 0, -3));
                    $answerType = $this->input("{$questionPath}.answerType");
                    
                    if ($currentStatus == TemplateStatus::Done->label() && in_array($answerType, [AnswerType::SingleChoice->value(), AnswerType::MultipleChoice->value()])) {
                        if (!is_array($value) || count($value) < 1) {
                            $fail($attribute);
                        }
                    }
                },
            ],
            'topics.*.questions.*.answers.*.isRight' => 'required|boolean',

            'topics.*.questions.*.answers.*.points' => 'nullable|array',
            'topics.*.questions.*.answers.*.points.*.name' => 'required|string',
            'topics.*.questions.*.answers.*.points.*.points' => 'required|numeric',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $fullFieldPaths = $validator->errors()->keys();

        $errorFields = collect($fullFieldPaths)
            ->map(fn ($fieldPath) => implode('.', array_filter(explode('.', $fieldPath), fn ($part) => !is_numeric($part))))
            ->unique()
            ->values()
            ->toArray();

        $response = [
            'success' => false,
            'errors' => $errorFields
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
