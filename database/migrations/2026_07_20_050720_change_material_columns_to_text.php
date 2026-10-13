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
        Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->text('material_id')->nullable()->change();
            $table->text('material_qty')->nullable()->change();
            $table->text('material_panjang')->nullable()->change();
            $table->text('material_lebar')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->integer('material_id')->nullable()->change();
            $table->integer('material_qty')->nullable()->change();
            $table->float('material_panjang')->nullable()->change();
            $table->float('material_lebar')->nullable()->change();
        });

    }
};
