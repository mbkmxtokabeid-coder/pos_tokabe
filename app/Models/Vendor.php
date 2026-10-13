<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use UUIDAsPrimaryKey;

    protected $table = 'vendor';

    protected $fillable = [
        'nama_vendor', 'total_pembelian', 'pembelian_sisa', 'pembelian_terbayar',
    ];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'id_vendor');
    }
}