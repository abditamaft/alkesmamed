<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah penanda Flash Sale di tabel products
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_flash_sale')->default(0)->after('is_active');
        });

        // Tambah Harga Coret di tabel product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('old_price')->nullable()->after('price')
                  ->comment('Harga sebelum diskon untuk Flash Sale');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_flash_sale');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('old_price');
        });
    }
};