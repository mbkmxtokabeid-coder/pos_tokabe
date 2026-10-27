<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\Barang;
use App\Models\Item;

class TokabeInvoiceEnhancementsTest extends TestCase
{
    use DatabaseTransactions;

    protected $pemilik;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pemilik = User::where('role', 'Pemilik')->first();
        $this->admin = User::where('role', 'Admin')->first();
    }

    public function test_unlock_invoice_option_is_visible_for_owner_when_locked()
    {
        $invoice = PenjualanTokabe::where('approval', 'Lock')->first();
        if (!$invoice) {
            $invoice = PenjualanTokabe::create([
                'invoice' => 5,
                'nomor_invoice' => 'TEST/INV/' . uniqid(),
                'tgl_penjualan' => date('Y-m-d H:i:s'),
                'customer' => 'Customer Test',
                'no_telepon' => '08123456789',
                'admin' => $this->pemilik->id,
                'order_by' => 'Langsung',
                'nama_sales' => '-',
                'tgl_selesai' => date('Y-m-d'),
                'jumlah_item' => 1,
                'jenis_pembayaran' => 'Cash Lunas',
                'total_harga' => 100000,
                'total_pembayaran' => 100000,
                'sisa_pembayaran' => 0,
                'status' => 'Lunas',
                'approval' => 'Lock',
            ]);
        }

        $res = $this->actingAs($this->pemilik)->get(route('list_invoice_tokabe'));
        $res->assertStatus(200);
        $res->assertSee('Unlock Invoice', false);
        $res->assertSee(route('tokabe.unlock', $invoice->id), false);
    }

    public function test_owner_can_unlock_invoice_via_get_or_post()
    {
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'TEST/INV/' . uniqid(),
            'tgl_penjualan' => date('Y-m-d H:i:s'),
            'customer' => 'Customer Test Lock',
            'no_telepon' => '08123456789',
            'admin' => $this->pemilik->id,
            'order_by' => 'Langsung',
            'nama_sales' => '-',
            'tgl_selesai' => date('Y-m-d'),
            'no_rek' => 'TKB',
            'jumlah_item' => 1,
            'jenis_pembayaran' => 'Cash Lunas',
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'approval' => 'Lock',
        ]);

        $this->assertEquals('Lock', $invoice->approval);

        // Test GET unlock
        $res = $this->actingAs($this->pemilik)->get(route('tokabe.unlock', $invoice->id));
        $res->assertRedirect();

        $invoice->refresh();
        $this->assertEquals('Unlock', $invoice->approval);
    }

    public function test_invoice_views_contain_editable_time_input_and_dp_helper()
    {
        // Add invoice biasa
        $resBiasa = $this->actingAs($this->admin)->get('/addInvoiceTokabe');
        $resBiasa->assertStatus(200);
        $resBiasa->assertSee('id="time-field"', false);
        $resBiasa->assertDontSee('placeholder="H:i" name="jam" value="" readonly="readonly"', false);
        $resBiasa->assertSee('Default 70%, dapat diedit manual jika negosiasi', false);

        // Add invoice custom
        $resCustom = $this->actingAs($this->admin)->get('/addInvoiceTokabeCustom');
        $resCustom->assertStatus(200);
        $resCustom->assertSee('id="time-field"', false);
        $resCustom->assertSee('Default 70%, dapat diedit manual jika negosiasi', false);

        // Edit invoice
        $invoice = PenjualanTokabe::first();
        if ($invoice) {
            $resEdit = $this->actingAs($this->pemilik)->get(route('edit.inv.tkb', $invoice->id));
            $resEdit->assertStatus(200);
            $resEdit->assertSee('id="time-field"', false);
            $resEdit->assertSee('Default 70%, dapat diedit manual jika negosiasi', false);
        }
    }

    public function test_store_invoice_saves_custom_time()
    {
        $barang = Barang::where('is_active', 1)->first();
        $customTime = '17:42';

        $payload = [
            'inv' => '5',
            'pelanggan' => 'Customer Jam Test',
            'perusahaan' => 'PT Test Jam',
            'tlp' => '081234567899',
            'adm' => $this->admin->id,
            'order' => 'Langsung',
            'sales' => '-',
            'tgl_jual' => '2026-10-24',
            'jam' => $customTime,
            'tgl_selesai' => '2026-10-25',
            'jns_pem' => 'Cash Lunas',
            'norek' => 'TKB',
            'tot_harga' => '500.000',
            'tot_pem' => '500.000',
            'sisa_pem' => '0',
            'barang_id' => [$barang ? $barang->id : 1],
            'qty' => [1],
            'satuan' => ['Pcs'],
            'hrg' => ['500.000'],
            'jlh_hrg' => ['500.000'],
            'deskripsi_item' => ['Barang Test'],
        ];

        $response = $this->actingAs($this->admin)->postJson('/addInvoiceTokabe', $payload);
        $this->assertTrue(in_array($response->status(), [200, 302]));

        $saved = PenjualanTokabe::where('customer', 'Customer Jam Test')->latest('id')->first();
        $this->assertNotNull($saved);
        $this->assertStringContainsString('2026-10-24 17:42:00', $saved->tgl_penjualan);
    }

    public function test_transfer_dp_saves_custom_negotiated_nominal()
    {
        $barang = Barang::where('is_active', 1)->first();
        $totalHarga = 1000000;
        // 70% would be 700.000, but negotiated DP is 500.000
        $negotiatedDp = 500000;

        $payload = [
            'inv' => '5',
            'pelanggan' => 'Customer DP Nego',
            'perusahaan' => 'PT Test DP',
            'tlp' => '081234567888',
            'adm' => $this->admin->id,
            'order' => 'Langsung',
            'sales' => '-',
            'tgl_jual' => '2026-10-24',
            'jam' => '14:00',
            'tgl_selesai' => '2026-10-26',
            'jns_pem' => 'Transfer DP',
            'dp' => number_format($negotiatedDp, 0, ',', '.'),
            'norek' => 'TKB',
            'tot_harga' => number_format($totalHarga, 0, ',', '.'),
            'tot_pem' => number_format($totalHarga, 0, ',', '.'),
            'sisa_pem' => number_format($totalHarga - $negotiatedDp, 0, ',', '.'),
            'barang_id' => [$barang ? $barang->id : 1],
            'qty' => [1],
            'satuan' => ['Pcs'],
            'hrg' => [number_format($totalHarga, 0, ',', '.')],
            'jlh_hrg' => [number_format($totalHarga, 0, ',', '.')],
            'deskripsi_item' => ['Barang DP Nego'],
        ];

        $response = $this->actingAs($this->admin)->postJson('/addInvoiceTokabe', $payload);
        $this->assertTrue(in_array($response->status(), [200, 302]));

        $saved = PenjualanTokabe::where('customer', 'Customer DP Nego')->latest('id')->first();
        $this->assertNotNull($saved);
        $this->assertEquals('Transfer DP', $saved->jenis_pembayaran);
        $this->assertEquals(500000, $saved->dp);
        $this->assertEquals(500000, $saved->sisa_pembayaran);
    }

    public function test_invoice_views_contain_integer_only_cleave_config_for_dp()
    {
        $resBiasa = $this->actingAs($this->admin)->get('/addInvoiceTokabe');
        $resBiasa->assertStatus(200);
        $resBiasa->assertSee('"numeralDecimalScale": 0', false);

        $resCustom = $this->actingAs($this->admin)->get('/addInvoiceTokabeCustom');
        $resCustom->assertStatus(200);
        $resCustom->assertSee('"numeralDecimalScale": 0', false);

        $invoice = PenjualanTokabe::first();
        if ($invoice) {
            $resEdit = $this->actingAs($this->pemilik)->get(route('edit.inv.tkb', $invoice->id));
            $resEdit->assertStatus(200);
            $resEdit->assertSee('"numeralDecimalScale": 0', false);
        }
    }

    public function test_update_invoice_handles_comma_and_dot_formatted_dp()
    {
        $barang = Barang::where('is_active', 1)->first();
        $invoice = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'TEST/INV/' . uniqid(),
            'tgl_penjualan' => date('Y-m-d H:i:s'),
            'customer' => 'Customer DP Update',
            'no_telepon' => '08123456789',
            'admin' => $this->pemilik->id,
            'order_by' => 'Langsung',
            'nama_sales' => '-',
            'tgl_selesai' => date('Y-m-d'),
            'no_rek' => 'TKB',
            'jumlah_item' => 1,
            'jenis_pembayaran' => 'Cash Lunas',
            'total_harga' => 10000,
            'total_pembayaran' => 10000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'approval' => 'Unlock',
        ]);

        $updatePayload = [
            'inv' => '5',
            'pelanggan' => 'Customer DP Update',
            'perusahaan' => 'PT Test Update',
            'tlp' => '08123456789',
            'adm' => $this->pemilik->id,
            'order' => 'Langsung',
            'sales' => '-',
            'tgl_jual' => '2026-10-24',
            'jam' => '13:00',
            'tgl_selesai' => '2026-10-25',
            'jns_pem' => 'Transfer DP',
            'dp' => '7,000',
            'norek' => 'TKB',
            'tot_harga' => '10,000',
            'tot_pem' => '10,000',
            'sisa_pem' => '3,000',
            'barang_id' => [$barang ? $barang->id : 1],
            'qty' => [1],
            'satuan' => ['Pcs'],
            'hrg' => ['10,000'],
            'jlh_hrg' => ['10,000'],
            'deskripsi_item' => ['Barang Update'],
        ];

        $res = $this->actingAs($this->pemilik)->putJson(route('update-invoiceTokabe', $invoice->id), $updatePayload);
        $this->assertTrue(in_array($res->status(), [200, 302]));

        $invoice->refresh();
        $this->assertEquals(10000, $invoice->total_harga);
        $this->assertEquals(7000, $invoice->dp);
        $this->assertEquals(3000, $invoice->sisa_pembayaran);
    }
}
