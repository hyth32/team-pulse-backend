<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/users', 'list')->withoutMiddleware('auth:sanctum');
    Route::post('/users', 'create');
    Route::delete('/users/{uuid}', 'delete');
});
