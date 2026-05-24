<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // --- FUNGSI REGISTER ---
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Set default sebagai pelanggan
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Register berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // --- FUNGSI LOGIN (Pelanggan) ---
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau Password salah'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // --- FUNGSI LOGIN ADMIN ---
    public function loginAdmin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada, password benar, dan role-nya 'admin'
        if (!$user || !Hash::check($request->password, $user->password) || $user->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak: Bukan akun admin'], 401);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Admin berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    // --- FUNGSI LOGOUT ---
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
}