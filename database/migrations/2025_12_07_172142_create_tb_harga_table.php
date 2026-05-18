<?php

use Illuminate\Database\Migrations\Migration;

// Tabel tb_harga sudah digantikan sepenuhnya oleh harga_harians.
// Migration ini dikosongkan agar tidak membuat tabel yang tidak dipakai.
return new class extends Migration
{
    public function up(): void
    {
        // Sengaja dikosongkan — digantikan oleh create_harga_harians_table
    }

    public function down(): void
    {
        // Sengaja dikosongkan
    }
};
