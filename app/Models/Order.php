<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
        'user',
        'menu',
        'quantity',
        'total_price',
        'status'
    ];

    // Relasi dengan OrderItem (Nanti kodenya bisa dilanjutkan di sini)
}