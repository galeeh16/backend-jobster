<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\API\Post\PostController;

Route::group(['prefix' => 'v1/posts'], function() {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{id}', [PostController::class, 'show']);
    Route::post('/apply/{id}', [PostController::class, 'apply'])->middleware(AuthMiddleware::class);
});