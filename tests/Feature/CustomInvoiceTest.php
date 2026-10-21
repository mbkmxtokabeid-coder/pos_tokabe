<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\PenjualanTokabe;

class CustomInvoiceTest extends TestCase
{
    public function test_admin_can_access_custom_invoice_page()
    {
        $admin = User::where('role', 'Admin')->first();
        $response = $this->actingAs($admin)->get('/addInvoiceTokabeCustom');

        $response->assertStatus(200);
        $response->assertSee('Tambah Invoice Keluar Custom');
        $response->assertSee('name="nomor_invoice_custom"', false);
        $response->assertSee('name="is_custom"', false);
    }

    public function test_owner_can_access_custom_invoice_page()
    {
        $owner = User::where('role', 'Pemilik')->first();
        $response = $this->actingAs($owner)->get('/addInvoiceTokabeCustom');

        $response->assertStatus(200);
        $response->assertSee('Tambah Invoice Keluar Custom');
    }

    public function test_can_create_invoice_with_custom_format_number()
    {
        $admin = User::where('role', 'Admin')->first();
        $item = Item::first();
        $customNumber = 'INV-CUSTOM/TOKABE/VIP-' . rand(1000, 9999);

        $payload = [
            'is_custom' => '1',
            'nomor_invoice_custom' => $customNumber,
            'pelanggan' => 'PT Klien Sukses Custom',
            'tlp' => '081234567899',
            'tgl_jual' => date('Y-m-d'),
            'jam' => '15:00',
            'tgl_selesai' => date('Y-m-d', strtotime('+2 days')),
            'jns_pem' => 'Cash Lunas',
            'inv' => 'Tokabe',
            'adm' => $admin->id,
            'order' => 'Langsung',
            'item_id' => [$item ? $item->id : 1],
            'satuan' => ['Pcs'],
            'hrg' => ['200000'],
            'qty' => [1],
            'jlh_hrg' => ['200000'],
            'tot_harga' => '200000',
            'tot_pem' => '200000',
            'sisa_pem' => '0',
            'dp' => '0',
        ];

        $response = $this->actingAs($admin)->postJson('/addInvoiceTokabeCustom', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('penjualan_tokabe', [
            'nomor_invoice' => $customNumber,
            'customer' => 'PT Klien Sukses Custom',
        ]);

        // Cleanup
        PenjualanTokabe::where('nomor_invoice', $customNumber)->forceDelete();
    }

    public function test_duplicate_custom_invoice_number_is_rejected()
    {
        $admin = User::where('role', 'Admin')->first();
        $existing = PenjualanTokabe::first();

        $payload = [
            'is_custom' => '1',
            'nomor_invoice_custom' => $existing->nomor_invoice,
            'pelanggan' => 'PT Klien Duplikat',
            'tlp' => '081234567899',
            'tgl_jual' => date('Y-m-d'),
            'jam' => '15:00',
            'tgl_selesai' => date('Y-m-d', strtotime('+2 days')),
            'jns_pem' => 'Cash Lunas',
            'inv' => 'Tokabe',
            'adm' => $admin->id,
            'order' => 'Langsung',
        ];

        $response = $this->actingAs($admin)->postJson('/addInvoiceTokabeCustom', $payload);

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'error',
        ]);
    }
}
