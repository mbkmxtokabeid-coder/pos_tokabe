<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use UUIDAsPrimaryKey;

    protected $table = 'pembelian';

    protected $fillable = [
        'anggaran',
        'id_vendor',
        'nomor_inv',
        'jenis_invoice',
        'ppn_persen',
        'ppn_nominal',
        'pph_persen',
        'pph_nominal',
        'tanggal',
        'tanggal_jto',
        'tgl_jto',
        'tgl_lunas',
        'status',
        'terbayar',
        'sisa',
        'jumlah_harga',
        'token',
        'approval',
        'approved_at',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'tanggal_jto' => 'date',
        'tgl_jto'     => 'date',
        'tgl_lunas'   => 'date',
        'approved_at' => 'datetime',
        'ppn_persen'  => 'decimal:2',
        'pph_persen'  => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor');
    }

    public function getEffectiveTanggalJtoAttribute()
    {
        return $this->tanggal_jto ?: $this->tgl_jto;
    }

    public function barang()
    {
        return $this->hasMany(PembelianBarang::class, 'pembelian_id');
    }

    public function pembelian_barang()
    {
        return $this->hasMany(PembelianBarang::class, 'pembelian_id');
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'anggaran');
    }

    public function anggaran()
    {
        return $this->belongsTo(Budget::class, 'anggaran');
    }
}
