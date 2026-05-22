<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class DashboardController extends Controller
{
    // Mengubah nama fungsi dari index menjadi dashboard
    public function dashboard(Request $request) 
    {
        $search = $request->input('search');

        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->get();
        } else {
            $users = User::all(); 
        }
        // KODE YANG BENAR (Baru)
        return view('admin.dashboard', compact('users'));
    }

    public function create()
    {
    // Mengarahkan ke file resources/views/admin/create.blade.php
    return view('admin.create');
    }

    // Tambahkan method store ini
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        // 2. Proses simpan ke database (Contoh: User::create(...))
        
        // 3. Kembalikan respons atau redirect
        return redirect()->route('dashboard')->with('success', 'User berhasil ditambahkan!');
    }
}