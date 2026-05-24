<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('input_pedagang', function (Blueprint $table) {
            $table->string('satuan')->nullable()->after('nama_barang');
        });

        Schema::table('harga_harians', function (Blueprint $table) {
            $table->string('satuan')->nullable()->after('nama_barang');
        });

        // Set default values for existing data
        DB::table('input_pedagang')->whereNull('satuan')->update(['satuan' => '-']);
        DB::table('harga_harians')->whereNull('satuan')->update(['satuan' => '-']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('input_pedagang', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });

        Schema::table('harga_harians', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });
    }
};
