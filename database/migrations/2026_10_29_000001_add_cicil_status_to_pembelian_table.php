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
        DB::statement("ALTER TABLE `pembelian` MODIFY COLUMN `status` ENUM('Lunas', 'Belum Lunas', 'Cicil') NOT NULL DEFAULT 'Belum Lunas'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `pembelian` MODIFY COLUMN `status` ENUM('Lunas', 'Belum Lunas') NOT NULL DEFAULT 'Belum Lunas'");
    }
};

