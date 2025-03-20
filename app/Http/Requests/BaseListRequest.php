<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseListRequest extends FormRequest
{
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
