<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'budget';

    protected $fillable = [
        'nama_budget',
        'kategori_id',
        'anggaran',
        'tanggal',
        'deleted_at',
    ];
    function kategori_barang()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }
}
