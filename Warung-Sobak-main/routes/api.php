<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;

// ============================================================
// 1. ENDPOINT UNTUK USER (Login & Registrasi)
// ============================================================
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// ============================================================
// 2. ENDPOINT UNTUK MANAJEMEN MENU (Soto & Bakso)
// ============================================================
Route::get('/menus', [MenuController::class, 'index']);           // GET semua menu
Route::post('/menus', [MenuController::class, 'store']);          // POST menu baru (Admin)
Route::put('/menus/{id}', [MenuController::class, 'update']);     // PUT update menu (Admin)
Route::delete('/menus/{id}', [MenuController::class, 'destroy']); // DELETE menu (Admin)

// ============================================================
// 3. ENDPOINT UNTUK TRANSAKSI / ORDER (SUDAH DIUPDATE LENGKAP)
// ============================================================
Route::get('/orders', [OrderController::class, 'index']);             // GET semua order
Route::post('/orders', [OrderController::class, 'store']);            // POST transaksi baru dari Android
Route::get('/orders/ringkasan', [OrderController::class, 'ringkasan']); // GET total omset & nota (Admin)
Route::get('/orders/{id}', [OrderController::class, 'show']);         // GET detail 1 nota spesifik
Route::put('/orders/{id}', [OrderController::class, 'update']);       // PUT ubah status order (Admin)
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);   // DELETE hapus transaksi permanen

// ============================================================
// 4. ENDPOINT WEB ADMIN
// ============================================================
Route::middleware('web')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/user/create', [DashboardController::class, 'create'])->name('users.create');
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('users.store');
});