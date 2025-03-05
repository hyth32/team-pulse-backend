<?php

namespace App\Http\Services;

use App\Http\Requests\Test\CreateTestRequest;
use App\Http\Requests\Test\ListTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use Illuminate\Http\Request;
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

        $questions = $data['questions'];
        

        $periodicity = $data['periodicity'];
        $groups = $data['groups'];
        $employees = $data['employees'];

        return $data;
    }

    /**
     * Обновление теста
     * @param UpdateTestRequest $request
     */
    public function update(string $uuid, UpdateTestRequest $request)
    {
        $data = $request->validated();

        $test = Test::findOrFail($uuid);
        $test->update($data);

        return $test;
    }

    /**
     * Получение теста по id
     * @param string $uuid
     */
    public static function view(string $uuid)
    {
        return Test::findOrFail($uuid);
    }

    /**
     * Удаление теста по id
     * @param string $uuid
     */
    public function delete(string $uuid)
    {
        $test = Test::findOrFail($uuid);
        return $test->delete();
    }
}
