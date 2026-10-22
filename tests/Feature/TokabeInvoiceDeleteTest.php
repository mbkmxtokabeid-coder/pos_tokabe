<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\PenjualanJasaTokabe;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class TokabeInvoiceDeleteTest extends TestCase
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
                'email' => 'pemilik_delete_test@example.com',
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
                'email' => 'admin_delete_test@example.com',
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'status' => 'Aktif',
                'nomor_telepon' => '08123456788',
            ]);
        }
    }

    /**
     * Test admin tidak boleh mengakses halaman konfirmasi hapus invoice (/tokabe-delete/{id})
     */
    public function test_admin_cannot_access_delete_confirmation_page(): void
    {
        $inv = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'TEST/DEL/001',
            'is_custom' => 0,
            'tgl_penjualan' => now(),
            'customer' => 'Customer Test Del',
            'no_telepon' => '08111111111',
            'admin' => $this->admin->id,
            'tgl_selesai' => now(),
            'jumlah_item' => 1,
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
        ]);

        $response = $this->actingAs($this->admin)->get(route('tokabe.delete.page', $inv->id));

        // RoleMiddleware redirect jika bukan Pemilik
        $response->assertRedirect('/welcomeTKB');

        // Cleanup
        $inv->forceDelete();
    }

    /**
     * Test admin tidak boleh mengeksekusi DELETE /deleteInvoiceTokabe/{id}
     */
    public function test_admin_cannot_execute_delete_action(): void
    {
        $inv = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'TEST/DEL/002',
            'is_custom' => 0,
            'tgl_penjualan' => now(),
            'customer' => 'Customer Test Del 2',
            'no_telepon' => '08111111111',
            'admin' => $this->admin->id,
            'tgl_selesai' => now(),
            'jumlah_item' => 1,
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('delete.invoiceTokabe', $inv->id));

        $response->assertRedirect('/welcomeTKB');

        // Pastikan invoice belum terhapus
        $this->assertDatabaseHas('penjualan_tokabe', [
            'id' => $inv->id,
            'deleted_at' => null,
        ]);

        $inv->forceDelete();
    }

    /**
     * Test pemilik (Owner) bisa mengakses halaman konfirmasi hapus invoice
     */
    public function test_owner_can_access_delete_confirmation_page(): void
    {
        $inv = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'TEST/DEL/003',
            'is_custom' => 0,
            'tgl_penjualan' => now(),
            'customer' => 'Customer Test Del 3',
            'no_telepon' => '08111111111',
            'admin' => $this->pemilik->id,
            'tgl_selesai' => now(),
            'jumlah_item' => 1,
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
        ]);

        $response = $this->actingAs($this->pemilik)->get(route('tokabe.delete.page', $inv->id));

        $response->assertStatus(200);
        $response->assertViewIs('pages.invoices.tokabe.delete');
        $response->assertSee('TEST/DEL/003');

        $inv->forceDelete();
    }

    /**
     * Test pemilik (Owner) dapat menghapus invoice biasa dan stok barang kembali
     */
    public function test_owner_can_delete_regular_invoice(): void
    {
        $barang = Barang::where('is_active', 1)->first() ?? Barang::first();
        $initialStock = $barang ? $barang->stok : 0;

        $inv = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'TEST/DEL/REGULAR',
            'is_custom' => 0,
            'tgl_penjualan' => now(),
            'customer' => 'Customer Regular Test',
            'no_telepon' => '08111111111',
            'admin' => $this->pemilik->id,
            'tgl_selesai' => now(),
            'jumlah_item' => 2,
            'total_harga' => 200000,
            'total_pembayaran' => 200000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
        ]);

        if ($barang) {
            PenjualanJasaTokabe::create([
                'barang_id' => $barang->id,
                'penjualan_id' => $inv->id,
                'deskripsi_item' => 'Item test regular',
                'qty' => 2,
                'satuan' => 'Pcs',
                'harga' => 100000,
                'jumlah_harga' => 200000,
            ]);
        }

        $response = $this->actingAs($this->pemilik)->delete(route('delete.invoiceTokabe', $inv->id));

        $response->assertRedirect(route('list_invoice_tokabe'));

        // Cek soft delete pada invoice
        $this->assertSoftDeleted('penjualan_tokabe', ['id' => $inv->id]);

        // Cek line items di-soft delete
        $this->assertSoftDeleted('penjualan_jasa_tokabe', ['penjualan_id' => $inv->id]);

        // Cek stok dipulihkan
        if ($barang) {
            $barang->refresh();
            $this->assertEquals($initialStock + 2, $barang->stok);
            // Kembalikan stok
            $barang->stok = $initialStock;
            $barang->save();
        }

        PenjualanJasaTokabe::withTrashed()->where('penjualan_id', $inv->id)->forceDelete();
        $inv->forceDelete();
    }

    /**
     * Test pemilik (Owner) dapat menghapus invoice custom dan redirect ke list custom
     */
    public function test_owner_can_delete_custom_invoice(): void
    {
        $inv = PenjualanTokabe::create([
            'invoice' => 5,
            'nomor_invoice' => 'CUSTOM-INV-9999',
            'is_custom' => 1,
            'tgl_penjualan' => now(),
            'customer' => 'Customer Custom Test',
            'no_telepon' => '08111111111',
            'admin' => $this->pemilik->id,
            'tgl_selesai' => now(),
            'jumlah_item' => 1,
            'total_harga' => 500000,
            'total_pembayaran' => 500000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'TKB',
        ]);

        $response = $this->actingAs($this->pemilik)->delete(route('delete.invoiceTokabe', $inv->id));

        $response->assertRedirect(route('list_invoice_tokabe_custom'));

        // Cek soft delete pada invoice
        $this->assertSoftDeleted('penjualan_tokabe', ['id' => $inv->id]);

        $inv->forceDelete();
    }
}
