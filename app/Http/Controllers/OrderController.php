<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. Menampilkan halaman Kelola Order di Web Admin
    public function index()
    {
        // Ambil semua order dari yang terbaru, beserta data user (pelanggan) dan menu-nya
        $orders = Order::with(['user', 'menu'])->orderBy('created_at', 'desc')->get();
        
        return view('admin.orders', compact('orders'));
    }

    // 2. Mengubah status pesanan (Pending -> Diproses -> Selesai)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,dibatalkan'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // 3. Menangani pesanan baru yang masuk dari API / Postman (BARU DITAMBAHKAN)
    public function store(Request $request)
    {
        // Menyimpan data pesanan ke database
        $order = Order::create([
            'user_id'       => $request->user_id,
            'menu_id'       => $request->menu_id,
            'jumlah'        => $request->jumlah,
            'total_harga'   => $request->total_harga,
            // Jika nomor_pesanan dikosongkan di Postman, otomatis bikin format: SBK-JamSaatIni
            'nomor_pesanan' => $request->nomor_pesanan ?? 'SBK-' . time(), 
            'catatan'       => $request->catatan,
        ]);

        // Mengirimkan balasan sukses ke Postman
        return response()->json([
            'status'  => 'success',
            'message' => 'Pesanan berhasil dibuat!',
            'data'    => $order
        ], 201);
    }
}