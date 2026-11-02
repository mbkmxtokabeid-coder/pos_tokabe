<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBeban extends Model
{
    use HasFactory;

    protected $table = 'kategori_beban';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function bebanBelanja()
    {
        return $this->hasMany(BebanBelanja::class, 'kategori_id');
    }
}
