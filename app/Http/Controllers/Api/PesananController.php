<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan; 
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input utama dari Android
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'nama_pemesan' => 'required',
            'nomor_meja' => 'required',
            'total_harga' => 'required',
            'metode_pembayaran' => 'required',
            'items' => 'required|array', // Harus berupa list/array menu
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
            ], 422);
        }

        $teksMenuGabungan = [];
        $totalPorsiSemua = 0;

        // Looping list items di dalam Laravel untuk menyusun teks nama menu
        foreach ($request->items as $item) {
            $namaMenuReal = "Menu ID: " . $item['menuId'];
            
            switch ($item['menuId']) {
                case 1: $namaMenuReal = "Bakso Urat"; break;
                case 3: $namaMenuReal = "Soto Ayam Ambengan"; break;
                case 4: $namaMenuReal = "Soto Daging Sapi"; break;
                case 5: $namaMenuReal = "Soto Mie Bogor"; break;
                case 6: $namaMenuReal = "Bakso Telur Spesial"; break;
                case 7: $namaMenuReal = "Bakso Urat Jumbo"; break;
                case 8: $namaMenuReal = "Bakso Mercon"; break;
                case 9: $namaMenuReal = "Nasi Putih"; break;
                case 10: $namaMenuReal = "Es Teh Manis"; break;
                case 11: $namaMenuReal = "Es Jeruk Peras"; break;
                case 12: $namaMenuReal = "Es Campur"; break;
                case 13: $namaMenuReal = "Kopi Hitam Panas"; break;
            }

            $teksMenuGabungan[] = $namaMenuReal . " (" . $item['jumlah'] . "x)";
            $totalPorsiSemua += $item['jumlah'];
        }

        // Gabungkan array menjadi string teks tunggal dipisahkan koma
        $stringMenuFinal = implode(', ', $teksMenuGabungan);

        // Pastikan format total harga memiliki awalan "Rp " agar sinkron dengan web admin
        $totalHargaFormat = $request->total_harga;
        if (!str_contains($totalHargaFormat, 'Rp')) {
            $totalHargaFormat = "Rp " . number_format((int)$totalHargaFormat, 0, ',', '.');
        }

        // Simpan hanya SATU baris data nota ke database
        $pesanan = Pesanan::create([
            'nomor_meja' => $request->nomor_meja,
            'nama_pemesan' => $stringMenuFinal, // Kolom ini menampung list semua menu rapi
            'jumlah_porsi' => $totalPorsiSemua,  // Total porsi keseluruhan akumulasi
            'total_harga' => $totalHargaFormat,  // Mengikuti total harga asli dari Android
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'Menunggu'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil disimpan dalam satu nota!',
            'data' => $pesanan 
        ], 200);
    }
}