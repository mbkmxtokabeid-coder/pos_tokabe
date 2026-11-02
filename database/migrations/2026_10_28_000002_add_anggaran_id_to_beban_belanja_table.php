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
        Schema::table('beban_belanja', function (Blueprint $table) {
            $table->unsignedBigInteger('anggaran_id')->nullable()->after('nominal');
            $table->foreign('anggaran_id')->references('id')->on('budget')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beban_belanja', function (Blueprint $table) {
            $table->dropForeign(['anggaran_id']);
            $table->dropColumn('anggaran_id');
        });
    }
};
