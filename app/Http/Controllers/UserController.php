<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

    // [POST] Registrasi User Baru (Bisa dari Android & Web Admin)
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default role tetap customer
        ]);

        // PINTAR: Jika yang request Android (minta JSON), kirim data JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil!',
                'data' => $user
            ], 201);
        }

        // Jika yang request browser Web Admin, lakukan redirect
        return redirect()->back()->with('success', 'Registrasi berhasil!');
    }

    // [POST] Login Khusus User/Pelanggan dari Android
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada dan password-nya benar
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah!'
            ], 401);
        }

        // Pastikan yang login adalah customer, bukan admin
        if ($user->role !== 'customer') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Akun ini terdaftar sebagai Admin.'
            ], 403);
        }

        // Cek dan cetak TOKEN Sanctum untuk Android
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Pelanggan Berhasil!',
            'token' => $token,
            'data' => $user
        ], 200);
    }

    // [POST] Login Khusus Admin dari Android
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah!'
            ], 401);
        }

        // Pastikan yang login memang role-nya Admin
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak! Anda bukan Admin.'
            ], 403);
        }

        // Cetak TOKEN Sanctum khusus Admin
        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Admin Berhasil!',
            'token' => $token,
            'data' => $user
        ], 200);
    }

    // [POST] Logout untuk menghapus token di Android
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout, token dihapus!'
        ], 200);
    }
}