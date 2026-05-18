<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Migration ini sengaja di-skip karena tabel pasars sudah dibuat
// oleh migration 2025_11_22_131614_create_pasars_table
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pasars')) {
            Schema::create('pasars', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pasar');
                $table->string('alamat')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Sengaja dikosongkan agar tidak drop tabel yang dipakai migration lain
    }
};
