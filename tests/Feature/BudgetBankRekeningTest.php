<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\KategoriBarang;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BudgetBankRekeningTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::where('role', 'Pemilik')->first() ?: User::first();
        $this->kategori = KategoriBarang::first() ?: KategoriBarang::create([
            'nama_kategori' => 'Kategori Anggaran ' . uniqid(),
        ]);
    }

    public function test_can_view_tambah_anggaran_page_with_bank_and_rekening_fields(): void
    {
        $response = $this->actingAs($this->user)->get(route('budget.add'));
        $response->assertStatus(200);
        $response->assertSee('name="bank"', false);
        $response->assertSee('name="nomor_rekening"', false);
    }

    public function test_can_create_budget_with_bank_and_nomor_rekening(): void
    {
        $payload = [
            'nama_anggaran'  => 'Kas BCA Operasional ' . rand(100, 999),
            'kategori'       => $this->kategori->id,
            'anggaran'       => '5.000.000',
            'bank'           => 'BCA',
            'nomor_rekening' => '8880123456',
            'tanggal'        => now()->toDateString(),
        ];

        $response = $this->actingAs($this->user)->post(route('budget.store'), $payload);
        $response->assertRedirect(route('budget.list'));

        $this->assertDatabaseHas('budget', [
            'nama_budget'    => $payload['nama_anggaran'],
            'kategori_id'    => $this->kategori->id,
            'anggaran'       => 5000000,
            'bank'           => 'BCA',
            'nomor_rekening' => '8880123456',
        ]);
    }

    public function test_can_create_budget_without_bank_and_nomor_rekening(): void
    {
        $payload = [
            'nama_anggaran'  => 'Kas Tunai Kecil ' . rand(100, 999),
            'kategori'       => $this->kategori->id,
            'anggaran'       => '1.000.000',
            'bank'           => '',
            'nomor_rekening' => '',
            'tanggal'        => now()->toDateString(),
        ];

        $response = $this->actingAs($this->user)->post(route('budget.store'), $payload);
        $response->assertRedirect(route('budget.list'));

        $this->assertDatabaseHas('budget', [
            'nama_budget'    => $payload['nama_anggaran'],
            'bank'           => null,
            'nomor_rekening' => null,
        ]);
    }

    public function test_can_update_budget_with_bank_and_nomor_rekening(): void
    {
        $budget = Budget::create([
            'nama_budget'    => 'Kas Awal ' . uniqid(),
            'kategori_id'    => $this->kategori->id,
            'anggaran'       => 2000000,
            'bank'           => null,
            'nomor_rekening' => null,
            'tanggal'        => now()->toDateString(),
        ]);

        // Check edit view displays input fields
        $editResp = $this->actingAs($this->user)->get(route('budget.edit', $budget->id));
        $editResp->assertStatus(200);
        $editResp->assertSee('name="bank"', false);
        $editResp->assertSee('name="nomor_rekening"', false);

        $payload = [
            'nama_anggaran'  => 'Kas Diupdate ' . rand(100, 999),
            'kategori'       => $this->kategori->id,
            'anggaran'       => '3.500.000',
            'bank'           => 'Mandiri',
            'nomor_rekening' => '1420009998881',
            'tanggal'        => now()->toDateString(),
        ];

        $response = $this->actingAs($this->user)->put(route('budget.update', $budget->id), $payload);
        $response->assertRedirect(route('budget.list'));

        $budget->refresh();
        $this->assertEquals($payload['nama_anggaran'], $budget->nama_budget);
        $this->assertEquals(3500000, $budget->anggaran);
        $this->assertEquals('Mandiri', $budget->bank);
        $this->assertEquals('1420009998881', $budget->nomor_rekening);
    }

    public function test_budget_list_renders_bank_and_nomor_rekening(): void
    {
        $budget = Budget::create([
            'nama_budget'    => 'Anggaran BSI Khusus ' . uniqid(),
            'kategori_id'    => $this->kategori->id,
            'anggaran'       => 15000000,
            'bank'           => 'Bank Syariah Indonesia',
            'nomor_rekening' => '7123456789',
            'tanggal'        => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->user)->get(route('budget.list'));
        $response->assertStatus(200);
        $response->assertSee($budget->nama_budget);
        $response->assertSee('Bank Syariah Indonesia');
        $response->assertSee('7123456789');
    }
}

