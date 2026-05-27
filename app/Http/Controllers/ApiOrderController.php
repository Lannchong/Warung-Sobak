<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class ApiOrderController extends Controller
{
    // [POST] Fungsi menerima pesanan dari Android & memotong stok
    public function store(Request $request)
    {
        // 1. Validasi data yang dikirim dari aplikasi Android
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'menu_id'     => 'required|exists:menus,id',
            'jumlah'      => 'required|integer|min:1',
            'total_harga' => 'required|numeric',
        ]);

        // 2. Gunakan Database Transaction agar aman (jika ada 1 error, semua proses dibatalkan)
        DB::beginTransaction();

        try {
            // 3. Ambil data menu dan kunci datanya agar tidak bentrok saat diakses user lain bersamaan
            $menu = Menu::lockForUpdate()->find($request->menu_id);

            // 4. Cek apakah stok menu masih mencukupi
            if ($menu->stok < $request->jumlah) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Maaf, stok ' . $menu->nama_menu . ' tidak mencukupi. Sisa stok: ' . $menu->stok
                ], 400);
            }

            // 5. PROSES POTONG STOK
            $menu->stok -= $request->jumlah;
            $menu->save();

            // 6. SIMPAN DATA KE TABEL ORDERS
            $order = new Order();
            $order->user_id       = $request->user_id;
            $order->menu_id       = $request->menu_id;
            $order->jumlah        = $request->jumlah;
            $order->total_harga   = $request->total_harga;
            
            // Otomatis bikin nomor pesanan misal: SBK-1698765432
            $order->nomor_pesanan = $request->nomor_pesanan ?? 'SBK-' . time(); 
            $order->catatan       = $request->catatan; // Sekalian simpan catatan pembeli
            
            $order->status        = 'pending'; // Status awal saat pertama kali dipesan
            $order->save();

            // Selesai dan simpan permanen ke database
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pesanan berhasil dibuat, stok ' . $menu->nama_menu . ' otomatis berkurang!',
                'data'    => $order
            ], 201);

        } catch (\Exception $e) {
            // Jika ada error di tengah jalan, batalkan semua perubahan stok
            DB::rollback();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    // [GET] Mengambil riwayat pesanan khusus untuk 1 user/pelanggan tertentu
    public function userOrders($user_id)
    {
        // Mengambil pesanan berdasarkan user_id, beserta data relasi menunya
        $orders = Order::with('menu')
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Berhasil mengambil riwayat pesanan pelanggan',
            'data'    => $orders
        ], 200);
    }
}