<?php

namespace App\Models;

use App\Traits\UUIDAsPrimaryKey;
use Illuminate\Database\Eloquent\Model;

class PembelianPembayaran extends Model
{
    use UUIDAsPrimaryKey;

    protected $table = 'pembelian_pembayaran';

    protected $fillable = [
        'pembelian_id',
        'cicilan_ke',
        'tanggal_bayar',
        'jumlah_bayar',
        'anggaran_id',
        'status_approval',
        'catatan',
        'user_id',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar'  => 'integer',
        'cicilan_ke'    => 'integer',
        'approved_at'   => 'datetime',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    public function anggaran()
    {
        return $this->belongsTo(Budget::class, 'anggaran_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

