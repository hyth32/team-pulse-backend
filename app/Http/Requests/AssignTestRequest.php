<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="AssignTestRequest",
 *      type="object",
 *      description="Тело запроса для назначения теста",
 *      @OA\Property(
 *         property="periodicity",
 *         type="object",
 *         @OA\Property(property="name", type="string", example="Каждую неделю", description="Название периодичности"),
 *         @OA\Property(
 *             property="timeframe",
 *             type="object",
 *             @OA\Property(property="from", type="integer", example=1740566491, description="Временная метка начала теста"),
 *             @OA\Property(property="to", type="integer", example=1740566491, description="Временная метка окончания теста")
 *         )
 *     ),
 *     @OA\Property(property="start_date", type="integer", example=1740566491, description="Временная метка начала прохождения теста"),
 *     @OA\Property(property="end_date", type="integer", example=1740566491, description="Временная метка окончания прохождения теста"),
 *     @OA\Property(
 *         property="groups",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Frontend", description="Название группы")
 *         )
 *     ),
 *     @OA\Property(
 *         property="employees",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="id", example="1", description="ID пользователя")
 *         )
 *     )
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
            'employees.*.id' => 'required|integer',
        ];
    }
}
