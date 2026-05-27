<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function googleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'google_id' => 'required|string',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Jika user sudah ada, update google_id nya (jaga-jaga kalau dia dulunya daftar manual)
            $user->update(['google_id' => $request->google_id]);
        } else {
            // Jika user belum ada, daftarkan otomatis (jalur cepat)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'google_id' => $request->google_id,
                'role' => 'pelanggan', // Otomatis jadi pelanggan
                'password' => Hash::make(Str::random(24)) // Bikin password acak karena loginnya pakai Google
            ]);
        }

        // Buat Bearer Token Sanctum
        $token = $user->createToken('AndroidAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Google berhasil',
            'user' => $user,
            'token' => $token // <--- Ini "Bearer Code" yang nanti disimpan di Android
        ]);
    }
}