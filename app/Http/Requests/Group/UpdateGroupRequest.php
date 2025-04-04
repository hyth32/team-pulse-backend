<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="UpdateGroupRequest",
 *      type="object",
 *      description="Тело запроса для обновления группы",
 *      @OA\Property(property="name", type="string", description="Название группы", example="Frontend"),
 *      @OA\Property(property="employees", type="array",
 *         @OA\Items(type="string", format="uuid", example="123e4567-e89b-12d3-a456-426614174000", description="ID пользователя")
 *     ),
 * )
 */
class UpdateGroupRequest extends FormRequest
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
            'name' => 'nullable|string',
            'employees' => 'nullable|array',
        ];
    }
}
