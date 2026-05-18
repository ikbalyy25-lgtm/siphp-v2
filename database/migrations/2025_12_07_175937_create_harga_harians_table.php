<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('harga_harians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasar_id');

            // Nullable agar Admin tetap bisa input manual
            $table->unsignedBigInteger('pedagang_id')->nullable();

            $table->string('kategori');
            $table->string('nama_barang');
            $table->date('tanggal');
            $table->decimal('harga_kemarin', 15, 0);
            $table->decimal('harga_hari_ini', 15, 0);

            // PERBAIKAN DI SINI:
            // Tambahkan 'publish' dan 'draft' agar sesuai dengan Unit Test Anda
            $table->enum('status', ['pending', 'update', 'publish', 'draft'])->default('pending');

            // Opsi Alternatif (Jika ingin lebih bebas, pakai string saja):
            // $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga_harians');
    }
};
