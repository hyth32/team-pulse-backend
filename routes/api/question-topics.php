<?php

use App\Http\Controllers\Api\V1\QuestionTopicController;
use Illuminate\Support\Facades\Route;

Route::controller(QuestionTopicController::class)->group(function () {
    Route::get('/question-topics', 'list');
    Route::post('/question-topics', 'create');
    Route::put('/question-topics/{uuid}', 'update');
    Route::delete('/question-topics/{uuid}', 'delete');
});
