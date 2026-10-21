<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LaporanBarangExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents, withHeadingRow
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $penjualanBarangs;

    function __construct(Collection $penjualanBarangs)
    {
        $this->penjualanBarangs = $penjualanBarangs;
    }

    public function headings(): array
    {
        return [
            ['#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#'],
            [
                'No',
                'Nomor Invoice',
                'Tanggal',
                'Pelanggan',
                'Perusahaan',
                'Nomor HP',
                'Deskripsi',
                'Status',
                'Alasan Batal',
                'Qty',
                'Harga',
                'Grand Total',
            ]
        ];
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function array(): array
    {
        Carbon::setLocale('id');
        $data = [];

        foreach ($this->penjualanBarangs as $index => $penjualan) {
            if (isset($penjualan->deleted_at) && $penjualan->deleted_at !== null && $penjualan->status !== 'Batal') {
                $penjualan->status = 'Dihapus';
            }
            
            $penjualan->tgl_penjualan = Carbon::parse($penjualan->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');

            $qty = $penjualan->qty == 0 ? '0.00' : $penjualan->qty;
                    $hargaBarang = $penjualan->harga == 0 ? '0.00' : $penjualan->harga;
            $jumlah_harga = $penjualan->jumlah_harga == 0 ? '0.00' : $penjualan->jumlah_harga;
            $deskripsi = $penjualan->jenis_barang . ' ' . $penjualan->deskripsi_item;

            $data[] = [
                $index + 1, // Nomor otomatis berdasarkan index
                $penjualan->nomor_invoice,
                $penjualan->tgl_penjualan,
                $penjualan->customer,
                $penjualan->perusahaan,
                $penjualan->no_telepon ?? '-',
                $deskripsi,
                $penjualan->status,
                ($penjualan->status == 'Batal' && !empty($penjualan->alasan_batal)) ? $penjualan->alasan_batal : '-',
                $qty,
                $hargaBarang,
                $jumlah_harga,
            ];
        }

        return $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge and center the title
                $event->sheet->getDelegate()->mergeCells('A1:L1');
                $event->sheet->getDelegate()->getStyle('A1:L1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                ]);

                $event->sheet->getDelegate()->setCellValue('A1', 'LAPORAN PENJUALAN BARANG IKHTIAR BERKAH');
                // HEADING
                $heading = 'A2:L2';
                $event->sheet->getDelegate()->getStyle($heading)->getFont()->setBold(true);
                // Apply border to all cells in the table
                $lastColumn = $event->sheet->getDelegate()->getHighestColumn();
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $range = 'A2:' . $lastColumn . $lastRow;
                $event->sheet->getDelegate()->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                // Calculate and apply total in column L
                $totalRange = 'L' . ($lastRow + 1);
                $event->sheet->getDelegate()->setCellValue($totalRange, '=SUM(L3:L' . $lastRow . ')');
                $columnL = 'L3:L' . $lastRow;
                $columnK = 'K3:K' . $lastRow;
                $event->sheet->getDelegate()->setCellValue('K' . ($lastRow + 1), 'Jumlah');

                $footerDataKiri = [
                    [
                        'row' => $lastRow + 3,
                        'text' => 'IKHTIAR BERKAH, ' . Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY'),
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 4,
                        'text' => 'Disiapkan Oleh,',
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 8,
                        'text' => Auth::user()->nama,
                        'underline' => true,
                    ],
                    [
                        'row' => $lastRow + 9,
                        'text' => Auth::user()->role,
                        'underline' => false,
                    ],

                ];

                foreach ($footerDataKiri as $data) {
                    $row = $data['row'];
                    $text = $data['text'];
                    $underline = $data['underline'];

                    $event->sheet->getDelegate()->mergeCells('A' . $row . ':' . 'E' . $row);
                    $event->sheet->getDelegate()->getStyle('A' . $row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => false,
                            'size' => 10,
                            'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('A' . $row, $text);
                }

                $footerDataKanan = [

                    [
                        'row' => $lastRow + 4,
                        'text' => 'Diketahui Oleh,',
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 8,
                        'text' => '...............',
                        'underline' => true,
                    ],
                    [
                        'row' => $lastRow + 9,
                        'text' => 'Pimpinan',
                        'underline' => false,
                    ],

                ];

                foreach ($footerDataKanan as $data) {
                    $row = $data['row'];
                    $text = $data['text'];
                    $underline = $data['underline'];


                    $event->sheet->getDelegate()->getStyle('G' . $row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => false,
                            'size' => 10,
                            'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('G' . $row, $text);
                }

                $event->sheet->getDelegate()->getStyle('K' . ($lastRow + 1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($totalRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($columnK)->getNumberFormat()->setFormatCode('Rp#,##0.00;[Red]-Rp#,##0.00');
                $event->sheet->getDelegate()->getStyle($totalRange)->getNumberFormat()->setFormatCode('Rp#,##0.00;[Red]-Rp#,##0.00');
            },
        ];
    }
}
