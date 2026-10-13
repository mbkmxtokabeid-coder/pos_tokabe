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
        Schema::create('rekening', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank', 100);
            $table->string('atas_nama', 150);
            $table->string('nomor_rekening', 100);
            $table->string('keterangan', 255)->nullable();
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });

        // Seed initial bank account shown in invoice
        DB::table('rekening')->insert([
            'nama_bank' => 'BSI',
            'atas_nama' => 'PT. Total Karya Berkah',
            'nomor_rekening' => '3557999999',
            'keterangan' => 'Rekening Utama Pembayaran Invoice Tokabe',
            'status' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening');
    }
};
