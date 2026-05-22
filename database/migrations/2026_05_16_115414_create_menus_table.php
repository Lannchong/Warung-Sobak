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
            $table->string('name'); // Nama menu (contoh: "Soto Ayam")
            $table->integer('price'); // Harga menu (contoh: 15000)
            $table->text('description')->nullable(); // Deskripsi menu
            $table->integer('stock')->default(0); // Sisa stok
            $table->string('image_url')->nullable(); // Link gambar menu
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