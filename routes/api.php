<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AuthController; // <--- Tambahan untuk Google Login

// RUTE JALUR BEBAS (Tanpa Perlu Login)
Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']); // <--- Rute Google Login Baru
Route::get('/menus', [ApiController::class, 'getMenus']);

// RUTE JALUR AMAN (Android Harus Kirim Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::post('/orders', [ApiController::class, 'createOrder']);
    Route::get('/orders/history', [ApiController::class, 'getOrderHistory']);
});