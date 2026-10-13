<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Convert all non-Pemilik roles to Admin to preserve referential integrity
        DB::table('users')->where('role', '!=', 'Pemilik')->update(['role' => 'Admin']);

        // 2. Update the enum column in users table to only allow 'Pemilik' and 'Admin'
        DB::statement("ALTER TABLE users MODIFY COLUMN role enum('Pemilik','Admin') NOT NULL DEFAULT 'Admin'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role enum('Pemilik','Admin','Produksi','Stockist','AdminTKB','Magang') NOT NULL");
    }
};
