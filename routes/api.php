<?php

use App\Http\Controllers\Api\V1\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::controller(TestController::class)->group(function () {
        Route::get('/tests', 'list');
        Route::post('/tests', 'create');
        Route::put('/tests/{uuid}', 'update');
        Route::get('/tests/{uuid}', 'view');
        Route::delete('/tests/{uuid}', 'delete');
    });
});
