<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('input_pedagang', function (Blueprint $table) {
            // Tambahkan kolom harga_pedagang (JSON)
            $table->json('harga_pedagang')->nullable()->after('tanggal');
            
            // Ubah harga_pedagang_1, 2, 3 menjadi nullable untuk backward compatibility
            // jika ada input yang pedagangnya kurang dari 3
            $table->decimal('harga_pedagang_1', 15, 0)->nullable()->change();
            $table->decimal('harga_pedagang_2', 15, 0)->nullable()->change();
            $table->decimal('harga_pedagang_3', 15, 0)->nullable()->change();
        });

        // Migrate existing data to json (optional but good for consistency)
        $inputs = DB::table('input_pedagang')->get();
        foreach ($inputs as $input) {
            $harga_pedagang = array_filter([
                $input->harga_pedagang_1,
                $input->harga_pedagang_2,
                $input->harga_pedagang_3,
            ], function($v) { return $v !== null; });

            if (!empty($harga_pedagang)) {
                DB::table('input_pedagang')
                    ->where('id', $input->id)
                    ->update(['harga_pedagang' => json_encode(array_values($harga_pedagang))]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('input_pedagang', function (Blueprint $table) {
            $table->dropColumn('harga_pedagang');
            $table->decimal('harga_pedagang_1', 15, 0)->nullable(false)->change();
            $table->decimal('harga_pedagang_2', 15, 0)->nullable(false)->change();
            $table->decimal('harga_pedagang_3', 15, 0)->nullable(false)->change();
        });
    }
};
