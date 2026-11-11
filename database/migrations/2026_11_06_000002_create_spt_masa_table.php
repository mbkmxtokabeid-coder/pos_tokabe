<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('spt_masa')) {
            Schema::create('spt_masa', function (Blueprint $table) {
                $table->id();
                $table->string('nomor_spt', 50)->nullable()->index();
                $table->unsignedTinyInteger('masa_pajak_bulan');
                $table->unsignedSmallInteger('masa_pajak_tahun');
                $table->string('jenis_pajak', 100);
                $table->string('nomor_billing', 100)->nullable();
                $table->date('tgl_billing')->nullable();
                $table->date('tgl_jatuh_tempo');
                $table->bigInteger('dpp')->default(0);
                $table->decimal('tarif_persen', 5, 2)->default(0);
                $table->bigInteger('nominal_pajak')->default(0);
                $table->date('tgl_bayar')->nullable();
                $table->string('ntpn', 100)->nullable();
                $table->enum('status', ['Belum Dibayar', 'Menunggu Pembayaran', 'Lunas', 'Terlambat'])->default('Belum Dibayar');
                $table->string('bukti_bayar')->nullable();
                $table->text('keterangan')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['masa_pajak_tahun', 'masa_pajak_bulan']);
                $table->index('status');
                $table->index('tgl_bayar');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('spt_masa');
    }
};

