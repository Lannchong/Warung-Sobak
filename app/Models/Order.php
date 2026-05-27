<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Kita lengkapi fillable-nya agar Laravel tidak membuang data dari Postman
    protected $fillable = [
        'user_id',
        'menu_id',       
        'jumlah',        
        'nomor_pesanan',
        'total_harga',
        'status',
        'catatan',
    ];

    // Hubungan: 1 Pesanan dimiliki oleh 1 User (Pelanggan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hubungan: 1 Pesanan merujuk pada 1 Menu makanan (Ini yang menyembuhkan error di Postman)
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    // Hubungan: 1 Pesanan bisa memiliki banyak menu makanan di dalamnya (Opsional jika nanti butuh)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}