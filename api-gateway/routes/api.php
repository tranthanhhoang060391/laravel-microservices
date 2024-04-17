<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TokenController;

Route::post('/user/register', [UserController::class, 'register']);
Route::put('/user/update', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::get('/user/profile', [UserController::class, 'profile'])->middleware('auth:sanctum');

Route::post('/token/create', [TokenController::class, 'create']);
Route::delete('/token/delete', [TokenController::class, 'revoke'])->middleware('auth:sanctum');
Route::put('/token/refresh', [TokenController::class, 'refresh'])->middleware('auth:sanctum');

