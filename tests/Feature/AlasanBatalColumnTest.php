<?php

namespace Tests\Feature;

use App\Exports\LaporanBarangExport;
use App\Exports\LaporanExport;
use App\Models\PenjualanTokabe;
use Tests\TestCase;

class AlasanBatalColumnTest extends TestCase
{
    /**
     * Test header and column structure in daftar-invoiceTokabe view.
     */
    public function test_daftar_invoice_tokabe_has_alasan_batal_next_to_status(): void
    {
        $content = file_get_contents(resource_path('views/pages/invoices/tokabe/daftar-invoiceTokabe.blade.php'));

        $this->assertStringContainsString('<th scope="col">Status</th>', $content);
        $this->assertStringContainsString('<th scope="col">Alasan Batal</th>', $content);

        // Verify Alasan Batal is directly after Status in thead
        $posStatus = strpos($content, '<th scope="col">Status</th>');
        $posAlasan = strpos($content, '<th scope="col">Alasan Batal</th>');
        $posApproval = strpos($content, '<th scope="col">Approval</th>');

        $this->assertNotFalse($posStatus);
        $this->assertNotFalse($posAlasan);
        $this->assertNotFalse($posApproval);
        $this->assertTrue($posStatus < $posAlasan, 'Status must come before Alasan Batal');
        $this->assertTrue($posAlasan < $posApproval, 'Alasan Batal must come before Approval');
    }

    /**
     * Test header and column structure in daftar-invoiceTokabeCustom view.
     */
    public function test_daftar_invoice_tokabe_custom_has_alasan_batal_next_to_status(): void
    {
        $content = file_get_contents(resource_path('views/pages/invoices/tokabe/daftar-invoiceTokabeCustom.blade.php'));

        $this->assertStringContainsString('<th scope="col" class="text-center">Status</th>', $content);
        $this->assertStringContainsString('<th scope="col">Alasan Batal</th>', $content);

        // Verify Alasan Batal is directly after Status in thead
        $posStatus = strpos($content, '<th scope="col" class="text-center">Status</th>');
        $posAlasan = strpos($content, '<th scope="col">Alasan Batal</th>');
        $posApproval = strpos($content, '<th scope="col" class="text-center">Approval</th>');

        $this->assertNotFalse($posStatus);
        $this->assertNotFalse($posAlasan);
        $this->assertNotFalse($posApproval);
        $this->assertTrue($posStatus < $posAlasan, 'Status must come before Alasan Batal');
        $this->assertTrue($posAlasan < $posApproval, 'Alasan Batal must come before Approval');
    }

    /**
     * Test LaporanExport has Alasan Batal next to Status.
     */
    public function test_laporan_export_headings_and_data(): void
    {
        $export = new LaporanExport(collect([]));
        $headings = $export->headings();

        $cols = $headings[1];
        $statusIndex = array_search('Status', $cols);
        $alasanIndex = array_search('Alasan Batal', $cols);

        $this->assertNotFalse($statusIndex);
        $this->assertNotFalse($alasanIndex);
        $this->assertEquals($statusIndex + 1, $alasanIndex, 'Alasan Batal must be immediately next to Status');

        // Test with sample data
        $sample = new PenjualanTokabe([
            'nomor_invoice' => 'INV-001',
            'tgl_penjualan' => '2026-10-21',
            'customer' => 'Customer A',
            'perusahaan' => 'PT ABC',
            'no_telepon' => '08123456789',
            'total_harga' => 100000,
            'status' => 'Batal',
            'alasan_batal' => 'Salah input item barang',
            'total_pembayaran' => 100000,
        ]);
        $sample->id = 1;

        $exportWithData = new LaporanExport(collect([$sample]));
        $dataRows = $exportWithData->array();

        $this->assertCount(1, $dataRows);
        $row = $dataRows[0];
        // Col J (index 9) is status, Col K (index 10) is alasan_batal
        $this->assertEquals('Batal', $row[9]);
        $this->assertEquals('Salah input item barang', $row[10]);
    }

    /**
     * Test LaporanBarangExport has Alasan Batal next to Status.
     */
    public function test_laporan_barang_export_headings_and_data(): void
    {
        $export = new LaporanBarangExport(collect([]));
        $headings = $export->headings();

        $cols = $headings[1];
        $statusIndex = array_search('Status', $cols);
        $alasanIndex = array_search('Alasan Batal', $cols);

        $this->assertNotFalse($statusIndex);
        $this->assertNotFalse($alasanIndex);
        $this->assertEquals($statusIndex + 1, $alasanIndex, 'Alasan Batal must be immediately next to Status');

        $sample = (object) [
            'nomor_invoice' => 'INV-001',
            'tgl_penjualan' => '2026-10-21',
            'customer' => 'Customer A',
            'perusahaan' => 'PT ABC',
            'no_telepon' => '08123456789',
            'jenis_barang' => 'Barang A',
            'deskripsi_item' => 'Deskripsi',
            'status' => 'Batal',
            'alasan_batal' => 'Customer minta cancel',
            'qty' => 2,
            'harga' => 50000,
            'jumlah_harga' => 100000,
        ];

        $exportWithData = new LaporanBarangExport(collect([$sample]));
        $dataRows = $exportWithData->array();

        $this->assertCount(1, $dataRows);
        $row = $dataRows[0];
        // Index 7 is Status, Index 8 is Alasan Batal
        $this->assertEquals('Batal', $row[7]);
        $this->assertEquals('Customer minta cancel', $row[8]);
    }

    /**
     * Test PDF views contain ALASAN BATAL next to STATUS.
     */
    public function test_pdf_views_contain_alasan_batal_next_to_status(): void
    {
        $pdfFiles = [
            resource_path('views/pages/laporan/pdf.blade.php'),
            resource_path('views/pages/laporan/pdf-non-status.blade.php'),
            resource_path('views/pages/laporan/pdf-laporan-barang.blade.php'),
        ];

        foreach ($pdfFiles as $path) {
            $content = file_get_contents($path);
            $this->assertStringContainsString('STATUS', $content, "File $path missing STATUS header");
            $this->assertStringContainsString('ALASAN BATAL', $content, "File $path missing ALASAN BATAL header");

            $posStatus = strpos($content, '<th scope="col">STATUS</th>');
            $posAlasan = strpos($content, '<th scope="col">ALASAN BATAL</th>');

            $this->assertNotFalse($posStatus, "File $path missing <th scope=\"col\">STATUS</th>");
            $this->assertNotFalse($posAlasan, "File $path missing <th scope=\"col\">ALASAN BATAL</th>");
            $this->assertTrue($posStatus < $posAlasan, "In $path, STATUS must precede ALASAN BATAL");
        }
    }
}
