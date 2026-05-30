<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Ulasan;
use App\Models\Favorite; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApiController extends Controller
{
    // 1. REGISTER PELANGGAN DARI ANDROID
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan',
        ]);

        $token = $user->createToken('android_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    // 2. LOGIN DARI ANDROID
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $token = $user->createToken('android_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    // 3. LOGOUT DARI ANDROID (HAPUS TOKEN)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout.'
        ], 200);
    }

    // 4. AMBIL DATA MENU UNTUK TAMPILAN DI HP ANDROID
    public function getMenus()
    {
        $menus = Menu::where('stok', '>', 0)->get();

        foreach ($menus as $menu) {
            $menu->foto_url = $menu->foto ? url('storage/' . $menu->foto) : null;
        }

        return response()->json([
            'success' => true,
            'data' => $menus
        ], 200);
    }

    // 5. KIRIM PESANAN BARU DARI ANDROID (SUDAH DIPERBARUI UNTUK KERANJANG)
    public function createOrder(Request $request)
    {
        $request->validate([
            'total_harga'       => 'required|string',
            'metode_pembayaran' => 'required|string',
            'nama_pemesan'      => 'required|string',
            'nomor_meja'        => 'required|integer',
            'items'             => 'required|array',
            'catatan'           => 'nullable|string'
        ]);

        $nomorPesananBersama = 'SBK-' . time();
        $savedOrders = [];
        $totalHargaClean = (int) preg_replace('/[^0-9]/', '', $request->total_harga);

        DB::beginTransaction();

        try {
            foreach ($request->items as $item) {
                $itemArray = (array) $item;
                $menuId = $itemArray['menu_id'] ?? $itemArray['menuId'] ?? $itemArray['id'] ?? null;
                $jumlah = $itemArray['jumlah'] ?? $itemArray['qty'] ?? 1;

                if (!$menuId) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'ID Menu tidak terbaca.'], 422);
                }

                $menu = Menu::find($menuId);
                if (!$menu || $menu->stok < $jumlah) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Menu tidak valid atau stok habis.'], 400);
                }

                $dataOrder = [
                    'user_id'       => Auth::id(), 
                    'menu_id'       => $menuId,
                    'jumlah'        => $jumlah,
                    'total_harga'   => $totalHargaClean,
                    'nomor_pesanan' => $nomorPesananBersama,
                    'status'        => 'pending',
                    'catatan'       => $request->catatan ?? '-',
                ];

                if (Schema::hasColumn('orders', 'nama_pemesan')) $dataOrder['nama_pemesan'] = $request->nama_pemesan;
                if (Schema::hasColumn('orders', 'nomor_meja')) $dataOrder['nomor_meja'] = $request->nomor_meja;
                if (Schema::hasColumn('orders', 'metode_pembayaran')) $dataOrder['metode_pembayaran'] = $request->metode_pembayaran;

                $order = Order::create($dataOrder);

                $menu->stok -= $jumlah;
                $menu->save();

                $savedOrders[] = $order;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan Anda berhasil dikirim!',
                'data'    => $savedOrders
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database internal: ' . $e->getMessage()
            ], 500);
        }
    }

    // 6. LIHAT RIWAYAT PESANAN SAYA DI ANDROID
    public function getOrderHistory()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ], 200);
    }

    // 7. FUNGSI UBAH PASSWORD DARI ANDROID (KETIKA USER SUDAH LOGIN DI HALAMAN PROFIL)
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:6',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password lama salah.'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password berhasil diperbarui!'], 200);
    }

    // 8. FUNGSI KIRIM ULASAN DARI ANDROID
    public function createReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'saran_kritik' => 'required|string', 
        ]);

        $review = Ulasan::create([
            'user_id' => Auth::id(), 
            'rating' => $request->rating,
            'saran_kritik' => $request->saran_kritik
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Ulasan Anda sangat membantu kualitas Warung Sobak.',
            'data' => $review
        ], 201);
    }

    // 9. FUNGSI TAMBAH/HAPUS FAVORIT (TOGGLE)
    public function toggleFavorite(Request $request)
    {
        $request->validate(['menu_id' => 'required|integer']);

        $userId = Auth::id();
        $menuId = $request->menu_id;

        $favorite = Favorite::where('user_id', $userId)->where('menu_id', $menuId)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true, 'message' => 'Dihapus dari favorit.', 'is_favorite' => false], 200);
        } else {
            Favorite::create(['user_id' => $userId, 'menu_id' => $menuId]);
            return response()->json(['success' => true, 'message' => 'Ditambahkan ke favorit!', 'is_favorite' => true], 200);
        }
    }

    // 10. AMBIL DAFTAR MENU FAVORIT SAYA
    public function getFavorites()
    {
        $userId = Auth::id();
        $favoriteMenuIds = Favorite::where('user_id', $userId)->pluck('menu_id');
        $menus = Menu::whereIn('id', $favoriteMenuIds)->get();

        foreach ($menus as $menu) {
            $menu->foto_url = $menu->foto ? url('storage/' . $menu->foto) : null;
        }

        return response()->json(['success' => true, 'data' => $menus], 200);
    }
}