<?php

use App\Http\Controllers\Api\V1\TemplateController;
use Illuminate\Support\Facades\Route;

Route::controller(TemplateController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/templates', 'list');
    Route::post('/templates', 'create');
    Route::put('/templates/{uuid}', 'update');

    Route::post('/templates/{uuid}/assign', 'assign');
    
    Route::get('/templates/{uuid}/topics/{topicUuid}', 'topicQuestions');

    Route::delete('/templates/{uuid}', 'delete');
});
