<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategori_invoice')) {
            Schema::create('kategori_invoice', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori', 120);
                $table->text('keterangan')->nullable();
                $table->boolean('use_custom_format')->default(true);
                $table->string('prefix', 40)->nullable()->default('INV');
                $table->string('format_nomor', 150)->default('{PREFIX}/{YEAR}/{NUMBER}');
                $table->unsignedTinyInteger('padding_digit')->default(3);
                $table->enum('reset_periode', ['yearly', 'monthly', 'never'])->default('yearly');
                $table->json('template_settings')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed kategori default agar siap digunakan
            DB::table('kategori_invoice')->insert([
                [
                    'nama_kategori'     => 'Invoice Standar Tokabe',
                    'keterangan'        => 'Format penomoran standar sistem Tokabe (001/TKB/ROM/YYYY)',
                    'use_custom_format' => false,
                    'prefix'            => 'TKB',
                    'format_nomor'      => '{NUMBER}/{PREFIX}/{ROMAN_MONTH}/{YEAR}',
                    'padding_digit'     => 3,
                    'reset_periode'     => 'monthly',
                    'template_settings' => json_encode([
                        'judul_invoice'    => 'INVOICE',
                        'subjudul_invoice' => '',
                        'label_penerima'   => 'Kepada Yth.',
                        'tampilkan_npwp'   => true,
                        'tampilkan_termin' => true,
                        'catatan_kaki'     => 'Pembayaran mohon ditransfer ke rekening resmi yang tertera pada invoice.',
                    ]),
                    'is_active'         => true,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
                [
                    'nama_kategori'     => 'Invoice Jasa Iklan',
                    'keterangan'        => 'Kategori khusus transaksi layanan dan penayangan iklan',
                    'use_custom_format' => true,
                    'prefix'            => 'IKL',
                    'format_nomor'      => '{PREFIX}/{NUMBER}/{YEAR}',
                    'padding_digit'     => 3,
                    'reset_periode'     => 'yearly',
                    'template_settings' => json_encode([
                        'judul_invoice'    => 'INVOICE JASA IKLAN',
                        'subjudul_invoice' => 'Layanan Publikasi & Media Iklan',
                        'label_penerima'   => 'Klien / Pengiklan',
                        'tampilkan_npwp'   => true,
                        'tampilkan_termin' => true,
                        'catatan_kaki'     => 'Harap cantumkan nomor invoice pada berita acara transfer pembayaran iklan.',
                    ]),
                    'is_active'         => true,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
                [
                    'nama_kategori'     => 'Invoice PADI UMKM',
                    'keterangan'        => 'Format invoice khusus transaksi marketplace PADI UMKM',
                    'use_custom_format' => true,
                    'prefix'            => 'UMKM',
                    'format_nomor'      => '{NUMBER}/INV/{PREFIX}/{YEAR}',
                    'padding_digit'     => 3,
                    'reset_periode'     => 'yearly',
                    'template_settings' => json_encode([
                        'judul_invoice'    => 'INVOICE PADI UMKM',
                        'subjudul_invoice' => 'Transaksi Pengadaan PADI UMKM',
                        'label_penerima'   => 'Instansi / BUMN Pemesan',
                        'tampilkan_npwp'   => true,
                        'tampilkan_termin' => true,
                        'catatan_kaki'     => 'Transaksi ini tercatat melalui ekosistem Pasar Digital (PaDi) UMKM.',
                    ]),
                    'is_active'         => true,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
            ]);
        }

        if (Schema::hasTable('penjualan_tokabe') && !Schema::hasColumn('penjualan_tokabe', 'kategori_invoice_id')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->unsignedBigInteger('kategori_invoice_id')->nullable()->after('nomor_invoice');
                $table->index('kategori_invoice_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penjualan_tokabe') && Schema::hasColumn('penjualan_tokabe', 'kategori_invoice_id')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->dropIndex(['kategori_invoice_id']);
                $table->dropColumn('kategori_invoice_id');
            });
        }
        Schema::dropIfExists('kategori_invoice');
    }
};