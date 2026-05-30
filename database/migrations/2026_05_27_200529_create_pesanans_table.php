<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_meja');
        $table->string('nama_pemesan');
        $table->integer('jumlah_porsi');
        $table->string('metode_pembayaran');
        $table->timestamps(); // INI yang otomatis mencatat WAKTU PEMESANAN (created_at)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
