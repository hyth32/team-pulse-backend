<?php

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Routing\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('users', 'list');
    Route::post('users', 'create');
});