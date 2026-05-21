<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Sesuaikan dengan nama Model kamu

class UserController extends Controller
{
    public function index()
    {
        // Mengambil semua data pengguna dari tabel database
        $dataPengguna = User::all(); 

        // Mengirim data ke file view (misal: resources/views/admin/pengguna.blade.php)
        return view('admin.pengguna', compact('dataPengguna'));
    }
}