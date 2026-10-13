<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class PembelianBarang extends Model
{
    use UUIDAsPrimaryKey;

    protected $table = 'pembelian_barang';

    protected $fillable = [
        'pembelian_id',
        'deskripsi',
        'qty',
        'satuan',
        'harga_barang',
        'total',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
