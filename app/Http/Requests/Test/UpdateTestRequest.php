<?php

namespace App\Http\Requests\Test;

use App\Enums\Test\TestStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *      schema="UpdateTestRequest",
 *      type="object",
 *      description="Тело запроса для обновления теста",
 *      allOf={@OA\Schema(ref="#/components/schemas/CreateTestRequest")},
 * )
 */
class UpdateTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(TestStatus::labels())],
            'tests' => 'required|array',

            'tests.*.topic' => 'nullable|string|max:255',
            'tests.*.questions' => 'nullable|array',
            'tests.*.questions.*.name' => 'required|string|max:255',
            'tests.*.questions.*.type' => 'required|integer',

            'tests.*.questions.*.tags' => 'nullable|array',

            'tests.*.questions.*.answers' => 'nullable|array',
            'tests.*.questions.*.answers.*.text' => 'required|string|max:1000',
            'tests.*.questions.*.answers.*.points' => 'nullable|array',
            'tests.*.questions.*.answers.*.points.*.name' => 'required|string|max:255',
            'tests.*.questions.*.answers.*.points.*.points' => 'required|integer',
        ];
    }
}
