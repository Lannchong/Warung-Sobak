<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Menghubungkan pesanan ke ID Pelanggan di tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Menghubungkan pesanan ke ID Menu di tabel menus
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade'); // Kolom menu_id sudah ditambahkan di sini
            $table->string('nomor_pesanan')->unique(); // Contoh: SBK-20260525-001
            $table->integer('jumlah'); // Kolom jumlah ditambahkan 
            $table->decimal('total_harga', 10, 2);
            // Status pesanan dikunci menggunakan enum agar aman
            $table->enum('status', ['pending', 'diproses', 'selesai', 'dibatalkan'])->default('pending');
            $table->string('catatan')->nullable(); // Catatan pembeli (misal: "tanpa seledri")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};