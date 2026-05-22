<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController; // Pastikan baris ini ada!

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});
// // Halaman utama sekaligus menghandle fitur Search
// // Ubah bagian akhir dari 'index' menjadi 'dashboard'
// Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
// // Halaman untuk form tambah data pengguna baru
// Route::get('/admin/user/create', [DashboardController::class, 'create'])->name('users.create');
// // BENAR (Ubah ke UserController)
// Route::post('/admin/user/store', [UserController::class, 'store'])->name('users.store');