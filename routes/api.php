<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;


// Endpoint untuk User (Login & Registrasi)
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// Endpoint untuk Manajemen Menu (Soto & Bakso)
Route::get('/menus', [MenuController::class, 'index']);       // GET semua menu
Route::post('/menus', [MenuController::class, 'store']);      // POST menu baru (Admin)
Route::put('/menus/{id}', [MenuController::class, 'update']); // PUT update menu (Admin)
Route::delete('/menus/{id}', [MenuController::class, 'destroy']); // DELETE menu (Admin)

// Endpoint untuk Transaksi / Order
Route::get('/orders', [OrderController::class, 'index']);       // 1. GET semua order
Route::post('/orders', [OrderController::class, 'store']);      // 2. POST transaksi baru
Route::get('/orders/{id}', [OrderController::class, 'show']);   // 3. GET cek 1 nota spesifik (BARU)
Route::put('/orders/{id}', [OrderController::class, 'update']);   // 4. PUT ubah status order
Route::get('/orders/ringkasan/all', [OrderController::class, 'ringkasan']); // 5. GET laporan omzet (BARU)
Route::delete('/orders/{id}', [OrderController::class, 'destroy']); // 6. DELETE hapus order (BARU)
// ==========================================

// 2. ENDPOINT WEB ADMIN (Bungkus Middleware Web)
// ==========================================
Route::middleware('web')->group(function () {

Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
// Halaman untuk form tambah data pengguna baru
Route::get('/admin/user/create', [DashboardController::class, 'create'])->name('users.create');
// BENAR (Ubah ke UserController)
Route::post('/admin/user/store', [UserController::class, 'store'])->name('users.store');

});