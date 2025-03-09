<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="CreateGroupRequest",
 *      type="object",
 *      description="Тело запроса для создания группы",
 *      @OA\Property(property="name", type="string", description="Название группы", example="Frontend"),
 *      @OA\Property(
 *         property="employees",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example="1", description="ID пользователя")
 *         )
 *     )
 * )
 */
class CreateGroupRequest extends FormRequest
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
            'employees' => 'nullable|array',
            'employees.*.id' => 'required|integer',
        ];
    }
}
