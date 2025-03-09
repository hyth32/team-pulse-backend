<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'nullable',
            'description' => 'nullable',
            'type' => 'nullable',

            'questions' => 'nullable|array',
            'questions.*.topic' => 'nullable|string|max:255',
            'questions.*.text' => 'nullable|string|max:255',
            'questions.*.type' => 'nullable|integer',

            'questions.*.answers' => 'nullable|array',
            'questions.*.answers.*.text' => 'nullable|string|max:1000',
        ];
    }
}
