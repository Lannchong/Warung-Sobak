<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama menu
            $table->integer('price'); // Harga
            $table->text('description')->nullable(); // Deskripsi
            $table->string('category')->nullable(); // <--- TAMBAHKAN INI UNTUK KATEGORI
            $table->string('image_url')->nullable(); // Link gambar
            $table->integer('stock')->default(0); // Sisa stok (Cukup tulis SATU KALI saja)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};