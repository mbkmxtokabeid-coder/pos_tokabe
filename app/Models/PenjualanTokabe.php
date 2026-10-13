<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanTokabe extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = "penjualan_tokabe";
    protected $guarded = [];
    protected $fillable = [
        'invoice',
        'nomor_invoice',
        'tgl_penjualan',
        'customer',
        'perusahaan',
        'no_telepon',
        'admin',
        'order_by',
        'nama_sales',
        'tgl_selesai',
        'jumlah_item',
        'dp',
        'potongan',
        'jenis_pembayaran',
        'no_rek',
        'total_harga',
        'status',
        'diskon',
        'ppn',
        'kode_ppn',
        'total_pembayaran',
        'sisa_pembayaran',
        'approval',
        'approved_at',
        'alasan_batal',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_by');
    }

    public function penjualanBarang()
    {
        return $this->hasMany(PenjualanJasaTokabe::class, 'penjualan_id');
    }
}
