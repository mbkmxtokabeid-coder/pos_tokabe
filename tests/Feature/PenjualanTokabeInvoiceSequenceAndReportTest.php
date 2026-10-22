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
    protected $createdFixtureIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->pemilik = User::where('role', 'Pemilik')->first() ?? User::first();
    }

    protected function tearDown(): void
    {
        if (!empty($this->createdFixtureIds)) {
            PenjualanJasaTokabe::withTrashed()->whereIn('penjualan_id', $this->createdFixtureIds)->forceDelete();
            PenjualanTokabe::withTrashed()->whereIn('id', $this->createdFixtureIds)->forceDelete();
        }
        parent::tearDown();
    }

    /**
     * Test bahwa nomor invoice sah setelah 8 Oktober 2026 adalah 003, dan tetap berlanjut jika dihapus/dibatalkan.
     */
    public function test_penomoran_invoice_tetap_berlanjut_meskipun_dihapus_atau_dibatalkan(): void
    {
        $controller = new PenjualanTokabeController();

        // Database sah per 8 Oktober memiliki 001 dan 002, sehingga nomor berikutnya adalah 003/TKB/X/2026
        $noInvoice = $controller->generateNoInvoice('2026-10-14');
        $this->assertEquals('003/TKB/X/2026', $noInvoice);

        // Buat dummy invoice 003 dan hapus (soft delete) untuk menguji penomoran berikutnya
        $inv003 = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => '003/TKB/X/2026',
            'is_custom' => 0,
            'tgl_penjualan' => '2026-10-14 10:00:00',
            'customer' => 'Fixture Test Del',
            'no_telepon' => '08123456789',
            'admin' => $this->pemilik->id,
            'status' => 'Belum Lunas',
            'tgl_selesai' => '2026-10-15',
            'jumlah_item' => 1,
            'jenis_pembayaran' => 'Transfer Lunas',
            'no_rek' => 'BCA 123456789',
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
        ]);
        $this->createdFixtureIds[] = $inv003->id;
        $inv003->delete();

        // Setelah 003 dihapus, nomor berikutnya harus melompat ke 004/TKB/X/2026 dan tidak mundur
        $nextInvoice = $controller->generateNoInvoice('2026-10-14');
        $this->assertEquals('004/TKB/X/2026', $nextInvoice);
        $this->assertNotEquals('002/TKB/X/2026', $nextInvoice);
        $this->assertNotEquals('003/TKB/X/2026', $nextInvoice);
    }

    /**
     * Helper untuk membuat data fixture report (batal & dihapus)
     */
    private function createReportFixtures(): array
    {
        $batal = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'FIXTURE-BATAL-001',
            'is_custom' => 0,
            'tgl_penjualan' => '2026-10-10 10:00:00',
            'customer' => 'Customer Fixture Batal',
            'no_telepon' => '08123456789',
            'admin' => $this->pemilik->id,
            'status' => 'Batal',
            'alasan_batal' => 'Uji Coba Laporan',
            'tgl_selesai' => '2026-10-11',
            'jumlah_item' => 1,
            'jenis_pembayaran' => 'Transfer Lunas',
            'no_rek' => 'BCA 123456789',
            'total_harga' => 150000,
            'total_pembayaran' => 150000,
            'sisa_pembayaran' => 150000,
        ]);
        $this->createdFixtureIds[] = $batal->id;

        PenjualanJasaTokabe::create([
            'penjualan_id' => $batal->id,
            'deskripsi_item' => 'Item Fixture Batal',
            'qty' => 1,
            'satuan' => 'Pcs',
            'harga' => 150000,
            'jumlah_harga' => 150000,
        ]);

        $dihapus = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'FIXTURE-DEL-002',
            'is_custom' => 0,
            'tgl_penjualan' => '2026-10-11 11:00:00',
            'customer' => 'Customer Fixture Dihapus',
            'no_telepon' => '08123456789',
            'admin' => $this->pemilik->id,
            'status' => 'Belum Lunas',
            'tgl_selesai' => '2026-10-12',
            'jumlah_item' => 1,
            'jenis_pembayaran' => 'Transfer Lunas',
            'no_rek' => 'BCA 123456789',
            'total_harga' => 200000,
            'total_pembayaran' => 200000,
            'sisa_pembayaran' => 200000,
        ]);
        $this->createdFixtureIds[] = $dihapus->id;

        PenjualanJasaTokabe::create([
            'penjualan_id' => $dihapus->id,
            'deskripsi_item' => 'Item Fixture Dihapus',
            'qty' => 1,
            'satuan' => 'Pcs',
            'harga' => 200000,
            'jumlah_harga' => 200000,
        ]);
        $dihapus->delete();

        return [$batal, $dihapus];
    }

    /**
     * Test bahwa laporan penjualan exportByInvoice menyertakan invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_invoice_menyertakan_invoice_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $this->createReportFixtures();
        $controller = new LaporanController();

        $request = new Request([
            'invoice' => 'semua',
            'status' => 'semua',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByInvoice($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('nomor_invoice', 'FIXTURE-BATAL-001'));
        $this->assertTrue($penjualans->contains('nomor_invoice', 'FIXTURE-DEL-002'));

        $invDel = $penjualans->firstWhere('nomor_invoice', 'FIXTURE-DEL-002');
        $this->assertEquals('Dihapus', $invDel->status);
    }

    /**
     * Test bahwa laporan penjualan exportByDate menyertakan invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_date_menyertakan_invoice_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $this->createReportFixtures();
        $controller = new LaporanController();

        $request = new Request([
            'invoice' => 'semua',
            'tgl_custom' => '08 Oct, 2026 to 14 Oct, 2026',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByDate($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('nomor_invoice', 'FIXTURE-BATAL-001'));
        $this->assertTrue($penjualans->contains('nomor_invoice', 'FIXTURE-DEL-002'));
    }

    /**
     * Test bahwa laporan penjualan exportByMonth menyertakan invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_month_menyertakan_invoice_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $this->createReportFixtures();
        $controller = new LaporanController();

        $request = new Request([
            'invoice' => 'semua',
            'bulan' => 'Oktober',
            'tahun' => '2026',
            'export_type' => 'pdf',
        ]);

        $response = $controller->exportByMonth($request);
        $penjualans = $response->getData()['penjualans'];

        $this->assertTrue($penjualans->contains('nomor_invoice', 'FIXTURE-BATAL-001'));
        $this->assertTrue($penjualans->contains('nomor_invoice', 'FIXTURE-DEL-002'));
    }

    /**
     * Test bahwa laporan penjualan exportByBarang menyertakan line item invoice yang dibatalkan maupun dihapus.
     */
    public function test_laporan_export_by_barang_menyertakan_line_item_batal_dan_dihapus(): void
    {
        $this->actingAs($this->pemilik);
        $this->createReportFixtures();
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
