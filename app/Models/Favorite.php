<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    
    // Izinkan pengisian massal untuk user_id dan menu_id
    protected $fillable = ['user_id', 'menu_id'];
}