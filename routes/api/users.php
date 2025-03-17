<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/users', 'list');
    Route::post('/users', 'create');
    Route::get('/users/{uuid}', 'profile');
    Route::put('/users/{uuid}', 'changeProfile');
    Route::delete('/users/{uuid}', 'delete');
});
