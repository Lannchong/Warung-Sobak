<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // PASTIKAN SEMUA KOLOM TERDAFTAR DI SINI AGAR TIDAK ERROR NULL
    protected $fillable = [
        'user_id',
        'menu_id',
        'jumlah',
        'total_harga',
        'nomor_pesanan',
        'status',
        'catatan',
        'nama_pemesan',
        'nomor_meja',
        'metode_pembayaran',
        'detail_pesanan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}