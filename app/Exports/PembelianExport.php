<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembelianExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected Collection $pembelian;
    protected string $vendorName;
    protected string $periodeLabel;
    protected string $statusLabel;
    protected float $grandTotalTransaksi;
    protected float $grandTotalTerbayar;
    protected float $grandTotalSisa;

    public function __construct(
        Collection $pembelian,
        string $vendorName = 'Semua Vendor',
        string $periodeLabel = 'Seluruh Periode',
        string $statusLabel = 'Semua Status',
        float $grandTotalTransaksi = 0,
        float $grandTotalTerbayar = 0,
        float $grandTotalSisa = 0
    ) {
        $this->pembelian = $pembelian;
        $this->vendorName = $vendorName;
        $this->periodeLabel = $periodeLabel;
        $this->statusLabel = $statusLabel;
        $this->grandTotalTransaksi = $grandTotalTransaksi;
        $this->grandTotalTerbayar = $grandTotalTerbayar;
        $this->grandTotalSisa = $grandTotalSisa;
    }

    public function collection(): Collection
    {
        $rows = $this->pembelian->map(function ($item, $index) {
            return [
                $index + 1,
                $item->nomor_inv ?: '-',
                optional($item->tanggal)->format('d/m/Y') ?: '-',
                optional($item->effective_tanggal_jto)->format('d/m/Y') ?: '-',
                optional($item->vendor)->nama_vendor ?: '-',
                (float) ($item->jumlah_harga ?? 0),
                (float) ($item->terbayar ?? 0),
                (float) ($item->sisa ?? 0),
                $item->status ?: '-',
            ];
        });

        if ($rows->isNotEmpty()) {
            $rows->push([
                '',
                '',
                '',
                '',
                'GRAND TOTAL',
                $this->grandTotalTransaksi,
                $this->grandTotalTerbayar,
                $this->grandTotalSisa,
                '',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No.',
            'No. Invoice',
            'Tanggal Pembelian',
            'Tanggal Jatuh Tempo',
            'Nama Vendor',
            'Total Transaksi (Rp)',
            'Total Terbayar (Rp)',
            'Sisa Tagihan (Rp)',
            'Status',
        ];
    }
}
