<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penjualan_tokabe', function (Blueprint $table) {
            $table->bigInteger('dp')->nullable()->change();
            $table->bigInteger('total_harga')->change();
            $table->bigInteger('potongan')->nullable()->change();
            $table->bigInteger('diskon')->nullable()->change();
            $table->bigInteger('ppn')->nullable()->change();
            $table->bigInteger('total_pembayaran')->change();
            $table->bigInteger('sisa_pembayaran')->change();
        });

        Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->bigInteger('harga')->change();
            $table->bigInteger('jumlah_harga')->change();
        });

        Schema::table('pembelian', function (Blueprint $table) {
            $table->bigInteger('terbayar')->change();
            $table->bigInteger('sisa')->change();
            $table->bigInteger('jumlah_harga')->change();
        });

        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->bigInteger('harga_barang')->change();
            $table->bigInteger('total')->change();
        });

        Schema::table('vendor', function (Blueprint $table) {
            $table->bigInteger('total_pembelian')->default(0)->change();
            $table->bigInteger('pembelian_sisa')->default(0)->change();
            $table->bigInteger('pembelian_terbayar')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_tokabe', function (Blueprint $table) {
            $table->integer('dp')->nullable()->change();
            $table->integer('total_harga')->change();
            $table->integer('potongan')->nullable()->change();
            $table->integer('diskon')->nullable()->change();
            $table->integer('ppn')->nullable()->change();
            $table->integer('total_pembayaran')->change();
            $table->integer('sisa_pembayaran')->change();
        });

        Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->integer('harga')->change();
            $table->integer('jumlah_harga')->change();
        });

        Schema::table('pembelian', function (Blueprint $table) {
            $table->integer('terbayar')->change();
            $table->integer('sisa')->change();
            $table->integer('jumlah_harga')->change();
        });

        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->integer('harga_barang')->change();
            $table->integer('total')->change();
        });

        Schema::table('vendor', function (Blueprint $table) {
            $table->integer('total_pembelian')->default(0)->change();
            $table->integer('pembelian_sisa')->default(0)->change();
            $table->integer('pembelian_terbayar')->default(0)->change();
        });
    }
};
