<?php

use App\Models\Barang;
use App\Models\Item;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $activeBarangs = Barang::where('is_active', 1)
            ->whereNotNull('jenis_barang')
            ->get();

        foreach ($activeBarangs as $b) {
            $nama = trim($b->jenis_barang);
            if ($nama !== '') {
                Item::firstOrCreate(
                    ['nama_item' => $nama],
                    ['jenis_pajak' => 'tanpa_pajak']
                );
            }
        }
    }

    public function down(): void
    {
        // Tetap simpan data item agar relasi tidak rusak
    }
};
