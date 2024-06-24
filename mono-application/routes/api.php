<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::put('/product/update/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete']);

    // Order service routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/order/create', [OrderController::class, 'create']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::put('/order/update/{id}', [OrderController::class, 'update']);
    Route::delete('/order/delete/{id}', [OrderController::class, 'delete']);
    Route::get('/order/user/{user_id}', [OrderController::class, 'userOrders']);
});


