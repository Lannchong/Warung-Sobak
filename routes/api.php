<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;



// 1. ENDPOINT API (Untuk Aplikasi Android Warung Sobak)

// ---> BAGIAN AUTH & USER (Semua dipusatkan di UserController biar nggak ribet)
Route::post('/login', [UserController::class, 'loginUser']);       // Android Login Pelanggan
Route::post('/admin/login', [UserController::class, 'loginAdmin']);// Android Login Admin
Route::post('/register', [UserController::class, 'store']);        // Android Register Akun Baru
Route::post('/logout', [UserController::class, 'logout']);         // Android Logout (Hapus Token)
Route::get('/users', [UserController::class, 'index']);            // Ambil data semua user


// ---> Bagian Manajemen Menu (Soto & Bakso)
Route::get('/menus', [MenuController::class, 'index']);            // GET semua menu
Route::post('/menus', [MenuController::class, 'store']);           // POST menu baru (Admin)
Route::put('/menus/{id}', [MenuController::class, 'update']);      // PUT update menu (Admin)
Route::delete('/menus/{id}', [MenuController::class, 'destroy']);  // DELETE menu (Admin)


// ---> Bagian Transaksi / Order
Route::get('/orders', [OrderController::class, 'index']);          // GET semua order
Route::post('/orders', [OrderController::class, 'store']);         // POST transaksi baru
Route::put('/orders/{id}', [OrderController::class, 'update']);    // PUT ubah status order
Route::delete('/orders/{id}', [OrderController::class, 'destroy']);// DELETE membatalkan pesanan



// 2. ENDPOINT WEB ADMIN (Bungkus Middleware Web)


Route::middleware('web')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    // Halaman untuk form tambah data pengguna baru
    Route::get('/admin/user/create', [DashboardController::class, 'create'])->name('users.create');
    // Menyimpan data user dari Web Admin (tetap pakai UserController)
    Route::post('/admin/user/store', [UserController::class, 'store'])->name('users.store');
});