<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user');          // Kolom untuk nama pemesan
            $table->string('menu');          // Kolom untuk nama menu
            $table->integer('quantity');     // Kolom untuk jumlah pesanan
            $table->integer('total_price');  // Kolom untuk total harga
            $table->string('status')->default('diproses'); // Status otomatis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};