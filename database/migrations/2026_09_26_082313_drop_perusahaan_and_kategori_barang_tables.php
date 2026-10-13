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
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('perusahaan')) {
            $foreignKeys = DB::select("
                SELECT TABLE_NAME, CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND REFERENCED_TABLE_NAME = 'perusahaan'
            ");

            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
                } catch (\Throwable $e) {
                    // Ignore if already dropped
                }
            }

            Schema::dropIfExists('perusahaan');
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Reverting this is not practical without data.
    }
};
