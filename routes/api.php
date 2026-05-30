<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\PesananController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ApiOrderController; 

// =========================================================================
// RUTE JALUR BEBAS (Tanpa Perlu Login / Token)
// =========================================================================
Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']); 

// Fitur Lupa Password (Kirim Link Token ke Gmail)
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']); 

// RUTE BARU (Pilihan 1): Memproses token & ganti password baru dari Android
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/menus', [ApiController::class, 'getMenus']);
Route::post('/menus', [MenuController::class, 'store']); 

// =========================================================================
// RUTE JALUR AMAN (Android Wajib Kirim Token)
// =========================================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiController::class, 'logout']);
    
    // --- API PESANAN MULTI-MENU (KERANJANG) ANDROID & POSTMAN ---
    Route::post('/orders', [ApiOrderController::class, 'store']); 
    
    Route::post('/buat-pesanan', [PesananController::class, 'store']);
    Route::get('/orders/history', [ApiController::class, 'getOrderHistory']);
    
    // API Ubah Password (Saat User Sudah Login)
    Route::post('/change-password', [ApiController::class, 'changePassword']);
    
    // API Tambah Ulasan
    Route::post('/create-review', [ApiController::class, 'createReview']);
    
    // --- API FAVORIT ---
    Route::post('/favorites/toggle', [ApiController::class, 'toggleFavorite']);
    Route::get('/favorites', [ApiController::class, 'getFavorites']);
});