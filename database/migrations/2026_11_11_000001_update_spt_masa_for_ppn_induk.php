<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('spt_masa')) {
            // Ubah kolom status menjadi varchar(50) agar mendukung 'Dikompensasikan', 'Nihil', 'Lunas', 'Belum Dibayar', dll.
            DB::statement("ALTER TABLE `spt_masa` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Belum Dibayar'");

            Schema::table('spt_masa', function (Blueprint $table) {
                if (!Schema::hasColumn('spt_masa', 'pembetulan_ke')) {
                    $table->unsignedTinyInteger('pembetulan_ke')->default(0)->after('masa_pajak_tahun');
                }
                if (!Schema::hasColumn('spt_masa', 'status_kb_lb')) {
                    $table->string('status_kb_lb', 30)->nullable()->default('Kurang Bayar')->after('nominal_pajak');
                }
                if (!Schema::hasColumn('spt_masa', 'nominal_lebih_bayar')) {
                    $table->bigInteger('nominal_lebih_bayar')->default(0)->after('status_kb_lb');
                }
                if (!Schema::hasColumn('spt_masa', 'opsi_lebih_bayar')) {
                    $table->string('opsi_lebih_bayar', 80)->nullable()->after('nominal_lebih_bayar');
                }
                if (!Schema::hasColumn('spt_masa', 'tgl_lapor')) {
                    $table->date('tgl_lapor')->nullable()->after('tgl_bayar');
                }
                if (!Schema::hasColumn('spt_masa', 'rincian_spt')) {
                    $table->longText('rincian_spt')->nullable()->after('keterangan');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('spt_masa')) {
            Schema::table('spt_masa', function (Blueprint $table) {
                $cols = ['pembetulan_ke', 'status_kb_lb', 'nominal_lebih_bayar', 'opsi_lebih_bayar', 'tgl_lapor', 'rincian_spt'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('spt_masa', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};

