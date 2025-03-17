<?php

namespace App\Http\Requests\Test;

use App\Http\Requests\BaseListRequest;

class ListAssignedGroupsRequest extends BaseListRequest
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
        $rules = [];
        return array_merge(parent::rules(), $rules);
    }
}
