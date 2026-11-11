<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriInvoice extends Model
{
    use HasFactory;

    protected $table = 'kategori_invoice';

    protected $fillable = [
        'nama_kategori',
        'keterangan',
        'use_custom_format',
        'prefix',
        'format_nomor',
        'padding_digit',
        'reset_periode',
        'template_settings',
        'is_active',
    ];

    protected $casts = [
        'use_custom_format' => 'boolean',
        'padding_digit'     => 'integer',
        'template_settings' => 'array',
        'is_active'         => 'boolean',
    ];

    public function invoices()
    {
        return $this->hasMany(PenjualanTokabe::class, 'kategori_invoice_id');
    }

    public static function toRomanMonth(int $month): string
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        return $map[$month] ?? 'I';
    }

    /**
     * Menghasilkan nomor invoice otomatis berdasarkan pola kategori, periode reset,
     * dan menjamin tidak ada duplikasi di tabel penjualan_tokabe.
     */
    public function generateNomorInvoice(?string $tanggal = null): string
    {
        $date = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $year = $date->format('Y');
        $monthNum = (int) $date->format('n');
        $monthTwoDigit = $date->format('m');
        $romanMonth = self::toRomanMonth($monthNum);
        $prefix = trim((string) ($this->prefix ?: 'INV'));
        $padding = max(1, min(8, (int) ($this->padding_digit ?: 3)));

        // Hitung urutan dasar berdasarkan periode reset
        $query = PenjualanTokabe::withTrashed();

        if ($this->use_custom_format) {
            $query->where('kategori_invoice_id', $this->id);
        }

        if ($this->reset_periode === 'monthly') {
            $query->whereYear('tgl_penjualan', $year)
                ->whereMonth('tgl_penjualan', $monthNum);
        } elseif ($this->reset_periode === 'yearly') {
            $query->whereYear('tgl_penjualan', $year);
        }

        $seq = $query->count() + 1;

        $pattern = $this->use_custom_format && trim((string) $this->format_nomor) !== ''
            ? trim((string) $this->format_nomor)
            : '{NUMBER}/{PREFIX}/{ROMAN_MONTH}/{YEAR}';

        // Loop anti-duplikat hingga menemukan nomor yang belum pernah dipakai
        $maxAttempts = 500;
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $numberFormatted = str_pad((string) $seq, $padding, '0', STR_PAD_LEFT);

            // Ganti token format
            $monthReplacement = (strpos($pattern, '{ROMAN_MONTH}') !== false || !$this->use_custom_format) ? $romanMonth : $monthTwoDigit;

            $candidate = str_replace(
                ['{PREFIX}', '{NUMBER}', '{MONTH}', '{ROMAN_MONTH}', '{YEAR}'],
                [$prefix, $numberFormatted, $monthTwoDigit, $romanMonth, $year],
                $pattern
            );

            $exists = PenjualanTokabe::withTrashed()
                ->where('nomor_invoice', $candidate)
                ->exists();

            if (!$exists) {
                return $candidate;
            }
            $seq++;
        }

        return $prefix . '/' . $year . '/' . str_pad((string) $seq, $padding, '0', STR_PAD_LEFT) . '-' . rand(10, 99);
    }
}