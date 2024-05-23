<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductServiceController;

// Public routes
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/token-revoke', [AuthController::class, 'tokenRevoke']);
    Route::put('/auth/token-refresh', [AuthController::class, 'tokenRefresh']);

    Route::put('/user/update', [UserController::class, 'update']);
    Route::get('/user/profile', [UserController::class, 'profile']);

    // Product service routes
    Route::get('/products', [ProductServiceController::class, 'getProducts']);
    Route::post('/product/create', [ProductServiceController::class, 'createProduct']);
    Route::get('/product/{id}', [ProductServiceController::class, 'getProduct']);
    Route::put('/product/update/{id}', [ProductServiceController::class, 'updateProduct']);
    Route::delete('/product/delete/{id}', [ProductServiceController::class, 'deleteProduct']);

});
