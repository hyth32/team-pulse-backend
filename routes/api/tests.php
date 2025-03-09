<?php

use App\Http\Controllers\Api\V1\TestController;
use Illuminate\Support\Facades\Route;

Route::controller(TestController::class)->group(function () {
    Route::get('/tests', 'list');
    Route::post('/tests', 'create');
    Route::put('/tests/{uuid}', 'update');
    Route::get('/tests/{uuid}', 'view');
    Route::delete('/tests/{uuid}', 'delete');
    Route::post('/tests/{uuid}/assign', 'assign');
});
