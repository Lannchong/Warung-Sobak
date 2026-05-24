<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. [GET] Mengambil semua data pesanan
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    // 2. [POST] Membuat pesanan baru dari Android
    public function store(Request $request)
    {
        // Validasi data yang dikirim Android agar tidak ada data kosong/rusak
        $validated = $request->validate([
            'user' => 'required|string',
            'menu' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|integer',
        ]);

        // Secara otomatis set status awal pesanan menjadi 'diproses'
        $validated['status'] = 'diproses'; 

        // Menyimpan data yang sudah lolos validasi (lebih aman)
        $order = Order::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat!',
            'data' => $order
        ], 201);
    }

    // 3. [GET] Mengambil 1 data pesanan spesifik berdasarkan ID (Cek Status Nota)
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    // 4. [PUT] Mengubah status pesanan (Oleh Admin/Kasir)
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        // Memastikan status yang diinput hanya bisa antara: diproses, selesai, atau dibatalkan
        $request->validate([
            'status' => 'required|string|in:diproses,selesai,dibatalkan'
        ]);

        // Update status baru
        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status pesanan berhasil diupdate!',
            'data' => $order
        ], 200);
    }

    // 5. [GET] Fitur Simpel Ringkasan Pendapatan & Total Nota untuk Owner/Admin
    public function ringkasan()
    {
        return response()->json([
            'status' => 'success',
            'total_pesanan' => Order::count(),
            'total_pendapatan' => Order::sum('total_price')
        ], 200);
    }

    // 6. [DELETE] Menghapus pesanan secara permanen dari database
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan atau sudah dihapus'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dihapus dari sistem!'
        ], 200);
    }
}