<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('spt_masa')) {
            Schema::table('spt_masa', function (Blueprint $table) {
                if (!Schema::hasColumn('spt_masa', 'approval')) {
                    $table->string('approval', 30)->default('Lock')->after('rincian_spt');
                }
                if (!Schema::hasColumn('spt_masa', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approval');
                }
            });
        }

        if (Schema::hasTable('beban_belanja')) {
            Schema::table('beban_belanja', function (Blueprint $table) {
                if (!Schema::hasColumn('beban_belanja', 'approval')) {
                    $table->string('approval', 30)->default('Lock')->after('admin_id');
                }
                if (!Schema::hasColumn('beban_belanja', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable()->after('approval');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('spt_masa')) {
            Schema::table('spt_masa', function (Blueprint $table) {
                if (Schema::hasColumn('spt_masa', 'approved_at')) {
                    $table->dropColumn('approved_at');
                }
                if (Schema::hasColumn('spt_masa', 'approval')) {
                    $table->dropColumn('approval');
                }
            });
        }

        if (Schema::hasTable('beban_belanja')) {
            Schema::table('beban_belanja', function (Blueprint $table) {
                if (Schema::hasColumn('beban_belanja', 'approved_at')) {
                    $table->dropColumn('approved_at');
                }
                if (Schema::hasColumn('beban_belanja', 'approval')) {
                    $table->dropColumn('approval');
                }
            });
        }
    }
};

