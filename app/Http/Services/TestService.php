<?php

namespace App\Http\Services;

use App\Http\Requests\Test\CreateTestRequest;
use App\Models\Test;

class TestService
{
    public function save(CreateTestRequest $request)
    {
        $data = $request->validated();

        $periodicity = $data['periodicity'];
        $questions = $data['questions'];
        $groups = $data['groups'];
        $employees = $data['employees'];

        return $data;
    }
}
