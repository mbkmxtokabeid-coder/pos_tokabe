<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->enum('jenis_invoice', ['Barang', 'Jasa', 'Barang dan Jasa', 'Jasa Iklan', 'Tanpa Pajak'])
                ->default('Barang')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->enum('jenis_invoice', ['Barang', 'Jasa', 'Barang dan Jasa', 'Jasa Iklan'])
                ->default('Barang')
                ->change();
        });
    }
};