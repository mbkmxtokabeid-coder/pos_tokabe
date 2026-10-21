<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomInvoiceSeparationTest extends TestCase
{
    protected function getAuthenticatedUser($role = 'Pemilik')
    {
        return User::where('role', $role)->first() ?: User::first();
    }

    public function test_custom_invoice_routes_accessible_and_render_correct_views()
    {
        $user = $this->getAuthenticatedUser('Pemilik');

        $responseRegular = $this->actingAs($user)->get('/list-invoice/tokabe');
        $responseRegular->assertStatus(200);
        $responseRegular->assertViewIs('pages.invoices.tokabe.daftar-invoiceTokabe');

        $responseCustom = $this->actingAs($user)->get('/list-invoice/custom');
        $responseCustom->assertStatus(200);
        $responseCustom->assertViewIs('pages.invoices.tokabe.daftar-invoiceTokabeCustom');
        $responseCustom->assertSee('Invoice Keluar Custom');
        $responseCustom->assertSee('Tambah Invoice Custom');
    }

    public function test_custom_invoice_cards_data_endpoint_returns_valid_json()
    {
        $user = $this->getAuthenticatedUser('Pemilik');

        $response = $this->actingAs($user)->get('/list-invoice/custom/cards-data?timeframe=year');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'periodLabel',
            'card1' => ['nominal', 'count'],
            'card2' => ['nominal', 'count'],
            'card3' => ['nominal', 'count'],
            'card4' => ['nominal', 'count'],
            'cardHapus' => ['nominal', 'count'],
        ]);
    }

    public function test_regular_and_custom_invoices_are_strictly_separated()
    {
        $user = $this->getAuthenticatedUser('Pemilik');
        $timestamp = time();

        $invoiceModel = Invoice::where('perusahaan_id', 2)->first() ?: Invoice::first();
        $invoiceId = $invoiceModel ? $invoiceModel->id : 5;

        // 1. Create a regular invoice
        $regInvNumber = "TEST-REG-{$timestamp}";
        $regularInv = PenjualanTokabe::create([
            'invoice' => $invoiceId,
            'nomor_invoice' => $regInvNumber,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => "Customer Regular {$timestamp}",
            'perusahaan' => "PT Regular {$timestamp}",
            'no_telepon' => "0812999901",
            'admin' => $user->id,
            'order_by' => 'Langsung',
            'nama_sales' => '-',
            'tgl_selesai' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jumlah_item' => 1,
            'dp' => 0,
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'Cash',
            'total_harga' => 100000,
            'total_pembayaran' => 100000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'approval' => 'Lock',
            'is_custom' => 0,
        ]);

        // 2. Create a custom invoice
        $custInvNumber = "CUST-SPECIAL-{$timestamp}";
        $customInv = PenjualanTokabe::create([
            'invoice' => $invoiceId,
            'nomor_invoice' => $custInvNumber,
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => "Customer Custom {$timestamp}",
            'perusahaan' => "PT Custom {$timestamp}",
            'no_telepon' => "0812999902",
            'admin' => $user->id,
            'order_by' => 'Langsung',
            'nama_sales' => '-',
            'tgl_selesai' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jumlah_item' => 1,
            'dp' => 0,
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'Cash',
            'total_harga' => 250000,
            'total_pembayaran' => 250000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'approval' => 'Lock',
            'is_custom' => 1,
        ]);

        try {
            // Check regular list
            $responseRegular = $this->actingAs($user)->get('/list-invoice/tokabe');
            $responseRegular->assertStatus(200);
            $responseRegular->assertSee($regInvNumber);
            $responseRegular->assertDontSee($custInvNumber);

            // Check custom list
            $responseCustom = $this->actingAs($user)->get('/list-invoice/custom');
            $responseCustom->assertStatus(200);
            $responseCustom->assertSee($custInvNumber);
            $responseCustom->assertDontSee($regInvNumber);

        } finally {
            // Clean up test invoices
            $regularInv->forceDelete();
            $customInv->forceDelete();
        }
    }

    public function test_sidebar_menu_links_to_custom_invoice_list()
    {
        $user = $this->getAuthenticatedUser('Pemilik');

        $response = $this->actingAs($user)->get('/list-invoice/custom');
        $response->assertStatus(200);

        // Sidebar must link to route('list_invoice_tokabe_custom') -> /list-invoice/custom
        $response->assertSee(route('list_invoice_tokabe_custom'));
        $response->assertSee('Invoice Keluar Custom');
    }
}
