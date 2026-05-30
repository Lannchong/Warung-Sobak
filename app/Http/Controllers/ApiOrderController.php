<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiOrderController extends Controller
{
    // [POST] Membuat pesanan baru (Multi-Menu) dari Android Keranjang
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required|integer',
            'nama_pemesan'      => 'required|string',
            'nomor_meja'        => 'required|integer',
            'total_harga'       => 'required|string',
            'metode_pembayaran' => 'required|string',
            'items'             => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $nomorPesananBersama = 'SBK-' . time();
        $savedOrders = [];
        $totalHargaClean = (int) preg_replace('/[^0-9]/', '', $request->total_harga);

        DB::beginTransaction();

        try {
            foreach ($request->items as $index => $item) {
                
                $itemArray = (array) $item;
                $menuId = $itemArray['menu_id'] ?? $itemArray['menuId'] ?? null;
                $jumlah = $itemArray['jumlah'] ?? $itemArray['qty'] ?? 1;

                if (!$menuId) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Gagal memproses pesanan: Parameter menu_id tidak terbaca oleh server.'
                    ], 422);
                }

                $menu = Menu::find($menuId);
                if (!$menu) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Menu dengan ID ' . $menuId . ' tidak ditemukan di database.'
                    ], 404);
                }

                if ($menu->stok < $jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Stok untuk menu ' . $menu->name . ' tidak mencukupi.'
                    ], 400);
                }

                // Mempertahankan format catatan bawaan sistem kamu
                $catatanOtomatis = "Meja: " . $request->nomor_meja . " | Pembayaran: " . $request->metode_pembayaran . " (Via Keranjang)";

                $dataOrder = [
                    'user_id'       => $request->user_id,
                    'menu_id'       => $menuId,
                    'jumlah'        => $jumlah,
                    'total_harga'   => $totalHargaClean,
                    
                    // 🚀 ANTI DUPLICATE 1062: Ditambah -$index agar baris menu ke-1 dan ke-2 nomornya beda murni!
                    'nomor_pesanan' => $nomorPesananBersama . '-' . $index,
                    
                    'status'        => 'pending',
                    'catatan'       => $request->catatan ?? $catatanOtomatis,
                ];

                if (Schema::hasColumn('orders', 'nama_pemesan')) {
                    $dataOrder['nama_pemesan'] = $request->nama_pemesan;
                }
                if (Schema::hasColumn('orders', 'nomor_meja')) {
                    $dataOrder['nomor_meja'] = $request->nomor_meja;
                }
                if (Schema::hasColumn('orders', 'metode_pembayaran')) {
                    $dataOrder['metode_pembayaran'] = $request->metode_pembayaran;
                }

                $order = Order::create($dataOrder);

                // Potong stok menu
                $menu->stok = $menu->stok - $jumlah;
                $menu->save();

                $savedOrders[] = [
                    'id'      => $order->id,
                    'menu_id' => $order->menu_id,
                    'jumlah'  => $order->jumlah
                ];
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Semua pesanan keranjang berhasil dibuat!',
                'data'    => $savedOrders
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan database internal: ' . $e->getMessage()
            ], 500);
        }
    }
}