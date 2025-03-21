<?php

namespace App\Http\Requests\Template;

use Illuminate\Foundation\Http\FormRequest;

class TemplateAssign extends FormRequest
{
    public function rules(): array
    {
        return [
            'templateId' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'subjectId' => 'nullable|string',
            'frequency' => 'nullable|string',
            'startDate' => 'required|string',
            'endDate' => 'nullable|string',
            'assignToAll' => 'required|boolean',
            'isAnonymous' => 'required|boolean',
            'lateResult' => 'required|boolean',
            'groupIds' => 'nullable|array',
            'employeeIds' => 'nullable|array',
        ];
    }
}
