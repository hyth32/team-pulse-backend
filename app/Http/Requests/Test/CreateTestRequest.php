<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateTestRequest",
 *     type="object",
 *     description="Request body for creating a new test",
 *     @OA\Property(property="name", type="string", example="New test", description="Name of the test"),
 *     @OA\Property(property="description", type="string", example="New test description", description="Description of the test"),
 *     @OA\Property(property="type", type="integer", example=1, description="Type of the test"),
 *     @OA\Property(
 *         property="questions",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="topic", type="string", example="default", description="Topic of the question"),
 *             @OA\Property(property="text", type="string", example="Question 1", description="Text of the question"),
 *             @OA\Property(property="type", type="integer", example=0, description="Type of the question"),
 *             @OA\Property(
 *                 property="answers",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="text", type="string", example="Answer 1", description="Text of the answer")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Property(
 *         property="periodicity",
 *         type="object",
 *         @OA\Property(property="name", type="string", example="Каждую неделю", description="Name of the periodicity"),
 *         @OA\Property(
 *             property="timeframe",
 *             type="object",
 *             @OA\Property(property="from", type="integer", example=1740566491, description="Start timestamp"),
 *             @OA\Property(property="to", type="integer", example=1740566491, description="End timestamp")
 *         )
 *     ),
 *     @OA\Property(property="start_date", type="integer", example=1740566491, description="Start date timestamp"),
 *     @OA\Property(property="end_date", type="integer", example=1740566491, description="End date timestamp"),
 *     @OA\Property(
 *         property="groups",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Frontend", description="Name of the group")
 *         )
 *     ),
 *     @OA\Property(
 *         property="employees",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="string", format="uuid", example="123e4567-e89b-12d3-a456-426614174000", description="Employee ID")
 *         )
 *     )
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
            'type' => 'required|integer',

            'questions' => 'nullable|array',
            'questions.*.topic' => 'nullable|string|max:255',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.type' => 'required|integer',

            'questions.*.answers' => 'nullable|array',
            'questions.*.answers.*.text' => 'required|string|max:1000',

            'periodicity' => 'nullable|array',
            'periodicity.name' => 'nullable|string|max:255',
            'periodicity.timeframe' => 'nullable|array',
            'periodicity.timeframe.from' => 'nullable|integer',
            'periodicity.timeframe.to' => 'nullable|integer|gte:periodicity.timeframe.from',

            'start_date' => 'nullable|integer',
            'end_date' => 'nullable|gte:start_date',

            'groups' => 'nullable|array',
            'groups.*.name' => 'required|string|max:255',

            'employees' => 'nullable|array',
            'employees.*.id' => 'required|string',
        ];
    }
}
