<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanJasaTokabe extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = "penjualan_jasa_tokabe";
    protected $guarded = [
        'id',
    ];
    
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(PenjualanTokabe::class, 'penjualan_id');
    }
}
