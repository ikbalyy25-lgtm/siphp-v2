<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ============================================================
//  Tabel input_pedagang
//  Admin Pasar input 3 harga pedagang per komoditas.
//  Rata-rata dihitung otomatis saat simpan.
//  Hasilnya masuk harga_harians status 'pending'
//  menunggu approve Admin Master.
// ============================================================
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('input_pedagang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasar_id');
            $table->unsignedBigInteger('user_id');
            $table->string('kategori');
            $table->string('nama_barang');
            $table->date('tanggal');
            $table->decimal('harga_pedagang_1', 15, 0);
            $table->decimal('harga_pedagang_2', 15, 0);
            $table->decimal('harga_pedagang_3', 15, 0);
            $table->decimal('rata_rata', 15, 0);
            $table->enum('status', ['terkirim', 'diapprove', 'ditolak'])->default('terkirim');
            $table->unsignedBigInteger('harga_harian_id')->nullable();
            $table->timestamps();

            $table->foreign('pasar_id')->references('id')->on('pasars')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('input_pedagang');
    }
};
