<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\Pembelian;
use App\Models\PembelianPembayaran;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PembelianCicilStatusTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_pembelian_with_status_cicil()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Test Cicil ' . uniqid(),
            'total_pembelian' => 0,
            'pembelian_sisa' => 0,
            'pembelian_terbayar' => 0,
        ]);
        $budget = Budget::first() ?: Budget::create([
            'nama_anggaran' => 'Budget Test ' . uniqid(),
            'anggaran' => 10000000,
            'tanggal' => now(),
        ]);

        $payload = [
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-CICIL-' . rand(1000, 9999),
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'tanggal_jto' => now()->addDays(7)->toDateString(),
            'anggaran' => $budget->id,
            'status' => 'Cicil',
            'terbayar' => '50.000',
            'items' => [
                [
                    'deskripsi' => 'Item Cicil 1',
                    'harga_barang' => '100.000',
                    'qty' => 1,
                    'satuan' => 'Pcs',
                ]
            ],
        ];

        $response = $this->actingAs($pemilik)->post(route('pembelian.store'), $payload);
        $response->assertRedirect(route('pembelian.vendor.show', $vendor->id));

        $pembelian = Pembelian::where('nomor_inv', $payload['nomor_inv'])->first();
        $this->assertNotNull($pembelian);
        $this->assertEquals('Cicil', $pembelian->status);
        $this->assertEquals(100000, $pembelian->jumlah_harga);
        $this->assertEquals(50000, $pembelian->terbayar);
        $this->assertEquals(50000, $pembelian->sisa);

        // Cicilan ke-1 otomatis tercatat di track record
        $pby1 = $pembelian->pembayaran()->where('cicilan_ke', 1)->first();
        $this->assertNotNull($pby1);
        $this->assertEquals(50000, $pby1->jumlah_bayar);
        $this->assertEquals('Approved', $pby1->status_approval);
    }

    public function test_can_update_pembelian_to_status_cicil()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Test Update ' . uniqid(),
            'total_pembelian' => 0,
            'pembelian_sisa' => 0,
            'pembelian_terbayar' => 0,
        ]);
        $budget = Budget::first() ?: Budget::create([
            'nama_anggaran' => 'Budget Test ' . uniqid(),
            'anggaran' => 10000000,
            'tanggal' => now(),
        ]);

        $pembelian = Pembelian::create([
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-UP-' . rand(1000, 9999),
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'tgl_jto' => now()->toDateString(),
            'anggaran' => $budget->id,
            'status' => 'Cicil',
            'jumlah_harga' => 200000,
            'terbayar' => 0,
            'sisa' => 200000,
            'token' => \Illuminate\Support\Str::uuid(),
            'approval' => 'Unlock',
        ]);

        $payload = [
            'id_vendor' => $vendor->id,
            'nomor_inv' => $pembelian->nomor_inv,
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'anggaran' => $budget->id,
            'status' => 'Cicil',
            'terbayar' => '75.000',
            'items' => [
                [
                    'deskripsi' => 'Barang Diupdate',
                    'harga_barang' => '200.000',
                    'qty' => 1,
                    'satuan' => 'Pcs',
                ]
            ],
        ];

        $response = $this->actingAs($pemilik)->put(route('pembelian.update', $pembelian->id), $payload);
        $response->assertRedirect(route('pembelian.vendor.show', $vendor->id));

        $pembelian->refresh();
        $this->assertEquals('Cicil', $pembelian->status);
        $this->assertEquals(75000, $pembelian->terbayar);
        $this->assertEquals(125000, $pembelian->sisa);
    }

    public function test_validation_rejects_invalid_status()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $vendor = Vendor::create(['nama_vendor' => 'Vendor Test ' . uniqid()]);
        $budget = Budget::first();

        $payload = [
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-INV-' . rand(1000, 9999),
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'anggaran' => $budget ? $budget->id : 1,
            'status' => 'Belum Lunas', // Belum Lunas is now removed and should be rejected
            'items' => [
                ['deskripsi' => 'Barang', 'harga_barang' => 10000, 'qty' => 1]
            ],
        ];

        $response = $this->actingAs($pemilik)->post(route('pembelian.store'), $payload);
        $response->assertSessionHasErrors(['status']);
    }

    public function test_show_vendor_page_renders_cicil_badge_and_filter()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor View ' . uniqid(),
            'total_pembelian' => 100000,
            'pembelian_sisa' => 50000,
            'pembelian_terbayar' => 50000,
        ]);
        $budget = Budget::first();

        $pembelian = Pembelian::create([
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-VIEW-' . rand(1000, 9999),
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'tgl_jto' => now()->toDateString(),
            'anggaran' => $budget ? $budget->id : 1,
            'status' => 'Cicil',
            'jumlah_harga' => 100000,
            'terbayar' => 50000,
            'sisa' => 50000,
            'token' => \Illuminate\Support\Str::uuid(),
            'approval' => 'Lock',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pembelian.vendor.show', $vendor->id));
        $response->assertStatus(200);
        $response->assertSee('badge-status-cicil');
        $response->assertSee('Cicil');
        $response->assertSee('filterStatusPembelian');
    }

    public function test_owner_can_pay_installments_up_to_max_5_limit()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Termin ' . uniqid(),
            'total_pembelian' => 50000,
            'pembelian_sisa' => 40000,
            'pembelian_terbayar' => 10000,
        ]);
        $budget = Budget::first() ?: Budget::create([
            'nama_anggaran' => 'Budget Test ' . uniqid(),
            'anggaran' => 10000000,
            'tanggal' => now(),
        ]);

        // Cicilan 1 sudah dicatat saat pembelian dibuat (DP 10.000)
        $pembelian = Pembelian::create([
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-5X-' . rand(1000, 9999),
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'tgl_jto' => now()->toDateString(),
            'anggaran' => $budget->id,
            'status' => 'Cicil',
            'jumlah_harga' => 50000,
            'terbayar' => 10000,
            'sisa' => 40000,
            'token' => \Illuminate\Support\Str::uuid(),
            'approval' => 'Lock',
        ]);

        PembelianPembayaran::create([
            'pembelian_id' => $pembelian->id,
            'cicilan_ke' => 1,
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => 10000,
            'anggaran_id' => $budget->id,
            'status_approval' => 'Approved',
            'user_id' => $pemilik->id,
        ]);

        // Cicilan ke-2 (Rp 10.000)
        $resp2 = $this->actingAs($pemilik)->post(route('pembelian.cicilan.store', $pembelian->id), [
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => '10.000',
            'anggaran_id' => $budget->id,
        ]);
        $resp2->assertSessionHas('success');
        $pembelian->refresh();
        $this->assertEquals(20000, $pembelian->terbayar);
        $this->assertEquals(30000, $pembelian->sisa);

        // Cicilan ke-3 (Rp 10.000)
        $this->actingAs($pemilik)->post(route('pembelian.cicilan.store', $pembelian->id), [
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => '10.000',
            'anggaran_id' => $budget->id,
        ]);

        // Cicilan ke-4 (Rp 10.000)
        $this->actingAs($pemilik)->post(route('pembelian.cicilan.store', $pembelian->id), [
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => '10.000',
            'anggaran_id' => $budget->id,
        ]);

        // Cicilan ke-5 bayar sebagian (Rp 5.000), sisa masih Rp 5.000
        $resp5 = $this->actingAs($pemilik)->post(route('pembelian.cicilan.store', $pembelian->id), [
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => '5.000',
            'anggaran_id' => $budget->id,
        ]);
        $resp5->assertSessionHas('success');
        $pembelian->refresh();
        $this->assertEquals(45000, $pembelian->terbayar);
        $this->assertEquals(5000, $pembelian->sisa);
        $this->assertEquals(5, $pembelian->approved_cicilan_count);

        // Percobaan cicilan ke-6 harus DITOLAK karena maks 5x tercapai
        $resp6 = $this->actingAs($pemilik)->post(route('pembelian.cicilan.store', $pembelian->id), [
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => '5.000',
            'anggaran_id' => $budget->id,
        ]);
        $resp6->assertSessionHas('error');
        $pembelian->refresh();
        $this->assertEquals(5, $pembelian->approved_cicilan_count);

        // Sisa diselesaikan oleh Pemilik via Pelunasan
        $this->actingAs($pemilik)->get(route('pembelian.lunas', $pembelian->id));
        $pembelian->refresh();
        $this->assertEquals('Lunas', $pembelian->status);
        $this->assertEquals(0, $pembelian->sisa);
        $this->assertEquals(50000, $pembelian->terbayar);
    }

    public function test_admin_submits_installment_pending_and_owner_approves()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $admin = User::where('role', 'Admin')->first() ?: $pemilik;
        $budget = Budget::first() ?: Budget::create([
            'nama_anggaran' => 'Budget Test ' . uniqid(),
            'anggaran' => 10000000,
            'tanggal' => now(),
        ]);
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Admin ' . uniqid(),
            'total_pembelian' => 100000,
            'pembelian_sisa' => 80000,
            'pembelian_terbayar' => 20000,
        ]);

        $pembelian = Pembelian::create([
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-ADM-' . rand(1000, 9999),
            'jenis_invoice' => 'Tanpa Pajak',
            'tanggal' => now()->toDateString(),
            'tgl_jto' => now()->toDateString(),
            'anggaran' => $budget->id,
            'status' => 'Cicil',
            'jumlah_harga' => 100000,
            'terbayar' => 20000,
            'sisa' => 80000,
            'token' => \Illuminate\Support\Str::uuid(),
            'approval' => 'Lock',
        ]);

        PembelianPembayaran::create([
            'pembelian_id' => $pembelian->id,
            'cicilan_ke' => 1,
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => 20000,
            'anggaran_id' => $budget->id,
            'status_approval' => 'Approved',
            'user_id' => $pemilik->id,
        ]);

        $initialBudgetAnggaran = (float) $budget->fresh()->anggaran;

        // Admin kirim pengajuan cicilan ke-2 (Rp 30.000)
        $respAdmin = $this->actingAs($admin)->post(route('pembelian.cicilan.store', $pembelian->id), [
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => '30.000',
            'anggaran_id' => $budget->id,
            'catatan' => 'Diajukan oleh Admin',
        ]);
        $respAdmin->assertSessionHas('success');

        // Belum disetujui, jadi sisa dan kas belum terpotong
        $pembelian->refresh();
        $this->assertEquals(80000, $pembelian->sisa);
        $this->assertEquals($initialBudgetAnggaran, (float) $budget->fresh()->anggaran);

        $pendingPayment = $pembelian->pembayaran()->where('status_approval', 'Pending')->first();
        $this->assertNotNull($pendingPayment);
        $this->assertEquals(30000, $pendingPayment->jumlah_bayar);

        // Pemilik menyetujui pengajuan cicilan
        $respApprove = $this->actingAs($pemilik)->post(route('pembelian.cicilan.approve', $pendingPayment->id));
        $respApprove->assertSessionHas('success');

        // Setelah disetujui, sisa berkurang dan kas terpotong
        $pembelian->refresh();
        $this->assertEquals(50000, $pembelian->terbayar);
        $this->assertEquals(50000, $pembelian->sisa);
        $this->assertEquals($initialBudgetAnggaran - 30000, (float) $budget->fresh()->anggaran);

        $pendingPayment->refresh();
        $this->assertEquals('Approved', $pendingPayment->status_approval);
        $this->assertEquals($pemilik->id, $pendingPayment->approved_by);
    }

    public function test_owner_can_view_approval_page_with_cicilan_details()
    {
        $pemilik = User::where('role', 'Pemilik')->first() ?: User::first();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Approval ' . uniqid(),
            'total_pembelian' => 0,
            'pembelian_sisa' => 0,
            'pembelian_terbayar' => 0,
        ]);
        $budget = Budget::first() ?: Budget::create([
            'nama_anggaran' => 'Budget Test ' . uniqid(),
            'anggaran' => 10000000,
            'tanggal' => now(),
        ]);

        $pembelian = Pembelian::create([
            'id_vendor' => $vendor->id,
            'nomor_inv' => 'INV-APP-' . rand(1000, 9999),
            'jenis_invoice' => 'Barang',
            'tanggal' => now()->toDateString(),
            'tgl_jto' => now()->addDays(7)->toDateString(),
            'anggaran' => $budget->id,
            'status' => 'Cicil',
            'jumlah_harga' => 350000,
            'terbayar' => 120000,
            'sisa' => 230000,
            'token' => \Illuminate\Support\Str::uuid(),
            'approval' => 'Lock',
        ]);

        PembelianPembayaran::create([
            'pembelian_id' => $pembelian->id,
            'cicilan_ke' => 1,
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => 120000,
            'anggaran_id' => $budget->id,
            'status_approval' => 'Approved',
            'user_id' => $pemilik->id,
        ]);

        $pendingPay = PembelianPembayaran::create([
            'pembelian_id' => $pembelian->id,
            'cicilan_ke' => 2,
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => 50000,
            'anggaran_id' => $budget->id,
            'status_approval' => 'Pending',
            'user_id' => $pemilik->id,
            'catatan' => 'Cicilan tahap dua',
        ]);

        $response = $this->actingAs($pemilik)->get(route('pembelian.approval.page', $pembelian->id));
        $response->assertStatus(200);
        $response->assertSee('Konfirmasi Persetujuan Edit Pembelian');
        $response->assertSee('Setujui & Buka Kunci', false);
        $response->assertSee($pembelian->nomor_inv);
        $response->assertSee('Riwayat Pembayaran Cicilan');
        $response->assertSee('Cicilan Ke-1');
        $response->assertSee('Pengajuan Cicilan Ke-2 Menunggu Persetujuan');
        $response->assertSee('Setujui Cicilan');
        $response->assertSee(route('pembelian.cicilan.approve', $pendingPay->id));
    }
}
