<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

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
            // Jika user sudah ada, update google_id nya
            $user->update(['google_id' => $request->google_id]);
        } else {
            // Jika user belum ada, daftarkan otomatis (jalur cepat)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'google_id' => $request->google_id,
                'role' => 'pelanggan', // Otomatis jadi pelanggan
                'password' => Hash::make(Str::random(24)) // Bikin password acak
            ]);
        }

        // Buat Bearer Token Sanctum
        $token = $user->createToken('AndroidAppToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Google berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }

    // PERBAIKAN TOTAL: Mengaktifkan pengiriman email asli melalui Laravel Mailer Broker
    public function forgotPassword(Request $request)
    {
        // 1. Validasi input email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format email tidak valid.'
            ], 422);
        }

        // 2. Cek apakah email terdaftar di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak terdaftar di sistem kami.'
            ], 404);
        }

        try {
            // 3. Eksekusi Kirim Link Reset Password melalui SMTP Google yang ada di .env
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Instruksi reset password telah dikirim ke email Anda.'
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat token reset, silakan coba beberapa saat lagi.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ], 500);
        }
    }

    // =========================================================================
    // FUNGSI BARU: Memproses input token dan memperbarui password dari Android
    // =========================================================================
    public function resetPassword(Request $request)
    {
        // 1. Validasi format masukan yang dikirim oleh Retrofit Android
        $validator = Validator::make($request->all(), [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:5|confirmed', // 'confirmed' otomatis mengecek field password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        try {
            // 2. Eksekusi pembaruan sandi via Password Broker bawaan Laravel
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    // Update password baru dan langsung enkripsi otomatis
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            // 3. Respon kembali ke aplikasi Android sesuai hasil statusnya
            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password Anda berhasil diperbarui! Silakan login kembali.'
                ], 200);
            }

            // Jika token salah, email beda, atau masa berlaku token habis (default 60 menit)
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password. Token tidak valid atau sudah kedaluwarsa.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database internal: ' . $e->getMessage()
            ], 500);
        }
    }
}