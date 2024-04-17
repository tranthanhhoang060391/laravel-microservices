<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// Public routes
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/token-revoke', [AuthController::class, 'tokenRevoke']);
    Route::put('/auth/token-refresh', [AuthController::class, 'tokenRefresh']);

    Route::put('/user/update', [UserController::class, 'update']);
    Route::get('/user/profile', [UserController::class, 'profile']);
});
