<?php

use App\Http\Controllers\Api\V1\TestController;
use Illuminate\Support\Facades\Route;

Route::controller(TestController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/tests', 'list');

    Route::post('/tests/assign', 'assign');

    Route::get('/tests/{uuid}/users', 'assignedUsers');
    Route::get('/tests/{uuid}/groups', 'assignedGroups');
});
