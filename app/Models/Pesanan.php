<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = ['nomor_meja', 'nama_pemesan', 'jumlah_porsi', 'metode_pembayaran'];
}