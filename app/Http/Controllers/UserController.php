<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // [GET] Mengambil semua data user
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    // [POST] Registrasi User Baru dari Android
    public function store(Request $request)
    {
        // Validasi input dari Android
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Buat user baru (password otomatis dienkripsi)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Defaultnya pasti customer
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil!',
            'data' => $user
        ], 201);
    }
}