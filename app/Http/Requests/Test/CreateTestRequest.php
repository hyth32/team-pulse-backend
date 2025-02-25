<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'nullable',
            'description' => 'nullable',
            'type' => 'nullable',
            'periodicity' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'assignee_id' => 'nullable',
        ];
    }
}
