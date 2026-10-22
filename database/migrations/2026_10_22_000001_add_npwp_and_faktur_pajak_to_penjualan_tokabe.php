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
            $table->string('npwp', 50)->nullable()->after('customer');
            $table->string('no_faktur_pajak', 50)->nullable()->after('kode_ppn');
            $table->string('faktur_pajak_status', 30)->default('Belum Diisi')->after('no_faktur_pajak');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');

            $table->index('no_faktur_pajak');
            $table->index('faktur_pajak_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_tokabe', function (Blueprint $table) {
            $table->dropIndex(['no_faktur_pajak']);
            $table->dropIndex(['faktur_pajak_status']);
            $table->dropColumn(['npwp', 'no_faktur_pajak', 'faktur_pajak_status', 'approved_by']);
        });
    }
};
