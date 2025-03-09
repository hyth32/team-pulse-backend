<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      schema="UpdateTagRequest",
 *      type="object",
 *      description="Тело запроса для обновления тегов",
 *      @OA\Property(
 *         property="tags",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="Стрессоустойчивость", description="Название тега")
 *         )
 *     )
 * )
 */
class UpdateTagRequest extends FormRequest
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
            //
        ];
    }
}
