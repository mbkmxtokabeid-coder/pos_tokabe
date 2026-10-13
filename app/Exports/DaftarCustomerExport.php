<?php

namespace App\Exports;

use Carbon\Carbon;

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

class DaftarCustomerExport implements FromArray, ShouldAutoSize, WithHeadings, WithEvents, withHeadingRow
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $customers;
protected $selectedInvoice;


    public function __construct(Collection $customers, $selectedInvoice)
    {
        $this->customers = $customers;
        $this->selectedInvoice = $selectedInvoice;
    }


    public function headings(): array
    {
        
        return [
            ['#', '#', '#', '#',],
            [
                'No',
                'Nama',
                'Perusahaan',
                'No. Telepon',
            ]
        ];
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function array(): array
    {
        // dd($this->selectedInvoice); 
        Carbon::setLocale('id');
        $data = [];
    
        foreach ($this->customers as $index => $cust) {
            // Filter data pelanggan berdasarkan invoice yang sesuai
            if ($cust->invoice == $this->selectedInvoice) {
                $data[] = [
                    $index + 1, // Nomor otomatis berdasarkan index
                    $cust->customer,
                    $cust->perusahaan,
                    $cust->no_telepon,
                ];
            } else {
                $data[] = [
                    $index + 1, // Nomor otomatis berdasarkan index
                    $cust->customer,
                    $cust->perusahaan,
                    $cust->no_telepon,
                ];
                
            }
        }
    
        return $data;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Merge and center the title
                $event->sheet->getDelegate()->mergeCells('A1:D1');
                $event->sheet->getDelegate()->getStyle('A1:D1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                ]);

                $event->sheet->getDelegate()->setCellValue('A1', 'DAFTAR DATA PELANGGAN');
                // HEADING
                $heading = 'A2:D2';
                $event->sheet->getDelegate()->getStyle($heading)->getFont()->setBold(true);
                // Apply border to all cells in the table
                $lastColumn = $event->sheet->getDelegate()->getHighestColumn();
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $range = 'A2:' . $lastColumn . $lastRow;
                $event->sheet->getDelegate()->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                // Calculate and apply total in column I

                // $footerDataKiri = [
                //     [
                //         'row' => $lastRow + 3,
                //         'text' => 'IKHTIAR BERKAH, ' . Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY'),
                //         'underline' => false,
                //     ],
                //     [
                //         'row' => $lastRow + 4,
                //         'text' => 'Disiapkan Oleh,',
                //         'underline' => false,
                //     ],
                //     [
                //         'row' => $lastRow + 8,
                //         'text' => Auth::user()->nama,
                //         'underline' => true,
                //     ],
                //     [
                //         'row' => $lastRow + 9,
                //         'text' => Auth::user()->role,
                //         'underline' => false,
                //     ],

                // ];

                // foreach ($footerDataKiri as $data) {
                //     $row = $data['row'];
                //     $text = $data['text'];
                //     $underline = $data['underline'];

                //     $event->sheet->getDelegate()->mergeCells('A' . $row . ':' . 'B' . $row);
                //     $event->sheet->getDelegate()->getStyle('A' . $row)->applyFromArray([
                //         'alignment' => [
                //             'horizontal' => Alignment::HORIZONTAL_CENTER,
                //         ],
                //         'font' => [
                //             'bold' => false,
                //             'size' => 10,
                //             'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                //         ],
                //     ]);
                //     $event->sheet->getDelegate()->setCellValue('A' . $row, $text);
                // }

                // $footerDataKanan = [

                //     [
                //         'row' => $lastRow + 4,
                //         'text' => 'Diketahui Oleh,',
                //         'underline' => false,
                //     ],
                //     [
                //         'row' => $lastRow + 8,
                //         'text' => '...............',
                //         'underline' => true,
                //     ],
                //     [
                //         'row' => $lastRow + 9,
                //         'text' => 'Pimpinan',
                //         'underline' => false,
                //     ],

                // ];

                // foreach ($footerDataKanan as $data) {
                //     $row = $data['row'];
                //     $text = $data['text'];
                //     $underline = $data['underline'];


                //     $event->sheet->getDelegate()->getStyle('C' . $row)->applyFromArray([
                //         'alignment' => [
                //             'horizontal' => Alignment::HORIZONTAL_CENTER,
                //         ],
                //         'font' => [
                //             'bold' => false,
                //             'size' => 10,
                //             'underline' => $underline ? Font::UNDERLINE_SINGLE : Font::UNDERLINE_NONE,
                //         ],
                //     ]);
                //     $event->sheet->getDelegate()->setCellValue('C' . $row, $text);
                // }
            },
        ];
    }
}
