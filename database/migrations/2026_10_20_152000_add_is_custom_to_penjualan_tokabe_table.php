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
        if (Schema::hasTable('penjualan_tokabe') && !Schema::hasColumn('penjualan_tokabe', 'is_custom')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->boolean('is_custom')->default(0)->after('nomor_invoice');
                $table->index('is_custom', 'idx_pt_is_custom');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('penjualan_tokabe') && Schema::hasColumn('penjualan_tokabe', 'is_custom')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->dropIndex('idx_pt_is_custom');
                $table->dropColumn('is_custom');
            });
        }
    }
};
