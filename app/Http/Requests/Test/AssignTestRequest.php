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
*       @OA\Property(property="startDate", type="string", format="date-time", description="Временная метка начала прохождения теста"),
*       @OA\Property(property="endDate", type="string", format="date-time", description="Временная метка окончания прохождения теста"),
*       @OA\Property(property="groups", type="array",
*           @OA\Items(type="string", format="uuid", description="ID группы")
*       ),
*       @OA\Property(property="employees", type="array",
*           @OA\Items(type="integer", description="ID пользователя", example="1"),
*       )
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
            'frequency' => 'nullable|integer',
            'startDate' => 'required|string',
            'endDate' => 'nullable|string',

            'groups' => 'nullable|array',
            'employees' => 'nullable|array'
        ];
    }
}
