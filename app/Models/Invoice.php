<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = "invoice";
    protected $guarded = [];
    protected $fillable = [
        'nama_invoice',
        'kode_invoice',
        'perusahaan_id',
    ];
    public function penjualanByInvoice()
    {
        return $this->hasMany(PenjualanTokabe::class, 'invoice');
    }


}
