<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // Pastikan ada kata 'stok' di dalam array ini
    protected $fillable = [
        'nama_menu', 
        'harga', 
        'kategori', 
        'stok', 
        'foto', 
        'deskripsi'
    ];
}