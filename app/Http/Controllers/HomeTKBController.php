<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanTokabe;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HomeTKBController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    
    function index() {

        Carbon::setLocale('id');

        // $penjualan = Penjualan::orderByDesc('tgl_penjualan')->take(10)->get();
        $yearNow = Carbon::now()->year;
        $monthNow = Carbon::now()->month;

        $dataPerjam = $this->getPenjualan();
        $dataPenjualan = $this->getDataPenjualan();
        $dataPenjualanLastYear = $this->getDataPenjualanLastYear();

        $dataPerminggu = $this->getPerminggu();
        $dataPerbulan = $this->getPerbulan();
        $dataPerbulanLastYear = $this->getPerbulanLastYear();

        $totalPerminggu = $this->getTotalPerMinggu();
        $totalPerbulan = $this->getTotalPerBulan();
        $totalPertahun = $this->getTotalPerTahun();
        $totalPertahunLalu = $this->getTotalPerTahunLalu();
       


        $hourArray = $dataPerjam['jamPenjualan'];
        $dateArray = $dataPenjualan['tanggalPenjualan'];
        $dateArrayLastYear = $dataPenjualanLastYear['tanggalPenjualanLastYear'];

        $weekArray = $dataPerminggu['tanggalPenjualan'];
        $monthArray = $dataPerbulan['bulanPenjualan'];
        $monthArrayLastYear = $dataPerbulanLastYear['bulanPenjualanLastYear'];

        $totalJamArray = $dataPerjam['totalPerjam'];
        $totalHarianArray = $dataPenjualan['totalHarian'];
        $totalHarianArrayLastYear = $dataPenjualanLastYear['totalHarianLastYear'];

        $totalMingguanArray = $dataPerminggu['totalHarian'];
        $totalBulananArray = $dataPerbulan['totalBulanan'];
        $totalBulananArrayLastYear = $dataPerbulanLastYear['totalBulananLastYear'];

        $jumlahPerHari = number_format(array_sum($dataPerjam['totalPerjam']), 0, ',', ',');
        $jumlahPerMinggu = number_format(array_sum($dataPerminggu['totalHarian']), 0, ',', ',');

        // Week For Donut Chart
        $datasInWeek = $totalPerminggu['weekDatas'];
        $percentageInWeek = $totalPerminggu['weekIn'];
        $percentageNotPaidWeek = $totalPerminggu['weekNotPaid'];
        $percentageCanceledWeek = $totalPerminggu['weekCanceled'];
        // Month For Donut Chart
        $datasInMonth = $totalPerbulan['monthDatas'];
        $percentageInMonth = $totalPerbulan['monthIn'];
        $percentageNotPaidMonth = $totalPerbulan['monthNotPaid'];
        $percentageCanceledMonth = $totalPerbulan['monthCanceled'];
        // Year For Donut Chart
        $datasInYear = $totalPertahun['yearDatas'];
        $percentageInYear = $totalPertahun['yearIn'];
        $percentageNotPaidYear = $totalPertahun['yearNotPaid'];
        $percentageCanceledYear = $totalPertahun['yearCanceled'];

        // Last Year For Donut Chart
        $datasInLastYear = $totalPertahunLalu['lastYearDatas'];
        $percentageInLastYear = $totalPertahunLalu['lastYearIn'];
        $percentageNotPaidLastYear = $totalPertahunLalu['lastYearNotPaid'];
        $percentageCanceledLastYear = $totalPertahunLalu['lastYearCanceled'];

        //data for vendorchart
       


        $jumlahPerBulan = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran'), 0, ',', ',');
        $jumlahPerTahun = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereYear('tgl_penjualan', $yearNow)->sum('total_pembayaran'), 0, ',', ',');

        $jumlahBBPerTahun = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', ',');
        $jumlahBBPerBulan = number_format(PenjualanTokabe::where('jenis_pembayaran', '<>', 'PO')->whereMonth('tgl_penjualan', $monthNow)->whereYear('tgl_penjualan', $yearNow)->where('status', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', ',');

        // foreach ($penjualan as $jual) {
        //     $jual->formatted_date = Carbon::parse($jual->tgl_penjualan)->isoFormat('DD MMMM YYYY');
        //     $jual->formatted_sisa = number_format($jual->sisa_pembayaran);
        // }


        // Ambil daftar tahun unik dari data penjualan
        $availableYears = PenjualanTokabe::selectRaw('YEAR(tgl_penjualan) as year')
            ->distinct()
            ->whereNotNull('tgl_penjualan')
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

        // Data Penjualan Berdasarkan Item & Pilihan Pajak
        $dataPembelianBarang = [
            'jenis' => [
                'today' => $this->getPembelianByJenisBarang('today', 'jenis'),
                'week' => $this->getPembelianByJenisBarang('week', 'jenis'),
                'month' => $this->getPembelianByJenisBarang('month', 'jenis'),
                'year' => $this->getPembelianByJenisBarang('year', 'jenis'),
                'last_year' => $this->getPembelianByJenisBarang('last_year', 'jenis'),
                'all' => $this->getPembelianByJenisBarang('all', 'jenis'),
            ],
            'pajak' => [
                'today' => $this->getPembelianByJenisBarang('today', 'pajak'),
                'week' => $this->getPembelianByJenisBarang('week', 'pajak'),
                'month' => $this->getPembelianByJenisBarang('month', 'pajak'),
                'year' => $this->getPembelianByJenisBarang('year', 'pajak'),
                'last_year' => $this->getPembelianByJenisBarang('last_year', 'pajak'),
                'all' => $this->getPembelianByJenisBarang('all', 'pajak'),
            ],
            'kategori' => [
                'today' => $this->getPembelianByJenisBarang('today', 'kategori'),
                'week' => $this->getPembelianByJenisBarang('week', 'kategori'),
                'month' => $this->getPembelianByJenisBarang('month', 'kategori'),
                'year' => $this->getPembelianByJenisBarang('year', 'kategori'),
                'last_year' => $this->getPembelianByJenisBarang('last_year', 'kategori'),
                'all' => $this->getPembelianByJenisBarang('all', 'kategori'),
            ],
        ];

        $dataPembelianInvoice = collect();
        $purchaseTaxColumnsReady = Schema::hasTable('pembelian')
            && Schema::hasColumn('pembelian', 'jenis_invoice')
            && Schema::hasColumn('pembelian', 'ppn_nominal')
            && Schema::hasColumn('pembelian', 'pph_nominal')
            && Schema::hasColumn('pembelian', 'sisa');
        if ($purchaseTaxColumnsReady) {
            $dataPembelianInvoice = Pembelian::query()
                ->join('vendor', 'pembelian.id_vendor', '=', 'vendor.id')
                ->where('pembelian.status', '<>', 'Batal')
                ->selectRaw('jenis_invoice as label, vendor.nama_vendor as vendor, tanggal, COUNT(*) as total_transaksi, SUM(pembelian.sisa) as total_nominal, COALESCE(SUM(pembelian.jumlah_harga), 0) as total_pembelian, COALESCE(SUM(pembelian.terbayar), 0) as terbayar, SUM(ppn_nominal) as total_ppn, SUM(pph_nominal) as total_pph')
                ->groupBy('jenis_invoice', 'vendor.nama_vendor', 'tanggal')
                ->orderBy('jenis_invoice')
                ->get()
                ->map(fn ($row) => [
                    'label' => $row->label ?: 'Tanpa Jenis',
                    'vendor' => $row->vendor,
                    'tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d'),
                    'total_transaksi' => (int) $row->total_transaksi,
                    'total_nominal' => (float) $row->total_nominal,
                    'total_pembelian' => (float) $row->total_pembelian,
                    'terbayar' => (float) $row->terbayar,
                    'total_ppn' => (float) $row->total_ppn,
                    'total_pph' => (float) $row->total_pph,
                ])->values();
        }

        $daftarTagihanVendor = collect();
        if (Schema::hasTable('pembelian') && Schema::hasTable('vendor')) {
            $daftarTagihanVendor = Pembelian::query()
                ->join('vendor', 'pembelian.id_vendor', '=', 'vendor.id')
                ->where('pembelian.status', '<>', 'Batal')
                ->selectRaw('vendor.nama_vendor, sum(pembelian.jumlah_harga) as total_pembelian, sum(pembelian.terbayar) as terbayar, sum(pembelian.sisa) as sisa_tagihan')
                ->groupBy('vendor.id', 'vendor.nama_vendor')
                ->orderByDesc('sisa_tagihan')
                ->orderByDesc('total_pembelian')
                ->get()
                ->map(fn ($item) => [
                    'nama_vendor' => $item->nama_vendor,
                    'total_pembelian' => (float) $item->total_pembelian,
                    'terbayar' => (float) ($item->terbayar ?? ($item->total_pembelian - $item->sisa_tagihan)),
                    'sisa_tagihan' => (float) $item->sisa_tagihan,
                ])->values();
        }

        return view('pages.welcomeTKB', compact('jumlahPerBulan', 'jumlahPerTahun', 'dateArray','dateArrayLastYear', 'monthArray','monthArrayLastYear', 'totalHarianArray','totalHarianArrayLastYear', 'totalBulananArray','totalBulananArrayLastYear', 'hourArray', 'totalJamArray', 'weekArray', 'totalMingguanArray', 'jumlahPerHari', 'jumlahPerMinggu', 'datasInWeek', 'percentageInWeek', 'percentageNotPaidWeek', 'percentageCanceledWeek', 'datasInMonth', 'percentageInMonth', 'percentageNotPaidMonth', 'percentageCanceledMonth', 'datasInYear', 'percentageInYear', 'percentageNotPaidYear', 'percentageCanceledYear', 'datasInLastYear', 'percentageInLastYear', 'percentageNotPaidLastYear', 'percentageCanceledLastYear', 'jumlahBBPerBulan', 'jumlahBBPerTahun', 'dataPembelianBarang', 'dataPembelianInvoice', 'daftarTagihanVendor', 'availableYears'));
    }

    public function getPembelianByJenisBarang($timeframe = 'year', $groupBy = 'jenis', $customParams = [], $taxFilter = 'all')
    {
        try {
            $yearNow = Carbon::now()->year;
            $monthNow = Carbon::now()->month;
            $today = Carbon::today()->toDateString();
            $lastYear = Carbon::now()->subYear()->year;

            // Query strictly dari Management Item (tabel items)
            $baseQuery = DB::table('penjualan_jasa_tokabe')
                ->join('items', 'penjualan_jasa_tokabe.item_id', '=', 'items.id')
                ->join('penjualan_tokabe', 'penjualan_jasa_tokabe.penjualan_id', '=', 'penjualan_tokabe.id')
                ->whereNull('penjualan_jasa_tokabe.deleted_at')
                ->whereNull('penjualan_tokabe.deleted_at')
                ->where('penjualan_tokabe.status', '<>', 'Batal')
                ->select(
                    'items.id as item_id',
                    'items.nama_item',
                    'items.jenis_pajak',
                    'penjualan_jasa_tokabe.qty',
                    'penjualan_jasa_tokabe.jumlah_harga',
                    'penjualan_tokabe.tgl_penjualan'
                );

            if ($timeframe === 'today') {
                $baseQuery->whereDate('penjualan_tokabe.tgl_penjualan', $today);
            } elseif ($timeframe === 'week') {
                $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
                $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
                $baseQuery->whereBetween('penjualan_tokabe.tgl_penjualan', [$startOfWeek, $endOfWeek]);
            } elseif ($timeframe === 'month') {
                $baseQuery->whereMonth('penjualan_tokabe.tgl_penjualan', $monthNow)
                          ->whereYear('penjualan_tokabe.tgl_penjualan', $yearNow);
            } elseif ($timeframe === 'year') {
                $baseQuery->whereYear('penjualan_tokabe.tgl_penjualan', $yearNow);
            } elseif ($timeframe === 'last_year') {
                $baseQuery->whereYear('penjualan_tokabe.tgl_penjualan', $lastYear);
            } elseif ($timeframe === 'custom') {
                $type = $customParams['filter_type'] ?? 'date';
                if ($type === 'date' && !empty($customParams['date'])) {
                    $baseQuery->whereDate('penjualan_tokabe.tgl_penjualan', $customParams['date']);
                } elseif ($type === 'month' && !empty($customParams['month']) && !empty($customParams['year'])) {
                    $baseQuery->whereMonth('penjualan_tokabe.tgl_penjualan', (int)$customParams['month'])
                              ->whereYear('penjualan_tokabe.tgl_penjualan', (int)$customParams['year']);
                } elseif ($type === 'year' && !empty($customParams['year'])) {
                    $baseQuery->whereYear('penjualan_tokabe.tgl_penjualan', (int)$customParams['year']);
                } elseif ($type === 'range' && !empty($customParams['start_date']) && !empty($customParams['end_date'])) {
                    $baseQuery->whereBetween('penjualan_tokabe.tgl_penjualan', [$customParams['start_date'], $customParams['end_date'] . ' 23:59:59']);
                }
            }

            $subQuery = DB::query()->fromSub($baseQuery, 'sub');

            if ($taxFilter && $taxFilter !== 'all') {
                $subQuery->where('jenis_pajak', $taxFilter);
            }

            $taxLabels = [
                'ppn' => 'PPN',
                'pph' => 'PPh',
                'ppn_pph' => 'PPN & PPh',
                'tanpa_pajak' => 'Tanpa Pajak',
            ];

            if ($groupBy === 'pajak') {
                $results = $subQuery->select(
                    'jenis_pajak as raw_label',
                    DB::raw('SUM(qty) as total_qty'),
                    DB::raw('SUM(jumlah_harga) as total_nominal'),
                    DB::raw('COUNT(*) as total_transaksi')
                )
                ->groupBy('jenis_pajak')
                ->orderByDesc('total_nominal')
                ->get();

                $labels = [];
                $nominal = [];
                $qty = [];
                $items = [];

                foreach ($results as $row) {
                    $humanLabel = $taxLabels[$row->raw_label] ?? ucfirst(str_replace('_', ' ', $row->raw_label));
                    $labels[] = $humanLabel;
                    $nominal[] = (float) $row->total_nominal;
                    $qty[] = (int) $row->total_qty;
                    $items[] = [
                        'label' => $humanLabel,
                        'raw_tax' => $row->raw_label,
                        'total_qty' => (int) $row->total_qty,
                        'total_nominal' => (float) $row->total_nominal,
                        'formatted_nominal' => 'Rp. ' . number_format((float) $row->total_nominal, 0, ',', '.'),
                        'total_transaksi' => (int) $row->total_transaksi,
                    ];
                }
            } else {
                // GroupBy jenis: Item dari Manajemen Item (items table)
                $results = $subQuery->select(
                    'nama_item as label',
                    'jenis_pajak',
                    DB::raw('SUM(qty) as total_qty'),
                    DB::raw('SUM(jumlah_harga) as total_nominal'),
                    DB::raw('COUNT(*) as total_transaksi')
                )
                ->groupBy('nama_item', 'jenis_pajak')
                ->orderByDesc('total_nominal')
                ->get();

                $labels = [];
                $nominal = [];
                $qty = [];
                $items = [];

                foreach ($results as $row) {
                    $humanTax = $taxLabels[$row->jenis_pajak] ?? ucfirst(str_replace('_', ' ', $row->jenis_pajak));
                    $labels[] = $row->label;
                    $nominal[] = (float) $row->total_nominal;
                    $qty[] = (int) $row->total_qty;
                    $items[] = [
                        'label' => $row->label,
                        'jenis_pajak' => $row->jenis_pajak,
                        'jenis_pajak_label' => $humanTax,
                        'total_qty' => (int) $row->total_qty,
                        'total_nominal' => (float) $row->total_nominal,
                        'formatted_nominal' => 'Rp. ' . number_format((float) $row->total_nominal, 0, ',', '.'),
                        'total_transaksi' => (int) $row->total_transaksi,
                    ];
                }
            }

            return [
                'labels' => $labels,
                'nominal' => $nominal,
                'qty' => $qty,
                'items' => $items,
                'total_nominal_sum' => array_sum($nominal),
                'formatted_total_nominal' => 'Rp. ' . number_format(array_sum($nominal), 0, ',', '.'),
                'total_qty_sum' => array_sum($qty),
                'count' => count($labels),
            ];
        } catch (\Exception $e) {
            return [
                'labels' => [],
                'nominal' => [],
                'qty' => [],
                'items' => [],
                'total_nominal_sum' => 0,
                'formatted_total_nominal' => 'Rp. 0',
                'total_qty_sum' => 0,
                'count' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getBarangDataAjax(Request $request)
    {
        Carbon::setLocale('id');

        $timeframe = $request->input('timeframe', 'year');
        $taxFilter = $request->input('tax_filter', 'all');
        $filterType = $request->input('filter_type', 'date');
        $date = $request->input('date');
        $month = $request->input('month');
        $year = $request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $customParams = [
            'filter_type' => $filterType,
            'date' => $date,
            'month' => $month,
            'year' => $year,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $dataJenis = $this->getPembelianByJenisBarang($timeframe, 'jenis', $customParams, $taxFilter);
        $dataPajak = $this->getPembelianByJenisBarang($timeframe, 'pajak', $customParams, $taxFilter);
        $dataKategori = $this->getPembelianByJenisBarang($timeframe, 'kategori', $customParams, $taxFilter);

        $periodLabel = 'Tahun Ini (' . Carbon::now()->year . ')';
        if ($timeframe === 'today') {
            $periodLabel = 'Hari Ini (' . Carbon::today()->isoFormat('D MMMM YYYY') . ')';
        } elseif ($timeframe === 'week') {
            $periodLabel = 'Minggu Ini (' . Carbon::now()->startOfWeek()->isoFormat('D MMM') . ' - ' . Carbon::now()->endOfWeek()->isoFormat('D MMM YYYY') . ')';
        } elseif ($timeframe === 'month') {
            $periodLabel = 'Bulan Ini (' . Carbon::now()->isoFormat('MMMM YYYY') . ')';
        } elseif ($timeframe === 'year') {
            $periodLabel = 'Tahun Ini (' . Carbon::now()->year . ')';
        } elseif ($timeframe === 'last_year') {
            $periodLabel = 'Tahun Lalu (' . Carbon::now()->subYear()->year . ')';
        } elseif ($timeframe === 'all') {
            $periodLabel = 'Semua Periode';
        } elseif ($timeframe === 'custom') {
            if ($filterType === 'date' && !empty($date)) {
                $periodLabel = 'Tanggal ' . Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY');
            } elseif ($filterType === 'month' && !empty($month) && !empty($year)) {
                $periodLabel = 'Bulan ' . Carbon::createFromDate((int)$year, (int)$month, 1)->locale('id')->isoFormat('MMMM YYYY');
            } elseif ($filterType === 'year' && !empty($year)) {
                $periodLabel = 'Tahun ' . $year;
            } elseif ($filterType === 'range' && !empty($startDate) && !empty($endDate)) {
                $periodLabel = 'Rentang ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
            }
        }

        return response()->json([
            'status' => 'success',
            'timeframe' => $timeframe,
            'tax_filter' => $taxFilter,
            'period_label' => $periodLabel,
            'data' => [
                'jenis' => $dataJenis,
                'pajak' => $dataPajak,
                'kategori' => $dataKategori,
            ]
        ]);
    }

    public function getPaymentChartDataAjax(Request $request)
    {
        Carbon::setLocale('id');

        $filterType = $request->input('filter_type', 'date'); // 'date', 'month', 'year'
        $date = $request->input('date');
        $month = (int)$request->input('month');
        $year = (int)$request->input('year');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($filterType === 'range' && !empty($startDate) && !empty($endDate)) {
            $periodLabel = 'Rentang ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y');
            $sql = "SELECT DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal, SUM(total_pembayaran) AS total_harian
                FROM penjualan_tokabe WHERE jenis_pembayaran <> 'PO' AND status <> 'Batal' AND deleted_at IS NULL
                AND DATE(tgl_penjualan) BETWEEN ? AND ? GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') ORDER BY tanggal ASC";
            $results = DB::select($sql, [$startDate, $endDate]);
            $totals = [];
            $total = 0;
            foreach ($results as $row) {
                $totals[] = (float) $row->total_harian;
                $total += (float) $row->total_harian;
            }
            return response()->json(['status' => 'success', 'filter_type' => 'range', 'period_label' => $periodLabel,
                'data' => ['dates' => array_map(fn ($row) => $row->tanggal, $results), 'totals' => $totals, 'total_transaksi' => $total]]);
        } elseif ($filterType === 'date' && !empty($date)) {
            $periodLabel = 'Tanggal ' . Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY');

            $sql = "SELECT
                DATE_FORMAT(tgl_penjualan, '%Y-%m-%d %H:%i') AS jam_penjualan,
                SUM(total_pembayaran) AS total_perjam
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
                AND status <> 'Batal'
                AND deleted_at IS NULL
                AND DATE(tgl_penjualan) = ?
            GROUP BY jam_penjualan
            ORDER BY jam_penjualan ASC";

            $results = DB::select($sql, [$date]);

            $hours = [];
            $totals = [];
            $totalHari = 0;

            foreach ($results as $row) {
                $time = date('H:i', strtotime($row->jam_penjualan));
                $hours[] = $time;
                $val = (float)$row->total_perjam;
                $totals[] = $val;
                $totalHari += $val;
            }

            return response()->json([
                'status' => 'success',
                'filter_type' => 'date',
                'period_label' => $periodLabel,
                'data' => [
                    'hours' => $hours,
                    'totals' => $totals,
                    'total_transaksi' => $totalHari,
                    'formatted_total' => 'Rp. ' . number_format($totalHari, 0, ',', '.'),
                ]
            ]);
        } elseif ($filterType === 'month' && !empty($month) && !empty($year)) {
            $periodLabel = 'Bulan ' . Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY');

            $sql = "SELECT
                DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal,
                SUM(total_pembayaran) AS total_harian
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
                AND status <> 'Batal'
                AND deleted_at IS NULL
                AND MONTH(tgl_penjualan) = ?
                AND YEAR(tgl_penjualan) = ?
            GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m-%d')
            ORDER BY tanggal ASC";

            $results = DB::select($sql, [$month, $year]);

            $totalsByDate = [];
            foreach ($results as $row) {
                $totalsByDate[$row->tanggal] = (float)$row->total_harian;
            }

            $now = Carbon::now();
            if ($year === $now->year && $month === $now->month) {
                $daysInMonth = $now->day;
            } else {
                $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            }

            return response()->json([
                'status' => 'success',
                'filter_type' => 'month',
                'period_label' => $periodLabel,
                'data' => [
                    'year' => $year,
                    'month' => sprintf('%02d', $month),
                    'days_in_month' => $daysInMonth,
                    'totals_by_date' => $totalsByDate,
                ]
            ]);
        } elseif ($filterType === 'year' && !empty($year)) {
            $periodLabel = 'Tahun ' . $year;

            $sql = "SELECT
                MONTH(tgl_penjualan) AS bulan,
                SUM(total_pembayaran) AS total_bulanan
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
                AND status <> 'Batal'
                AND deleted_at IS NULL
                AND YEAR(tgl_penjualan) = ?
            GROUP BY MONTH(tgl_penjualan)
            ORDER BY bulan ASC";

            $results = DB::select($sql, [$year]);

            $totalsByMonth = [];
            foreach ($results as $row) {
                $totalsByMonth[(int)$row->bulan] = (float)$row->total_bulanan;
            }

            $now = Carbon::now();

            return response()->json([
                'status' => 'success',
                'filter_type' => 'year',
                'period_label' => $periodLabel,
                'data' => [
                    'year' => $year,
                    'is_current_year' => ($year === $now->year),
                    'current_month' => $now->month,
                    'current_date' => $now->day,
                    'totals_by_month' => $totalsByMonth,
                ]
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Parameter tidak valid']);
    }



    public function getPenjualan()
    {
        try {
            $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m-%d%H:%i') AS jam_penjualan,
            -- DATE_FORMAT(tgl_penjualan, '%Y-%m-%d %H') AS jam_penjualan,
            SUM(total_pembayaran) AS total_perjam

            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
            AND DATE(tgl_penjualan) = CURDATE()
            GROUP BY jam_penjualan
            ORDER BY jam_penjualan DESC";

            $results = DB::select($sql);

            if (!empty($results)) {
                $jam_penjualan = [];
                $total_perjam = [];
                foreach ($results as $row) {
                    $datetime = date('H:i', strtotime($row->jam_penjualan));
                    $datetime_with_offset = date('Y-m-d H:i', strtotime($datetime . ' +7 hours'));
                    $jam_penjualan[] = $datetime_with_offset;
                    $total_perjam[] = $row->total_perjam;
                }
                return [
                    'jamPenjualan' => $jam_penjualan,
                    'totalPerjam' => $total_perjam,
                ];
            } else {
                return [
                    'jamPenjualan' => [],
                    'totalPerjam' => [],
                ];
            }
        } catch (\PDOException $e) {
            dd($e->getMessage());
            return 'Error';
        }
    }
        
    public function getDataPenjualan()
    {
        try {
            $sql = "SELECT
            tanggal_penjualan,
            bulan_penjualan,
            SUM(total_harian) AS total_harian,
            SUM(total_bulanan) AS total_bulanan
            FROM (
                SELECT
                    DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                    DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS bulan_penjualan,
                    total_pembayaran AS total_harian,
                    CASE WHEN MONTH(tgl_penjualan) AND YEAR(tgl_penjualan) = YEAR(NOW()) THEN total_pembayaran ELSE 0 END AS total_bulanan
                FROM penjualan_tokabe
                WHERE jenis_pembayaran <> 'PO'
                AND status <> 'Batal'
                AND deleted_at IS NULL
                AND MONTH(tgl_penjualan) = MONTH(NOW())
            ) AS subquery
            GROUP BY tanggal_penjualan, bulan_penjualan
            ORDER BY tanggal_penjualan DESC";


            $results = DB::select($sql);

            if (!empty($results)) {
                $bulan_penjualan = [];
                $tanggal_penjualan = [];
                $total_harian = [];
                $total_bulanan = [];

                foreach ($results as $row) {

                    $tanggal_penjualan[] = $row->bulan_penjualan;
                    $total_harian[] = $row->total_harian;
                    $total_bulanan[] = $row->total_bulanan;
                }

                return [
                    'bulanPenjualan' => $bulan_penjualan,
                    'tanggalPenjualan' => $tanggal_penjualan,
                    'totalHarian' => $total_harian,
                    'totalBulanan' => $total_bulanan,
                ];
            } else {
                return [
                    'bulanPenjualan' => [],
                    'tanggalPenjualan' => [],
                    'totalHarian' => [],
                    'totalBulanan' => [],
                ];
            }
            // dd($sql);
        } catch (\PDOException $e) {
            // dd($e->getMessage());
            return 'Error';
        }
    }
    public function getDataPenjualanLastYear()
{
    try {
        $sql = "SELECT
            tanggal_penjualan,
            bulan_penjualan,
            SUM(total_harian) AS total_harian,
            SUM(total_bulanan) AS total_bulanan
        FROM (
            SELECT
                DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS bulan_penjualan,
                total_pembayaran AS total_harian,
                CASE WHEN MONTH(tgl_penjualan) AND YEAR(tgl_penjualan) = YEAR(NOW()) - 1 THEN total_pembayaran ELSE 0 END AS total_bulanan
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
            AND MONTH(tgl_penjualan) <= MONTH(NOW())
            AND YEAR(tgl_penjualan) = YEAR(NOW()) - 1
        ) AS subquery
        GROUP BY tanggal_penjualan, bulan_penjualan
        ORDER BY tanggal_penjualan DESC";

        $results = DB::select($sql);

        if (!empty($results)) {
            $tanggal_penjualan = [];
            $total_harian = [];
            $total_bulanan = [];

            foreach ($results as $row) {
                $tanggal_penjualan[] = $row->bulan_penjualan;
                $total_harian[] = $row->total_harian;
                $total_bulanan[] = $row->total_bulanan;
            }

            return [
                'tanggalPenjualanLastYear' => $tanggal_penjualan,
                'totalHarianLastYear' => $total_harian,
                'totalBulananLastYear' => $total_bulanan,
            ];
        } else {
            return [
                'tanggalPenjualanLastYear' => [],
                'totalHarianLastYear' => [],
                'totalBulananLastYear' => [],
            ];
        }
    } catch (\PDOException $e) {
        return 'Error';
    }
}

    public function getPerminggu()
    {
        try {
            $sql = "SELECT
                tanggal_penjualan,
                SUM(total_harian) AS total_harian
                FROM (
                    SELECT
                        DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') AS tanggal_penjualan,
                        total_pembayaran AS total_harian
                    FROM penjualan_tokabe
                    WHERE jenis_pembayaran <> 'PO'
                    AND status <> 'Batal'
                    AND deleted_at IS NULL
                    AND tgl_penjualan >= DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 2) DAY) -- Start of current week (Monday)
                    AND tgl_penjualan <= DATE(NOW() - INTERVAL (DAYOFWEEK(NOW()) - 8) DAY) -- End of current week (Saturday)
                ) AS subquery
                GROUP BY tanggal_penjualan
                ORDER BY tanggal_penjualan DESC";

            $results = DB::select($sql);

            if (!empty($results)) {
                $tanggal_penjualan = [];
                $total_harian = [];

                foreach ($results as $row) {
                    $tanggal_penjualan[] = $row->tanggal_penjualan;
                    $total_harian[] = $row->total_harian;
                }

                return [
                    'tanggalPenjualan' => $tanggal_penjualan,
                    'totalHarian' => $total_harian,
                ];
            } else {
                return [
                    'tanggalPenjualan' => [],
                    'totalHarian' => [],

                ];
            }
        } catch (\PDOException $e) {
            // dd($e->getMessage());
            return 'Error';
        }
    }



    function getPerbulan()
    {
        try {
            $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m') AS bulan_penjualan,
            SUM(total_pembayaran) AS total_bulanan
        FROM (
            SELECT DISTINCT nomor_invoice, tgl_penjualan, total_pembayaran
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <> 'PO'
            AND status <> 'Batal'
            AND deleted_at IS NULL
                AND YEAR(tgl_penjualan) = YEAR(NOW())
        ) AS subquery
        GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m')
        ORDER BY bulan_penjualan DESC";

            $results = DB::select($sql);

            if (!empty($results)) {
                $bulan_penjualan = [];
                $total_bulanan = [];

                foreach ($results as $row) {
                    $bulan_penjualan[] = $row->bulan_penjualan;
                    $total_bulanan[] = $row->total_bulanan;
                }

                return [
                    'bulanPenjualan' => $bulan_penjualan,
                    'totalBulanan' => $total_bulanan,
                ];
            } else {
                return [
                    'bulanPenjualan' => [],
                    'totalBulanan' => [],
                ];
            }
        } catch (\PDOException $e) {
            dd($e->getMessage());
            return 'Error';
        }
    }
    
    function getPerbulanLastYear()
{
    try {
        $sql = "SELECT
            DATE_FORMAT(tgl_penjualan, '%Y-%m') AS bulan_penjualan,
            SUM(total_pembayaran) AS total_bulanan
        FROM (
            SELECT DISTINCT nomor_invoice, tgl_penjualan, total_pembayaran
            FROM penjualan_tokabe
            WHERE jenis_pembayaran <>'PO' AND
            status <> 'Batal'
            AND deleted_at IS NULL
                AND YEAR(tgl_penjualan) = YEAR(NOW()) - 1
        ) AS subquery
        GROUP BY DATE_FORMAT(tgl_penjualan, '%Y-%m')
        ORDER BY bulan_penjualan ASC";

        $results = DB::select($sql);

        if (!empty($results)) {
            $bulan_penjualan_last_year = [];
            $total_bulanan_last_year = [];

            foreach ($results as $row) {
                $bulan_penjualan_last_year[] = $row->bulan_penjualan;
                $total_bulanan_last_year[] = $row->total_bulanan;
            }

            return [
                'bulanPenjualanLastYear' => $bulan_penjualan_last_year,
                'totalBulananLastYear' => $total_bulanan_last_year,
            ];
        } else {
            return [
                'bulanPenjualanLastYear' => [],
                'totalBulananLastYear' => [],
            ];
        }
    } catch (\PDOException $e) {
        dd($e->getMessage());
        return 'Error';
    }
}


    function getTotalPerMinggu()
    {
        Carbon::setLocale('id');
        $today = Carbon::now();
        $startDate = $today->startOfWeek()->isoFormat('YYYY-MM-DD');
        $endDate = $today->endOfWeek()->isoFormat('YYYY-MM-DD');
        $previousWeekStart = $today->subWeek()->startOfWeek()->isoFormat('YYYY-MM-DD');
        $previousWeekEnd = $today->endOfWeek()->isoFormat('YYYY-MM-DD');

        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();
        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousWeekStart, $previousWeekEnd])->get();
        $penjualanInvoice = $invoice->sum('total_pembayaran');
        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];
        foreach ($invoice as $inv) {

            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }
        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }
        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = ($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0;
        // return [$canceledPaid, $preCanceledPaid, $moneyInIncreasePercentage, $canceledPaidIncreasePercentage, $remainNotPaidDecreasePercentage];
        // return [$moneyIn, $remainNotPaid, $canceledPaid];
        return [
            'weekDatas' => $datas,
            'weekIn' => $moneyInIncreasePercentage,
            'weekNotPaid' => $remainNotPaidDecreasePercentage,
            'weekCanceled' => $canceledPaidIncreasePercentage,
        ];
    }

    function getTotalPerBulan()
    {
        Carbon::setLocale('id');
        $today = Carbon::now()->addHours(7);

        $startDate = $today->startOfMonth()->isoFormat('YYYY-MM-DD');
        $endDate = $today->endOfMonth()->isoFormat('YYYY-MM-DD');
        $previousMonthStart = $today->subMonth()->startOfMonth()->isoFormat('YYYY-MM-DD');
        $previousMonthEnd = $today->endOfMonth()->isoFormat('YYYY-MM-DD');
        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();

        $invoicetest = PenjualanTokabe::where('tgl_penjualan', $endDate)->get();
        // dd($invoicetest);

        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousMonthStart, $previousMonthEnd])->get();
        // $penjualanInvoice = $invoice->sum('total_pembayaran');
        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];

        foreach ($invoice as $inv) {
            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }

        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }


        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = number_format((($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0), 2);

        return [
            'monthDatas' => $datas,
            'monthIn' => $moneyInIncreasePercentage,
            'monthNotPaid' => $remainNotPaidDecreasePercentage,
            'monthCanceled' => $canceledPaidIncreasePercentage,
        ];
    }

    function getTotalPerTahun()
    {
        Carbon::setLocale('id');
        $today = Carbon::now();
        $startDate = $today->startOfYear()->isoFormat('YYYY-MM-DD');
        $endDate = $today->endOfYear()->isoFormat('YYYY-MM-DD');
        $previousYearStart = $today->subYear()->startOfYear()->isoFormat('YYYY-MM-DD');
        $previousYearEnd = $today->endOfYear()->isoFormat('YYYY-MM-DD');

        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();
        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousYearStart, $previousYearEnd])->get();
        $penjualanInvoice = $invoice->sum('total_pembayaran');
        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];

        foreach ($invoice as $inv) {
            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }

        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }

        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = number_format((($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0), 2);
        // dd($canceledPaid, $preBtlBayar);
        return [
            'yearDatas' => $datas,
            'yearIn' => $moneyInIncreasePercentage,
            'yearNotPaid' => $remainNotPaidDecreasePercentage,
            'yearCanceled' => $canceledPaidIncreasePercentage,
        ];
    }

    function getTotalPerTahunLalu()
    {
        Carbon::setLocale('id');
        $today = Carbon::now();
        $startDate = $today->copy()->subYear()->startOfYear()->isoFormat('YYYY-MM-DD');
        $endDate = $today->copy()->subYear()->endOfYear()->isoFormat('YYYY-MM-DD');
        $previousYearStart = $today->copy()->subYears(2)->startOfYear()->isoFormat('YYYY-MM-DD');
        $previousYearEnd = $today->copy()->subYears(2)->endOfYear()->isoFormat('YYYY-MM-DD');

        $invoice = PenjualanTokabe::whereBetween('tgl_penjualan', [$startDate, $endDate])->get();
        $previousInv = PenjualanTokabe::whereBetween('tgl_penjualan', [$previousYearStart, $previousYearEnd])->get();

        $bayar = [];
        $blmBayar = [];
        $btlBayar = [];
        $preBayar = [];
        $preBlmBayar = [];
        $preBtlBayar = [];

        foreach ($invoice as $inv) {
            if ($inv->status == 'Belum Lunas') {
                $bayar[] = $inv->dp;
                $blmBayar[] = $inv->total_pembayaran - $inv->dp;
            } elseif ($inv->status == 'Lunas') {
                $bayar[] = $inv->total_pembayaran;
            } else {
                $btlBayar[] = $inv->total_pembayaran;
            }
        }

        foreach ($previousInv as $preinv) {
            if ($preinv->status == 'Belum Lunas') {
                $preBayar[] = $preinv->dp;
                $preBlmBayar[] = $preinv->total_pembayaran - $preinv->dp;
            } elseif ($preinv->status == 'Lunas') {
                $preBayar[] = $preinv->total_pembayaran;
            } else {
                $preBtlBayar[] = $preinv->total_pembayaran;
            }
        }

        $moneyIn = array_sum($bayar);
        $remainNotPaid = array_sum($blmBayar);
        $canceledPaid = array_sum($btlBayar);
        $preMoneyIn = array_sum($preBayar);
        $preRemainNotPaid = array_sum($preBlmBayar);
        $preCanceledPaid = array_sum($preBtlBayar);

        $datas = [$moneyIn, $remainNotPaid, $canceledPaid];

        $moneyInIncreasePercentage = number_format((($preMoneyIn != 0) ? (($moneyIn - $preMoneyIn) / $preMoneyIn) * 100 : 0), 2);
        $remainNotPaidDecreasePercentage = number_format((($preRemainNotPaid != 0) ? (($preRemainNotPaid - $remainNotPaid) / $preRemainNotPaid) * 100 : 0), 2);
        $canceledPaidIncreasePercentage = number_format((($preCanceledPaid != 0) ? (($canceledPaid - $preCanceledPaid) / $preCanceledPaid) * 100 : 0), 2);

        return [
            'lastYearDatas' => $datas,
            'lastYearIn' => $moneyInIncreasePercentage,
            'lastYearNotPaid' => $remainNotPaidDecreasePercentage,
            'lastYearCanceled' => $canceledPaidIncreasePercentage,
        ];
    }
}

