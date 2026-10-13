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
        // 1. Ganti 'Direct Assignment' atau 'Prokuremen' menjadi 'Procurement' pada tabel order
        DB::table('order')
            ->whereIn('order_by', ['Direct Assignment', 'Prokuremen'])
            ->update([
                'order_by' => 'Procurement',
                'updated_at' => now(),
            ]);

        // Jika opsi 'Procurement' belum ada, buat baru
        if (!DB::table('order')->where('order_by', 'Procurement')->exists()) {
            DB::table('order')->insert([
                'order_by' => 'Procurement',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Hapus jika masih tersisa record duplikat dengan nama selain 3 opsi utama
        DB::table('order')->whereIn('order_by', ['Direct Assignment', 'Prokuremen'])->delete();

        // 2. Perbarui histori transaksi di penjualan_tokabe yang menggunakan 'Direct Assignment' / 'Prokuremen'
        DB::table('penjualan_tokabe')
            ->whereIn('order_by', ['Direct Assignment', 'Prokuremen'])
            ->update(['order_by' => 'Procurement']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op rollback
    }
};
