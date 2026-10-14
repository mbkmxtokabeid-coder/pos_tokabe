<?php

namespace App\Exports;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan DB facade untuk query relasi

class LaporanExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents, WithHeadingRow
{
    /**
     * @return \Illuminate\Support\Collection
     */
    
    protected $penjualans;
    protected $namaPerusahaan;

    public function __construct(Collection $penjualans, $namaPerusahaan = null)
    {
        $this->penjualans = $penjualans;
        $this->namaPerusahaan = $namaPerusahaan;
    }

    public function headings(): array
    {
        return [
            // Disesuaikan menjadi 12 kolom
            ['#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#'],
            [
                'No',
                'Nomor Invoice',
                'Tanggal Penjualan',
                'Pelanggan',
                'Perusahaan',
                'Nomor HP',
                'Deskripsi Item',      // Kolom Baru
                'Jumlah Item',         // Kolom Baru
                'Total Harga',
                'Status',
                'Potongan/Diskon/PPN',
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

        foreach ($this->penjualans as $index => $penjualan) {
            if ((method_exists($penjualan, 'trashed') && $penjualan->trashed() && $penjualan->status !== 'Batal') ||
                (isset($penjualan->deleted_at) && $penjualan->deleted_at !== null && $penjualan->status !== 'Batal')) {
                $penjualan->status = 'Dihapus';
            }
            
            $penjualan->tgl_penjualan = Carbon::parse($penjualan->tgl_penjualan)->locale('id')->isoFormat('DD MMMM, YYYY');

            // --- AMBIL DATA BARANG SAMA SEPERTI PDF ---
            if (isset($penjualan->id)) {
                $tableRelasi = 'penjualan_jasa_tokabe';
                $fkColumn = 'penjualan_id';

                try {
                    $items = DB::table($tableRelasi)
                        ->leftJoin('barang', $tableRelasi . '.barang_id', '=', 'barang.id')
                        ->leftJoin('items', $tableRelasi . '.item_id', '=', 'items.id')
                        ->where($tableRelasi . '.' . $fkColumn, $penjualan->id)
                        ->select(DB::raw('COALESCE(items.nama_item, barang.jenis_barang) as jenis_barang'), $tableRelasi . '.deskripsi_item', $tableRelasi . '.qty', $tableRelasi . '.satuan')
                        ->get();
                } catch (\Exception $e) {
                    $items = collect([]);
                }
            } else {
                $items = collect([]);
            }

            $deskripsiArr = [];
            $jumlahArr = [];

            if ($items->count() > 0) {
                foreach ($items as $item) {
                    // Gabungkan jenis barang dan deskripsi dengan baris baru
                    $desc = $item->jenis_barang;
                    if (!empty($item->deskripsi_item)) {
                        $desc .= "\n(" . $item->deskripsi_item . ")";
                    }
                    $deskripsiArr[] = $desc;
                    $jumlahArr[] = $item->qty . ' ' . $item->satuan;
                }
            } else {
                $deskripsiArr[] = '-';
                $jumlahArr[] = '-';
            }

            // Gabungkan array menjadi string dipisah dua enter agar renggang per-item
            $deskripsiGabungan = implode("\n\n", $deskripsiArr);
            $jumlahGabungan = implode("\n\n", $jumlahArr);
            // ------------------------------------------

            $hasPpn = !empty($penjualan->ppn) && (float)$penjualan->ppn > 0;
            $hasDiskon = !empty($penjualan->diskon) && (float)$penjualan->diskon > 0;
            $hasPotongan = !empty($penjualan->potongan) && (float)$penjualan->potongan > 0;
            $hasPph = !empty($penjualan->pph) && (float)$penjualan->pph > 0;

            $autoPpnPersen = 0;
            if (!$hasPpn && !$hasDiskon && !$hasPotongan && !$hasPph && $penjualan->total_pembayaran > $penjualan->total_harga && $penjualan->total_harga > 0) {
                $autoPpnNominal = $penjualan->total_pembayaran - $penjualan->total_harga;
                $autoPpnPersen = round(($autoPpnNominal / $penjualan->total_harga) * 100);
                $hasPpn = true;
            }

            if ($hasDiskon) {
                $penjualan->lain = 'dsc ' . $penjualan->diskon . '%';
            } elseif ($hasPpn) {
                $ppnVal = !empty($penjualan->ppn) ? $penjualan->ppn : $autoPpnPersen;
                $penjualan->lain = 'ppn ' . $ppnVal . '%';
            } elseif ($hasPph) {
                $penjualan->lain = 'pph ' . $penjualan->pph . '%';
            } elseif ($hasPotongan) {
                $penjualan->lain = 'pot Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            } else {
                $penjualan->lain = '-';
            }

            $data[] = [
                $index + 1, // Nomor otomatis berdasarkan index
                $penjualan->nomor_invoice,
                $penjualan->tgl_penjualan,
                $penjualan->customer,
                $penjualan->perusahaan,
                $penjualan->no_telepon ?? '-', // Nomor HP
                $deskripsiGabungan,    // Ditampilkan di Excel (Wrap Text)
                $jumlahGabungan,       // Ditampilkan di Excel (Wrap Text)
                $penjualan->total_harga,
                $penjualan->status,
                $penjualan->lain,
                $penjualan->total_pembayaran,
            ];
        }

        return $data;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge and center the title sampai kolom L (12 Kolom)
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

                $perusahaanTitle = !empty($this->namaPerusahaan) ? strtoupper($this->namaPerusahaan) : 'IKHTIAR BERKAH';
                $event->sheet->getDelegate()->setCellValue('A1', 'LAPORAN PENJUALAN ' . $perusahaanTitle . ' ');
                
                // HEADING
                $heading = 'A2:L2';
                $event->sheet->getDelegate()->getStyle($heading)->getFont()->setBold(true);
                
                // Apply border to all cells in the table
                $lastColumn = $event->sheet->getDelegate()->getHighestColumn(); // Seharusnya menjadi 'L'
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $range = 'A2:' . $lastColumn . $lastRow;
                
                // Wajib untuk multiline cell (agar \n berfungsi di Excel) & rata atas
                $event->sheet->getDelegate()->getStyle('A3:' . $lastColumn . $lastRow)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A3:' . $lastColumn . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

                $event->sheet->getDelegate()->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Calculate and apply total in column L (Grand Total sekarang di L)
                $totalRange = 'L' . ($lastRow + 1);
                $event->sheet->getDelegate()->setCellValue($totalRange, '=SUM(L3:L' . $lastRow . ')');
                $columnL = 'L3:L' . $lastRow;
                $columnI = 'I3:I' . $lastRow; // Total harga sekarang ada di I
                
                // Geser kata 'Jumlah' ke kolom K
                $event->sheet->getDelegate()->setCellValue('K' . ($lastRow + 1), 'Jumlah');
                $event->sheet->getDelegate()->getStyle('K' . ($lastRow + 1))->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle($totalRange)->getFont()->setBold(true);

                // Formatting Rupiah
                $event->sheet->getDelegate()->getStyle($columnL)->getNumberFormat()->setFormatCode('Rp#,##0.00');
                $event->sheet->getDelegate()->getStyle($columnI)->getNumberFormat()->setFormatCode('Rp#,##0.00');
                $event->sheet->getDelegate()->getStyle($totalRange)->getNumberFormat()->setFormatCode('Rp#,##0.00');

                $perusahaanNama = !empty($this->namaPerusahaan) ? $this->namaPerusahaan : 'IKHTIAR BERKAH';
                $footerDataKiri = [
                    [
                        'row' => $lastRow + 3,
                        'text' => $perusahaanNama . ', ' . Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY'),
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 4,
                        'text' => 'Disiapkan Oleh,',
                        'underline' => false,
                    ],
                    [
                        'row' => $lastRow + 8,
                        'text' => Auth::user()->nama ?? 'Admin',
                        'underline' => true,
                    ],
                    [
                        'row' => $lastRow + 9,
                        'text' => Auth::user()->role ?? 'Staff',
                        'underline' => false,
                    ],
                ];

                foreach ($footerDataKiri as $data) {
                    $row = $data['row'];
                    $text = $data['text'];
                    $underline = $data['underline'];

                    $event->sheet->getDelegate()->mergeCells('A' . $row . ':' . 'F' . $row);
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

                    // Menggeser posisi tanda tangan dari kolom I ke kolom J agar sejajar kanan tabel (12 kolom)
                    $event->sheet->getDelegate()->getStyle('J' . $row)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'font' => [
                            'bold' => false,
                            'size' => 10,
                            'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                        ],
                    ]);
                    $event->sheet->getDelegate()->setCellValue('J' . $row, $text);
                }
            },
        ];
    }
}