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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel provinces
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            
            // Kolom untuk Kota/Kabupaten
            $table->string('type')->default('Kota'); 
            $table->string('name');
            
            // INI KOLOM YANG BIKIN ERROR (Wajib Ditambahkan)
            $table->integer('shipping_cost')->default(0); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
