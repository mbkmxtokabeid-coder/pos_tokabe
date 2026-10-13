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
        if (!Schema::hasColumn('pembelian_barang', 'satuan')) {
            Schema::table('pembelian_barang', function (Blueprint $table) {
                $table->string('satuan')->nullable()->after('qty');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pembelian_barang', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });
    }
};
