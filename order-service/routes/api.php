<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/order/create', [OrderController::class, 'create']);
Route::get('/order/{id}', [OrderController::class, 'show']);
Route::put('/order/update/{id}', [OrderController::class, 'update']);
Route::delete('/order/delete/{id}', [OrderController::class, 'delete']);
