<?php

namespace Tests\Feature;

use App\Models\BebanBelanja;
use App\Models\Budget;
use App\Models\KategoriBeban;
use App\Models\Rekening;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class BebanBelanjaWorkflowTest extends TestCase
{
    protected $admin;
    protected $kategori;
    protected $rekening;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::where('role', 'Admin')->first();
        if (!$this->admin) {
            $this->admin = User::create([
                'nama' => 'Admin Test Beban',
                'email' => 'admin_beban_test@example.com',
                'password' => bcrypt('password'),
                'role' => 'Admin',
                'status' => 'Aktif',
                'nomor_telepon' => '08123456788',
            ]);
        }

        $this->kategori = KategoriBeban::first();
        if (!$this->kategori) {
            $this->kategori = KategoriBeban::create([
                'nama_kategori' => 'Operasional & Utilitas',
                'deskripsi' => 'Testing kategori',
            ]);
        }

        $this->rekening = Rekening::first();
    }

    public function test_can_view_beban_belanja_index_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('beban-belanja.index'));
        $response->assertStatus(200);
        $response->assertSee('Beban Belanja');
        $response->assertSee('Daftar Pengeluaran Beban');
    }

    public function test_can_create_new_beban_belanja_with_budget_and_deducts_balance(): void
    {
        $kategoriBarang = \App\Models\KategoriBarang::first() ?? \App\Models\KategoriBarang::create(['nama_kategori' => 'Kategori Test']);
        $budget = Budget::create([
            'nama_budget' => 'Anggaran Uji Coba ' . uniqid(),
            'kategori_id' => $kategoriBarang->id,
            'anggaran' => 1000000,
            'tanggal' => Carbon::now()->toDateString(),
        ]);

        $uniqueNote = 'Beli Lakban & Plastik Packing ' . uniqid();
        $response = $this->actingAs($this->admin)->post(route('beban-belanja.store'), [
            'tanggal' => Carbon::now()->toDateString(),
            'kategori_id' => $this->kategori->id,
            'nama_beban' => $uniqueNote,
            'nominal' => '250.000',
            'anggaran_id' => $budget->id,
            'penerima' => 'Toko Mitra Sejati',
            'keterangan' => 'Keperluan packing pesanan invoice',
        ]);

        $response->assertRedirect(route('beban-belanja.index'));

        $this->assertDatabaseHas('beban_belanja', [
            'nama_beban' => $uniqueNote,
            'nominal' => 250000,
            'anggaran_id' => $budget->id,
            'penerima' => 'Toko Mitra Sejati',
        ]);

        // Verifikasi saldo anggaran berkurang: 1.000.000 - 250.000 = 750.000
        $budget->refresh();
        $this->assertEquals(750000, $budget->anggaran);
    }

    public function test_can_create_new_beban_belanja_without_budget(): void
    {
        $uniqueNote = 'Beli Kopi & Snack Tamu ' . uniqid();
        $response = $this->actingAs($this->admin)->post(route('beban-belanja.store'), [
            'tanggal' => Carbon::now()->toDateString(),
            'kategori_id' => $this->kategori->id,
            'nama_beban' => $uniqueNote,
            'nominal' => '50.000',
            'anggaran_id' => '',
            'penerima' => 'Minimarket',
            'keterangan' => 'Pengeluaran umum toko',
        ]);

        $response->assertRedirect(route('beban-belanja.index'));

        $this->assertDatabaseHas('beban_belanja', [
            'nama_beban' => $uniqueNote,
            'nominal' => 50000,
            'anggaran_id' => null,
        ]);
    }

    public function test_can_update_beban_belanja_and_adjusts_budget_balance(): void
    {
        $kategoriBarang = \App\Models\KategoriBarang::first() ?? \App\Models\KategoriBarang::create(['nama_kategori' => 'Kategori Test']);
        $budget = Budget::create([
            'nama_budget' => 'Anggaran Update ' . uniqid(),
            'kategori_id' => $kategoriBarang->id,
            'anggaran' => 1000000,
            'tanggal' => Carbon::now()->toDateString(),
        ]);

        // Simpan data awal dengan anggaran
        $beban = BebanBelanja::create([
            'nomor_beban' => BebanBelanja::generateNomorBeban(),
            'tanggal' => Carbon::now()->toDateString(),
            'kategori_id' => $this->kategori->id,
            'nama_beban' => 'Service Printer UV Lama',
            'nominal' => 200000,
            'anggaran_id' => $budget->id,
            'admin_id' => $this->admin->id,
        ]);
        $budget->anggaran -= 200000;
        $budget->save();

        // Update nominal beban menjadi 350.000 (bertambah 150.000, sisa saldo budget harus jadi 650.000)
        $response = $this->actingAs($this->admin)->put(route('beban-belanja.update', $beban->id), [
            'tanggal' => Carbon::now()->toDateString(),
            'kategori_id' => $this->kategori->id,
            'nama_beban' => 'Service Printer UV & Ganti Tinta',
            'nominal' => '350.000',
            'anggaran_id' => $budget->id,
            'penerima' => 'Teknisi Handal',
            'keterangan' => 'Sudah selesai diservis',
        ]);

        $response->assertRedirect(route('beban-belanja.index'));

        $this->assertDatabaseHas('beban_belanja', [
            'id' => $beban->id,
            'nama_beban' => 'Service Printer UV & Ganti Tinta',
            'nominal' => 350000,
            'anggaran_id' => $budget->id,
            'penerima' => 'Teknisi Handal',
        ]);

        $budget->refresh();
        $this->assertEquals(650000, $budget->anggaran);
    }

    public function test_can_delete_beban_belanja_and_restores_budget_balance(): void
    {
        $kategoriBarang = \App\Models\KategoriBarang::first() ?? \App\Models\KategoriBarang::create(['nama_kategori' => 'Kategori Test']);
        $budget = Budget::create([
            'nama_budget' => 'Anggaran Hapus ' . uniqid(),
            'kategori_id' => $kategoriBarang->id,
            'anggaran' => 500000,
            'tanggal' => Carbon::now()->toDateString(),
        ]);

        $beban = BebanBelanja::create([
            'nomor_beban' => BebanBelanja::generateNomorBeban(),
            'tanggal' => Carbon::now()->toDateString(),
            'kategori_id' => $this->kategori->id,
            'nama_beban' => 'Pengeluaran Dibatalkan ' . uniqid(),
            'nominal' => 150000,
            'anggaran_id' => $budget->id,
            'admin_id' => $this->admin->id,
        ]);
        $budget->anggaran -= 150000;
        $budget->save();

        $response = $this->actingAs($this->admin)->delete(route('beban-belanja.destroy', $beban->id));
        $response->assertRedirect(route('beban-belanja.index'));

        $this->assertSoftDeleted('beban_belanja', [
            'id' => $beban->id,
        ]);

        // Verifikasi saldo anggaran dikembalikan: 350.000 + 150.000 = 500.000
        $budget->refresh();
        $this->assertEquals(500000, $budget->anggaran);
    }

    public function test_can_export_beban_belanja_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('beban-belanja.export-pdf'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'application/pdf'));

        // Alias route juga berjalan
        $aliasResponse = $this->actingAs($this->admin)->get(route('beban-belanja.export'));
        $aliasResponse->assertStatus(200);
        $this->assertTrue(str_contains($aliasResponse->headers->get('content-type'), 'application/pdf'));
    }

    public function test_can_create_kategori_beban(): void
    {
        $uniqueName = 'Kategori Beban Baru ' . uniqid();
        $response = $this->actingAs($this->admin)->post(route('kategori-beban.store'), [
            'nama_kategori' => $uniqueName,
            'deskripsi' => 'Deskripsi untuk kategori baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('open_modal_kategori', true);

        $this->assertDatabaseHas('kategori_beban', [
            'nama_kategori' => $uniqueName,
            'deskripsi' => 'Deskripsi untuk kategori baru',
        ]);
    }

    public function test_can_update_kategori_beban(): void
    {
        $kat = KategoriBeban::create([
            'nama_kategori' => 'Kategori Sebelum Edit ' . uniqid(),
            'deskripsi' => 'Deskripsi awal',
        ]);

        $response = $this->actingAs($this->admin)->put(route('kategori-beban.update', $kat->id), [
            'nama_kategori' => 'Kategori Sesudah Edit ' . uniqid(),
            'deskripsi' => 'Deskripsi diperbarui',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('open_modal_kategori', true);

        $kat->refresh();
        $this->assertEquals('Deskripsi diperbarui', $kat->deskripsi);
    }

    public function test_can_delete_unused_kategori_beban(): void
    {
        $kat = KategoriBeban::create([
            'nama_kategori' => 'Kategori Bebas ' . uniqid(),
            'deskripsi' => 'Akan dihapus',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('kategori-beban.destroy', $kat->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas('open_modal_kategori', true);

        $this->assertDatabaseMissing('kategori_beban', [
            'id' => $kat->id,
        ]);
    }

    public function test_cannot_delete_kategori_beban_with_existing_transactions(): void
    {
        $kat = KategoriBeban::create([
            'nama_kategori' => 'Kategori Terpakai ' . uniqid(),
            'deskripsi' => 'Sudah ada beban belanja',
        ]);

        // Buat beban belanja yang merujuk ke kategori ini
        BebanBelanja::create([
            'nomor_beban' => BebanBelanja::generateNomorBeban(),
            'tanggal' => Carbon::now()->toDateString(),
            'kategori_id' => $kat->id,
            'nama_beban' => 'Belanja Terkait Kategori',
            'nominal' => 75000,
            'admin_id' => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('kategori-beban.destroy', $kat->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $response->assertSessionHas('open_modal_kategori', true);

        // Kategori tidak boleh terhapus
        $this->assertDatabaseHas('kategori_beban', [
            'id' => $kat->id,
        ]);
    }
}
