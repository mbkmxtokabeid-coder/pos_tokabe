<?php

use App\Models\Item;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $defaultItems = [
            ['nama_item' => 'Barang', 'jenis_pajak' => 'ppn'],
            ['nama_item' => 'Barang dan Jasa', 'jenis_pajak' => 'ppn_pph'],
            ['nama_item' => 'Jasa', 'jenis_pajak' => 'pph'],
            ['nama_item' => 'Jasa Periklanan', 'jenis_pajak' => 'ppn_pph'],
        ];

        foreach ($defaultItems as $row) {
            Item::updateOrCreate(
                ['nama_item' => $row['nama_item']],
                ['jenis_pajak' => $row['jenis_pajak']]
            );
        }
    }

    public function down(): void
    {
        // Tetap simpan data item agar relasi tidak rusak
    }
};
