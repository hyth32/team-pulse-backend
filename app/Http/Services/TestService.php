<?php

namespace App\Http\Services;

use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Models\Test;

class TestService
{
    /**
     * Получение списка тестов
     * @param ListTestRequest $request
     */
    public static function list(ListTestRequest $request)
    {
        return Test::skip($request['offset'])->take($request['limit'])->get();
    }

    /**
     * Сохранение теста
     * @param CreateTestRequest $request
     */
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
