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
        if (!Schema::hasColumn('pembelian', 'tanggal_jto')) {
            Schema::table('pembelian', function (Blueprint $table) {
                $table->date('tanggal_jto')->nullable()->after('tanggal');
            });

            if (Schema::hasColumn('pembelian', 'tgl_jto')) {
                \Illuminate\Support\Facades\DB::statement('UPDATE pembelian SET tanggal_jto = tgl_jto WHERE tanggal_jto IS NULL AND tgl_jto IS NOT NULL');
            }
        }
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn('tanggal_jto');
        });
    }
};
