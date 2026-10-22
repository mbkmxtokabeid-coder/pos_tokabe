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
        'approved_by',
        'alasan_batal',
        'is_custom',
        'npwp',
        'no_faktur_pajak',
        'faktur_pajak_status',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'admin');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_by');
    }

    public function penjualanBarang()
    {
        return $this->hasMany(PenjualanJasaTokabe::class, 'penjualan_id');
    }

    public function approvalRequests()
    {
        return $this->hasMany(InvoiceApprovalRequest::class, 'invoice_id');
    }

    public function latestApprovalRequest()
    {
        return $this->hasOne(InvoiceApprovalRequest::class, 'invoice_id')->latestOfMany();
    }

    /**
     * Helper to check if faktur pajak is already completed
     */
    public function isFakturPajakLengkap(): bool
    {
        return !empty($this->no_faktur_pajak);
    }

    /**
     * Accessor for status_faktur_pajak
     */
    public function getStatusFakturPajakAttribute(): string
    {
        return $this->isFakturPajakLengkap() ? 'Selesai' : 'Belum Diisi';
    }

    /**
     * Check if invoice is currently locked for admin
     */
    public function isLockedForAdmin(): bool
    {
        return $this->approval !== 'Unlock';
    }
}
