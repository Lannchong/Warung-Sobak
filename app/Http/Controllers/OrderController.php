<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // [GET] Mengambil semua data pesanan
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    // [POST] Membuat pesanan baru dari Android
    public function store(Request $request)
    {
        // Validasi data yang dikirim Android
        $validated = $request->validate([
            'user' => 'required|string',
            'menu' => 'required|string',
            'quantity' => 'required|integer',
            'total_price' => 'required|integer',
        ]);

        // Simpan pesanan ke database (status otomatis 'diproses')
        $order = Order::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan berhasil dibuat!',
            'data' => $order
        ], 201);
    }

    // [PUT] Mengubah status pesanan (oleh Admin)
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // Admin biasanya hanya mengubah status (misal dari 'diproses' jadi 'selesai')
        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status pesanan berhasil diupdate!',
            'data' => $order
        ], 200);
    }
}