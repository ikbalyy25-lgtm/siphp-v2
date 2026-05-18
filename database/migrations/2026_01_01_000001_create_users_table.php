<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

// ============================================================
//  Migration ini dikosongkan karena tabel users sudah dibuat
//  oleh migration 0001_01_01_000000_create_users_table
//  (yang sudah diupdate ke versi SIPHP)
// ============================================================
return new class extends Migration
{
    public function up(): void
    {
        // Sudah ditangani oleh migration 0001_01_01_000000
    }

    public function down(): void
    {
        // Tidak ada yang perlu di-rollback di sini
    }
};
