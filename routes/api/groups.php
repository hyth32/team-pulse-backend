<?php

use App\Http\Controllers\Api\V1\GroupController;
use Illuminate\Support\Facades\Route;

Route::controller(GroupController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/groups', 'list');
    Route::post('/groups', 'create');
    Route::put('/groups/{uuid}', 'update');
    Route::delete('/groups/{uuid}', 'delete');
});
