<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembelian', 'jenis_invoice')) {
            Schema::table('pembelian', function (Blueprint $table) {
                $table->enum('jenis_invoice', ['Barang', 'Jasa', 'Barang dan Jasa', 'Jasa Iklan'])
                    ->default('Barang')
                    ->after('nomor_inv');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn('jenis_invoice');
        });
    }
};