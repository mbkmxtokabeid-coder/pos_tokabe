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
        Schema::table('pembelian', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelian', 'approval')) {
                $table->string('approval', 191)->nullable()->default('Lock')->after('token');
            }
            if (!Schema::hasColumn('pembelian', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approval');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            if (Schema::hasColumn('pembelian', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('pembelian', 'approval')) {
                $table->dropColumn('approval');
            }
        });
    }
};
