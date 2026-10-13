<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'nama_item',
        'jenis_pajak',
    ];

    public function penjualanJasaTokabe()
    {
        return $this->hasMany(PenjualanJasaTokabe::class, 'item_id');
    }

    public function getJenisBarangAttribute(): string
    {
        return $this->nama_item;
    }
}
