<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\API\Admin\Posts\PostController;
use App\Http\Controllers\API\Admin\JobTitle\JobTitleController;

Route::middleware(AuthMiddleware::class)->group(function() {
    Route::group(['prefix' => 'v1/admin'], function() {
        // Job Title
        Route::group(['prefix' => 'job-title'], function() {
            Route::get('/', [JobTitleController::class, 'index']);
            Route::post('/', [JobTitleController::class, 'store']);
            Route::get('/{id}', [JobTitleController::class, 'show']);
            Route::put('/{id}', [JobTitleController::class, 'update']);
            Route::delete('/{id}', [JobTitleController::class, 'destroy']);
        });
    
        Route::group(['prefix' => 'posts'], function() {
            Route::post('/', [PostController::class, 'store']);
        });
    });
});