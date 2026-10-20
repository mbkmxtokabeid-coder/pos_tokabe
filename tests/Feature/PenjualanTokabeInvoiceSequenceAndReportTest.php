<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PenjualanTokabe;
use App\Models\PenjualanJasaTokabe;
use App\Models\Invoice;
use App\Models\User;
use App\Http\Controllers\PenjualanTokabeController;
use App\Http\Controllers\LaporanController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenjualanTokabeInvoiceSequenceAndReportTest extends TestCase
{
    protected $pemilik;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pemilik = User::where('role', 'Pemilik')->first() ?? User::first();
    }

    /**
     * Test bahwa nomor invoice tetap berlanjut meskipun invoice sebelumnya dibatalkan atau dihapus.
     */
    public function test_penomoran_invoice_tetap_berlanjut_meskipun_dihapus_atau_dibatalkan(): void
    {
        $controller = new PenjualanTokabeController();

        // Ambil nomor yang digenerate untuk Oktober 2026
        $latestInvoice = \App\Models\PenjualanTokabe::withTrashed()
            ->whereYear('tgl_penjualan', 2026)
            ->whereMonth('tgl_penjualan', 10)
            ->orderBy('id', 'desc')
            ->first();
        $expectedNumber = 1;
        if ($latestInvoice && preg_match('/^(\d+)\/TKB\//', $latestInvoice->nomor_invoice, $m)) {
            $expectedNumber = (int)$m[1] + 1;
        }
        $expectedFormat = sprintf('%03d/TKB/X/2026', $expectedNumber);
        $noInvoice = $controller->generateNoInvoice('2026-10-14');

        // Nomor invoice harus berlanjut dan TIDAK mundur ke 002 atau 003
        $this->assertEquals($expectedFormat, $noInvoice);
        $this->assertNotEquals('002/TKB/X/2026', $noInvoice);
        $this->assertNotEquals('003/TKB/X/2026', $noInvoice);
    }

    /**
     * Test bahwa laporan penjualan exportByInvoice menyertakan invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_invoice_menyertakan_invoice_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $controller = new LaporanController();

        // Request Semua Status
        $request = new Request([
            'invoice' => 'semua',
            'status' => 'semua',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByInvoice($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('nomor_invoice', '002/TKB/X/2026'));
        $this->assertTrue($penjualans->contains('nomor_invoice', '003/TKB/X/2026'));

        $inv003 = $penjualans->firstWhere('nomor_invoice', '003/TKB/X/2026');
        $this->assertEquals('Dihapus', $inv003->status);
    }

    /**
     * Test bahwa laporan penjualan exportByDate menyertakan invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_date_menyertakan_invoice_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $controller = new LaporanController();

        $request = new Request([
            'invoice' => 'semua',
            'tgl_custom' => '08 Oct, 2026 to 14 Oct, 2026',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByDate($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('nomor_invoice', '002/TKB/X/2026'));
        $this->assertTrue($penjualans->contains('nomor_invoice', '003/TKB/X/2026'));
    }

    /**
     * Test bahwa laporan penjualan exportByMonth menyertakan invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_month_menyertakan_invoice_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $controller = new LaporanController();

        $request = new Request([
            'invoice' => 'semua',
            'bulan' => 'Oktober',
            'tahun' => '2026',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByMonth($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('nomor_invoice', '002/TKB/X/2026'));
        $this->assertTrue($penjualans->contains('nomor_invoice', '003/TKB/X/2026'));
    }

    /**
     * Test bahwa laporan penjualan exportByBarang menyertakan line item invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_barang_menyertakan_line_item_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $controller = new LaporanController();

        $request = new Request([
            'invoice' => 'semua',
            'tgl_custom' => '08 Oct, 2026 to 14 Oct, 2026',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByBarang($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('status', 'Batal'));
        $this->assertTrue($penjualans->contains('status', 'Dihapus'));
    }
}
