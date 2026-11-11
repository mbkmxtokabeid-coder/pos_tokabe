<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SptMasa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spt_masa';

    protected $fillable = [
        'nomor_spt',
        'masa_pajak_bulan',
        'masa_pajak_tahun',
        'pembetulan_ke',
        'jenis_pajak',
        'nomor_billing',
        'tgl_billing',
        'tgl_jatuh_tempo',
        'dpp',
        'tarif_persen',
        'nominal_pajak',
        'status_kb_lb',
        'nominal_lebih_bayar',
        'opsi_lebih_bayar',
        'tgl_bayar',
        'tgl_lapor',
        'ntpn',
        'status',
        'bukti_bayar',
        'keterangan',
        'rincian_spt',
        'approval',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'masa_pajak_bulan'    => 'integer',
        'masa_pajak_tahun'    => 'integer',
        'pembetulan_ke'       => 'integer',
        'tgl_billing'         => 'date',
        'tgl_jatuh_tempo'     => 'date',
        'tgl_bayar'           => 'date',
        'tgl_lapor'           => 'date',
        'dpp'                 => 'integer',
        'tarif_persen'        => 'float',
        'nominal_pajak'       => 'integer',
        'nominal_lebih_bayar' => 'integer',
        'rincian_spt'         => 'array',
        'approved_at'         => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getMasaPajakLabelAttribute(): string
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $b = $bulanNama[$this->masa_pajak_bulan] ?? (string) $this->masa_pajak_bulan;
        return "{$b} {$this->masa_pajak_tahun}";
    }

    public function getPembetulanLabelAttribute(): string
    {
        $ke = (int) ($this->pembetulan_ke ?? 0);
        return $ke === 0 ? 'NORMAL' : "PEMBETULAN KE-{$ke}";
    }

    public function getEffectiveStatusAttribute(): string
    {
        if (in_array($this->status, ['Lunas', 'Dikompensasikan', 'Restitusi', 'Nihil'], true)) {
            return $this->status;
        }
        if ($this->tgl_jatuh_tempo && Carbon::today()->gt($this->tgl_jatuh_tempo)) {
            return 'Terlambat';
        }
        return $this->status ?: 'Belum Dibayar';
    }

    public static function generateNomorSpt(?int $tahun = null, ?int $bulan = null): string
    {
        $tahun = $tahun ?: (int) date('Y');
        $bulan = $bulan ?: (int) date('n');
        $bulanPad = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
        $prefix = "SPT/{$tahun}/{$bulanPad}/";

        $last = self::withTrashed()
            ->where('nomor_spt', 'LIKE', $prefix . '%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($last && preg_match('/(\d+)$/', $last->nomor_spt, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        return $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
