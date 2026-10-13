<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Delete all barang records associated with Tinta category (id: 7)
        DB::table('barang')->where('kategori_id', 7)->delete();

        // 2. Delete Tinta category from kategori_barang
        DB::table('kategori_barang')->where('id', 7)->orWhere('nama_kategori', 'Tinta')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-insert Tinta category if rolled back
        DB::table('kategori_barang')->insertOrIgnore([
            'id' => 7,
            'nama_kategori' => 'Tinta',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
