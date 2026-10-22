<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateTokabeDataCommand extends Command
{
    protected $signature = 'migrate:tokabe-data {--dry-run : Uji coba migrasi tanpa menyimpan perubahan}';
    protected $description = 'ETL data historis Tokabe dari staging lama ke skema baru tanpa membawa data Ibekami';

    // Menyimpan mapping [source_table_old_id => target_id] untuk menjaga relasi foreign key item
    private array $penjualanIdMap = [];

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info("===================================================================");
        $this->info("       ETL DATA MIGRATION: OLD SYSTEM -> TOKABE NEW SCHEMA         ");
        $this->info($isDryRun ? "       [STATUS: DRY-RUN (Simulasi - Rollback otomatis)]" : "       [STATUS: LIVE EXECUTION]");
        $this->info("===================================================================");

        // 1. Validasi koneksi staging
        try {
            DB::connection('db_old')->getPdo();
            $this->info("✓ Koneksi ke database staging (db_old) berhasil.");
        } catch (\Throwable $e) {
            $this->error("Gagal terhubung ke database staging 'db_old': " . $e->getMessage());
            return self::FAILURE;
        }

        DB::beginTransaction();

        try {
            Schema::disableForeignKeyConstraints();

            $this->migrateUsers();
            $this->migrateInvoiceMaster();
            $this->migrateInvoicesAndItems();
            $this->migrateAndCleanSPK();
            $this->migrateAndCleanSPJ();
            $this->purgeIbekamiDeliveryOrders();

            Schema::enableForeignKeyConstraints();

            // Run verification
            $this->verifyPostMigration();

            if ($isDryRun) {
                DB::rollBack();
                $this->warn("\n[DRY RUN SELESAI] Seluruh transaksi di-rollback. Database tujuan tetap tidak berubah.");
            } else {
                DB::commit();
                $this->info("\n[SUKSES] Seluruh data historis Tokabe berhasil dimigrasikan dan diselaraskan!");
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            Schema::enableForeignKeyConstraints();
            $this->error("\n[ERROR MIGRASI GAGAL] " . $e->getMessage());
            $this->error("Line: " . $e->getLine() . " in " . $e->getFile());
            return self::FAILURE;
        }
    }

    private function migrateUsers(): void
    {
        $this->info("\n1. Memigrasikan Data Pengguna (Users)...");
        $oldUsers = DB::connection('db_old')->table('users')->get();

        foreach ($oldUsers as $u) {
            // Normalisasi role: hanya 'Pemilik' dan 'Admin' yang diizinkan di skema baru
            $newRole = ($u->role === 'Pemilik') ? 'Pemilik' : 'Admin';

            DB::table('users')->updateOrInsert(
                ['id' => $u->id],
                [
                    'nama' => $u->nama,
                    'email' => $u->email,
                    'password' => $u->password,
                    'status' => $u->status ?? 'Aktif',
                    'nomor_telepon' => $u->nomor_telepon ?? '0',
                    'role' => $newRole,
                    'remember_token' => $u->remember_token,
                    'device_token' => $u->device_token,
                    'created_at' => $u->created_at,
                    'updated_at' => $u->updated_at,
                ]
            );
        }
        $this->line("   ✓ " . count($oldUsers) . " user dipetakan ke role Pemilik/Admin.");
    }

    private function migrateInvoiceMaster(): void
    {
        $this->info("\n2. Memigrasikan Master Invoice Tokabe...");
        $tokabeInvoice = DB::connection('db_old')->table('invoice')->where('id', 5)->first();

        if ($tokabeInvoice) {
            DB::table('invoice')->updateOrInsert(
                ['id' => 5],
                [
                    'nama_invoice' => $tokabeInvoice->nama_invoice,
                    'kode_invoice' => $tokabeInvoice->kode_invoice,
                    'perusahaan_id' => $tokabeInvoice->perusahaan_id,
                    'created_at' => $tokabeInvoice->created_at,
                    'updated_at' => $tokabeInvoice->updated_at,
                ]
            );
            $this->line("   ✓ Master Invoice Tokabe (ID: 5, Kode: TKBP) siap.");
        }

        // Pastikan tidak ada master invoice Ibekami di tabel invoice baru
        $deletedOld = DB::table('invoice')->whereIn('id', [1, 2, 3, 4])->delete();
        if ($deletedOld > 0) {
            $this->line("   ✓ Dihapus $deletedOld master invoice lama milik Ibekami.");
        }
    }

    private function migrateInvoicesAndItems(): void
    {
        $this->info("\n3. Memigrasikan Header Invoice & Line Items Tokabe (Dual-Source)...");

        $validBarangIds = DB::table('barang')->pluck('id')->all();

        // -------------------------------------------------------------
        // SOURCE A: Tabel penjualan_tokabe dari staging lama
        // -------------------------------------------------------------
        $tokabeInvoices = DB::connection('db_old')->table('penjualan_tokabe')->get();
        $this->line("   -> [Source A] Memeriksa " . count($tokabeInvoices) . " invoice di tabel penjualan_tokabe lama...");

        $sourceACount = 0;
        foreach ($tokabeInvoices as $inv) {
            $this->upsertTokabeHeader($inv);
            $this->penjualanIdMap['pt_' . $inv->id] = $inv->id;
            $sourceACount++;
        }

        // Detail items untuk Source A
        $oldItems = DB::connection('db_old')->table('penjualan_jasa_tokabe')->get();
        $this->line("   -> [Source A] Memeriksa " . count($oldItems) . " line item di penjualan_jasa_tokabe lama...");

        $itemACount = 0;
        foreach ($oldItems as $item) {
            $targetPenjualanId = $this->penjualanIdMap['pt_' . $item->penjualan_id] ?? null;
            if (!$targetPenjualanId) {
                continue;
            }

            $bId = (is_numeric($item->barang_id) && in_array((int)$item->barang_id, $validBarangIds))
                ? (int)$item->barang_id
                : null;

            DB::table('penjualan_jasa_tokabe')->updateOrInsert(
                ['id' => $item->id],
                [
                    'barang_id' => $bId,
                    'item_id' => null,
                    'material_id' => $item->material_id,
                    'material_qty' => $item->material_qty,
                    'penjualan_id' => $targetPenjualanId,
                    'deskripsi_item' => $item->deskripsi_item ?? '-',
                    'qty' => $item->qty ?? 1,
                    'satuan' => $item->satuan ?? 'Set',
                    'harga' => $item->harga ?? 0,
                    'jumlah_harga' => $item->jumlah_harga ?? 0,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'deleted_at' => $item->deleted_at,
                    'material_panjang' => $item->material_panjang,
                    'material_lebar' => $item->material_lebar,
                ]
            );
            $itemACount++;
        }

        // -------------------------------------------------------------
        // SOURCE B: Invoice Tokabe yang tertinggal di tabel penjualan lama
        // -------------------------------------------------------------
        $hiddenTokabeInvoices = DB::connection('db_old')
            ->table('penjualan')
            ->where(function ($q) {
                $q->where('invoice', 5)
                  ->orWhere('nomor_invoice', 'LIKE', '%TKBP%')
                  ->orWhere('nomor_invoice', 'LIKE', '%TKB%');
            })
            ->get();

        $this->line("   -> [Source B] Memulihkan " . count($hiddenTokabeInvoices) . " invoice Tokabe dari tabel penjualan lama...");

        $sourceBCount = 0;
        $itemBCount = 0;

        foreach ($hiddenTokabeInvoices as $hInv) {
            $existing = DB::table('penjualan_tokabe')->where('nomor_invoice', $hInv->nomor_invoice)->first();
            if ($existing) {
                $targetId = $existing->id;
            } else {
                // Tentukan ID yang aman (gunakan ID lama jika tersedia, atau biarkan auto-increment)
                $idCollision = DB::table('penjualan_tokabe')->where('id', $hInv->id)->exists();
                $targetId = $idCollision ? null : $hInv->id;

                $kodePpn = $this->detectKodePpn($hInv->customer, $hInv->perusahaan);

                $insertData = [
                    'invoice' => 5, // TKB Pajak
                    'nomor_invoice' => $hInv->nomor_invoice,
                    'is_custom' => 0,
                    'tgl_penjualan' => $hInv->tgl_penjualan,
                    'customer' => $hInv->customer,
                    'perusahaan' => $hInv->perusahaan,
                    'no_telepon' => $hInv->no_telepon ?? '-',
                    'admin' => $hInv->admin ?? 19,
                    'order_by' => $hInv->order_by ?? 'Walk in',
                    'nama_sales' => $hInv->nama_sales,
                    'tgl_selesai' => $hInv->tgl_selesai,
                    'jumlah_item' => $hInv->jumlah_item ?? 0,
                    'dp' => $hInv->dp ?? 0,
                    'jenis_pembayaran' => $hInv->jenis_pembayaran ?? 'Cash Lunas',
                    'no_rek' => !empty($hInv->no_rek) ? $hInv->no_rek : 'TKB',
                    'total_harga' => $hInv->total_harga ?? 0,
                    'status' => $hInv->status ?? 'Lunas',
                    'potongan' => $hInv->potongan ?? 0,
                    'diskon' => $hInv->diskon ?? 0,
                    'ppn' => $hInv->ppn ?? 0,
                    'kode_ppn' => $kodePpn,
                    'pph' => null,
                    'total_pembayaran' => $hInv->total_pembayaran ?? 0,
                    'sisa_pembayaran' => $hInv->sisa_pembayaran ?? 0,
                    'approval' => $hInv->approval ?? 'Lock',
                    'approved_at' => $hInv->approved_at,
                    'alasan_batal' => $hInv->alasan_batal,
                    'created_at' => $hInv->created_at,
                    'updated_at' => $hInv->updated_at,
                    'deleted_at' => $hInv->deleted_at,
                ];

                if ($targetId) {
                    $insertData['id'] = $targetId;
                    DB::table('penjualan_tokabe')->insert($insertData);
                } else {
                    $targetId = DB::table('penjualan_tokabe')->insertGetId($insertData);
                }
                $sourceBCount++;
            }

            $this->penjualanIdMap['p_' . $hInv->id] = $targetId;

            // Pindahkan line items dari penjualan_barang ke penjualan_jasa_tokabe
            $hiddenItems = DB::connection('db_old')
                ->table('penjualan_barang')
                ->where('penjualan_id', $hInv->id)
                ->get();

            foreach ($hiddenItems as $pb) {
                // Cek apakah item ini sudah ada
                $itemExists = DB::table('penjualan_jasa_tokabe')
                    ->where('penjualan_id', $targetId)
                    ->where('deskripsi_item', $pb->deskripsi_item)
                    ->where('jumlah_harga', $pb->jumlah_harga)
                    ->exists();

                if ($itemExists) {
                    continue;
                }

                $bId = (is_numeric($pb->barang_id) && in_array((int)$pb->barang_id, $validBarangIds))
                    ? (int)$pb->barang_id
                    : null;

                $pbIdCollision = DB::table('penjualan_jasa_tokabe')->where('id', $pb->id)->exists();

                $itemData = [
                    'barang_id' => $bId,
                    'item_id' => null,
                    'material_id' => $pb->material_id,
                    'material_qty' => $pb->material_qty,
                    'penjualan_id' => $targetId,
                    'deskripsi_item' => $pb->deskripsi_item ?? '-',
                    'qty' => $pb->qty ?? 1,
                    'satuan' => $pb->satuan ?? 'Set',
                    'harga' => $pb->hargaBarang ?? 0,
                    'jumlah_harga' => $pb->jumlah_harga ?? 0,
                    'created_at' => $pb->created_at,
                    'updated_at' => $pb->updated_at,
                    'deleted_at' => $pb->deleted_at,
                    'material_panjang' => $pb->material_panjang,
                    'material_lebar' => $pb->material_lebar,
                ];

                if (!$pbIdCollision) {
                    $itemData['id'] = $pb->id;
                    DB::table('penjualan_jasa_tokabe')->insert($itemData);
                } else {
                    DB::table('penjualan_jasa_tokabe')->insert($itemData);
                }

                $itemBCount++;
            }
        }

        $this->line("   ✓ Source A: $sourceACount invoice & $itemACount items berhasil diselaraskan.");
        $this->line("   ✓ Source B: $sourceBCount invoice Tokabe & $itemBCount detail items berhasil diselamatkan dari tabel lama.");
    }

    private function upsertTokabeHeader(object $inv): void
    {
        $kodePpn = $inv->kode_ppn ?? $this->detectKodePpn($inv->customer, $inv->perusahaan);

        DB::table('penjualan_tokabe')->updateOrInsert(
            ['id' => $inv->id],
            [
                'invoice' => 5,
                'nomor_invoice' => $inv->nomor_invoice,
                'is_custom' => $inv->is_custom ?? 0,
                'tgl_penjualan' => $inv->tgl_penjualan,
                'customer' => $inv->customer,
                'perusahaan' => $inv->perusahaan,
                'no_telepon' => $inv->no_telepon ?? '-',
                'admin' => $inv->admin ?? 19,
                'order_by' => $inv->order_by ?? 'Walk in',
                'nama_sales' => $inv->nama_sales,
                'tgl_selesai' => $inv->tgl_selesai,
                'jumlah_item' => $inv->jumlah_item ?? 0,
                'dp' => $inv->dp ?? 0,
                'jenis_pembayaran' => $inv->jenis_pembayaran ?? 'Cash Lunas',
                'no_rek' => !empty($inv->no_rek) ? $inv->no_rek : 'TKB',
                'total_harga' => $inv->total_harga ?? 0,
                'status' => $inv->status ?? 'Lunas',
                'potongan' => $inv->potongan ?? 0,
                'diskon' => $inv->diskon ?? 0,
                'ppn' => $inv->ppn ?? 0,
                'kode_ppn' => $kodePpn,
                'pph' => $inv->pph ?? null,
                'total_pembayaran' => $inv->total_pembayaran ?? 0,
                'sisa_pembayaran' => $inv->sisa_pembayaran ?? 0,
                'approval' => $inv->approval ?? 'Lock',
                'approved_at' => $inv->approved_at,
                'alasan_batal' => $inv->alasan_batal,
                'created_at' => $inv->created_at,
                'updated_at' => $inv->updated_at,
                'deleted_at' => $inv->deleted_at,
            ]
        );
    }

    private function detectKodePpn(?string $customer, ?string $perusahaan): string
    {
        $text = strtolower(($customer ?? '') . ' ' . ($perusahaan ?? ''));
        if (preg_match('/imigrasi|bpn|kantor|dinas|kementerian|pemko|pemkab/i', $text)) {
            return '02'; // Instansi Pemerintah
        }
        if (preg_match('/bank|mandiri|bni|bri|btn|syariah|telkom|angkasa pura|injourney|jasa raharja|pln|pertamina|sumut/i', $text)) {
            return '03'; // BUMN
        }
        return '01'; // Umum / Swasta
    }

    private function migrateAndCleanSPK(): void
    {
        $this->info("\n4. Memigrasikan & Membersihkan Surat Perintah Kerja (SPK)...");

        // 1. Ambil SPK Tokabe dari staging
        $tokabeSpk = DB::connection('db_old')
            ->table('spk')
            ->where(function ($q) {
                $q->where('nomor_invoice', 'LIKE', '%TKB%')
                  ->orWhere('nomor_invoice', 'LIKE', '%TKBP%');
            })
            ->get();

        foreach ($tokabeSpk as $spk) {
            DB::table('spk')->updateOrInsert(
                ['id' => $spk->id],
                [
                    'nomor_invoice' => $spk->nomor_invoice,
                    'customer' => $spk->customer,
                    'jumlah' => $spk->jumlah ?? 1,
                    'satuan' => $spk->satuan ?? 'Set',
                    'jenis_bahan' => $spk->jenis_bahan,
                    'ketebalan' => $spk->ketebalan,
                    'ukuran' => $spk->ukuran,
                    'lain' => $spk->lain,
                    'express' => $spk->express ?? 'N',
                    'tgl_mulai' => $spk->tgl_mulai,
                    'target_selesai' => $spk->target_selesai,
                    'timeline' => $spk->timeline ?? '0',
                    'timeline_design' => $spk->timeline_design,
                    'timeline_produksi' => $spk->timeline_produksi,
                    'status_spk' => $spk->status_spk ?? 'Selesai',
                    'status_kerja' => $spk->status_kerja ?? 'Selesai',
                    'pekerjaan' => $spk->pekerjaan,
                    'gambar' => $spk->gambar,
                    'created_at' => $spk->created_at,
                    'updated_at' => $spk->updated_at,
                ]
            );
        }

        // 2. Bersihkan seluruh SPK Ibekami yang masih tertinggal di tabel spk
        $purgedCount = DB::table('spk')
            ->where('nomor_invoice', 'NOT LIKE', '%TKB%')
            ->where('nomor_invoice', 'NOT LIKE', '%TKBP%')
            ->delete();

        $this->line("   ✓ " . count($tokabeSpk) . " SPK Tokabe berhasil dipertahankan.");
        $this->line("   ✓ $purgedCount SPK Ibekami berhasil dibuang dari sistem baru.");
    }

    private function migrateAndCleanSPJ(): void
    {
        $this->info("\n5. Memigrasikan & Membersihkan Surat Perintah Jalan (SPJ)...");

        // 1. Ambil SPJ Tokabe dari staging
        $tokabeSpj = DB::connection('db_old')
            ->table('spj')
            ->where(function ($q) {
                $q->where('perusahaan', 'LIKE', '%tokabe%')
                  ->orWhere('perusahaan', 'LIKE', '%total karya berkah%');
            })
            ->get();

        foreach ($tokabeSpj as $spj) {
            DB::table('spj')->updateOrInsert(
                ['id' => $spj->id],
                [
                    'nomor_spj' => $spj->nomor_spj,
                    'perusahaan' => $spj->perusahaan,
                    'nama_pemberi_tugas' => $spj->nama_pemberi_tugas,
                    'nama_kurir' => $spj->nama_kurir,
                    'tanggal_tugas' => $spj->tanggal_tugas,
                    'waktu_berangkat' => $spj->waktu_berangkat,
                    'tujuan' => $spj->tujuan,
                    'biaya_bahan_bakar' => $spj->biaya_bahan_bakar,
                    'jarak_tempuh' => $spj->jarak_tempuh,
                    'deskripsi_barang' => $spj->deskripsi_barang,
                    'keterangan_tambahan' => $spj->keterangan_tambahan,
                    'deskripsi_tugas' => $spj->deskripsi_tugas,
                    'jam_kembali' => $spj->jam_kembali,
                    'status' => $spj->status ?? 'Belum Bayar',
                    'created_at' => $spj->created_at,
                    'updated_at' => $spj->updated_at,
                ]
            );
        }

        // 2. Bersihkan seluruh SPJ Ibekami yang masih tertinggal di tabel spj
        $purgedSpj = DB::table('spj')
            ->where('perusahaan', 'NOT LIKE', '%tokabe%')
            ->where('perusahaan', 'NOT LIKE', '%total karya berkah%')
            ->delete();

        $this->line("   ✓ " . count($tokabeSpj) . " SPJ Tokabe berhasil dipertahankan.");
        $this->line("   ✓ $purgedSpj SPJ Ibekami berhasil dibuang dari sistem baru.");
    }

    private function purgeIbekamiDeliveryOrders(): void
    {
        $this->info("\n6. Membersihkan Entitas Surat Pengantar Barang (SPB) Ibekami...");
        // Seluruh 309 data SPB di data lama adalah pesanan pengantaran plakat/souvenir Ibekami
        $spbCount = DB::table('spb')->count();
        $bSpbCount = DB::table('barang_spb')->count();

        if ($spbCount > 0 || $bSpbCount > 0) {
            DB::table('barang_spb')->truncate();
            DB::table('spb')->truncate();
            $this->line("   ✓ Berhasil membersihkan $spbCount SPB dan $bSpbCount barang_spb milik Ibekami.");
        } else {
            $this->line("   ✓ Tabel SPB & barang_spb sudah bersih.");
        }
    }

    private function verifyPostMigration(): void
    {
        $this->info("\n===================================================================");
        $this->info("                 AUDIT HASIL & REKONSILIASI DATA                   ");
        $this->info("===================================================================");

        // A. Cek Total Invoices & Nominal
        $totalInvoices = DB::table('penjualan_tokabe')->count();
        $totalNominal = DB::table('penjualan_tokabe')->sum('total_pembayaran');
        $totalItems = DB::table('penjualan_jasa_tokabe')->count();
        $totalItemsValue = DB::table('penjualan_jasa_tokabe')->sum('jumlah_harga');

        $this->line("  [INVOICE HEADER] Total Record : $totalInvoices invoice");
        $this->line("  [INVOICE HEADER] Total Nominal: Rp " . number_format($totalNominal, 0, ',', '.'));
        $this->line("  [LINE ITEMS]     Total Record : $totalItems item");
        $this->line("  [LINE ITEMS]     Total Nilai  : Rp " . number_format($totalItemsValue, 0, ',', '.'));

        // B. Cek Kebocoran Ibekami
        $ibekamiLeakCount = DB::table('penjualan_tokabe')
            ->where('nomor_invoice', 'LIKE', 'IB%')
            ->orWhere('nomor_invoice', 'LIKE', 'IBS%')
            ->orWhere('nomor_invoice', 'LIKE', 'IBTR%')
            ->orWhere('nomor_invoice', 'LIKE', 'IBPL%')
            ->orWhere('invoice', '!=', 5)
            ->count();

        if ($ibekamiLeakCount === 0) {
            $this->info("  [LEAKAGE CHECK]  ✓ ZERO LEAKAGE: 0 data Ibekami ditemukan di penjualan_tokabe.");
        } else {
            $this->error("  [LEAKAGE CHECK]  ✗ PERINGATAN: Ditemukan $ibekamiLeakCount invoice Ibekami!");
        }

        // C. Cek Orphan Line Items
        $orphanItems = DB::table('penjualan_jasa_tokabe as pjt')
            ->leftJoin('penjualan_tokabe as pt', 'pjt.penjualan_id', '=', 'pt.id')
            ->whereNull('pt.id')
            ->count();

        if ($orphanItems === 0) {
            $this->info("  [INTEGRITY]      ✓ ZERO ORPHANS: 0 orphan item (semua line items memiliki header sah).");
        } else {
            $this->error("  [INTEGRITY]      ✗ Ditemukan $orphanItems orphan line items!");
        }

        // D. Cek SPK & SPJ
        $remainingSpk = DB::table('spk')->count();
        $remainingSpj = DB::table('spj')->count();
        $this->line("  [SPK TOKABE]     Total Tersimpan: $remainingSpk record");
        $this->line("  [SPJ TOKABE]     Total Tersimpan: $remainingSpj record");
        $this->info("===================================================================");
    }
}
