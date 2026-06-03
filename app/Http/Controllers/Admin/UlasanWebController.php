<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ulasan;

class UlasanWebController extends Controller
{
    public function index()
    {
        // Ambil semua data ulasan, sertakan data user-nya, urutkan dari yang terbaru
        $ulasan = Ulasan::with('user')->latest()->get();
        
        // Kirim datanya ke halaman (view) Blade
        return view('admin.ulasan.index', compact('ulasan'));
    }
}