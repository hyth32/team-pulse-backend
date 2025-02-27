<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->mergeIfMissing([
            'offset' => 0,
            'limit' => 10,
        ]);
        return [
            'offset' => 'integer',
            'limit' => 'integer',
        ];
    }
}
