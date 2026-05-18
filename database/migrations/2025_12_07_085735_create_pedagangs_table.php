<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedagangs', function (Blueprint $table) {
            $table->id();
            // INI PENTING: ID Pasar tempat dia berjualan
            $table->unsignedBigInteger('pasar_id');
            $table->string('nama_pedagang');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedagangs');
    }
};
