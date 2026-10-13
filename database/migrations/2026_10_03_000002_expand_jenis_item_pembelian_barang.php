<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->enum('jenis_item', ['Barang', 'Jasa', 'Barang dan Jasa', 'Jasa Iklan'])
                ->default('Barang')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->enum('jenis_item', ['Barang', 'Jasa'])->default('Barang')->change();
        });
    }
};