<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class InvoiceValidationTest extends TestCase
{
    public function test_add_invoice_biasa_requires_all_fields()
    {
        $admin = User::where('role', 'Admin')->first();

        // Empty payload
        $response = $this->actingAs($admin)->postJson('/addInvoiceTokabe', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'pelanggan',
            'tlp',
            'adm',
            'order',
            'tgl_jual',
            'jam',
            'tgl_selesai',
            'jns_pem',
        ]);
    }

    public function test_add_invoice_biasa_validates_items_and_dp()
    {
        $admin = User::where('role', 'Admin')->first();

        // Payload with order=sales but missing sales, and jns_pem=Transfer DP but dp=0
        $payload = [
            'pelanggan' => 'Budi Santoso',
            'tlp' => '08123456789',
            'adm' => $admin->id,
            'order' => 'sales',
            'tgl_jual' => date('Y-m-d'),
            'jam' => '12:00',
            'tgl_selesai' => date('Y-m-d', strtotime('+1 day')),
            'jns_pem' => 'Transfer DP',
            'dp' => '0',
            'item_id' => [],
        ];

        $response = $this->actingAs($admin)->postJson('/addInvoiceTokabe', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['sales', 'item_id']);
    }

    public function test_add_invoice_custom_requires_custom_number_and_all_fields()
    {
        $admin = User::where('role', 'Admin')->first();

        $response = $this->actingAs($admin)->postJson('/addInvoiceTokabeCustom', [
            'is_custom' => '1',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'nomor_invoice_custom',
            'pelanggan',
            'tlp',
            'adm',
            'order',
            'tgl_jual',
            'jam',
            'tgl_selesai',
            'jns_pem',
        ]);
    }

    public function test_view_invoice_biasa_and_custom_contain_required_attributes()
    {
        $admin = User::where('role', 'Admin')->first();

        $resBiasa = $this->actingAs($admin)->get('/addInvoiceTokabe');
        $resBiasa->assertStatus(200);
        $resBiasa->assertSee('name="pelanggan" id="customer" placeholder="Nama Customer" value="" list="customerList" required', false);
        $resBiasa->assertSee('name="tlp" placeholder="08xxxxxxxx" value="" required', false);
        $resBiasa->assertSee('Nama Customer <span class="text-danger">*</span>', false);
        $resBiasa->assertSee('Nomor Telepon <span class="text-danger">*</span>', false);
        $resBiasa->assertSee('Nama Admin <span class="text-danger">*</span>', false);
        $resBiasa->assertSee('Order Dari <span class="text-danger">*</span>', false);
        $resBiasa->assertSee('Target Penyelesaian <span class="text-danger">*</span>', false);

        $resCustom = $this->actingAs($admin)->get('/addInvoiceTokabeCustom');
        $resCustom->assertStatus(200);
        $resCustom->assertSee('name="pelanggan" id="customer" placeholder="Nama Customer" value="" list="customerList" required', false);
        $resCustom->assertSee('name="tlp" placeholder="08xxxxxxxx" value="" required', false);
        $resCustom->assertSee('Nama Customer <span class="text-danger">*</span>', false);
        $resCustom->assertSee('Nomor Telepon <span class="text-danger">*</span>', false);
        $resCustom->assertSee('Nama Admin <span class="text-danger">*</span>', false);
        $resCustom->assertSee('Order Dari <span class="text-danger">*</span>', false);
        $resCustom->assertSee('Target Penyelesaian <span class="text-danger">*</span>', false);
    }
}
