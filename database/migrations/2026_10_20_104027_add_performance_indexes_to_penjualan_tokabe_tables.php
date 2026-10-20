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
        if (Schema::hasTable('penjualan_tokabe')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->index('tgl_penjualan', 'idx_pt_tgl_penjualan');
                $table->index('status', 'idx_pt_status');
                $table->index('approval', 'idx_pt_approval');
                $table->index(['deleted_at', 'tgl_penjualan'], 'idx_pt_deleted_tgl');
            });
        }

        if (Schema::hasTable('penjualan_jasa_tokabe')) {
            Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
                $table->index('deleted_at', 'idx_pjt_deleted_at');
                $table->index(['penjualan_id', 'deleted_at'], 'idx_pjt_penjualan_del');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('penjualan_tokabe')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->dropIndex('idx_pt_tgl_penjualan');
                $table->dropIndex('idx_pt_status');
                $table->dropIndex('idx_pt_approval');
                $table->dropIndex('idx_pt_deleted_tgl');
            });
        }

        if (Schema::hasTable('penjualan_jasa_tokabe')) {
            Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
                $table->dropIndex('idx_pjt_deleted_at');
                $table->dropIndex('idx_pjt_penjualan_del');
            });
        }
    }
};
