<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateTestRequest",
 *     type="object",
 *     description="Тело запроса для создания теста",
 *     @OA\Property(property="name", type="string", example="New test", description="Название теста"),
 *     @OA\Property(property="description", type="string", example="New test description", description="Описание теста"),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="topic", type="string", example="default", description="Тема вопроса"),
 *             @OA\Property(property="text", type="string", example="Question 1", description="Текст вопроса"),
 *             @OA\Property(property="type", type="integer", example=0, description="Тип ответа на вопрос"),
 *              @OA\Property(
 *                 property="tags",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="name", type="string", example="Tag 1", description="Тег вопроса"),
 *                     @OA\Property(property="points", type="integer", example="8", description="Количество поинтов на тег")
 *                 ),
 *             ),
 *             @OA\Property(
 *                 property="answers",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="text", type="string", example="Answer 1", description="Текст вопроса")
 *                 )
 *             )
 *         )
 *     ),
 * )
 */
class CreateTestRequest extends FormRequest
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
            'tests' => 'nullable|array',

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
