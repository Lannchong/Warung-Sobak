<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ulasan; // Memanggil model Ulasan yang kamu buat

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk dari Android
        $request->validate([
            'user_id' => 'required|integer',
            'rating' => 'required|integer',
            'saran_kritik' => 'required|string',
        ]);

        // 2. Simpan ke database
        $ulasan = Ulasan::create([
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'saran_kritik' => $request->saran_kritik,
        ]);

        // 3. Beri respon sukses ke Android
        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim!',
            'data' => $ulasan
        ], 201);
    }
}