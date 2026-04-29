<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('slug')
                  ->comment('Teks singkat untuk grid teks atas');
            $table->string('image_1')->nullable()->after('content')
                  ->comment('Gambar untuk grid bawah kiri');
            $table->string('image_2')->nullable()->after('image_1')
                  ->comment('Gambar untuk grid atas kanan');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'image_1', 'image_2']);
        });
    }
};