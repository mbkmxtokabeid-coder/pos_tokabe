<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama_item')->unique();
            $table->enum('jenis_pajak', ['ppn', 'pph', 'ppn_pph', 'tanpa_pajak']);
            $table->timestamps();
        });

        Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id')->nullable()->change();
            $table->foreignId('item_id')
                ->nullable()
                ->after('barang_id')
                ->constrained('items')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::table('penjualan_jasa_tokabe')->whereNotNull('item_id')->exists()) {
            throw new RuntimeException('Item invoice sudah digunakan; hapus relasi item invoice sebelum rollback.');
        }

        Schema::table('penjualan_jasa_tokabe', function (Blueprint $table) {
            $table->dropConstrainedForeignId('item_id');
            $table->unsignedBigInteger('barang_id')->nullable(false)->change();
        });

        Schema::dropIfExists('items');
    }
};
