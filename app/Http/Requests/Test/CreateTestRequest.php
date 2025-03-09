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

            'questions' => 'nullable|array',
            'questions.*.topic' => 'nullable|string|max:255',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.type' => 'required|integer',

            'questions.*.tags' => 'nullable|array',
            'questions.*.tags.*.name' => 'required|string|max:255',
            'questions.*.tags.*.points' => 'nullable|integer',

            'questions.*.answers' => 'nullable|array',
            'questions.*.answers.*.text' => 'required|string|max:1000',
        ];
    }
}
