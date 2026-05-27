<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class ApiMenuController extends Controller
{
    // [GET] Mengambil semua data menu untuk aplikasi Android
    public function index()
    {
        $menus = Menu::orderBy('created_at', 'desc')->get();

        // Kita ubah sedikit datanya agar Android langsung mendapat Link URL Foto yang siap pakai
        $menus->transform(function ($menu) {
            if ($menu->foto) {
                // Menghasilkan link lengkap seperti: http://127.0.0.1:8000/storage/menus/namafoto.jpg
                $menu->foto_url = asset('storage/' . $menu->foto);
            } else {
                $menu->foto_url = null;
            }
            return $menu;
        });

        // Mengembalikan data dalam format JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil daftar menu Warung Sobak',
            'data' => $menus
        ], 200);
    }
}