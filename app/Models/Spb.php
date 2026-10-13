<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spb extends Model
{
    use HasFactory;
    protected $table = 'spb';
    protected $fillable = [
        'nama_spb',
        'customer',
        'perusahaan',
        'nomor_telepon',
        'nama_penandatangan',
        'jumlah_item',
        'status',
    ];
    public function barangSpb()
    {
        return $this->hasMany(BarangSpb::class, 'barang_id');
    }

}
