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
        Schema::create('invoice_approval_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('user_id'); // Admin pemohon
            $table->string('tipe_request', 50)->default('edit_faktur_pajak');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_owner')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Owner yang memverifikasi
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('penjualan_tokabe')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['invoice_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_approval_requests');
    }
};
