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
        Schema::create('kategori_beban', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->string('deskripsi', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('beban_belanja', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_beban', 50)->unique();
            $table->date('tanggal');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('nama_beban', 255);
            $table->unsignedBigInteger('nominal')->default(0);
            $table->unsignedBigInteger('rekening_id')->nullable();
            $table->string('penerima', 150)->nullable();
            $table->string('bukti_struk', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kategori_id')->references('id')->on('kategori_beban')->onDelete('set null');
            $table->foreign('rekening_id')->references('id')->on('rekening')->onDelete('set null');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');

            $table->index(['tanggal', 'kategori_id']);
            $table->index('nomor_beban');
        });

        // Seed kategori beban default
        $kategoriDefaults = [
            ['nama_kategori' => 'Operasional & Utilitas', 'deskripsi' => 'Tagihan listrik, air (PDAM), wifi/internet, sewa tempat toko'],
            ['nama_kategori' => 'Perlengkapan & Packing', 'deskripsi' => 'Lakban, bubble wrap, plastik packing, kertas nota dan ATK toko'],
            ['nama_kategori' => 'Pemeliharaan Mesin & Alat', 'deskripsi' => 'Servis mesin UV, maintenance mesin sablon/printer, sparepart'],
            ['nama_kategori' => 'Gaji & Upah', 'deskripsi' => 'Gaji karyawan, uang lembur, upah freelance / tukang'],
            ['nama_kategori' => 'Konsumsi & Dapur', 'deskripsi' => 'Air galon, kopi/teh, konsumsi lembur dan operasional harian'],
            ['nama_kategori' => 'Pemasaran & Promosi', 'deskripsi' => 'Biaya iklan FB/IG/TikTok Ads, brosur promosi, endorsement'],
            ['nama_kategori' => 'Beban Lain-lain', 'deskripsi' => 'Biaya administrasi bank, retribusi, dan pengeluaran umum lainnya'],
        ];

        foreach ($kategoriDefaults as $kat) {
            DB::table('kategori_beban')->insert([
                'nama_kategori' => $kat['nama_kategori'],
                'deskripsi'     => $kat['deskripsi'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beban_belanja');
        Schema::dropIfExists('kategori_beban');
    }
};
