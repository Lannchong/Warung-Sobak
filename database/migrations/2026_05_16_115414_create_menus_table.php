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
            $table->string('nama_menu'); // Laci untuk nama makanan/minuman
            $table->integer('harga');    // Laci untuk angka harga
            $table->string('kategori');  // Laci untuk Makanan/Minuman
            $table->text('deskripsi')->nullable(); // Laci deskripsi (boleh kosong)
            $table->string('foto')->nullable();    // Laci path foto (boleh kosong)
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