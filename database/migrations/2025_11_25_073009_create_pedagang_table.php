<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedagang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email');
            $table->string('jenis_barang');
            $table->string('kontak');
            $table->string('lokasi_penjualan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedagang');
    }
};
