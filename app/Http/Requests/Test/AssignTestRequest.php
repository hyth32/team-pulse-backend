<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="AssignTestRequest",
 *      type="object",
 *      description="Тело запроса для назначения теста",
 *      @OA\Property(property="name", type="string", example="New test name"),
 *      @OA\Property(property="description", type="string", example="New test description"),
 *      @OA\Property(property="frequency", type="string", format="uuid"),
*       @OA\Property(property="startDate", type="string", format="datetime", description="Временная метка начала прохождения теста"),
*       @OA\Property(property="endDate", type="string", format="datetime", description="Временная метка окончания прохождения теста"),
*       @OA\Property(property="subjectId", type="string", format="uuid", description="ID пользователя, на оценку которого направлен тест"),
*       @OA\Property(property="assignToAll", type="bool", description="Метка назначения всем сотрудникам"),
*       @OA\Property(property="groups", type="array",
*           @OA\Items(type="string", format="uuid", description="ID группы")
*       ),
*       @OA\Property(property="employees", type="array",
*           @OA\Items(type="string", format="uuid", description="ID пользователя", example="123e4567-e89b-12d3-a456-426614174000"),
*       ),
*       @OA\Property(property="isAnonymous", type="bool", description="Метка анонимности"),
 * )
 */
class AssignTestRequest extends FormRequest
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
            'frequency' => 'nullable|string',
            'startDate' => 'required|string',
            'endDate' => 'nullable|string',
            'isAnonymous' => 'required|boolean',

            'subjectId' => 'nullable|string',
            'assignToAll' => 'required|boolean',
            'groups' => 'nullable|array',
            'employees' => 'nullable|array',
        ];
    }
}
