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
        if (!Schema::hasColumn('penjualan_tokabe', 'is_padi_umkm')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->boolean('is_padi_umkm')->default(false)->after('is_custom');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('penjualan_tokabe', 'is_padi_umkm')) {
            Schema::table('penjualan_tokabe', function (Blueprint $table) {
                $table->dropColumn('is_padi_umkm');
            });
        }
    }
};

