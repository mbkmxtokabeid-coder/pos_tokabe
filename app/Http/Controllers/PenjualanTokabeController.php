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
use App\Models\Material;
use App\Models\InvoiceApprovalRequest;
use Carbon\Carbon;
use DateTimezone;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        // 1-3. KONSOLIDASI STATISTIK KARTU (Cached 30s for fast page load)
        // =========================================================================
        $cardStats = Cache::remember('tkb_invoice_card_stats_' . $yearNow . '_' . $monthNow, 30, function () use ($yearNow, $monthNow) {
            $nonPoStats = DB::table('penjualan_tokabe')
                ->whereNull('deleted_at')
                ->where(function($q) {
                    $q->where('is_custom', 0)->orWhereNull('is_custom');
                })
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

            $poStats = DB::table('penjualan_tokabe')
                ->whereNull('deleted_at')
                ->where(function($q) {
                    $q->where('is_custom', 0)->orWhereNull('is_custom');
                })
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

            $generalStats = DB::table('penjualan_tokabe')
                ->whereNull('deleted_at')
                ->where(function($q) {
                    $q->where('is_custom', 0)->orWhereNull('is_custom');
                })
                ->selectRaw("
                    COUNT(CASE WHEN status = 'Batal' AND YEAR(tgl_penjualan) = ? THEN 1 END) as batal,
                    COALESCE(SUM(CASE WHEN status = 'Batal' AND YEAR(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as jumlah_batal,
                    COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as total_per_bulan,
                    COALESCE(SUM(total_pembayaran), 0) as jumlah_invoice
                ", [$yearNow, $yearNow, $yearNow, $monthNow])
                ->first();

            return [
                'jlhInvoice' => $nonPoStats->jlh_invoice ?? 0,
                'totalPerTahun' => $nonPoStats->total_per_tahun ?? 0,
                'jumlahPerTahun' => $nonPoStats->total_per_tahun ?? 0,
                'lunas' => $nonPoStats->lunas ?? 0,
                'totalLunasThn' => ($nonPoStats->total_lunas ?? 0) + ($nonPoStats->tot_dp_tahun ?? 0),
                'belumLunas' => $nonPoStats->belum_lunas ?? 0,
                'jumlahBBPerTahun' => $nonPoStats->jumlah_bb_per_tahun ?? 0,
                'belumLunasMonth' => $nonPoStats->belum_lunas_month ?? 0,
                'jumlahBBPerBulan' => $nonPoStats->jumlah_bb_per_bulan ?? 0,
                'jumlahPO' => $poStats->jumlah_po ?? 0,
                'countAllInvPO' => $poStats->count_all_inv_po ?? 0,
                'jumlahPOLunas' => $poStats->jumlah_po_lunas ?? 0,
                'countAllSettledPO' => $poStats->count_all_settled_po ?? 0,
                'jumlahPOBlmLunas' => $poStats->jumlah_po_blm_lunas ?? 0,
                'countAllOutstandingPO' => $poStats->count_all_outstanding_po ?? 0,
                'jumlahBBPerBulanPO' => $poStats->jumlah_bb_per_bulan_po ?? 0,
                'blmLunasPO' => $poStats->blm_lunas_po ?? 0,
                'batal' => $generalStats->batal ?? 0,
                'jumlahBatal' => $generalStats->jumlah_batal ?? 0,
                'totalPerBulan' => $generalStats->total_per_bulan ?? 0,
                'jumlahPerBulan' => $generalStats->total_per_bulan ?? 0,
                'jumlahInvoice' => $generalStats->jumlah_invoice ?? 0,
            ];
        });

        extract($cardStats);

        // =========================================================================
        // 4. DATA INVOICE TOKABE (Eager load user & penjualanBarang cegah N+1)
        // =========================================================================
        $penjualan = PenjualanTokabe::with(['user:id,nama', 'penjualanBarang:id,penjualan_id,deskripsi_item'])
            ->where(function($q) {
                $q->where('is_custom', 0)->orWhereNull('is_custom');
            })
            ->orderByDesc('tgl_penjualan')
            ->get();

        // Perhitungan PPN & PPh Tahun Ini (Query kolom relevan saja)
        $dataPajak = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->where(function($q) {
                $q->where('is_custom', 0)->orWhereNull('is_custom');
            })
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
            ->where(function($q) {
                $q->where('is_custom', 0)->orWhereNull('is_custom');
            })
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

        $query = PenjualanTokabe::query()->where(function($q) {
            $q->where('is_custom', 0)->orWhereNull('is_custom');
        });
        $card4Query = PenjualanTokabe::query()->where(function($q) {
            $q->where('is_custom', 0)->orWhereNull('is_custom');
        });

        $periodLabel = '';
        $card4MonthName = '';

        if ($timeframe === 'today') {
            $today = $now->toDateString();
            $query->whereDate('tgl_penjualan', $today);
            $periodLabel = 'HARI INI';
            
            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F Y'));
        } elseif ($timeframe === 'week') {
            $startOfWeek = $now->copy()->startOfWeek()->format('Y-m-d 00:00:00');
            $endOfWeek = $now->copy()->endOfWeek()->format('Y-m-d 23:59:59');
            $query->whereBetween('tgl_penjualan', [$startOfWeek, $endOfWeek]);
            $periodLabel = 'MINGGU INI';

            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F Y'));
        } elseif ($timeframe === 'month') {
            $query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $periodLabel = 'BULAN ' . strtoupper($now->translatedFormat('F Y'));

            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F Y'));
        } elseif ($timeframe === 'year') {
            $query->whereYear('tgl_penjualan', $yearNow);
            $periodLabel = 'TAHUN ' . $yearNow;

            $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
            $card4MonthName = strtoupper($now->translatedFormat('F'));
        } elseif ($timeframe === 'last_year') {
            $lastYear = $yearNow - 1;
            $query->whereYear('tgl_penjualan', $lastYear);
            $periodLabel = 'TAHUN ' . $lastYear;

            $card4Query->whereYear('tgl_penjualan', $lastYear)->whereMonth('tgl_penjualan', 12);
            $card4MonthName = 'DESEMBER ' . $lastYear;
        } elseif ($timeframe === 'custom') {
            if ($mode === 'date' && $date) {
                $parsedDate = Carbon::parse($date);
                $query->whereDate('tgl_penjualan', $parsedDate->toDateString());
                $periodLabel = 'TANGGAL ' . $parsedDate->format('d/m/Y');

                $card4Query->whereYear('tgl_penjualan', $parsedDate->year)->whereMonth('tgl_penjualan', $parsedDate->month);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F Y'));
            } elseif ($mode === 'month' && $month && $year) {
                $query->whereYear('tgl_penjualan', $year)->whereMonth('tgl_penjualan', $month);
                $parsedDate = Carbon::createFromDate($year, $month, 1);
                $periodLabel = 'BULAN ' . strtoupper($parsedDate->translatedFormat('F Y'));

                $card4Query->whereYear('tgl_penjualan', $year)->whereMonth('tgl_penjualan', $month);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F Y'));
            } elseif ($mode === 'year' && $year) {
                $query->whereYear('tgl_penjualan', $year);
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
            $totalNominalPo = PenjualanTokabe::where(function($q) {
                $q->where('is_custom', 0)->orWhereNull('is_custom');
            })->where('jenis_pembayaran', 'PO')->where('status', '<>', 'Batal')->sum('total_pembayaran');
            $countInvPo = PenjualanTokabe::where(function($q) {
                $q->where('is_custom', 0)->orWhereNull('is_custom');
            })->where('jenis_pembayaran', 'PO')->where('status', '<>', 'Batal')->count();
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
            'cardHapus' => ['nominal' => '0', 'count' => 0],
            'card6' => ['nominal' => number_format($ppnTotal, 0, ',', '.'), 'count' => $ppnTotalCount],
            'card7' => ['nominal' => number_format($pphTotal, 0, ',', '.'), 'count' => $pphTotalCount],
            'card8' => ['nominal' => number_format($ppn01, 0, ',', '.'), 'count' => $ppn01Count],
            'card9' => ['nominal' => number_format($ppn02, 0, ',', '.'), 'count' => $ppn02Count],
            'card10' => ['nominal' => number_format($ppn03, 0, ',', '.'), 'count' => $ppn03Count],
            'card11' => ['nominal' => number_format($ppn04, 0, ',', '.'), 'count' => $ppn04Count],
        ]);
    }

    public function listInvoiceTokabeCustom()
    {
        // Auto-lock invoice custom jika sudah lewat batas waktu unlock (6 jam)
        PenjualanTokabe::where('is_custom', 1)
            ->where('approval', 'Unlock')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<', now()->subHours(6))
            ->update(['approval' => 'Lock', 'approved_at' => null]);

        $perusahaan = null;
        $invoices = collect();

        Carbon::setLocale('id');
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;

        $rekenings = Rekening::all()->keyBy('id');

        $cardStats = Cache::remember('tkb_custom_invoice_card_stats_' . $yearNow . '_' . $monthNow, 30, function () use ($yearNow, $monthNow) {
            $nonPoStats = DB::table('penjualan_tokabe')
                ->whereNull('deleted_at')
                ->where('is_custom', 1)
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

            $poStats = DB::table('penjualan_tokabe')
                ->whereNull('deleted_at')
                ->where('is_custom', 1)
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

            $generalStats = DB::table('penjualan_tokabe')
                ->whereNull('deleted_at')
                ->where('is_custom', 1)
                ->selectRaw("
                    COUNT(CASE WHEN status = 'Batal' AND YEAR(tgl_penjualan) = ? THEN 1 END) as batal,
                    COALESCE(SUM(CASE WHEN status = 'Batal' AND YEAR(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as jumlah_batal,
                    COALESCE(SUM(CASE WHEN YEAR(tgl_penjualan) = ? AND MONTH(tgl_penjualan) = ? THEN total_pembayaran ELSE 0 END), 0) as total_per_bulan,
                    COALESCE(SUM(total_pembayaran), 0) as jumlah_invoice
                ", [$yearNow, $yearNow, $yearNow, $monthNow])
                ->first();

            return [
                'jlhInvoice' => $nonPoStats->jlh_invoice ?? 0,
                'totalPerTahun' => $nonPoStats->total_per_tahun ?? 0,
                'jumlahPerTahun' => $nonPoStats->total_per_tahun ?? 0,
                'lunas' => $nonPoStats->lunas ?? 0,
                'totalLunasThn' => ($nonPoStats->total_lunas ?? 0) + ($nonPoStats->tot_dp_tahun ?? 0),
                'belumLunas' => $nonPoStats->belum_lunas ?? 0,
                'jumlahBBPerTahun' => $nonPoStats->jumlah_bb_per_tahun ?? 0,
                'belumLunasMonth' => $nonPoStats->belum_lunas_month ?? 0,
                'jumlahBBPerBulan' => $nonPoStats->jumlah_bb_per_bulan ?? 0,
                'jumlahPO' => $poStats->jumlah_po ?? 0,
                'countAllInvPO' => $poStats->count_all_inv_po ?? 0,
                'jumlahPOLunas' => $poStats->jumlah_po_lunas ?? 0,
                'countAllSettledPO' => $poStats->count_all_settled_po ?? 0,
                'jumlahPOBlmLunas' => $poStats->jumlah_po_blm_lunas ?? 0,
                'countAllOutstandingPO' => $poStats->count_all_outstanding_po ?? 0,
                'jumlahBBPerBulanPO' => $poStats->jumlah_bb_per_bulan_po ?? 0,
                'blmLunasPO' => $poStats->blm_lunas_po ?? 0,
                'batal' => $generalStats->batal ?? 0,
                'jumlahBatal' => $generalStats->jumlah_batal ?? 0,
                'totalPerBulan' => $generalStats->total_per_bulan ?? 0,
                'jumlahPerBulan' => $generalStats->total_per_bulan ?? 0,
                'jumlahInvoice' => $generalStats->jumlah_invoice ?? 0,
                'hapus' => 0,
                'jumlahHapus' => 0,
            ];
        });

        extract($cardStats);

        // Perhitungan PPN & PPh Tahun Ini untuk Custom Invoice
        $dataPajak = DB::table('penjualan_tokabe')
            ->whereNull('deleted_at')
            ->where('is_custom', 1)
            ->where('status', '!=', 'Batal')
            ->whereYear('tgl_penjualan', $yearNow)
            ->select('ppn', 'diskon', 'potongan', 'pph', 'total_pembayaran', 'total_harga', 'kode_ppn')
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

        $penjualan = PenjualanTokabe::with(['user:id,nama', 'penjualanBarang:id,penjualan_id,deskripsi_item'])
            ->where('is_custom', 1)
            ->orderByDesc('tgl_penjualan')
            ->get();

        foreach ($penjualan as $inv) {
            $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->format('d M Y');
            $inv->formatted_tgl_update = Carbon::parse($inv->updated_at)->addHour(7)->format('d M Y H:i');
            $inv->formatted_total_pembayaran = number_format($inv->total_pembayaran, 0, ',', ',');
            $inv->formatted_dp = number_format($inv->dp ?? 0, 0, ',', ',');
            if ($inv->dp == null) {
                $inv->dp = 0;
            }
        }

        $availableYears = DB::table('penjualan_tokabe')
            ->where('is_custom', 1)
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

        return view('pages.invoices.tokabe.daftar-invoiceTokabeCustom', compact(
            'invoices', 'perusahaan', 'penjualan', 'rekenings',
            'jlhInvoice', 'lunas', 'belumLunas', 'batal', 
            'totalLunasThn', 'jumlahBatal', 'jumlahInvoice', 'jumlahBBPerTahun', 'jumlahBBPerBulan', 
            'jumlahPerBulan', 'jumlahPerTahun', 'totalPerTahun', 'belumLunasMonth',
            'jumlahPO', 'countAllInvPO', 'jumlahPOLunas', 'countAllSettledPO', 
            'jumlahPOBlmLunas', 'countAllOutstandingPO', 'jumlahBBPerBulanPO', 'blmLunasPO',
            'hapus', 'jumlahHapus',
            'ppnTotal', 'ppnTotalCount', 'ppn01', 'ppn01Count', 'ppn02', 'ppn02Count',
            'ppn03', 'ppn03Count', 'ppn04', 'ppn04Count', 'pphTotal', 'pphTotalCount', 'availableYears'
        ));
    }

    public function getCustomCardsData(Request $request)
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

        $query = PenjualanTokabe::query()->where('is_custom', 1);
        $card4Query = PenjualanTokabe::query()->where('is_custom', 1);
        $queryDeleted = PenjualanTokabe::onlyTrashed()->where('is_custom', 1);

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
            if ($mode === 'date' && !empty($date)) {
                $parsedDate = Carbon::parse($date);
                $query->whereDate('tgl_penjualan', $parsedDate->toDateString());
                $queryDeleted->whereDate('tgl_penjualan', $parsedDate->toDateString());
                $periodLabel = 'TANGGAL ' . $parsedDate->format('d/m/Y');

                $card4Query->whereYear('tgl_penjualan', $parsedDate->year)->whereMonth('tgl_penjualan', $parsedDate->month);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F Y'));
            } elseif ($mode === 'month' && !empty($month) && !empty($year)) {
                $query->whereYear('tgl_penjualan', (int)$year)->whereMonth('tgl_penjualan', (int)$month);
                $queryDeleted->whereYear('tgl_penjualan', (int)$year)->whereMonth('tgl_penjualan', (int)$month);
                $parsedDate = Carbon::createFromDate((int)$year, (int)$month, 1);
                $periodLabel = 'BULAN ' . strtoupper($parsedDate->translatedFormat('F Y'));

                $card4Query->whereYear('tgl_penjualan', (int)$year)->whereMonth('tgl_penjualan', (int)$month);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F Y'));
            } elseif ($mode === 'year' && !empty($year)) {
                $query->whereYear('tgl_penjualan', (int)$year);
                $queryDeleted->whereYear('tgl_penjualan', (int)$year);
                $periodLabel = 'TAHUN ' . $year;

                $card4Month = ($year == $yearNow) ? $monthNow : 12;
                $card4Query->whereYear('tgl_penjualan', (int)$year)->whereMonth('tgl_penjualan', $card4Month);
                $parsedDate = Carbon::createFromDate((int)$year, $card4Month, 1);
                $card4MonthName = strtoupper($parsedDate->translatedFormat('F') . ($year != $yearNow ? ' ' . $year : ''));
            } else {
                $query->whereYear('tgl_penjualan', $yearNow);
                $queryDeleted->whereYear('tgl_penjualan', $yearNow);
                $periodLabel = 'TAHUN ' . $yearNow;

                $card4Query->whereYear('tgl_penjualan', $yearNow)->whereMonth('tgl_penjualan', $monthNow);
                $card4MonthName = strtoupper($now->translatedFormat('F'));
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
            $totalNominalPo = PenjualanTokabe::where('is_custom', 1)->where('jenis_pembayaran', 'PO')->where('status', '<>', 'Batal')->sum('total_pembayaran');
            $countInvPo = PenjualanTokabe::where('is_custom', 1)->where('jenis_pembayaran', 'PO')->where('status', '<>', 'Batal')->count();
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

        // Pajak PPN & PPh
        $dataPajak = (clone $query)->where('status', '!=', 'Batal')
            ->select('ppn', 'diskon', 'potongan', 'pph', 'total_pembayaran', 'total_harga', 'kode_ppn')
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
            'cardHapus' => ['nominal' => '0', 'count' => 0],
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
        $catalogItems = Item::orderBy('nama_item')->get();
        if ($catalogItems->isEmpty()) {
            $activeBarangs = Barang::where('is_active', 1)->get();
            foreach ($activeBarangs as $b) {
                $nama = trim($b->jenis_barang);
                if ($nama !== '') {
                    Item::firstOrCreate(
                        ['nama_item' => $nama],
                        ['jenis_pajak' => 'tanpa_pajak']
                    );
                }
            }
            $catalogItems = Item::orderBy('nama_item')->get();
        }
        $jenisBarang = $catalogItems;

        $kontak = PenjualanTokabe::select('customer', 'no_telepon', 'perusahaan', 'npwp')->whereNotNull('customer')->get();
        try {
            $rekenings = Rekening::where('status', 'Aktif')->get();
        } catch (\Throwable $e) {
            $rekenings = collect();
        }
        return view('pages.invoices.tokabe.addInvoice', compact('invoice', 'admin', 'order', 'no_invoice', 'hariIni', 'formatJam', 'kontak', 'jenisBarang', 'rekenings'));
    }

    public function addInvoiceCustom()
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
        $catalogItems = Item::orderBy('nama_item')->get();
        if ($catalogItems->isEmpty()) {
            $activeBarangs = Barang::where('is_active', 1)->get();
            foreach ($activeBarangs as $b) {
                $nama = trim($b->jenis_barang);
                if ($nama !== '') {
                    Item::firstOrCreate(
                        ['nama_item' => $nama],
                        ['jenis_pajak' => 'tanpa_pajak']
                    );
                }
            }
            $catalogItems = Item::orderBy('nama_item')->get();
        }
        $jenisBarang = $catalogItems;

        $kontak = PenjualanTokabe::select('customer', 'no_telepon', 'perusahaan', 'npwp')->whereNotNull('customer')->get();
        try {
            $rekenings = Rekening::where('status', 'Aktif')->get();
        } catch (\Throwable $e) {
            $rekenings = collect();
        }
        return view('pages.invoices.tokabe.addInvoiceCustom', compact('invoice', 'admin', 'order', 'no_invoice', 'hariIni', 'formatJam', 'kontak', 'jenisBarang', 'rekenings'));
    }

    public function generateNoInvoice($tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); // Tahun
        $month = $tanggal->format('m'); // Bulan dalam angka

        // Konversi bulan ke angka Romawi
        $romawiBulan = $this->convertToRoman((int)$month);

        // Ambil invoice Tokabe reguler terakhir di bulan dan tahun yang sama
        // Hanya mencari nomor dengan format standar: {angka 1-4 digit}/TKB/{romawiBulan}/{year}
        // dan mengabaikan invoice custom agar urutan nomor standar tidak terganggu
        $lastInvoice = PenjualanTokabe::withTrashed()
            ->where(function ($q) {
                $q->where('is_custom', 0)->orWhereNull('is_custom');
            })
            ->where('nomor_invoice', 'REGEXP', "^[0-9]{1,4}/TKB/{$romawiBulan}/{$year}$")
            ->orderByRaw("CAST(SUBSTRING_INDEX(nomor_invoice, '/', 1) AS UNSIGNED) DESC")
            ->value('nomor_invoice');

        $last_number = $lastInvoice ? ((int) explode('/', $lastInvoice)[0]) : 0;

        // Cari nomor urut berikutnya yang belum pernah dipakai (termasuk invoice yang dihapus/soft-deleted)
        do {
            $last_number++;
            $candidateNumber = sprintf('%03d', $last_number) . "/TKB/{$romawiBulan}/{$year}";
        } while (PenjualanTokabe::withTrashed()->where('nomor_invoice', $candidateNumber)->exists());

        return $candidateNumber;
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
        $rules = [
            'pelanggan'   => ['required', 'string', 'max:255'],
            'npwp'        => ['nullable', 'string', 'max:50'],
            'tlp'         => ['required', 'string', 'max:50'],
            'adm'         => ['required'],
            'order'       => ['required', 'string'],
            'tgl_jual'    => ['required', 'string'],
            'jam'         => ['required', 'string'],
            'tgl_selesai' => ['required', 'string'],
            'jns_pem'     => ['required', 'string', 'max:50'],
        ];

        $messages = [
            'pelanggan.required'   => 'Nama pelanggan wajib diisi.',
            'tlp.required'         => 'Nomor telepon wajib diisi.',
            'adm.required'         => 'Nama admin wajib dipilih.',
            'order.required'       => 'Order dari wajib dipilih.',
            'tgl_jual.required'    => 'Tanggal penjualan wajib dipilih.',
            'jam.required'         => 'Jam penjualan wajib diisi.',
            'tgl_selesai.required' => 'Target penyelesaian wajib dipilih.',
            'jns_pem.required'     => 'Jenis pembayaran wajib dipilih.',
        ];

        if (strtolower((string)$request->order) === 'sales') {
            $rules['sales'] = ['required', 'string', 'max:255'];
            $messages['sales.required'] = 'Nama sales wajib diisi jika order dari Sales.';
        }

        if ($request->filled('is_custom') || $request->filled('nomor_invoice_custom')) {
            $rules['nomor_invoice_custom'] = ['required', 'string', 'max:255'];
            $messages['nomor_invoice_custom.required'] = 'Nomor invoice custom wajib diisi.';
        }

        if ($request->has('item_id')) {
            $rules['item_id'] = ['required', 'array', 'min:1'];
            $rules['item_id.*'] = ['required', 'integer', 'exists:items,id'];
            $rules['satuan'] = ['required', 'array', 'min:1'];
            $rules['satuan.*'] = ['required', 'string'];
            $rules['hrg'] = ['required', 'array', 'min:1'];
            $rules['hrg.*'] = ['required'];
            $rules['qty'] = ['required', 'array', 'min:1'];
            $rules['qty.*'] = ['required', 'numeric', 'min:1'];

            $messages['item_id.required'] = 'Minimal harus ada 1 item.';
            $messages['item_id.min'] = 'Minimal harus ada 1 item.';
            $messages['item_id.*.required'] = 'Pilihan item wajib dipilih.';
            $messages['item_id.*.exists'] = 'Item yang dipilih tidak valid.';
            $messages['satuan.*.required'] = 'Satuan item wajib dipilih.';
            $messages['hrg.*.required'] = 'Harga item wajib diisi.';
            $messages['qty.*.required'] = 'Quantity item wajib diisi.';
            $messages['qty.*.min'] = 'Quantity minimal 1.';

            if ($request->has('deskripsi_item')) {
                $rules['deskripsi_item'] = ['required', 'array', 'min:1'];
                $rules['deskripsi_item.*'] = ['required', 'string'];
                $messages['deskripsi_item.*.required'] = 'Deskripsi item wajib diisi.';
            }
        }

        $request->validate($rules, $messages);

        if ($request->has('hrg') && is_array($request->hrg)) {
            foreach ($request->hrg as $idx => $hrgVal) {
                $cleanHrg = intval(preg_replace('/[^0-9]/', '', (string)$hrgVal));
                if ($cleanHrg <= 0) {
                    $rowNum = $idx + 1;
                    return response()->json([
                        'status' => 'error',
                        'message' => "Harga item baris #{$rowNum} wajib diisi dan lebih dari 0."
                    ], 422);
                }
            }
        }

        if ($request->jns_pem === 'Transfer DP') {
            $dpCheck = intval(preg_replace('/[^0-9]/', '', $request->dp ?? '0'));
            if ($dpCheck <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nominal DP wajib diisi untuk jenis pembayaran Transfer DP.'
                ], 422);
            }
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
                            $material = Material::find($matId);
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
    
    // Penanganan nomor invoice: jika custom, gunakan nomor yang diinputkan user
    if ($request->filled('nomor_invoice_custom')) {
        $nmr_inv = trim($request->input('nomor_invoice_custom'));
        // Cek duplikasi nomor invoice (termasuk yang soft-deleted)
        $isDuplicate = PenjualanTokabe::withTrashed()->where('nomor_invoice', $nmr_inv)->exists();
        if ($isDuplicate) {
            return response()->json([
                'status' => 'error',
                'message' => "Nomor invoice '{$nmr_inv}' sudah pernah digunakan. Silakan gunakan format nomor lain."
            ], 400);
        }
    } else {
        $nmr_inv = $this->generateNoInvoice($request->tgl_jual);
    }

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
        $existingPenjualan->npwp = $request->filled('npwp') ? trim($request->input('npwp')) : null;
        $existingPenjualan->no_faktur_pajak = null;
        $existingPenjualan->faktur_pajak_status = 'Belum Diisi';
        $existingPenjualan->approval = 'Lock';
        $existingPenjualan->approved_at = null;
        if ($request->filled('is_custom')) {
            $existingPenjualan->is_custom = 1;
        }
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
                    $matIds = $request->material_id[$key] ?? [];
                    $matQtys = $request->material_qty[$key] ?? [];
                    $matPanjangs = $request->material_panjang[$key] ?? [];
                    $matLebars = $request->material_lebar[$key] ?? [];
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
                            $material = Material::find($mId);
                            $mQty = array_values($matQtys)[$mIndex] ?? 0;
                            if ($material && $mQty > 0) $material->decrement('stok', $mQty);
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
        $penjualan->admin = $request->adm ?: (Auth::id() ?: 1);
        $penjualan->order_by = $request->order ?: 'Langsung';
        $penjualan->nama_sales = $request->sales ?: '-';
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
        $penjualan->npwp = $request->filled('npwp') ? trim($request->input('npwp')) : null;
        $penjualan->no_faktur_pajak = null;
        $penjualan->faktur_pajak_status = 'Belum Diisi';
        $penjualan->approval = 'Lock';
        $penjualan->approved_at = null;
        $penjualan->is_custom = $request->filled('is_custom') ? 1 : 0;
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
                    $matIds = $request->material_id[$key] ?? [];
                    $matQtys = $request->material_qty[$key] ?? [];
                    $matPanjangs = $request->material_panjang[$key] ?? [];
                    $matLebars = $request->material_lebar[$key] ?? [];
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
                if (!empty($matIds)) {
                    foreach (array_values($matIds) as $mIndex => $mId) {
                        $material = Material::find($mId);
                        $mQty = array_values($matQtys)[$mIndex] ?? 0;
                        if ($material && $mQty > 0) {
                            $material->decrement('stok', $mQty);
                        }
                    }
                }
            }
        }
        $this->saveCatalogInvoiceItems($request, $penjualan);
    }

    $this->clearTokabeCaches();
    return response()->json(['status' => 'success', 'message' => 'Data Sudah Berhasil Disimpan']);
}

    private function clearTokabeCaches($invoiceDate = null): void
    {
        $now = Carbon::now();
        $y = $now->year;
        $m = $now->month;
        Cache::forget("tkb_invoice_card_stats_{$y}_{$m}");
        Cache::forget("tkb_custom_invoice_card_stats_{$y}_{$m}");
        Cache::forget("tkb_dashboard_pembelian_barang_{$y}_{$m}");

        if ($invoiceDate) {
            try {
                $d = Carbon::parse($invoiceDate);
                $dy = $d->year;
                $dm = $d->month;
                Cache::forget("tkb_invoice_card_stats_{$dy}_{$dm}");
                Cache::forget("tkb_custom_invoice_card_stats_{$dy}_{$dm}");
                Cache::forget("tkb_dashboard_pembelian_barang_{$dy}_{$dm}");
            } catch (\Exception $e) {
                // ignore invalid date parse
            }
        }
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
        if (!auth()->check() || auth()->user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik yang dapat menghapus invoice.');
            return redirect()->back();
        }

        $invoice = PenjualanTokabe::findOrFail($id);
        $isCustom = ($invoice->is_custom == 1);
        $nomorInvoice = $invoice->nomor_invoice;

        // 1. Kembalikan stok barang & material jika invoice belum pernah dibatalkan
        if ($invoice->status !== 'Batal') {
            $penjBarang = PenjualanJasaTokabe::where('penjualan_id', $invoice->id)->get();
            foreach ($penjBarang as $item) {
                if ($item->barang_id) {
                    $barangObj = Barang::find($item->barang_id);
                    if ($barangObj) {
                        $barangObj->stok += $item->qty;
                        $barangObj->save();
                    }
                }
                if (!empty($item->material_id) && !empty($item->material_qty)) {
                    $mIds = json_decode($item->material_id, true) ?: [];
                    $mQtys = json_decode($item->material_qty, true) ?: [];
                    foreach ($mIds as $mIdx => $mId) {
                        $matObj = Material::find($mId);
                        $qtyToRestore = $mQtys[$mIdx] ?? 0;
                        if ($matObj && $qtyToRestore > 0) {
                            $matObj->increment('stok', $qtyToRestore);
                        }
                    }
                }
            }
        }

        // 2. Hapus line items terkait
        PenjualanJasaTokabe::where('penjualan_id', $invoice->id)->delete();

        // 3. Hapus SPK jika ada yang terhubung dengan nomor invoice ini
        DB::table('spk')->where('nomor_invoice', $nomorInvoice)->delete();

        // 4. Hapus header invoice
        $invoice->delete();

        // 5. Bersihkan cache statistik & laporan
        $this->clearTokabeCaches($invoice->tgl_penjualan);

        Alert::success('Berhasil Dihapus', "Invoice #{$nomorInvoice} berhasil dihapus dari sistem.");

        if ($isCustom) {
            return redirect()->route('list_invoice_tokabe_custom')->with('success', "Invoice #{$nomorInvoice} berhasil dihapus.");
        }
        return redirect()->route('list_invoice_tokabe')->with('success', "Invoice #{$nomorInvoice} berhasil dihapus.");
    }

    public function editInvoice($id)
    {
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Error', 'Data invoice tidak ditemukan.');
            return redirect()->route('list_invoice_tokabe');
        }
        $invoice = Invoice::find($inv->invoice) ?? Invoice::first();
        $admin_now = User::find($inv->admin) ?? Auth::user();
        $admin = User::whereIn('role', ['Admin', 'Pemilik'])->get();
        $order = Order::all();
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $jenisBarang = Barang::with('kategori_item')->where('is_active', 1)->get();
        $items = Item::orderBy('nama_item')->get();
        if ($items->isEmpty()) {
            $activeBarangs = Barang::where('is_active', 1)->get();
            foreach ($activeBarangs as $b) {
                $nama = trim($b->jenis_barang);
                if ($nama !== '') {
                    Item::firstOrCreate(
                        ['nama_item' => $nama],
                        ['jenis_pajak' => 'tanpa_pajak']
                    );
                }
            }
            $items = Item::orderBy('nama_item')->get();
        }
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
        $isOwner = Auth::check() && Auth::user()->role === 'Pemilik';
        $isAdmin = Auth::check() && Auth::user()->role === 'Admin';
        $isLocked = ($inv->approval !== 'Unlock') && !$isOwner;
        $latestApprovalRequest = $inv->latestApprovalRequest;

        // Material untuk form edit
        return view('pages.invoices.tokabe.edit-invoicesTokabe', compact('inv', 'jam', 'nomor_unik', 'perusahaan', 'admin', 'order', 'sales', 'jenisBarang', 'items', 'nama_adm', 'barang', 'invoice', 'penjualan_barang', 'rekenings', 'isLocked', 'isOwner', 'isAdmin', 'latestApprovalRequest'));
    }

    public function updateInvoice(Request $request, $id)
    {
        $penjualan = PenjualanTokabe::findOrFail($id);
        if (Auth::check() && Auth::user()->role === 'Admin' && $penjualan->approval !== 'Unlock') {
            Alert::error('Akses Ditolak', 'Invoice berstatus Terkunci. Anda harus meminta izin/approval Owner terlebih dahulu.');
            if ($penjualan->is_custom == 1) {
                return redirect()->route('list_invoice_tokabe_custom');
            }
            return redirect()->route('list_invoice_tokabe');
        }

        // 1. VALIDASI INPUT TERLEBIH DAHULU (Penting agar data tidak hilang!)
        $request->validate([
            'item_id.*' => ['nullable', 'integer', 'exists:items,id'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'no_faktur_pajak' => ['nullable', 'string', 'max:50'],
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
                if (!empty($barang->material_id) && !empty($barang->material_qty)) {
                    $mIds = json_decode($barang->material_id, true) ?: [];
                    $mQtys = json_decode($barang->material_qty, true) ?: [];
                    foreach ($mIds as $mIdx => $mId) {
                        $matObj = Material::find($mId);
                        $qtyToRestore = $mQtys[$mIdx] ?? 0;
                        if ($matObj && $qtyToRestore > 0) {
                            $matObj->increment('stok', $qtyToRestore);
                        }
                    }
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
        $penjualan->invoice = $request->input('inv', $penjualan->invoice ?? 5);
        $penjualan->nomor_invoice = !empty(trim($nmr_inv)) ? $nmr_inv : $penjualan->nomor_invoice;
        $penjualan->tgl_penjualan = !empty($request->tgl_jual) ? $tanggalPenjualan : $penjualan->tgl_penjualan;
        $penjualan->customer = $request->input('pelanggan', $penjualan->customer);
        $penjualan->perusahaan = $request->input('perusahaan', $penjualan->perusahaan);
        $penjualan->no_telepon = $request->input('tlp', $penjualan->no_telepon);
        $penjualan->admin = $request->input('adm', $penjualan->admin);
        $penjualan->order_by = $request->input('order', $penjualan->order_by);
        $penjualan->nama_sales = $request->input('sales', $penjualan->nama_sales);
        $penjualan->tgl_selesai = $request->input('tgl_selesai', $penjualan->tgl_selesai);
        $penjualan->jumlah_item = $item > 0 ? $item : $penjualan->jumlah_item;
        $penjualan->dp = $dp;
        $penjualan->jenis_pembayaran = $request->input('jns_pem', $penjualan->jenis_pembayaran);
        $penjualan->no_rek = $norek;
        $penjualan->total_harga = $tot_harga > 0 ? $tot_harga : $penjualan->total_harga;
        $penjualan->status = !empty($request->jns_pem) ? $status : $penjualan->status;
        
        $penjualan->ppn = $ppnVal;
        $penjualan->kode_ppn = $kodePpn;
        $penjualan->pph = $pphVal;
        $penjualan->diskon = null;
        $penjualan->potongan = null;
        
        $penjualan->total_pembayaran = $tot_pem > 0 ? $tot_pem : $penjualan->total_pembayaran;
        $penjualan->sisa_pembayaran = $sisa_pem;
        $penjualan->npwp = $request->filled('npwp') ? trim($request->input('npwp')) : $penjualan->npwp;

        $fakturInput = trim((string)$request->input('no_faktur_pajak'));
        if ($fakturInput !== '') {
            $penjualan->no_faktur_pajak = $fakturInput;
            $penjualan->faktur_pajak_status = 'Lengkap';
        } else {
            $penjualan->no_faktur_pajak = null;
            $penjualan->faktur_pajak_status = 'Belum Diisi';
        }
        $penjualan->approval = 'Lock'; // Otomatis terkunci kembali secara permanen
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
                    $matIds = $request->material_id[$key] ?? [];
                    $matQtys = $request->material_qty[$key] ?? [];
                    $matPanjangs = $request->material_panjang[$key] ?? [];
                    $matLebars = $request->material_lebar[$key] ?? [];
                }
                
                $itemData = new PenjualanJasaTokabe();
                $itemData->barang_id = $value;
                $itemData->penjualan_id = $penjualan->id;
                $itemData->deskripsi_item = $request->deskripsi_item[$key] ?? null;
                $itemData->qty = $request->qty[$key];
                $itemData->satuan = $request->satuan[$key];
                $itemData->harga = intval(str_replace(',', '', $hrg));
                $itemData->jumlah_harga = intval(str_replace(',', '', $request->jlh_hrg[$key] ?? '0'));
                $itemData->material_id = !empty($matIds) ? json_encode(array_values($matIds)) : null;
                $itemData->material_qty = !empty($matQtys) ? json_encode(array_values($matQtys)) : null;
                $itemData->material_panjang = !empty($matPanjangs) ? json_encode(array_values($matPanjangs)) : null;
                $itemData->material_lebar = !empty($matLebars) ? json_encode(array_values($matLebars)) : null;
                
                $itemData->save();

                // Potong Stok Barang Biasa
                $barang = Barang::find($itemData->barang_id);
                if ($barang) {
                    $barang->stok -= $itemData->qty;
                    $barang->save();
                }
                
                if (!empty($matIds)) {
                    foreach (array_values($matIds) as $mIndex => $mId) {
                        $material = Material::find($mId);
                        $mQty = array_values($matQtys)[$mIndex] ?? 0;
                        if ($material && $mQty > 0) {
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
        $this->clearTokabeCaches($penjualan->tgl_penjualan);
        
        Alert::success('Data Invoice Berhasil diUpdate');
        if ($penjualan->is_custom == 1) {
            return redirect()->route('list_invoice_tokabe_custom');
        }
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
            $this->clearTokabeCaches($invoice->tgl_penjualan);

            return back()->with(['batal' => 'Invoice telah dibatalkan']);
        } else {
            $invoice->status = 'Lunas';
            $invoice->sisa_pembayaran = 0;
            $invoice->save();
            $this->clearTokabeCaches($invoice->tgl_penjualan);
            return back()->with(['lunas' => 'Invoice sudah Lunas']);
        }
    }
    
    public function requestEditFaktur(Request $request, $id)
    {
        $inv = PenjualanTokabe::findOrFail($id);
        $user = Auth::user();

        $approvalRequest = InvoiceApprovalRequest::updateOrCreate(
            [
                'invoice_id' => $inv->id,
                'status' => 'Pending',
            ],
            [
                'user_id' => $user->id,
                'tipe_request' => 'edit_faktur_pajak',
                'catatan_admin' => $request->input('catatan', 'Permintaan izin buka kunci (unlock) invoice untuk pengisian Nomor Faktur Pajak.'),
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Permintaan akses edit untuk invoice #{$inv->nomor_invoice} berhasil diajukan kepada Owner.",
                'data' => $approvalRequest,
            ]);
        }

        Alert::success('Berhasil', "Permintaan akses edit untuk invoice #{$inv->nomor_invoice} telah diajukan kepada Owner.");
        return redirect()->back();
    }

    public function unlock($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik yang dapat menyetujui unlock invoice.');
            return redirect('list-invoice/tokabe');
        }
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Unlock';
        $inv->approved_at = now();
        $inv->approved_by = Auth::id();
        $inv->save();

        // Update pending approval request jika ada
        InvoiceApprovalRequest::where('invoice_id', $inv->id)
            ->where('status', 'Pending')
            ->update([
                'status' => 'Approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'catatan_owner' => 'Disetujui oleh Owner untuk pengisian Nomor Faktur Pajak.',
            ]);

        $this->clearTokabeCaches($inv->tgl_penjualan);
        Alert::success('Invoice Tokabe berhasil di-Unlock');
        if ($inv->is_custom == 1) {
            return redirect()->route('list_invoice_tokabe_custom');
        }
        return redirect()->route('list_invoice_tokabe');
    }

    public function lock($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik yang dapat me-lock invoice.');
            return redirect('list-invoice/tokabe');
        }
        $inv = PenjualanTokabe::find($id);
        if (!$inv) {
            Alert::error('Data tidak ditemukan');
            return redirect()->back();
        }
        $inv->approval = 'Lock';
        $inv->approved_at = null;
        $inv->save();
        $this->clearTokabeCaches($inv->tgl_penjualan);
        Alert::success('Invoice Tokabe berhasil di-Lock');
        return redirect()->back();
    }

    public function approvalPage($id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $latestRequest = $invoice->latestApprovalRequest;
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
        }
        return view('pages.invoices.tokabe.approval', compact('invoice', 'penjualan_barang', 'barang', 'latestRequest'));
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

    public function batalPage($id)
    {
        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $invoice->formatted_sisa_pembayaran = number_format($invoice->sisa_pembayaran, 0, ',', '.');
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
        }
        return view('pages.invoices.tokabe.batal', compact('invoice', 'penjualan_barang', 'barang'));
    }

    public function deletePage($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik yang dapat mengakses halaman konfirmasi penghapusan invoice.');
            return redirect()->back();
        }

        $invoice = PenjualanTokabe::findOrFail($id);
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $invoice->formatted_total_pembayaran = number_format($invoice->total_pembayaran, 0, ',', '.');
        $invoice->formatted_sisa_pembayaran = number_format($invoice->sisa_pembayaran, 0, ',', '.');
        $barang = [];
        foreach ($penjualan_barang as $item) {
            $barang[] = $this->productForInvoiceLine($item);
        }
        return view('pages.invoices.tokabe.delete', compact('invoice', 'penjualan_barang', 'barang'));
    }

    public function statusLunas($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik yang dapat menyetujui pelunasan invoice.');
            return redirect('list-invoice/tokabe');
        }
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
        $this->clearTokabeCaches($inv->tgl_penjualan);
        Alert::success('Invoice Tokabe Sudah Lunas');
        if ($inv->is_custom == 1) {
            return redirect()->route('list_invoice_tokabe_custom');
        }
        return redirect()->route('list_invoice_tokabe');
    }

    public function storeStatusBatal(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik yang dapat membatalkan invoice.');
            return redirect('list-invoice/tokabe');
        }
        $invoice = PenjualanTokabe::findOrFail($id);
        if ($invoice->status !== 'Batal') {
            $penjBarang = PenjualanJasaTokabe::where('penjualan_id', $invoice->id)->get();
            foreach ($penjBarang as $barang) {
                $barangObj = Barang::find($barang->barang_id);
                if ($barangObj) {
                    $barangObj->stok += $barang->qty;
                    $barangObj->save();
                }
                if (!empty($barang->material_id) && !empty($barang->material_qty)) {
                    $mIds = json_decode($barang->material_id, true) ?: [];
                    $mQtys = json_decode($barang->material_qty, true) ?: [];
                    foreach ($mIds as $mIdx => $mId) {
                        $matObj = Material::find($mId);
                        $qtyToRestore = $mQtys[$mIdx] ?? 0;
                        if ($matObj && $qtyToRestore > 0) {
                            $matObj->increment('stok', $qtyToRestore);
                        }
                    }
                }
            }
        }
        $invoice->status = 'Batal';
        $invoice->alasan_batal = $request->alasan_batal;
        $invoice->save();
        $this->clearTokabeCaches($invoice->tgl_penjualan);
        if ($invoice->is_custom == 1) {
            return redirect()->route('list_invoice_tokabe_custom')->with('batal', 'Invoice Tokabe berhasil dibatalkan.');
        }
        return redirect()->route('list_invoice_tokabe')->with('batal', 'Invoice Tokabe berhasil dibatalkan.');
    }

    public function cetakInvoiceTKB(Request $request, $id)
    {
        $type = strtolower($request->query('type', 'asli'));
        if (!in_array($type, ['asli', 'copy'])) {
            $type = 'asli';
        }
        $penjualan = PenjualanTokabe::findOrFail($id);
        $invoice = Invoice::find($penjualan->invoice) ?? Invoice::first();
        $penjualan_barang = PenjualanJasaTokabe::where('penjualan_id', $id)->get();
        $admin_now = User::find($penjualan->admin) ?? (Auth::user() ?? User::first());
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