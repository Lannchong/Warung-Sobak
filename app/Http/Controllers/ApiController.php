<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
            'role' => 'pelanggan', // Otomatis jadi pelanggan
        ]);

        // Membuat token akses keamanan untuk Android
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
        // Hanya mengambil menu yang stoknya di atas 0
        $menus = Menu::where('stok', '>', 0)->get();

        // Otomatis buat URL foto penuh agar Android mudah load gambar
        foreach ($menus as $menu) {
            $menu->foto_url = $menu->foto ? url('storage/' . $menu->foto) : null;
        }

        return response()->json([
            'success' => true,
            'data' => $menus
        ], 200);
    }

    // 5. KIRIM PESANAN BARU DARI ANDROID (CHECKOUT)
    public function createOrder(Request $request)
    {
        $request->validate([
            'total_harga' => 'required|numeric',
            'detail_pesanan' => 'required|string', // Isinya list menu yang dibeli berbentuk teks/JSON
            'catatan' => 'nullable|string'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(), // Otomatis mendeteksi ID user yang sedang login di HP
            'total_harga' => $request->total_harga,
            'detail_pesanan' => $request->detail_pesanan,
            'catatan' => $request->catatan,
            'status' => 'pending' // Status awal pesanan masuk
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan Anda berhasil dikirim!',
            'data' => $order
        ], 201);
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
}