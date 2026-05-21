<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;

// Endpoint untuk User (Login & Registrasi)
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// Endpoint untuk Manajemen Menu (Soto & Bakso)
Route::get('/menus', [MenuController::class, 'index']);       // GET semua menu
Route::post('/menus', [MenuController::class, 'store']);      // POST menu baru (Admin)
Route::put('/menus/{id}', [MenuController::class, 'update']); // PUT update menu (Admin)
Route::delete('/menus/{id}', [MenuController::class, 'destroy']); // DELETE menu (Admin)

// Endpoint untuk Transaksi / Order
Route::get('/orders', [OrderController::class, 'index']);     // GET semua order
Route::post('/orders', [OrderController::class, 'store']);    // POST transaksi baru
Route::put('/orders/{id}', [OrderController::class, 'update']); // PUT ubah status orderphp artisan serve --host=0.0.0.0