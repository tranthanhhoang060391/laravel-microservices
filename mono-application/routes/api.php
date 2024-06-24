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
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::post('/product/create', [ProductController::class, 'createProduct']);
    Route::get('/product/{id}', [ProductController::class, 'getProduct']);
    Route::put('/product/update/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'deleteProduct']);

    // Order service routes
    Route::get('/orders', [OrderController::class, 'getOrders']);
    Route::post('/order/create', [OrderController::class, 'createOrder']);
    Route::get('/order/{id}', [OrderController::class, 'getOrder']);
    Route::put('/order/update/{id}', [OrderController::class, 'updateOrder']);
    Route::delete('/order/delete/{id}', [OrderController::class, 'deleteOrder']);
    Route::get('/order/user/{user_id}', [OrderController::class, 'getUserOrders']);
});


