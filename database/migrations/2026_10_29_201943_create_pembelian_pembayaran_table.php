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
        Schema::create('pembelian_pembayaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pembelian_id')->references('id')->on('pembelian')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('cicilan_ke'); // 1 s/d 5
            $table->date('tanggal_bayar');
            $table->bigInteger('jumlah_bayar');
            $table->foreignId('anggaran_id')->constrained('budget')->onUpdate('cascade')->onDelete('restrict');
            $table->enum('status_approval', ['Pending', 'Approved', 'Rejected'])->default('Approved');
            $table->string('catatan', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['pembelian_id', 'status_approval']);
            $table->index('tanggal_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_pembayaran');
    }
};
