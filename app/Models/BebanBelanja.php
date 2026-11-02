<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BebanBelanja extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'beban_belanja';

    protected $fillable = [
        'nomor_beban',
        'tanggal',
        'kategori_id',
        'nama_beban',
        'nominal',
        'anggaran_id',
        'rekening_id',
        'penerima',
        'bukti_struk',
        'keterangan',
        'admin_id',
    ];

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBeban::class, 'kategori_id');
    }

    public function anggaran()
    {
        return $this->belongsTo(Budget::class, 'anggaran_id');
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'rekening_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    public function getFormattedTanggalAttribute(): string
    {
        if (empty($this->tanggal)) {
            return '-';
        }
        return Carbon::parse($this->tanggal)->locale('id')->isoFormat('D MMMM Y');
    }

    /**
     * Generate auto nomor beban belanja: BBN/YYYYMM/0001
     */
    public static function generateNomorBeban(?string $tanggal = null): string
    {
        $date = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $prefix = 'BBN/' . $date->format('Ym') . '/';

        $lastRecord = self::withTrashed()
            ->where('nomor_beban', 'LIKE', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        if ($lastRecord && preg_match('/' . preg_quote($prefix, '/') . '(\d+)$/', $lastRecord->nomor_beban, $matches)) {
            $nextSequence = intval($matches[1]) + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad($nextSequence, 4, '0', STR_PAD_LEFT);
    }
}
