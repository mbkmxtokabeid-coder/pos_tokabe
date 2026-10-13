<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekening';

    protected $fillable = [
        'nama_bank',
        'atas_nama',
        'nomor_rekening',
        'keterangan',
        'status',
    ];

    /**
     * Get the formatted string for invoice display:
     * e.g. "BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999"
     */
    public function getFormattedAttribute(): string
    {
        return "{$this->nama_bank} | A/N : {$this->atas_nama} | No. Rek : {$this->nomor_rekening}";
    }
}
