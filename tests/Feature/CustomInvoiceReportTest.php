<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\LaporanCustomController;

class CustomInvoiceReportTest extends TestCase
{
    protected function getAuthenticatedUser($role = 'Pemilik')
    {
        return User::where('role', $role)->first() ?: User::first();
    }

    public function test_owner_and_admin_can_access_custom_invoice_report_page()
    {
        $pemilik = $this->getAuthenticatedUser('Pemilik');
        $response = $this->actingAs($pemilik)->get('/daftar-laporan/custom');
        $response->assertStatus(200);
        $response->assertSee('Laporan Invoice Keluar Custom');
        $response->assertSee('Rekap');
        $response->assertSee('Ekspor Custom');

        $admin = $this->getAuthenticatedUser('Admin');
        if ($admin) {
            $responseAdmin = $this->actingAs($admin)->get('/daftar-laporan/custom');
            $responseAdmin->assertStatus(200);
            $responseAdmin->assertSee('Laporan Invoice Keluar Custom');
        }
    }

    public function test_sidebar_contains_link_to_custom_invoice_report()
    {
        $user = $this->getAuthenticatedUser('Pemilik');
        $response = $this->actingAs($user)->get('/daftar-laporan');
        $response->assertStatus(200);
        $response->assertSee(route('laporan.custom'));
        $response->assertSee('Laporan Invoice Custom');
    }

    public function test_custom_report_controller_only_includes_custom_invoices()
    {
        $user = $this->getAuthenticatedUser('Pemilik');
        $this->actingAs($user);

        $timestamp = time();
        $invoiceModel = Invoice::where('perusahaan_id', 2)->first() ?: Invoice::first();
        $invoiceId = $invoiceModel ? $invoiceModel->id : 5;

        // Regular invoice
        $regInv = PenjualanTokabe::create([
            'invoice' => $invoiceId,
            'nomor_invoice' => "REG-REP-{$timestamp}",
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => "Regular Customer {$timestamp}",
            'perusahaan' => "Regular PT {$timestamp}",
            'no_telepon' => "0812999911",
            'admin' => $user->id,
            'order_by' => 'Langsung',
            'nama_sales' => '-',
            'tgl_selesai' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jumlah_item' => 1,
            'dp' => 0,
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'Cash',
            'total_harga' => 150000,
            'total_pembayaran' => 150000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'approval' => 'Lock',
            'is_custom' => 0,
        ]);

        // Custom invoice
        $custInv = PenjualanTokabe::create([
            'invoice' => $invoiceId,
            'nomor_invoice' => "CUST-REP-{$timestamp}",
            'tgl_penjualan' => Carbon::now()->format('Y-m-d H:i:s'),
            'customer' => "Custom Customer {$timestamp}",
            'perusahaan' => "Custom PT {$timestamp}",
            'no_telepon' => "0812999922",
            'admin' => $user->id,
            'order_by' => 'Langsung',
            'nama_sales' => '-',
            'tgl_selesai' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'jumlah_item' => 1,
            'dp' => 0,
            'jenis_pembayaran' => 'Cash Lunas',
            'no_rek' => 'Cash',
            'total_harga' => 275000,
            'total_pembayaran' => 275000,
            'sisa_pembayaran' => 0,
            'status' => 'Lunas',
            'approval' => 'Lock',
            'is_custom' => 1,
        ]);

        try {
            $controller = new LaporanCustomController();
            $request = new Request([
                'invoice' => 'semua',
                'status' => 'semua',
                'export_type' => 'pdf',
            ]);

            $response = $controller->exportByInvoice($request);
            $penjualans = $response->getData()['penjualans'];

            // Custom report MUST contain custom invoice and MUST NOT contain regular invoice
            $this->assertTrue($penjualans->contains('nomor_invoice', "CUST-REP-{$timestamp}"));
            $this->assertFalse($penjualans->contains('nomor_invoice', "REG-REP-{$timestamp}"));
        } finally {
            $regInv->forceDelete();
            $custInv->forceDelete();
        }
    }
}
