<?php

use App\Http\Controllers\Api\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::controller(TagController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('tags', 'list');
    Route::post('tags', 'create');
    Route::put('tags/{uuid}', 'update');
    Route::delete('tags/{uuid}', 'delete');
});
