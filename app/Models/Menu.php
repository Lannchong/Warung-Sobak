<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // Sesuaikan nama di dalam array ini dengan nama kolom di file migration tabel menus kamu
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image_url', // Disamakan dengan input Postman kamu
        'stock' // <-- Tambahkan ini agar Laravel mengizinkan pengisian stok
    ];
}