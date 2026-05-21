<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    // Fungsi untuk mengambil semua data makanan & minuman
    public function index()
    {
        // Mengambil semua data dari tabel menus di phpMyAdmin
        $menus = DB::table('menus')->get();

        // Mengirimkan data ke Android dalam format JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil data menu Warung Sobak',
            'data' => $menus
        ], 200);
    }
}