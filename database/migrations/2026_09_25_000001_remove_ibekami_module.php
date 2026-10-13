<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (['invoice_update_logs', 'penjualan_barang', 'penjualan'] as $table) {
            Schema::dropIfExists($table);
        }

        if (Schema::hasTable('invoice') && Schema::hasTable('perusahaan')) {
            $ibekamiCompanyIds = DB::table('perusahaan')
                ->whereRaw('LOWER(nama_perusahaan) LIKE ?', ['%ibekam%'])
                ->pluck('id');

            DB::table('invoice')->whereIn('perusahaan_id', $ibekamiCompanyIds)->delete();
            DB::table('perusahaan')->whereIn('id', $ibekamiCompanyIds)->delete();
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice');
            $table->foreign('invoice')->references('id')->on('invoice')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nomor_invoice');
            $table->dateTime('tgl_penjualan');
            $table->string('customer');
            $table->string('perusahaan')->nullable();
            $table->string('no_telepon');
            $table->unsignedBigInteger('admin');
            $table->foreign('admin')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('order_by', ['Walk in', 'Tokopedia', 'Shopee', 'WhatsApp/Online', 'Sales']);
            $table->string('nama_sales')->nullable();
            $table->date('tgl_selesai');
            $table->integer('jumlah_item');
            $table->integer('dp')->nullable();
            $table->enum('jenis_pembayaran', ['Cash Lunas', 'Cash Belum Lunas', 'Transfer DP', 'Transfer Lunas', 'DP', 'PO']);
            $table->text('no_rek')->nullable();
            $table->integer('total_harga');
            $table->enum('status', ['Lunas', 'Belum Lunas', 'Batal']);
            $table->integer('potongan')->nullable();
            $table->integer('diskon')->nullable();
            $table->integer('ppn')->nullable();
            $table->integer('total_pembayaran');
            $table->integer('sisa_pembayaran');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('penjualan_barang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barang')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('penjualan_id');
            $table->foreign('penjualan_id')->references('id')->on('penjualan')->onUpdate('cascade')->onDelete('cascade');
            $table->text('deskripsi_item');
            $table->integer('qty');
            $table->string('satuan');
            $table->integer('hargaBarang');
            $table->integer('jumlah_harga');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};