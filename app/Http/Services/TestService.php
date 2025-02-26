<?php

namespace App\Http\Services;

use App\Http\Requests\Test\CreateTestRequest;
use App\Models\Test;

class TestService
{
    public function save(CreateTestRequest $request)
    {
        $data = $request->validated();
        $test = Test::create($data);

        return $test;
    }
}
