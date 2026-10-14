<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Item;
use App\Models\Barang;
use App\Models\User;
use App\Http\Controllers\PenjualanTokabeController;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PenjualanTokabeItemSelectionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_add_invoice_provides_selectable_items()
    {
        $admin = User::whereIn('role', ['Admin', 'Pemilik'])->first();
        $this->assertNotNull($admin, 'Admin or Pemilik user should exist');

        $this->actingAs($admin);

        $controller = app()->make(PenjualanTokabeController::class);
        $response = $controller->addInvoice();

        $data = $response->getData();
        $this->assertArrayHasKey('jenisBarang', $data);

        $jenisBarang = $data['jenisBarang'];
        $this->assertGreaterThan(0, $jenisBarang->count(), 'jenisBarang passed to view must not be empty');

        $firstItem = $jenisBarang->first();
        $this->assertInstanceOf(Item::class, $firstItem);
        $this->assertNotEmpty($firstItem->nama_item);
        $this->assertNotEmpty($firstItem->jenis_pajak);
    }

    public function test_item_model_exposes_jenis_barang_and_harga_jual()
    {
        $item = Item::first();
        $this->assertNotNull($item);

        $this->assertEquals($item->nama_item, $item->jenis_barang);
        $this->assertIsInt($item->harga_jual);
    }
}
