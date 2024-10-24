<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\ProfileController;
use App\Http\Controllers\API\Auth\RegisterController;

Route::group(['prefix' => 'v1/auth'], function() {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/me', [ProfileController::class, 'me'])->middleware(AuthMiddleware::class);
    Route::post('/update', [ProfileController::class, 'update'])->middleware(AuthMiddleware::class);
});