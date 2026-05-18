<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harga_harians', function (Blueprint $table) {
            // Menambahkan kolom prediksi_besok setelah kolom harga_hari_ini
            $table->decimal('prediksi_besok', 15, 2)->nullable()->after('harga_hari_ini');
        });
    }

    public function down(): void
    {
        Schema::table('harga_harians', function (Blueprint $table) {
            $table->dropColumn('prediksi_besok');
        });
    }
};
