<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\InvoiceApprovalRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TokabeNpwpAndFakturPajakWorkflowTest extends TestCase
{
    protected $pemilik;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pemilik = User::where('role', 'Pemilik')->first();
        if (!$this->pemilik) {
            $this->pemilik = User::create([
                'nama' => 'Pemilik Test',
                'email' => 'pemilik_faktur_test@example.com',
                'password' => bcrypt('password'),
                'role' => 'Pemilik',
                'status' => 'Aktif',
                'nomor_telepon' => '08123456789',
            ]);
        }

        $this->admin = User::where('role', 'Admin')->first();
        if (!$this->admin) {
            $this->admin = User::create([
                'nama' => 'Admin Test',
                'email' => 'admin_faktur_test@example.com',
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'status' => 'Aktif',
                'nomor_telepon' => '08123456788',
            ]);
        }
    }

    /**
     * Requirement 1 & 2:
     * Inisialisasi Invoice Baru menyimpan NPWP dan otomatis berstatus Terkunci (Locked)
     * dengan Nomor Faktur Pajak kosong (Belum Diisi).
     */
    public function test_new_invoice_is_locked_and_faktur_pajak_is_unfilled_initially(): void
    {
        $uniqueNo = 'TKB/TEST/' . uniqid();
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => $uniqueNo,
            'is_custom' => 0,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => 'PT Pelanggan Maju',
            'no_telepon' => '081234567890',
            'admin' => $this->admin->id,
            'tgl_selesai' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jumlah_item' => 1,
            'total_harga' => 500000,
            'total_pembayaran' => 500000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'order_by' => 'Toko',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
            'npwp' => '01.234.567.8-901.000',
            'no_faktur_pajak' => null,
            'faktur_pajak_status' => 'Belum Diisi',
            'approval' => 'Lock',
            'approved_at' => null,
        ]);

        $this->assertDatabaseHas('penjualan_tokabe', [
            'id' => $invoice->id,
            'npwp' => '01.234.567.8-901.000',
            'no_faktur_pajak' => null,
            'faktur_pajak_status' => 'Belum Diisi',
            'approval' => 'Lock',
        ]);

        $this->assertFalse($invoice->isFakturPajakLengkap());
        $this->assertEquals('Belum Diisi', $invoice->status_faktur_pajak);
        $this->assertTrue($invoice->isLockedForAdmin());

        $invoice->forceDelete();
    }

    /**
     * Requirement 3 (Pengajuan Akses):
     * Admin tidak dapat langsung mengubah invoice yang berstatus terkunci.
     */
    public function test_admin_cannot_update_locked_invoice(): void
    {
        $uniqueNo = 'TKB/LOCK/' . uniqid();
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => $uniqueNo,
            'is_custom' => 0,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => 'Customer Lock Test',
            'no_telepon' => '081234567890',
            'admin' => $this->admin->id,
            'tgl_selesai' => Carbon::now()->format('Y-m-d'),
            'jumlah_item' => 1,
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'order_by' => 'Toko',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
            'npwp' => '01.234.567.8-901.000',
            'no_faktur_pajak' => null,
            'approval' => 'Lock',
        ]);

        // Coba lakukan PUT update invoice saat status masih Lock sebagai Admin
        $response = $this->actingAs($this->admin)->put(route('update-invoiceTokabe', $invoice->id), [
            'pelanggan' => 'Customer Renamed Attempt',
            'tlp' => '081234567890',
            'adm' => $this->admin->id,
            'order' => 'Toko',
            'npwp' => '01.234.567.8-901.000',
            'no_faktur_pajak' => '010.000-26.00000001',
        ]);

        $response->assertRedirect(route('list_invoice_tokabe'));

        // Pastikan invoice di database tetap tidak berubah dan tidak terisi nomor faktur
        $invoice->refresh();
        $this->assertNull($invoice->no_faktur_pajak);
        $this->assertEquals('Lock', $invoice->approval);

        $invoice->forceDelete();
    }

    /**
     * Requirement 3 (Pengajuan Akses):
     * Admin mengajukan izin "Minta Akses Edit" penginputan Nomor Faktur Pajak.
     */
    public function test_admin_can_submit_request_edit_faktur(): void
    {
        $uniqueNo = 'TKB/REQ/' . uniqid();
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => $uniqueNo,
            'is_custom' => 0,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => 'Customer Req Test',
            'no_telepon' => '081234567890',
            'admin' => $this->admin->id,
            'tgl_selesai' => Carbon::now()->format('Y-m-d'),
            'jumlah_item' => 1,
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'order_by' => 'Toko',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
            'approval' => 'Lock',
        ]);

        $response = $this->actingAs($this->admin)->post(route('tokabe.request.edit_faktur', $invoice->id), [
            'catatan' => 'Mohon buka kunci untuk input Faktur Pajak PT Toko',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('invoice_approval_requests', [
            'invoice_id' => $invoice->id,
            'user_id' => $this->admin->id,
            'tipe_request' => 'edit_faktur_pajak',
            'status' => 'Pending',
            'catatan_admin' => 'Mohon buka kunci untuk input Faktur Pajak PT Toko',
        ]);

        InvoiceApprovalRequest::where('invoice_id', $invoice->id)->delete();
        $invoice->forceDelete();
    }

    /**
     * Requirement 3 (Verifikasi Owner):
     * Owner memberikan persetujuan (approval) untuk membuka kunci (unlock).
     */
    public function test_owner_can_unlock_invoice_and_approve_pending_request(): void
    {
        $uniqueNo = 'TKB/UNL/' . uniqid();
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => $uniqueNo,
            'is_custom' => 0,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => 'Customer Unlock Test',
            'no_telepon' => '081234567890',
            'admin' => $this->admin->id,
            'tgl_selesai' => Carbon::now()->format('Y-m-d'),
            'jumlah_item' => 1,
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'order_by' => 'Toko',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
            'approval' => 'Lock',
        ]);

        $req = InvoiceApprovalRequest::create([
            'invoice_id' => $invoice->id,
            'user_id' => $this->admin->id,
            'tipe_request' => 'edit_faktur_pajak',
            'status' => 'Pending',
            'catatan_admin' => 'Mohon izin buka faktur',
        ]);

        $response = $this->actingAs($this->pemilik)->post(route('tokabe.unlock', $invoice->id));
        $response->assertRedirect(route('list_invoice_tokabe'));

        $invoice->refresh();
        $req->refresh();

        $this->assertEquals('Unlock', $invoice->approval);
        $this->assertNotNull($invoice->approved_at);
        $this->assertEquals($this->pemilik->id, $invoice->approved_by);

        $this->assertEquals('Approved', $req->status);
        $this->assertEquals($this->pemilik->id, $req->reviewed_by);
        $this->assertNotNull($req->reviewed_at);

        $req->delete();
        $invoice->forceDelete();
    }

    /**
     * Requirement 3 (Pengisian Data & Penyimpanan Permanen):
     * Setelah izin diberikan (Unlock), Admin dapat menyimpan Nomor Faktur Pajak,
     * dan invoice otomatis kembali terkunci secara permanen dengan status visual Selesai.
     */
    public function test_filling_faktur_pajak_relocks_invoice_permanently(): void
    {
        $uniqueNo = 'TKB/FP/' . uniqid();
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => $uniqueNo,
            'is_custom' => 0,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => 'PT Pelanggan Pajak',
            'no_telepon' => '081234567890',
            'admin' => $this->admin->id,
            'tgl_selesai' => Carbon::now()->format('Y-m-d'),
            'jumlah_item' => 1,
            'total_harga' => 200000,
            'total_pembayaran' => 200000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'order_by' => 'Toko',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
            'npwp' => '01.234.567.8-901.000',
            'no_faktur_pajak' => null,
            'faktur_pajak_status' => 'Belum Diisi',
            'approval' => 'Unlock', // Telah disetujui Owner
        ]);

        $response = $this->actingAs($this->admin)->put(route('update-invoiceTokabe', $invoice->id), [
            'pelanggan' => 'PT Pelanggan Pajak',
            'tlp' => '081234567890',
            'adm' => $this->admin->id,
            'order' => 'Toko',
            'npwp' => '01.234.567.8-901.000',
            'no_faktur_pajak' => '010.000-26.00000001',
            'tot_pem' => '200.000',
            'sisa_pem' => '0',
            'item_id' => [],
        ]);

        $invoice->refresh();

        // 1. Nomor Faktur tersimpan
        $this->assertEquals('010.000-26.00000001', $invoice->no_faktur_pajak);
        // 2. Status faktur pajak menjadi Lengkap
        $this->assertEquals('Lengkap', $invoice->faktur_pajak_status);
        $this->assertTrue($invoice->isFakturPajakLengkap());
        // 3. Otomatis kembali terkunci secara permanen
        $this->assertEquals('Lock', $invoice->approval);
        $this->assertEquals('Selesai', $invoice->status_faktur_pajak);

        $invoice->forceDelete();
    }
}
