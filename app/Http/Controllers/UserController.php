<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // --- AMBIL SEMUA DATA USER ---
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    // --- REGISTRASI (Android Pelanggan) ---
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'pelanggan', 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil dibuat! Silakan login.'
        ], 201);
    }

    // --- LOGIN USER / PELANGGAN (Android) ---
    public function loginUser(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau Password salah!'], 401);
        }

        // Bikin token buat Android
        $token = $user->createToken('android-token')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'user'    => $user,
            'token'   => $token
        ], 200);
    }

    // --- LOGOUT (Android) ---
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil!']);
    }

    // --- AMBIL DATA PROFIL 1 USER (Android) ---
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil data profil',
            'data' => $user
        ], 200);
    }
}