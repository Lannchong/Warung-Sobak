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
            // Data Pelanggan langsung nempel di pesanan
            $table->string('customer_name'); 
            $table->string('table_number')->nullable(); // Nomor meja (opsional)
            
            // Total belanjaan & status pesanan
            $table->integer('total_price'); 
            $table->enum('status', ['pending', 'dimasak', 'selesai'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};