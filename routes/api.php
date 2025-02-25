<?php

use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\QuestionTopicController;
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

    Route::controller(GroupController::class)->group(function () {
        Route::get('/groups', 'list');
        Route::post('/groups', 'create');
        Route::put('/groups/{uuid}', 'update');
        Route::delete('/groups/{uuid}', 'delete');
    });

    Route::controller(QuestionTopicController::class)->group(function () {
        Route::get('/question-topics', 'list');
        Route::post('/question-topics', 'create');
        Route::put('/question-topics/{uuid}', 'update');
        Route::delete('/question-topics/{uuid}', 'delete');
    });
});
