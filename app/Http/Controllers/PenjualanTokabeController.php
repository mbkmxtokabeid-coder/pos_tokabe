<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use App\Models\PenjualanTokabe;
use App\Models\Barang;
use App\Models\PenjualanJasaTokabe;
use App\Models\Rekening;
use Carbon\Carbon;
use DateTimezone;

use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PenjualanTokabeController extends Controller
{

    public function listInvoiceTokabe()
    {
        DB::table('penjualan_tokabe')
            ->where('approval', 'Unlock')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<', now()->subHours(6))
            ->update(['approval' => 'Lock', 'approved_at' => null]);

        $perusahaan = null;
        $invoices = collect();

        Carbon::setLocale('id');
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;

        // Preload seluruh rekening untuk mencegah N+1 query di Blade
        $rekenings = Rekening::all()->keyBy('id');

        // =========================================================================
        // 1. KONSOLIDASI CARD NON PO (Jenis Pembayaran != 'PO' atau NULL)
        // =========================================================================
        $nonPoStats = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->where(function($q) {
                $q->where('jenis_pembayaran', '<>', 'PO')->orWhereNull('jenis_pembayaran');
            })
            ->selectRaw("
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? THEN 1 END) as jlh_invoice,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as total_per_tahun,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Lunas' THEN total_pembayaran ELSE 0 END), 0) as total_lunas,
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Lunas' THEN 1 END) as lunas,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN dp ELSE 0 END), 0) as tot_dp_tahun,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN sisa_pembayaran ELSE 0 END), 0) as jumlah_bb_per_tahun,
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN 1 END) as belum_lunas,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN sisa_pembayaran ELSE 0 END), 0) as jumlah_bb_per_bulan,
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN 1 END) as belum_lunas_month
            ", [
                $yearNow, $yearNow, $yearNow, $yearNow, $yearNow, $yearNow, $yearNow,
                $yearNow, $monthNow, $yearNow, $monthNow
            ])
            ->first();

        $jlhInvoice = $nonPoStats->jlh_invoice ?? 0;
        $totalPerTahun = $nonPoStats->total_per_tahun ?? 0;
        $jumlahPerTahun = $totalPerTahun;
        $lunas = $nonPoStats->lunas ?? 0;
        $totalLunasThn = ($nonPoStats->total_lunas ?? 0) + ($nonPoStats->tot_dp_tahun ?? 0);
        $belumLunas = $nonPoStats->belum_lunas ?? 0;
        $jumlahBBPerTahun = $nonPoStats->jumlah_bb_per_tahun ?? 0;
        $belumLunasMonth = $nonPoStats->belum_lunas_month ?? 0;
        $jumlahBBPerBulan = $nonPoStats->jumlah_bb_per_bulan ?? 0;

        // =========================================================================
        // 2. KONSOLIDASI CARD PO (Purchase Order - jenis_pembayaran == 'PO')
        // =========================================================================
        $poStats = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->where('jenis_pembayaran', 'PO')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status <> 'Batal' THEN total_pembayaran ELSE 0 END), 0) as jumlah_po,
                COUNT(CASE WHEN status <> 'Batal' THEN 1 END) as count_all_inv_po,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Lunas' THEN total_pembayaran ELSE 0 END), 0) as jumlah_po_lunas,
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Lunas' THEN 1 END) as count_all_settled_po,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN total_pembayaran ELSE 0 END), 0) as jumlah_po_blm_lunas,
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN 1 END) as count_all_outstanding_po,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN sisa_pembayaran ELSE 0 END), 0) as jumlah_bb_per_bulan_po,
                COUNT(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? AND status = 'Belum Lunas' THEN 1 END) as blm_lunas_po
            ", [
                $yearNow, $yearNow, $yearNow, $yearNow,
                $yearNow, $monthNow, $yearNow, $monthNow
            ])
            ->first();

        $jumlahPO = $poStats->jumlah_po ?? 0;
        $countAllInvPO = $poStats->count_all_inv_po ?? 0;
        $jumlahPOLunas = $poStats->jumlah_po_lunas ?? 0;
        $countAllSettledPO = $poStats->count_all_settled_po ?? 0;
        $jumlahPOBlmLunas = $poStats->jumlah_po_blm_lunas ?? 0;
        $countAllOutstandingPO = $poStats->count_all_outstanding_po ?? 0;
        $jumlahBBPerBulanPO = $poStats->jumlah_bb_per_bulan_po ?? 0;
        $blmLunasPO = $poStats->blm_lunas_po ?? 0;

        // =========================================================================
        // 3. KONSOLIDASI BATAL & UMUM
        // =========================================================================
        $generalStats = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->selectRaw("
                COUNT(CASE WHEN status = 'Batal' AND YEAR(tgl_penjualan) = ? THEN 1 END) as batal,
                COALESCE(SUM(CASE WHEN status = 'Batal' AND YEAR(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as jumlah_batal,
                COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as total_per_bulan,
                COALESCE(SUM(total_pembayaran), 0) as jumlah_invoice
            ", [$yearNow, $yearNow, $yearNow, $monthNow])
            ->first();

        $batal = $generalStats->batal ?? 0;
        $jumlahBatal = $generalStats->jumlah_batal ?? 0;
        $totalPerBulan = $generalStats->total_per_bulan ?? 0;
        $jumlahPerBulan = $totalPerBulan;
        $jumlahInvoice = $generalStats->jumlah_invoice ?? 0;

        // KONSOLIDASI DIHAPUS (Soft Deleted) TAHUN INI
        $deletedStats = DB::table('penjualan_tokabe')
            ->whereNotNull('deleted_at')
            ->whereYear('tgl_penjualan', $yearNow)
            ->selectRaw("
                COUNT(*) as hapus,
                COALESCE(SUM(total_pembayaran), 0) as jumlah_hapus
            ")
            ->first();

        $hapus = $deletedStats->hapus ?? 0;
        $jumlahHapus = $deletedStats->jumlah_hapus ?? 0;

        // =========================================================================
        // 4. DATA INVOICE TOKABE (Eager load user & penjualanBarang cegah N+1)
        // =========================================================================
        $penjualan = PenjualanTokabe::with(['user:id,nama', 'penjualanBarang:id,penjualan_id,deskripsi_item'])
            ->orderByDesc('tgl_penjualan')
            ->get();

        // Perhitungan PPN & PPh Tahun Ini (Query kolom relevan saja)
        $dataPajak = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->whereYear('tgl_penjualan', $yearNow)
            ->where('status', '!=', 'Batal')
            ->select('ppn', 'diskon', 'potongan', 'pph', 'total_pembayaran', 'total_harga')
            ->get();

        $ppnTotal = 0;
        $ppnTotalCount = 0;
        $ppn01 = 0;
        $ppn01Count = 0;
        $ppn02 = 0;
        $ppn02Count = 0;
        $ppn03 = 0;
        $ppn03Count = 0;
        $ppn04 = 0;
        $ppn04Count = 0;
        $pphTotal = 0;
        $pphTotalCount = 0;

        foreach ($dataPajak as $inv) {
            $hasPpn = !empty($inv->ppn) && (float)$inv->ppn > 0;
            $hasDiskon = !empty($inv->diskon) && (float)$inv->diskon > 0;
            $hasPotongan = !empty($inv->potongan) && (float)$inv->potongan > 0;
            $hasPph = !empty($inv->pph) && (float)$inv->pph > 0;
            $nominalPpn = 0;

            if ($hasPpn) {
                $nominalPpn = ($inv->ppn / 100) * ($inv->total_harga ?? 0);
            } elseif (!$hasDiskon && !$hasPotongan && !$hasPph && $inv->total_pembayaran > $inv->total_harga && $inv->total_harga > 0) {
                $nominalPpn = $inv->total_pembayaran - $inv->total_harga;
                $hasPpn = true;
            }

            if ($hasPpn && $nominalPpn > 0) {
                $ppnTotal += $nominalPpn;
                $ppnTotalCount++;

                $kode = $inv->kode_ppn ?? '01';
                if ($kode == '01') {
                    $ppn01 += $nominalPpn;
                    $ppn01Count++;
                } elseif ($kode == '02') {
                    $ppn02 += $nominalPpn;
                    $ppn02Count++;
                } elseif ($kode == '03') {
                    $ppn03 += $nominalPpn;
                    $ppn03Count++;
                } elseif ($kode == '04') {
                    $ppn04 += $nominalPpn;
                    $ppn04Count++;
                }
            }

            if ($hasPph) {
                $nominalPph = ($inv->pph / 100) * ($inv->total_harga ?? 0);
                $pphTotal += $nominalPph;
                $pphTotalCount++;
            }
        }
        
        foreach ($penjualan as $inv) {
            $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->format('d M Y');
            $inv->formatted_tgl_update = Carbon::parse($inv->updated_at)->addHour(7)->format('d M Y H:i');
            $inv->formatted_total_pembayaran = number_format($inv->total_pembayaran, 0, ',', ',');
            $inv->formatted_dp = number_format($inv->dp, 0, ',', ',');
            if ($inv->dp == null) {
                $inv->dp = 0;
            }
        }

        $availableYears = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->whereNotNull('tgl_penjualan')
            ->selectRaw('DISTINCT YEAR(tgl_penjualan) as year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [$yearNow, $yearNow - 1];
        } else {
            if (!in_array($yearNow, $availableYears)) {
                array_unshift($availableYears, $yearNow);
            }
            rsort($availableYears);
        }

        return view('pages.invoices.tokabe.daftar-invoiceTokabe', compact(
            'invoices', 'perusahaan', 'penjualan', 'rekenings', 'jlhInvoice', 'lunas', 'belumLunas', 'batal', 
            'totalLunasThn', 'jumlahBatal', 'jumlahInvoice', 'jumlahBBPerTahun', 'jumlahBBPerBulan', 
            'jumlahPerBulan', 'jumlahPerTahun', 'belumLunasMonth',
            'jumlahPO', 'countAllInvPO', 'jumlahPOLunas', 'countAllSettledPO', 
            'jumlahPOBlmLunas', 'countAllOutstandingPO', 'jumlahBBPerBulanPO', 'blmLunasPO',
            'hapus', 'jumlahHapus',
            'ppnTotal', 'ppnTotalCount', 'ppn01', 'ppn01Count', 'ppn02', 'ppn02Count',
            'ppn03', 'ppn03Count', 'ppn04', 'ppn04Count', 'pphTotal', 'pphTotalCount', 'availableYears'
        ));
    }

    public function getCardsData(Request $request)
    {
        $timeframe = $request->query('timeframe', 'year');
        $mode = $request->query('mode');
        $date = $request->query('date');
        $month = $request->query('month');
        $year = $request->query('year');

        Carbon::setLocale('id');
        $now = Carbon::now(new \DateTimeZone('Asia/Jakarta'));
        $yearNow = $now->year;
        $monthNow = $now->month;

        $query = PenjualanTokabe::query();
        $card4Query = PenjualanTokabe::query();
        $queryDeleted = PenjualanTokabe::onlyTrashed();

        $periodLabel = '';
        $card4MonthName = '';

        if ($timeframe === 'today') {
            $today = $now->toDateString();
            $query->whereDate('tgl_penjualan', $today);
            $queryDeleted->whereDate('tgl_penjualan', $today);
            $periodLabel = 'HARI INI';
            
            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F Y'));
        } elseif ($timeframe === 'week') {
            $startOfWeek = $now->copy()->startOfWeek()->format('Y-m-d 00:00:00');
            $endOfWeek = $now->copy()->endOfWeek()->format('Y-m-d 23:59:59');
            $query->whereBetween('tgl_penjualan', [$startOfWeek, $endOfWeek]);
            $queryDeleted->whereBetween('tgl_penjualan', [$startOfWeek, $endOfWeek]);
            $periodLabel = 'MINGGU INI';

            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F Y'));
        } elseif ($timeframe === 'month') {
            $query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $queryDeleted->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $periodLabel = 'BULAN ' . strtoupper($now->translatedFormat('F Y'));

            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F Y'));
        } elseif ($timeframe === 'year') {
            $query->whereYear('tgl_penjualan', $yearNow);
            $queryDeleted->whereYear('tgl_penjualan', $yearNow);
            $periodLabel = 'TAHUN ' . $yearNow;

            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F'));
        } elseif ($timeframe === 'last_year') {
            $lastYear = $yearNow - 1;
            $query->whereYear('tgl_penjualan', $lastYear);
            $queryDeleted->whereYear('tgl_penjualan', $lastYear);
            $periodLabel = 'TAHUN ' . $lastYear;

            $card4Query->whereYear('tgl_penjualan', $lastYear)->whereMonth('tgl_penjualan', 12);
            $card4MonthName = 'DESEMBER ' . $lastYear;
        } elseif ($timeframe === 'custom') {
            if ($mode === 'date' && $date) {
                $parsedDate = Carbon::parse($date);
                $query->whereDate('tgl_penjualan', $parsedDate->toDateString());
                $queryDeleted->whereDate('tgl_penjualan', $parsedDate->toDateString());
                $periodLabel = 'TANGGAL ' . $parsedDate->format('d/m/Y');

                $card4Query->whereYear('tgl_penjualan', $parsedDate->year)->whereMonth('tgl_penjualan', $parsedDate->month);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F Y'));
            } elseif ($mode === 'month' && $month && $year) {
                $query->whereYear('tgl_penjualan', $year)->whereMonth('tgl_penjualan', $month);
                $queryDeleted->whereYear('tgl_penjualan', $year)->whereMonth('tgl_penjualan', $month);
                $parsedDate = Carbon::createFromDate($year, $month, 1);
                $periodLabel = 'BULAN ' . strtoupper($parsedDate->translatedFormat('F Y'));

                $card4Query->whereYear('tgl_penjualan', $year)->whereMonth('tgl_penjualan', $month);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F Y'));
            } elseif ($mode === 'year' && $year) {
                $query->whereYear('tgl_penjualan', $year);
                $queryDeleted->whereYear('tgl_penjualan', $year);
                $periodLabel = 'TAHUN ' . $year;

                $card4Month = ($year == $yearNow) ? $monthNow : 12;
                $card4Query->whereYear('tgl_penjualan', $year)->whereMonth('tgl_penjualan', $card4Month);
                $parsedDate = Carbon::createFromDate($year, $card4Month, 1);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F') . ($year != $yearNow ? ' ' . $year : ''));
            }
        }

        // =========================================================================
        // CARD NON-PO (jenis_pembayaran != 'PO' atau NULL)
        // =========================================================================
        $queryNonPo = (clone $query)->where(function($q) {
            $q->where('jenis_pembayaran', '<>', 'PO')->orWhereNull('jenis_pembayaran');
        });
        $card4QueryNonPo = (clone $card4Query)->where(function($q) {
            $q->where('jenis_pembayaran', '<>', 'PO')->orWhereNull('jenis_pembayaran');
        });

        // Non-PO Card 1: Total Non-PO
        $totalNominalNonPo = (clone $queryNonPo)->sum('total_pembayaran');
        $jlhInvoiceNonPo = (clone $queryNonPo)->count();
        $valCard1 = number_format($totalNominalNonPo, 0, ',', '.');

        // Non-PO Card 2: Terbayar Non-PO
        $dataLunasNonPo = (clone $queryNonPo)->where('status', 'Lunas');
        $dataBlmLunasNonPo = (clone $queryNonPo)->where('status', 'Belum Lunas');
        $totLunasNonPo = (clone $dataLunasNonPo)->sum('total_pembayaran');
        $totDpNonPo = (clone $dataBlmLunasNonPo)->sum('dp');
        $lunasNonPo = (clone $dataLunasNonPo)->count();
        $totalLunasThnVal = $totLunasNonPo + $totDpNonPo;
        $valCard2 = number_format($totalLunasThnVal, 0, ',', '.');

        // Non-PO Card 3: Belum Terbayar Non-PO Periode
        $belumLunasNonPo = (clone $dataBlmLunasNonPo)->count();
        $totalBBNonPo = (clone $dataBlmLunasNonPo)->sum('sisa_pembayaran');
        $valCard3 = number_format($totalBBNonPo, 0, ',', '.');

        // Non-PO Card 4: Belum Terbayar Non-PO Bulan
        $dataBlmLunasMonthNonPo = (clone $card4QueryNonPo)->where('status', 'Belum Lunas');
        $belumLunasMonthNonPo = (clone $dataBlmLunasMonthNonPo)->count();
        $totalBBMonthNonPo = (clone $dataBlmLunasMonthNonPo)->sum('sisa_pembayaran');
        $valCard4 = number_format($totalBBMonthNonPo, 0, ',', '.');

        // =========================================================================
        // CARD PO (Purchase Order - jenis_pembayaran == 'PO')
        // =========================================================================
        $queryPo = (clone $query)->where('jenis_pembayaran', 'PO');
        $card4QueryPo = (clone $card4Query)->where('jenis_pembayaran', 'PO');

        // PO Card 1: Jumlah Seluruh PO
        if ($timeframe === 'year' || empty($timeframe)) {
            $totalNominalPo = PenjualanTokabe::where('jenis_pembayaran', 'PO')->where('status', '<>', 'Batal')->sum('total_pembayaran');
            $countInvPo = PenjualanTokabe::where('jenis_pembayaran', 'PO')->where('status', '<>', 'Batal')->count();
        } else {
            $totalNominalPo = (clone $queryPo)->where('status', '<>', 'Batal')->sum('total_pembayaran');
            $countInvPo = (clone $queryPo)->where('status', '<>', 'Batal')->count();
        }
        $valPo1 = number_format($totalNominalPo, 0, ',', '.');

        // PO Card 2: PO Lunas Periode
        $dataLunasPo = (clone $queryPo)->where('status', 'Lunas');
        $valPo2 = number_format((clone $dataLunasPo)->sum('total_pembayaran'), 0, ',', '.');
        $countPo2 = (clone $dataLunasPo)->count();

        // PO Card 3: PO Belum Lunas Periode
        $dataBlmLunasPo = (clone $queryPo)->where('status', 'Belum Lunas');
        $valPo3 = number_format((clone $dataBlmLunasPo)->sum('total_pembayaran'), 0, ',', '.');
        $countPo3 = (clone $dataBlmLunasPo)->count();

        // PO Card 4: PO Belum Lunas Bulan
        $dataBlmLunasMonthPo = (clone $card4QueryPo)->where('status', 'Belum Lunas');
        $valPo4 = number_format((clone $dataBlmLunasMonthPo)->sum('sisa_pembayaran'), 0, ',', '.');
        $countPo4 = (clone $dataBlmLunasMonthPo)->count();

        // Card 5: Batal
        $dataBatal = (clone $query)->where('status', 'Batal');
        $batal = (clone $dataBatal)->count();
        $totalBatal = (clone $dataBatal)->sum('total_pembayaran');
        $valCard5 = number_format($totalBatal, 0, ',', '.');

        // Card Hapus: Dihapus (Soft Deleted)
        $hapus = (clone $queryDeleted)->count();
        $totalHapus = (clone $queryDeleted)->sum('total_pembayaran');
        $valCardHapus = number_format($totalHapus, 0, ',', '.');

        // Pajak PPN & PPh
        $dataPajak = (clone $query)->where('status', '!=', 'Batal')
            ->select('ppn', 'diskon', 'potongan', 'pph', 'total_pembayaran', 'total_harga')
            ->get();
        $ppnTotal = 0;
        $ppnTotalCount = 0;
        $ppn01 = 0;
        $ppn01Count = 0;
        $ppn02 = 0;
        $ppn02Count = 0;
        $ppn03 = 0;
        $ppn03Count = 0;
        $ppn04 = 0;
        $ppn04Count = 0;
        $pphTotal = 0;
        $pphTotalCount = 0;

        foreach ($dataPajak as $inv) {
            $hasPpn = !empty($inv->ppn) && (float)$inv->ppn > 0;
            $hasDiskon = !empty($inv->diskon) && (float)$inv->diskon > 0;
            $hasPotongan = !empty($inv->potongan) && (float)$inv->potongan > 0;
            $hasPph = !empty($inv->pph) && (float)$inv->pph > 0;
            $nominalPpn = 0;

            if ($hasPpn) {
                $nominalPpn = ($inv->ppn / 100) * ($inv->total_harga ?? 0);
            } elseif (!$hasDiskon && !$hasPotongan && !$hasPph && $inv->total_pembayaran > $inv->total_harga && $inv->total_harga > 0) {
                $nominalPpn = $inv->total_pembayaran - $inv->total_harga;
                $hasPpn = true;
            }

            if ($hasPpn && $nominalPpn > 0) {
                $ppnTotal += $nominalPpn;
                $ppnTotalCount++;

                $kode = $inv->kode_ppn ?? '01';
                if ($kode == '01') {
                    $ppn01 += $nominalPpn;
                    $ppn01Count++;
                } elseif ($kode == '02') {
                    $ppn02 += $nominalPpn;
                    $ppn02Count++;
                } elseif ($kode == '03') {
                    $ppn03 += $nominalPpn;
                    $ppn03Count++;
                } elseif ($kode == '04') {
                    $ppn04 += $nominalPpn;
                    $ppn04Count++;
                }
            }

            if ($hasPph) {
                $nominalPph = ($inv->pph / 100) * ($inv->total_harga ?? 0);
                $pphTotal += $nominalPph;
                $pphTotalCount++;
            }
        }

        return response()->json([
            'status' => 'success',
            'periodLabel' => $periodLabel,
            'card4MonthName' => $card4MonthName,
            'card1' => ['nominal' => $valCard1, 'count' => $jlhInvoiceNonPo],
            'card2' => ['nominal' => $valCard2, 'count' => $lunasNonPo],
            'card3' => ['nominal' => $valCard3, 'count' => $belumLunasNonPo],
            'card4' => ['nominal' => $valCard4, 'count' => $belumLunasMonthNonPo],
            'cardPo1' => ['nominal' => $valPo1, 'count' => $countInvPo],
            'cardPo2' => ['nominal' => $valPo2, 'count' => $countPo2],
            'cardPo3' => ['nominal' => $valPo3, 'count' => $countPo3],
            'cardPo4' => ['nominal' => $valPo4, 'count' => $countPo4],
            'card5' => ['nominal' => $valCard5, 'count' => $batal],
            'cardHapus' => ['nominal' => $valCardHapus, 'count' => $hapus],
            'card6' => ['nominal' => number_format($ppnTotal, 0, ',', '.'), 'count' => $ppnTotalCount],
            'card7' => ['nominal' => number_format($pphTotal, 0, ',', '.'), 'count' => $pphTotalCount],
            'card8' => ['nominal' => number_format($ppn01, 0, ',', '.'), 'count' => $ppn01Count],
            'card9' => ['nominal' => number_format($ppn02, 0, ',', '.'), 'count' => $ppn02Count],
            'card10' => ['nominal' => number_format($ppn03, 0, ',', '.'), 'count' => $ppn03Count],
            'card11' => ['nominal' => number_format($ppn04, 0, ',', '.'), 'count' => $ppn04Count],
        ]);
    }

    public function addInvoice()
    {
        $no_invoice = $this->generateNoInvoice();
        $invoice = Invoice::where('perusahaan_id', 2)->get();
        $admin = User::whereIn('role', ['Admin', 'Pemilik'])->where('status', 'Aktif')->get();
        $order = Order::all();
        $materials = collect();
        $tanggal = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); 
        $month = $tanggal->format('m'); 
        $day = $tanggal->format('d');
        $formatJam = $tanggal->format('H:i'); 
        $hariIni = sprintf('%s-%s-%s', $year, $month, $day);
        $jenisBarang = Item::orderBy('nama_item')->get();

        $kontak = PenjualanTokabe::select('customer', 'no_telepon', 'perusahaan')->get();
        try {
            $rekenings = Rekening::where('status', 'Aktif')->get();
        } catch (\Throwable $e) {
            $rekenings = collect();
        }
        return view('pages.invoices.tokabe.addInvoice', compact('invoice', 'admin', 'order', 'no_invoice', 'hariIni', 'formatJam', 'kontak', 'jenisBarang', 'rekenings'));
    }

    public function generateNoInvoice($tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); // Tahun
        $month = $tanggal->format('m'); // Bulan dalam angka

        // Konversi bulan ke angka Romawi
        $romawiBulan = $this->convertToRoman((int)$month);

        // Ambil invoice terakhir di bulan dan tahun yang sama berdasarkan tgl_penjualan
        $lastInvoice = PenjualanTokabe::whereYear('tgl_penjualan', $year)
            ->whereMonth('tgl_penjualan', $month)
            ->orderByRaw("CAST(SUBSTRING_INDEX(nomor_invoice, '/', 1) AS UNSIGNED) DESC")
            ->pluck('nomor_invoice')
            ->first();

        $last_number = $lastInvoice ? ((int) explode('/', $lastInvoice)[0]) : 0;

        // Nomor urut berikutnya
        $next_unique_number = sprintf('%03d', $last_number + 1);

        // Format nomor invoice baru
        return "{$next_unique_number}/TKB/{$romawiBulan}/{$year}";
    }

    private function convertToRoman($number)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$number] ?? '';
    }

    public function getUpdatedInvoiceNumber(Request $request)
    {
        $tanggal = $request->input('tanggal'); // Tanggal yang dipilih user
        $newInvoice = $this->generateNoInvoice($tanggal);

        return response()->json(['no_invoice' => $newInvoice]);
    }

    public function storeInvoiceTokabe(Request $request)
{
        if ($request->has('item_id')) {
            $request->validate([
                'item_id' => ['required', 'array', 'min:1'],
                'item_id.*' => ['required', 'integer', 'exists:items,id'],
            ]);
        }

        // --- 1. VALIDASI & FILTERING STOK MULTI-MATERIAL ---
    if (!empty($request->barang_id)) {
        foreach ($request->barang_id as $key => $value) {
            $barangObj = Barang::find($value);
            // Jika is_material_required == 1 (ON), berarti barang Non-Material (tidak pakai material)
            if ($barangObj && $barangObj->is_material_required == 1) {
                continue;
            }

            // Jika is_material_required == 0 (OFF), barang memakai material -> cek stoknya
            if (isset($request->material_id[$key]) && is_array($request->material_id[$key])) {
                foreach ($request->material_id[$key] as $matIndex => $matId) {
                    if (!empty($matId)) {
                        $material = null;
                        $qtyMat = $request->material_qty[$key][$matIndex] ?? 0;
                        
                        if ($material && $material->stok < $qtyMat) {
                            return response()->json([
                                'status' => 'error', 
                                'message' => "Stok material '{$material->jenis_material}' tidak mencukupi. Sisa: {$material->stok}"
                            ], 400);
                        }
                    }
                }
            }
        }
    }
    // --- END VALIDASI ---

    $invoice = is_numeric($request->inv)
        ? Invoice::find($request->inv)
        : (Invoice::where('nama_invoice', $request->inv)->first() ?? Invoice::where('nama_invoice', 'LIKE', '%TKB%')->first() ?? Invoice::first());
    $nmr_inv = $this->generateNoInvoice($request->tgl_jual);

    $status = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 'Lunas' : 'Belum Lunas';
    $tanggalPenjualan = $request->tgl_jual . ":" . $request->jam;
    $item = count($request->input('item_id', $request->input('barang_id', [])));
    $sisa = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 0 : $request->sisa_pem;
    
    // PEMBERSIHAN ANGKA MENGGUNAKAN PREG_REPLACE (Lebih kuat dari str_replace)
    $tot_harga = intval(preg_replace('/[^0-9]/', '', $request->tot_harga ?? '0'));
    $tot_pem = intval(preg_replace('/[^0-9]/', '', $request->tot_pem ?? '0'));
    $sisa_pem = intval(preg_replace('/[^0-9]/', '', $sisa ?? '0'));
    $dp = intval(preg_replace('/[^0-9]/', '', $request->dp ?? '0'));

    $norek = $request->norek ?: 'TKB';

    $existingPenjualan = PenjualanTokabe::where('nomor_invoice', $nmr_inv)
        ->where('customer', $request->pelanggan)
        ->first();

    // Logika Pengambilan PPN & PPH (dan potongan lama jika ada)
    $ppnVal = null;
    $kodePpn = null;
    $pphVal = null;

    if ($request->has('ppn') || $request->has('pph')) {
        $rawPpn = intval(preg_replace('/[^0-9]/', '', $request->input('ppn') ?? '0'));
        if ($rawPpn > 0) {
            $ppnVal = $rawPpn;
            $kodePpn = $request->input('kode_ppn') ?? '01';
        }
        $rawPph = intval(preg_replace('/[^0-9]/', '', $request->input('pph') ?? '0'));
        if ($rawPph > 0) {
            $pphVal = $rawPph;
        }
    } else {
        $jenisPotongan = $request->select_potongan ?? $request->input('select-potongan');
        $rawPotongan = $request->ptg ?? ($request->biaya_lain ?? $request->input('ptg'));
        $nilai_potongan = intval(preg_replace('/[^0-9]/', '', $rawPotongan ?? '0'));
        if ($jenisPotongan == 'PPN' && $nilai_potongan > 0) {
            $ppnVal = $nilai_potongan;
            $kodePpn = $request->input('kode_ppn') ?? '01';
        } else if ($jenisPotongan == 'PPH' && $nilai_potongan > 0) {
            $pphVal = $nilai_potongan;
        }
    }

    $nominalPpn = $ppnVal ? round($tot_harga * ($ppnVal / 100)) : 0;
    $nominalPph = $pphVal ? round($tot_harga * ($pphVal / 100)) : 0;
    $expectedPem = max(0, $tot_harga + $nominalPpn - $nominalPph);

    if ($tot_pem <= 0 || abs($tot_pem - $expectedPem) > 5) {
        $tot_pem = $expectedPem;
    }

    if ($status == 'Lunas') {
        $sisa_pem = 0;
    } else {
        $sisa_pem = max(0, $tot_pem - $dp);
    }

    if ($existingPenjualan) {
        // Update Data
        $existingPenjualan->tgl_penjualan = $tanggalPenjualan;
        $existingPenjualan->perusahaan = $request->perusahaan;
        $existingPenjualan->no_telepon = $request->tlp;
        $existingPenjualan->admin = $request->adm;
        $existingPenjualan->order_by = $request->order;
        $existingPenjualan->nama_sales = $request->sales;
        $existingPenjualan->tgl_selesai = $request->tgl_selesai;
        $existingPenjualan->jumlah_item = $item;
        $existingPenjualan->dp = $dp;
        $existingPenjualan->jenis_pembayaran = $request->jns_pem;
        $existingPenjualan->no_rek = $norek;
        $existingPenjualan->total_harga = $tot_harga;
        $existingPenjualan->status = $status;
        
        $existingPenjualan->ppn = $ppnVal;
        $existingPenjualan->kode_ppn = $kodePpn;
        $existingPenjualan->pph = $pphVal;
        $existingPenjualan->diskon = null;
        $existingPenjualan->potongan = null;

        $existingPenjualan->total_pembayaran = $tot_pem;
        $existingPenjualan->sisa_pembayaran = $sisa_pem;
        $existingPenjualan->save();

        foreach ($request->input('barang_id', []) as $key => $value) {
            $barang = Barang::find($value);
            if ($barang != null) {
                $existingPenjualanBarang = PenjualanJasaTokabe::where('penjualan_id', $existingPenjualan->id)
                    ->where('barang_id', $barang->id)
                    ->first();

                if ($barang && $barang->is_material_required == 1) {
                    $matIds = [];
                    $matQtys = [];
                    $matPanjangs = [];
                    $matLebars = [];
                } else {
                    }

                $data_brg = [
                    'deskripsi_item' => $request->deskripsi_item[$key] ?? '',
                    'qty' => $request->qty[$key] ?? 0,
                    'satuan' => !empty($request->satuan[$key]) ? $request->satuan[$key] : 'Pcs',
                    'harga' => intval(preg_replace('/[^0-9]/', '', $request->hrg[$key] ?? '0')),
                    'jumlah_harga' => intval(preg_replace('/[^0-9]/', '', $request->jlh_hrg[$key] ?? '0')),
                    'material_id' => !empty($matIds) ? json_encode(array_values($matIds)) : null,
                    'material_qty' => !empty($matQtys) ? json_encode(array_values($matQtys)) : null,
                    'material_panjang' => !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null,
                    'material_lebar' => !empty($matLebars) ? json_encode(array_values($matLebars)) : null,
                ];

                if ($existingPenjualanBarang) {
                    $existingPenjualanBarang->update($data_brg);
                } else {
                    $data_brg['barang_id'] = $barang->id;
                    $data_brg['penjualan_id'] = $existingPenjualan->id;
                    PenjualanJasaTokabe::create($data_brg);
                }

                if (!$existingPenjualanBarang) {
                    $barang->decrement('stok', $request->qty[$key] ?? 0);
                    if (!empty($matIds)) {
                        foreach (array_values($matIds) as $mIndex => $mId) {
                            $material = null;
                            $mQty = array_values($matQtys)[$mIndex] ?? 0;
                            if ($material) $material->decrement('stok', $mQty);
                        }
                    }
                }
            }
        }
        $this->saveCatalogInvoiceItems($request, $existingPenjualan);
    } else {
        // Create Baru
        $penjualan = new PenjualanTokabe();
        $penjualan->invoice = $invoice ? $invoice->id : 5;
        $penjualan->nomor_invoice = $nmr_inv;
        $penjualan->tgl_penjualan = $tanggalPenjualan;
        $penjualan->customer = $request->pelanggan;
        $penjualan->perusahaan = $request->perusahaan;
        $penjualan->no_telepon = $request->tlp;
        $penjualan->admin = $request->adm;
        $penjualan->order_by = $request->order;
        $penjualan->nama_sales = $request->sales;
        $penjualan->tgl_selesai = $request->tgl_selesai;
        $penjualan->jumlah_item = $item;
        $penjualan->dp = $dp;
        $penjualan->jenis_pembayaran = $request->jns_pem;
        $penjualan->no_rek = $norek;
        $penjualan->total_harga = $tot_harga;
        $penjualan->status = $status;

        $penjualan->ppn = $ppnVal;
        $penjualan->kode_ppn = $kodePpn;
        $penjualan->pph = $pphVal;
        $penjualan->diskon = null;
        $penjualan->potongan = null;

        $penjualan->total_pembayaran = $tot_pem;
        $penjualan->sisa_pembayaran = $sisa_pem;
        $penjualan->save();

        foreach ($request->input('barang_id', []) as $key => $value) {
            $barang = Barang::find($value);
            if ($barang != null) {
                if ($barang && $barang->is_material_required == 1) {
                    $matIds = [];
                    $matQtys = [];
                    $matPanjangs = [];
                    $matLebars = [];
                } else {
                    }
                
                $penjualan_brg = [
                    'barang_id' => $barang->id,
                    'penjualan_id' => $penjualan->id,
                    'deskripsi_item' => $request->deskripsi_item[$key] ?? '',
                    'qty' => $request->qty[$key] ?? 0,
                    'satuan' => !empty($request->satuan[$key]) ? $request->satuan[$key] : 'Pcs',
                    'harga' => intval(preg_replace('/[^0-9]/', '', $request->hrg[$key] ?? '0')),
                    'jumlah_harga' => intval(preg_replace('/[^0-9]/', '', $request->jlh_hrg[$key] ?? '0')),
                    'material_id' => !empty($matIds) ? json_encode(array_values($matIds)) : null,
                    'material_qty' => !empty($matQtys) ? json_encode(array_values($matQtys)) : null,
                    'material_panjang' => !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null,
                    'material_lebar' => !empty($matLebars) ? json_encode(array_values($matLebars)) : null,
                ];
                PenjualanJasaTokabe::create($penjualan_brg);
                $barang->decrement('stok', $request->qty[$key] ?? 0);
            }
        }
        $this->saveCatalogInvoiceItems($request, $penjualan);
    }

    return response()->json(['status' => 'success', 'message' => 'Data Sudah Berhasil Disimpan']);
}

    private function saveCatalogInvoiceItems(Request $request, PenjualanTokabe $penjualan): void
    {
        PenjualanJasaTokabe::where('penjualan_id', $penjualan->id)
            ->whereNotNull('item_id')
            ->delete();

        foreach ($request->input('item_id', []) as $key => $itemId) {
            $catalogItem = Item::findOrFail($itemId);

            PenjualanJasaTokabe::create([
                'barang_id' => null,
                'item_id' => $catalogItem->id,
                'penjualan_id' => $penjualan->id,
                'deskripsi_item' => $request->input("deskripsi_item.$key", ''),
                'qty' => $request->input("qty.$key", 0),
                'satuan' => $request->input("satuan.$key") ?: 'Pcs',
                'harga' => intval(preg_replace('/[^0-9]/', '', $request->input("hrg.$key", '0'))),
                'jumlah_harga' => intval(preg_replace('/[^0-9]/', '', $request->input("jlh_hrg.$key", '0'))),
            ]);
        }
    }

    public function delete($id)
    {
        $penjualan = PenjualanTokabe::find($id);
        
        if (!$penjualan) {
            Alert::error('Data tidak ditemukan');
            return redirect('list-invoice/tokabe');
        }

        $pjBarang = PenjualanJasaTokabe::where('penjualan_id', $penjualan->id)->get();

        // Mengembalikan stok barang yang terkait jika belum dibatalkan sebelumnya
        if ($penjualan->status !== 'Batal') {
            foreach ($pjBarang as $barang) {
                $barangObj = Barang::find($barang->barang_id);
                if ($barangObj) {
                    $barangObj->stok += $barang->qty;
                    $barangObj->save();
                }
            }
        }

        // Menghapus penjualan dan barang penjualan terkait
        $penjualan->penjualanBarang()->delete();
        $penjualan->delete();
        Alert::success('Data Berhasil dihapus');
        return redirect('list-invoice/tokabe');
    }

    public function editInvoice($id)
    {
        $inv = PenjualanTokabe::find($id);
        $invoice = Invoice::find($inv->invoice);
        $admin_now = User::find($inv->admin);
        $admin = User::whereIn('role', ['Admin', 'Pemilik'])->get();
        $order = Order::all();
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $jenisBarang = Barang::with('kategori_item')->where('is_active', 1)->get();
        $items = Item::orderBy('nama_item')->get();
        $jam = substr($inv->tgl_penjualan, 11, 5);
        $nomor_unik = $inv->nomor_invoice;
        $perusahaan = $inv->perusahaan;
        $nama_adm = $admin_now->nama;
        $sales = $inv->nama_sales;
        
        if (!$perusahaan) {
            $perusahaan  = '-';
        }
        if (!$sales) {
            $sales = '-';
        }
        
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
            
            // Jembatan ALIAS: Kita mengalihkan variabel `harga` menjadi `hargaBarang`.
            // Hal ini memastikan Form Edit yang menggunakan {{$jual->hargaBarang}} tetap dapat terisi nilainya
            // meskipun di database Tokabe nama kolom sebenarnya adalah `harga`.
            $item->hargaBarang = $item->harga;
        }

        try {
            $rekenings = Rekening::all();
        } catch (\Throwable $e) {
            $rekenings = collect();
        }
        // Material untuk form edit
        return view('pages.invoices.tokabe.edit-invoicesTokabe', compact('inv', 'jam', 'nomor_unik', 'perusahaan', 'admin', 'order', 'sales', 'jenisBarang', 'items', 'nama_adm', 'barang', 'invoice', 'penjualan_barang', 'rekenings'));
    }

    public function updateInvoice(Request $request, $id)
    {
        // 1. VALIDASI INPUT TERLEBIH DAHULU (Penting agar data tidak hilang!)
        $request->validate([
            'item_id.*' => ['nullable', 'integer', 'exists:items,id'],
        ]);
        $selectedItems = $request->input('item_id', []) + $request->input('barang_id', []);
        if (!empty($selectedItems)) {
            foreach ($selectedItems as $key => $value) {
                // Mengambil nilai string harga, jika kosong kita set menjadi string kosong ''
                $hrg = $request->hrg[$key] ?? '';
                // Validasi basic: barang, qty, satuan, dan harga harus ada.
                if (empty($value) || empty($request->qty[$key]) || empty($request->satuan[$key]) || $hrg === '') {
                    Alert::error('Error', 'Harap lengkapi semua kolom wajib pada item (Barang, Qty, Satuan, Harga).');
                    return redirect()->back()->withInput();
                }
            }
        }
        
        $penjualan = PenjualanTokabe::find($id);
        $nmr_inv = $request->kode . $request->kodeUnik;

        $status = ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') ? 'Lunas' : 'Belum Lunas';
        $tanggalPenjualan = $request->tgl_jual . ":" . $request->jam;
        $item = count($selectedItems);

        $tot_harga = intval(str_replace(',', '', $request->tot_harga));
        $tot_pem = intval(str_replace(',', '', $request->tot_pem));
        $dp = intval(str_replace(',', '', $request->dp));
        
        $sisa_pemb = $request->sisa_pem;

        if ($request->jns_pem == 'Cash Lunas' || $request->jns_pem == 'Transfer Lunas') {
            $sisa_pemb =  0;
        }
        $sisa_pem = intval(str_replace(',', '', $sisa_pemb));

        if(!$request->norek){
            $norek = 'TKB';
        } else {
            $norek = $request->norek;
        }

        // 2. KEMBALIKAN (RESTORE) STOK BARANG SEBELUMNYA (HANYA JIKA BELUM BATAL)
        $pjBarang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();

        if ($penjualan->status !== 'Batal') {
            foreach ($pjBarang as $barang) {
                $barangObj = Barang::find($barang->barang_id);
                if ($barangObj) {
                    $barangObj->stok += $barang->qty;
                    $barangObj->save();
                }
            }
        }
        
        // HAPUS ITEM LAMA (Sekarang aman karena sudah divalidasi di atas)
        $penjualan->penjualanBarang()->delete();

        // Logika Pengambilan PPN & PPH (dan potongan lama jika ada)
        $ppnVal = null;
        $kodePpn = null;
        $pphVal = null;

        if ($request->has('ppn') || $request->has('pph')) {
            $rawPpn = intval(preg_replace('/[^0-9]/', '', $request->input('ppn') ?? '0'));
            if ($rawPpn > 0) {
                $ppnVal = $rawPpn;
                $kodePpn = $request->input('kode_ppn') ?? '01';
            }
            $rawPph = intval(preg_replace('/[^0-9]/', '', $request->input('pph') ?? '0'));
            if ($rawPph > 0) {
                $pphVal = $rawPph;
            }
        } else {
            $jenisPotongan = $request->select_potongan ?? $request->input('select-potongan');
            $rawPotongan = $request->biaya_lain ?? ($request->ptg ?? $request->input('biaya_lain'));
            $nilai_potongan = intval(preg_replace('/[^0-9]/', '', $rawPotongan ?? '0'));
            if ($jenisPotongan == 'PPN' && $nilai_potongan > 0) {
                $ppnVal = $nilai_potongan;
                $kodePpn = $request->input('kode_ppn') ?? '01';
            } else if ($jenisPotongan == 'PPH' && $nilai_potongan > 0) {
                $pphVal = $nilai_potongan;
            }
        }

        $nominalPpn = $ppnVal ? round($tot_harga * ($ppnVal / 100)) : 0;
        $nominalPph = $pphVal ? round($tot_harga * ($pphVal / 100)) : 0;
        $expectedPem = max(0, $tot_harga + $nominalPpn - $nominalPph);

        if ($tot_pem <= 0 || abs($tot_pem - $expectedPem) > 5) {
            $tot_pem = $expectedPem;
        }

        if ($status == 'Lunas') {
            $sisa_pem = 0;
        } else {
            $sisa_pem = max(0, $tot_pem - $dp);
        }

        // 3. SIMPAN PENJUALAN
        $penjualan->invoice = $request->inv;
        $penjualan->nomor_invoice = $nmr_inv;
        $penjualan->tgl_penjualan = $tanggalPenjualan;
        $penjualan->customer = $request->pelanggan;
        $penjualan->perusahaan = $request->perusahaan;
        $penjualan->no_telepon = $request->tlp;
        $penjualan->admin = $request->adm;
        $penjualan->order_by = $request->order;
        $penjualan->nama_sales = $request->sales;
        $penjualan->tgl_selesai = $request->tgl_selesai;
        $penjualan->jumlah_item = $item;
        $penjualan->dp = $dp;
        $penjualan->jenis_pembayaran = $request->jns_pem;
        $penjualan->no_rek = $norek;
        $penjualan->total_harga = $tot_harga;
        $penjualan->status = $status;
        
        $penjualan->ppn = $ppnVal;
        $penjualan->kode_ppn = $kodePpn;
        $penjualan->pph = $pphVal;
        $penjualan->diskon = null;
        $penjualan->potongan = null;
        
        $penjualan->total_pembayaran = $tot_pem;
        $penjualan->sisa_pembayaran = $sisa_pem;
        $penjualan->save();
        
        // 4. TAMBAHKAN ITEM BARU & POTONG STOK
        if (!empty($request->barang_id)) {
            foreach ($request->barang_id as $key => $value) {
                $hrg = $request->hrg[$key] ?? 0;
                
                $barang = Barang::find($value);
                if ($barang && $barang->is_material_required == 1) {
                    $matIds = [];
                    $matQtys = [];
                    $matPanjangs = [];
                    $matLebars = [];
                } else {
                    }
                
                $itemData = new PenjualanJasaTokabe();
                $itemData->barang_id = $value;
                $itemData->penjualan_id = $penjualan->id;
                $itemData->deskripsi_item = $request->deskripsi_item[$key] ?? null;
                $itemData->qty = $request->qty[$key];
                $itemData->satuan = $request->satuan[$key];
                $itemData->harga = intval(str_replace(',', '', $hrg));
                $itemData->jumlah_harga = intval(str_replace(',', '', $request->jlh_hrg[$key] ?? '0'));
                
                $itemData->save();

                // Potong Stok Barang Biasa
                $barang = Barang::find($itemData->barang_id);
                if ($barang) {
                    $barang->stok -= $itemData->qty;
                    $barang->save();
                }
                
                if (!empty($matIds)) {
                    foreach (array_values($matIds) as $mIndex => $mId) {
                        $material = null;
                        $mQty = array_values($matQtys)[$mIndex] ?? 0;
                        if ($material) {
                            $material->decrement('stok', $mQty);
                        }
                    }
                }
            }
        }
        foreach ($request->input('item_id', []) as $key => $value) {
            $catalogItem = Item::findOrFail($value);
            $itemData = new PenjualanJasaTokabe();
            $itemData->item_id = $catalogItem->id;
            $itemData->barang_id = null;
            $itemData->penjualan_id = $penjualan->id;
            $itemData->deskripsi_item = $request->input("deskripsi_item.$key");
            $itemData->qty = $request->input("qty.$key");
            $itemData->satuan = $request->input("satuan.$key");
            $itemData->harga = intval(str_replace(',', '', $request->input("hrg.$key", '0')));
            $itemData->jumlah_harga = intval(str_replace(',', '', $request->input("jlh_hrg.$key", '0')));
            $itemData->save();
        }
        
        $penjualan->approval = 'Lock';
        $penjualan->approved_at = null;
        $penjualan->save();
        
        Alert::success('Data Invoice Berhasil diUpdate');
        return redirect('list-invoice/tokabe');
    }
    
    function ubahStatus($status, $id)
    {
        $invoice = PenjualanTokabe::find($id);
        if (!$invoice) {
            return back()->with(['error' => 'Data tidak ditemukan!']);
        }
        if ($status === 'batal') {
            $penjBarang = PenjualanJasaTokabe::where('penjualan_id', $invoice->id)->get();

            foreach ($penjBarang as $barang) {
                $barangObj = Barang::find($barang->barang_id);
                if ($barangObj) {
                    $barangObj->stok += $barang->qty;
                    $barangObj->save();
                }

                if ($barang->material_id && $barang->material_qty) {
                    $matIds = json_decode($barang->material_id, true);
                    $matQtys = json_decode($barang->material_qty, true);

                    if (is_array($matIds)) {
                        foreach ($matIds as $index => $matId) {
                            $materialObj = null;
                            if ($materialObj) {
                                $materialObj->stok += (float) ($matQtys[$index] ?? 0);
                                $materialObj->save();
                            }
                        }
                    } else {
                        // Fallback Format Lama
                        $materialObj = null;
                        if ($materialObj) {
                            $materialObj->stok += (float) $barang->material_qty;
                            $materialObj->save();
                        }
                    }
                }
            }
            $invoice->status = 'Batal';
            $invoice->save();

            return back()->with(['batal' => 'Invoice telah dibatalkan']);
        } else {
            $invoice->status = 'Lunas';
            $invoice->sisa_pembayaran = 0;
            $invoice->save();
            return back()->with(['lunas' => 'Invoice sudah Lunas']);
        }
    }
    
    public function unlock($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Unlock';
        $inv->approved_at = now();
        $inv->save();
        Alert::success('Invoice Tokabe berhasil di-Unlock');
        return redirect()->route('list_invoice_tokabe');
    }

    public function lock($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Lock';
        $inv->approved_at = null;
        $inv->save();
        Alert::success('Invoice Tokabe berhasil di-Lock');
        return redirect()->back();
    }

    public function approvalPage($id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
        }
        return view('pages.invoices.tokabe.approval', compact('invoice', 'penjualan_barang', 'barang'));
    }

    public function pelunasanPage($id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $invoice->formatted_sisa_pembayaran = number_format($invoice->sisa_pembayaran, 0, ',', '.');
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
        }
        return view('pages.invoices.tokabe.pelunasan', compact('invoice', 'penjualan_barang', 'barang'));
    }

    public function statusLunas($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        if ($inv->status === 'Batal') {
            Alert::error('Gagal', 'Invoice yang sudah dibatalkan tidak dapat diubah menjadi Lunas.');
            return redirect()->back();
        }
        $inv->status = 'Lunas';
        $inv->sisa_pembayaran = 0;
        $inv->save();
        Alert::success('Invoice Tokabe Sudah Lunas');
        return redirect()->route('list_invoice_tokabe');
    }

    public function storeStatusBatal(Request $request, $id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        if ($invoice->status !== 'Batal') {
            $penjBarang = PenjualanJasaTokabe::where('penjualan_id', $invoice->id)->get();
            foreach ($penjBarang as $barang) {
                $barangObj = Barang::find($barang->barang_id);
                if ($barangObj) {
                    $barangObj->stok += $barang->qty;
                    $barangObj->save();
                }
            }
        }
        $invoice->status = 'Batal';
        $invoice->alasan_batal = $request->alasan_batal;
        $invoice->save();
        return redirect()->back()->with('batal', 'Invoice Tokabe berhasil dibatalkan.');
    }

    public function cetakInvoiceTKB(Request $request, $id)
    {
        $type = strtolower($request->query('type', 'asli'));
        if (!in_array($type, ['asli', 'copy'])) {
            $type = 'asli';
        }
        $penjualan = PenjualanTokabe::where('id', $id)->first();
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        
        if ($penjualan->status == 'Lunas' && $penjualan->jenis_pembayaran != 'Cash Lunas' && $penjualan->jenis_pembayaran != 'Transfer Lunas') {
            $sisaBayarMod = 0 . '(Lunas)';
        } else {
            $sisaBayarMod = number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        }
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');

        $tanggalString = Carbon::parse($tanggal)->toDateString();
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

        $toko="Total Karya Berkah";
        
        $norek = $this->resolveNorek($penjualan->no_rek);
        
        if ($penjualan->dp == null) {
            $dp = 0;
        } else {
            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != null || $penjualan->ppn != null || $penjualan->pph != null) {
            if ($penjualan->diskon || $penjualan->ppn || $penjualan->pph) {
                $pengali = $penjualan->diskon ?: ($penjualan->ppn ?: $penjualan->pph);
                
                // Jika Diskon, nilainya minus di tampilan harga
                if ($penjualan->diskon) {
                    $biayaLain = number_format($pengali * -$penjualan->total_harga / 100, 0, ',', '.');
                } else {
                    $biayaLain = number_format($pengali * $penjualan->total_harga / 100, 0, ',', '.');
                }
                $persen = $pengali;
            } else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
            $persen = null;
        }

        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
            $hargaMod[] = number_format($item->harga, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }

        return view('pages.invoices.tokabe.downloadTKB', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'toko', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen', 'type'));
    }

    public function viewDownloadInvoiceTKB($id)
    {
        $penjualan = PenjualanTokabe::find($id);
        $invoice = Invoice::where('id', $penjualan->invoice)->first();
        $namaInvoice = $invoice->nama_invoice;
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $admin_now = User::where('id', $penjualan->admin)->first();
        $barang = [];
        $hargaMod = [];
        $jumlahHarga = [];
        $totHargaMod = number_format($penjualan->total_pembayaran, 0, ',', '.');
        if ($penjualan->status == 'Lunas' && $penjualan->jenis_pembayaran != 'Cash Lunas' && $penjualan->jenis_pembayaran != 'Transfer Lunas') {
            $sisaBayarMod = 0 . '(Lunas)';
        } else {
            $sisaBayarMod = number_format($penjualan->sisa_pembayaran, 0, ',', '.');
        }
        $admin = $admin_now->nama;
        $tanggal =  $penjualan->tgl_penjualan;
        Carbon::setLocale('id');
        $persen = '';
        
        $tanggalString = Carbon::parse($tanggal)->toDateString();
        $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

        $norek = $this->resolveNorek($penjualan->no_rek);
        
        if ($penjualan->dp == null) {
            $dp = 0;
        } else {
            $dp = number_format($penjualan->dp, 0, ',', '.');
        }

        if ($penjualan->diskon != null || $penjualan->potongan != 0 || $penjualan->ppn != null || $penjualan->pph != null) {
            if ($penjualan->diskon || $penjualan->ppn || $penjualan->pph) {
                $pengali = $penjualan->diskon ?: ($penjualan->ppn ?: $penjualan->pph);
                $potongan = $pengali / 100 * $penjualan->total_harga;
                $biayaLain = 'Rp.' . number_format($potongan, 0, ',', '.');
            } else {
                $biayaLain = 'Rp.' . number_format($penjualan->potongan, 0, ',', '.');
            }
        } else {
            $biayaLain = 0;
        }

        if ($penjualan->diskon) {
            $persen = number_format($penjualan->diskon, 0, ',', '.') . '%';
        } else if ($penjualan->ppn) {
            $persen = number_format($penjualan->ppn, 0, ',', '.') . '%';
        } else if ($penjualan->pph) {
            $persen = number_format($penjualan->pph, 0, ',', '.') . '%';
        }
        
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
            $hargaMod[] = number_format($item->harga, 0, ',', '.');
            $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
        }
        return view('pages.invoices.tokabe.downloadTKB', compact('invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 'sisaBayarMod', 'formatTanggal', 'admin', 'persen', 'namaInvoice'));
    }


    
    
    public function publicDownloadTokabe(Request $request, $id)
{
    // 1. CEK KEAMANAN LINK (Signature)
    if (! $request->hasValidSignature()) {
        abort(403, 'Maaf, Link download ini sudah kadaluarsa atau tidak valid.');
    }

    // 2. LOGIKA DATA
    $penjualan = PenjualanTokabe::find($id);
    
    if (!$penjualan) {
        abort(404);
    }

    $invoice = Invoice::where('id', $penjualan->invoice)->first();
    $namaInvoice = $invoice->nama_invoice;
    $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
    $admin_now = User::where('id', $penjualan->admin)->first();
    
    $barang = [];
    $hargaMod = [];
    $jumlahHarga = [];
    
    // --- LOGIKA BIAYA LAIN & TOTAL HARGA ---
    $nominalBiayaLain = 0;
    $persen = '';

    if ($penjualan->diskon || $penjualan->ppn || $penjualan->pph) {
        $pengali = $penjualan->diskon ?: ($penjualan->ppn ?: $penjualan->pph);
        $nominalBiayaLain = ($pengali / 100) * $penjualan->total_harga;
        $biayaLain = 'Rp.' . number_format($nominalBiayaLain, 0, ',', '.');
        $persen = number_format($pengali, 0, ',', '.') . '%';
    } else if ($penjualan->potongan && $penjualan->potongan != 0) {
        $nominalBiayaLain = $penjualan->potongan;
        $biayaLain = 'Rp.' . number_format($nominalBiayaLain, 0, ',', '.');
    } else {
        $biayaLain = 0;
    }

    // Update total harga dengan menambahkan biaya lain
    $totalPlusBiaya = $penjualan->total_harga + $nominalBiayaLain;
    $totHargaMod = number_format($totalPlusBiaya, 0, ',', '.');
    // ---------------------------------------

    if ($penjualan->status == 'Lunas' && !in_array($penjualan->jenis_pembayaran, ['Cash Lunas', 'Transfer Lunas'])) {
        $sisaBayarMod = 0 . '(Lunas)';
    } else {
        $sisaBayarMod = number_format($penjualan->sisa_pembayaran, 0, ',', '.');
    }

    $admin = $admin_now ? $admin_now->nama : 'Admin';
    $tanggal = $penjualan->tgl_penjualan;
    Carbon::setLocale('id');
    
    $tanggalString = Carbon::parse($tanggal)->toDateString();
    $formatTanggal = Carbon::parse($tanggalString)->isoFormat('DD MMMM YYYY');

    $norek = $this->resolveNorek($penjualan->no_rek);
    
    $dp = ($penjualan->dp == null) ? 0 : number_format($penjualan->dp, 0, ',', '.');

    foreach ($penjualan_barang as $item) {
        $barang[] = $this->productForInvoiceLine($item);
        $hargaMod[] = number_format($item->harga, 0, ',', '.');
        $jumlahHarga[] = number_format($item->jumlah_harga, 0, ',', '.');
    }
    
    return view('pages.invoices.tokabe.public-download-tkb', compact(
        'invoice', 'penjualan', 'barang', 'penjualan_barang', 'hargaMod', 
        'jumlahHarga', 'norek', 'totHargaMod', 'dp', 'biayaLain', 
        'sisaBayarMod', 'formatTanggal', 'admin', 'persen', 'namaInvoice'
    ));
}

    private function productForInvoiceLine(PenjualanJasaTokabe $line): \Illuminate\Support\Collection
    {
        $product = $line->item ?? $line->barang;

        return $product ? collect([$product]) : collect();
    }

    private function resolveNorek($no_rek)
    {
        if (!empty($no_rek)) {
            if (is_numeric($no_rek)) {
                $rek = Rekening::find($no_rek);
                if ($rek) return $rek->formatted;
            } else {
                $rek = Rekening::where('nama_bank', $no_rek)->orWhere('atas_nama', 'LIKE', "%{$no_rek}%")->first();
                if ($rek) return $rek->formatted;
            }
        }

        if ($no_rek == "BNI") {
            return "BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999";
        } elseif ($no_rek == "TKBBNI") {
            return "BNI | A/N : PT. Total Karya Berkah | No. Rek : 3528289999";
        }

        $activeRek = Rekening::where('status', 'Aktif')->first();
        if ($activeRek) {
            return $activeRek->formatted;
        }

        return "BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999";
    }
}