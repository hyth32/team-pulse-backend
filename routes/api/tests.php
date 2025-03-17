<?php

use App\Http\Controllers\Api\V1\TestController;
use Illuminate\Support\Facades\Route;

Route::controller(TestController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/tests', 'list');
    Route::get('/tests/templates', 'templateList');
    Route::get('/tests/{uuid}/users', 'assignedUsers');
    Route::get('/tests/{uuid}/groups', 'assignedGroups');
    Route::post('/tests', 'create');
    Route::put('/tests/{uuid}', 'update');
    Route::get('/tests/{uuid}', 'view');
    Route::delete('/tests/{uuid}', 'delete');
    Route::post('/tests/{uuid}/assign', 'assign');
});
