<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;


// ========================================================
// 1. RUTE LUAR (TIDAK MASUK PREFIX ADMIN)
// ========================================================

// Halaman Utama: Otomatis dialihkan ke login admin
Route::get('/', function () {
    return redirect('/admin/login');
});

// Tampilan Halaman Login
Route::get('/admin/login', function () {
    return view('admin.login'); 
})->name('login');

// Proses Eksekusi Login
Route::post('/admin/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard'); 
    }

    return back()->withErrors([
        'email' => 'Email atau password yang Anda masukkan salah.',
    ])->onlyInput('email');
})->name('login.post');

// Proses Eksekusi Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin/login'); 
})->name('logout');


// ========================================================
// 2. KELOMPOK RUTE KHUSUS ADMIN (OTOMATIS DIAWALI /admin/...)
// ========================================================
Route::middleware('web')->prefix('admin')->group(function () {
    
    // Halaman Dashboard Utama & Menu Lainnya
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::get('/menus', [DashboardController::class, 'menus'])->name('admin.menus');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('admin.orders');
    
    // --- RUTE UPDATE STATUS PESANAN (SUDAH DISUAIKAN DENGAN BLADE) ---
    Route::post('/orders/{id}/status', [DashboardController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    
    // Tampilan Halaman Pengaturan
    Route::get('/pengaturan', function () {
        return view('admin.settings'); 
    })->name('admin.settings');

    // Proses Simpan Profil (Nama & Email) 
    Route::post('/pengaturan/profil', function (Request $request) {
        $user = \App\Models\User::find(Auth::id()); 
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
        
        $user->update(['name' => $request->name, 'email' => $request->email]);
        return back()->with('success', 'Profil berhasil diperbarui!');
    })->name('admin.settings.updateProfile');

    // Proses Simpan Password Baru
    Route::post('/pengaturan/password', function (Request $request) {
        $user = \App\Models\User::find(Auth::id());
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:5',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password berhasil diubah!');
    })->name('admin.settings.updatePassword');

    // Rute Pengelolaan Data Terkait Dashboard
    Route::get('/users/create', [DashboardController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [DashboardController::class, 'store'])->name('admin.users.store');
    Route::get('/menus/create', [DashboardController::class, 'createMenu'])->name('admin.menus.create');
    Route::post('/menus', [DashboardController::class, 'storeMenu'])->name('admin.menus.store');
    Route::get('/menus/{id}/edit', [DashboardController::class, 'editMenu'])->name('admin.menus.edit');
    Route::put('/menus/{id}', [DashboardController::class, 'updateMenu'])->name('admin.menus.update');
    Route::delete('/menus/{id}', [DashboardController::class, 'destroyMenu'])->name('admin.menus.destroy');
    
});