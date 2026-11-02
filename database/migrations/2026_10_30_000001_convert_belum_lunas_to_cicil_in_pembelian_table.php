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
        // 1. Ubah semua data pembelian dengan status 'Belum Lunas' menjadi 'Cicil'
        DB::table('pembelian')->where('status', 'Belum Lunas')->update([
            'status' => 'Cicil',
        ]);

        // 2. Modifikasi definisi kolom ENUM agar hanya 'Lunas' dan 'Cicil'
        DB::statement("ALTER TABLE `pembelian` MODIFY COLUMN `status` ENUM('Lunas', 'Cicil') NOT NULL DEFAULT 'Cicil'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `pembelian` MODIFY COLUMN `status` ENUM('Lunas', 'Belum Lunas', 'Cicil') NOT NULL DEFAULT 'Cicil'");
    }
};

