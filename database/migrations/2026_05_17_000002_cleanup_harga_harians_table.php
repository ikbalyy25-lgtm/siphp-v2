<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('harga_harians', function (Blueprint $table) {
            if (Schema::hasColumn('harga_harians', 'prediksi_besok')) {
                $table->dropColumn('prediksi_besok');
            }
            if (Schema::hasColumn('harga_harians', 'pedagang_id')) {
                $table->dropColumn('pedagang_id');
            }
            if (Schema::hasColumn('harga_harians', 'harga_kemarin')) {
                $table->dropColumn('harga_kemarin');
            }
            if (!Schema::hasColumn('harga_harians', 'input_pedagang_id')) {
                $table->unsignedBigInteger('input_pedagang_id')->nullable()->after('pasar_id');
            }
        });

        $isMysql = DB::getDriverName() === 'mysql';

        if ($isMysql) {
            // Ubah dulu ke VARCHAR agar bebas melakukan konversi tanpa kena batasan ENUM
            DB::statement("ALTER TABLE harga_harians MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        }

        // Konversi nilai lama ke nilai baru
        DB::statement("UPDATE harga_harians SET status = 'published' WHERE status IN ('update', 'publish')");
        DB::statement("UPDATE harga_harians SET status = 'pending' WHERE status NOT IN ('pending', 'published')");

        if ($isMysql) {
            // Baru ubah ke ENUM final yang bersih
            DB::statement("ALTER TABLE harga_harians MODIFY COLUMN status ENUM('pending','published') DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        Schema::table('harga_harians', function (Blueprint $table) {
            $table->decimal('prediksi_besok', 15, 2)->nullable();
            $table->unsignedBigInteger('pedagang_id')->nullable();
            $table->decimal('harga_kemarin', 15, 0)->nullable();
            $table->dropColumn('input_pedagang_id');
        });
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE harga_harians MODIFY COLUMN status ENUM('pending','update','publish','draft') DEFAULT 'pending'");
        }
    }
};
