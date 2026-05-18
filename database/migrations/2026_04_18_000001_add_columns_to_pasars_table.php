<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasars', function (Blueprint $table) {
            if (!Schema::hasColumn('pasars', 'nama_pasar')) {
                $table->string('nama_pasar')->after('id');
            }
            if (!Schema::hasColumn('pasars', 'alamat')) {
                $table->string('alamat')->nullable()->after('nama_pasar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pasars', function (Blueprint $table) {
            $table->dropColumn(['nama_pasar', 'alamat']);
        });
    }
};
