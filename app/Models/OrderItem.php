<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'jumlah',
        'harga_satuan',
    ];

    // Hubungan: Detail item ini merujuk ke sebuah Menu makanan
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}