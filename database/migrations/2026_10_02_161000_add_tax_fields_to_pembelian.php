<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pembelian', 'ppn_persen')) {
            Schema::table('pembelian', function (Blueprint $table) {
                $table->decimal('ppn_persen', 5, 2)->default(0)->after('jenis_invoice');
                $table->bigInteger('ppn_nominal')->default(0)->after('ppn_persen');
                $table->decimal('pph_persen', 5, 2)->default(0)->after('ppn_nominal');
                $table->bigInteger('pph_nominal')->default(0)->after('pph_persen');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn(['ppn_persen', 'ppn_nominal', 'pph_persen', 'pph_nominal']);
        });
    }
};