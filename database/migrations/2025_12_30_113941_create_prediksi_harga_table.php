<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prediksi_harga', function (Blueprint $table) {
            $table->id();
            $table->string('komoditas');
            $table->integer('harga_prediksi');
            $table->date('tanggal');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('prediksi_harga');
    }
};
