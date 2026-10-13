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
        // 1. Ubah kolom order_by di penjualan_tokabe menjadi VARCHAR(50) agar mendukung Procurement
        DB::statement("ALTER TABLE penjualan_tokabe MODIFY COLUMN order_by VARCHAR(50) NULL");

        // 2. Bersihkan dan isi ulang tabel `order` hanya dengan 3 opsi: Procurement, WhatsApp, Sales
        DB::table('order')->truncate();

        DB::table('order')->insert([
            ['order_by' => 'Procurement', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'WhatsApp', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'Sales', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('order')->truncate();

        DB::table('order')->insert([
            ['order_by' => 'Shopee', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'Walk In', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'WhatsApp/Online', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'Sales', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'Tokopedia', 'created_at' => now(), 'updated_at' => now()],
            ['order_by' => 'Google/Google Business', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
};
