<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceAccountController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::get('/product/{id}', [ProductController::class, 'show']);
    Route::put('/product/update/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete']);
});

Route::post('service-accounts/token', [ServiceAccountController::class, 'issueToken']);
