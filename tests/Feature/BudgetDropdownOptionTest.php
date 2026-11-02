<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\KategoriBarang;
use App\Models\Pembelian;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BudgetDropdownOptionTest extends TestCase
{
    use DatabaseTransactions;

    protected function getUser()
    {
        $user = User::where('role', 'Pemilik')->where('status', 'Aktif')->first();
        if (!$user) {
            $user = User::create([
                'nama'          => 'Pemilik Test ' . uniqid(),
                'email'         => 'pemilik_' . uniqid() . '@test.com',
                'password'      => bcrypt('password'),
                'status'        => 'Aktif',
                'nomor_telepon' => '081234567890',
                'role'          => 'Pemilik',
            ]);
        }
        return $user;
    }

    protected function getKategori()
    {
        return KategoriBarang::first() ?: KategoriBarang::create([
            'nama_kategori' => 'Kategori Anggaran ' . uniqid(),
        ]);
    }

    public function test_budget_has_nama_anggaran_accessor(): void
    {
        $budget = Budget::create([
            'nama_budget' => 'Kas Operasional Khusus ' . uniqid(),
            'kategori_id' => $this->getKategori()->id,
            'anggaran'    => 5000000,
            'tanggal'     => now()->toDateString(),
        ]);

        $this->assertEquals($budget->nama_budget, $budget->nama_anggaran);
    }

    public function test_create_pembelian_renders_budget_name_and_sisa_kas(): void
    {
        $user = $this->getUser();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Test Dropdown ' . uniqid(),
            'no_telpon'   => '08123456789',
            'alamat'      => 'Jl Test',
        ]);

        $uniqueName = 'Anggaran Belanja Cetak ' . uniqid();
        Budget::create([
            'nama_budget' => $uniqueName,
            'kategori_id' => $this->getKategori()->id,
            'anggaran'    => 7500000,
            'tanggal'     => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get(route('pembelian.create', $vendor->id));

        $response->assertStatus(200);
        $response->assertSee($uniqueName . ' &mdash; Sisa Kas: Rp 7.500.000', false);
    }

    public function test_show_vendor_modal_renders_budget_name_and_sisa_kas(): void
    {
        $user = $this->getUser();
        $vendor = Vendor::create([
            'nama_vendor' => 'Vendor Test Show ' . uniqid(),
            'no_telpon'   => '08123456789',
            'alamat'      => 'Jl Test',
        ]);

        $uniqueName = 'Kas Utama Project ' . uniqid();
        $budget = Budget::create([
            'nama_budget' => $uniqueName,
            'kategori_id' => $this->getKategori()->id,
            'anggaran'    => 12500000,
            'tanggal'     => now()->toDateString(),
        ]);

        $pembelian = Pembelian::create([
            'anggaran'     => $budget->id,
            'id_vendor'    => $vendor->id,
            'nomor_inv'    => 'INV-MODAL-' . rand(1000, 9999),
            'status'       => 'Cicil',
            'terbayar'     => 1000000,
            'sisa'         => 1500000,
            'jumlah_harga' => 2500000,
            'tanggal'      => now()->toDateString(),
            'tgl_jto'      => now()->addDays(7)->toDateString(),
            'token'        => (string) \Illuminate\Support\Str::uuid(),
        ]);

        $response = $this->actingAs($user)->get(route('pembelian.vendor.show', $vendor->id));

        $response->assertStatus(200);
        $response->assertSee($uniqueName . ' &mdash; Sisa Kas: Rp 12.500.000', false);
    }
}
