<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retails', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('kategori'); // Contoh: Barang Campuran, Barang Pokok
            $table->string('alamat');
            $table->string('kontak'); // No HP
            $table->string('jam_buka'); // Contoh: 08.00 - 22.00
            $table->text('link_maps'); // Link Google Maps
            $table->string('gambar')->nullable(); // Foto Toko
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retails');
    }
};
