<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PenjualanTokabe;
use App\Models\SptMasa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SptMasaController extends Controller
{
    public const JENIS_PAJAK_LIST = [
        'SPT Masa PPN',
        'PPh Pasal 21',
        'PPh Pasal 23',
        'PPh Pasal 4 Ayat (2) / Final',
        'PPh Pasal 25',
        'PPh Final UMKM (0,5%)',
        'Pajak Lainnya',
    ];

    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $today = Carbon::today()->toDateString();

        // Auto-lock SPT Masa yang sudah dibuka (Unlock) lebih dari 6 jam
        SptMasa::where('approval', 'Unlock')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<', now()->subHours(6))
            ->update(['approval' => 'Lock', 'approved_at' => null]);

        // Sinkronisasi status Terlambat secara otomatis hanya untuk SPT Kurang Bayar yang melewati jatuh tempo
        SptMasa::whereIn('status', ['Belum Dibayar', 'Menunggu Pembayaran'])
            ->where(function ($q) {
                $q->whereNull('status_kb_lb')
                    ->orWhere('status_kb_lb', 'Kurang Bayar');
            })
            ->whereDate('tgl_jatuh_tempo', '<', $today)
            ->update(['status' => 'Terlambat']);

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        $filterBulan = $request->query('masa_pajak_bulan', 'all');
        $filterTahun = $request->query('masa_pajak_tahun', (string) $currentYear);
        $filterJenis = $request->query('jenis_pajak', 'all');
        $filterStatus = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = SptMasa::with('creator');

        if ($filterBulan !== 'all' && $filterBulan !== '') {
            $query->where('masa_pajak_bulan', (int) $filterBulan);
        }

        if ($filterTahun !== 'all' && $filterTahun !== '') {
            $query->where('masa_pajak_tahun', (int) $filterTahun);
        }

        if ($filterJenis !== 'all' && $filterJenis !== '') {
            if ($filterJenis === 'SPT Masa PPN') {
                $query->whereIn('jenis_pajak', ['SPT Masa PPN', 'PPN Masa']);
            } else {
                $query->where('jenis_pajak', $filterJenis);
            }
        }

        if ($filterStatus !== 'all' && $filterStatus !== '') {
            $query->where('status', $filterStatus);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_spt', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_billing', 'LIKE', "%{$search}%")
                    ->orWhere('jenis_pajak', 'LIKE', "%{$search}%")
                    ->orWhere('ntpn', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        // Statistik berdasarkan filter aktif
        $totalKewajibanDicatat = (int) (clone $query)->sum('nominal_pajak');
        $totalSudahDibayar = (int) (clone $query)->where('status', 'Lunas')->sum('nominal_pajak');
        $totalBelumDibayar = (int) (clone $query)->whereIn('status', ['Belum Dibayar', 'Menunggu Pembayaran', 'Terlambat'])->sum('nominal_pajak');
        $totalLebihBayar = (int) (clone $query)->sum('nominal_lebih_bayar');
        $jumlahTerlambat = (int) (clone $query)->where('status', 'Terlambat')->count();

        $sptList = $query
            ->orderByDesc('masa_pajak_tahun')
            ->orderByDesc('masa_pajak_bulan')
            ->orderByDesc('id')
            ->get();

        // Rekapitulasi Bulanan dalam Tahun Terpilih
        $rekapTahun = ($filterTahun !== 'all' && $filterTahun !== '') ? (int) $filterTahun : $currentYear;
        $rekapRows = SptMasa::where('masa_pajak_tahun', $rekapTahun)
            ->select(
                'masa_pajak_bulan',
                DB::raw('COUNT(*) as total_spt'),
                DB::raw('SUM(nominal_pajak) as total_kewajiban'),
                DB::raw("SUM(CASE WHEN status = 'Lunas' THEN nominal_pajak ELSE 0 END) as total_lunas"),
                DB::raw("SUM(CASE WHEN status IN ('Belum Dibayar', 'Menunggu Pembayaran', 'Terlambat') THEN nominal_pajak ELSE 0 END) as total_belum_lunas"),
                DB::raw('SUM(COALESCE(nominal_lebih_bayar, 0)) as total_lebih_bayar')
            )
            ->groupBy('masa_pajak_bulan')
            ->orderBy('masa_pajak_bulan')
            ->get()
            ->keyBy('masa_pajak_bulan');

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $rekapBulanan = [];
        foreach ($bulanNames as $num => $nama) {
            $row = $rekapRows->get($num);
            $rekapBulanan[$num] = [
                'bulan'             => $num,
                'nama_bulan'        => $nama,
                'total_spt'         => $row ? (int) $row->total_spt : 0,
                'total_kewajiban'   => $row ? (int) $row->total_kewajiban : 0,
                'total_lunas'       => $row ? (int) $row->total_lunas : 0,
                'total_belum_lunas' => $row ? (int) $row->total_belum_lunas : 0,
                'total_lebih_bayar' => $row ? (int) $row->total_lebih_bayar : 0,
            ];
        }

        // Daftar tahun tersedia
        $availableYears = SptMasa::select('masa_pajak_tahun')
            ->distinct()
            ->orderByDesc('masa_pajak_tahun')
            ->pluck('masa_pajak_tahun')
            ->toArray();

        if (!in_array($currentYear, $availableYears)) {
            array_unshift($availableYears, $currentYear);
        }
        if (!in_array($currentYear - 1, $availableYears)) {
            $availableYears[] = $currentYear - 1;
        }
        rsort($availableYears);

        $jenisPajakList = self::JENIS_PAJAK_LIST;

        return view('pages.spt-masa.index', compact(
            'sptList',
            'totalKewajibanDicatat',
            'totalSudahDibayar',
            'totalBelumDibayar',
            'totalLebihBayar',
            'jumlahTerlambat',
            'rekapBulanan',
            'rekapTahun',
            'availableYears',
            'jenisPajakList',
            'bulanNames',
            'filterBulan',
            'filterTahun',
            'filterJenis',
            'filterStatus',
            'search',
            'currentYear',
            'currentMonth'
        ));
    }

    /**
     * Endpoint AJAX: Tarik otomatis ringkasan Penyerahan (Penjualan Tokabe) & Perolehan (Pembelian Vendor)
     * untuk mengisi Formulir Induk SPT Masa PPN sesuai periode bulan & tahun.
     */
    public function tarikDataTransaksiAjax(Request $request)
    {
        $bulan = (int) $request->query('bulan', date('n'));
        $tahun = (int) $request->query('tahun', date('Y'));

        // 1. Data Penjualan (Pajak Keluaran - Bagian I)
        $invoices = PenjualanTokabe::whereYear('tgl_penjualan', $tahun)
            ->whereMonth('tgl_penjualan', $bulan)
            ->where('status', '!=', 'Batal')
            ->get();

        // I.A.2: Dipungut sendiri dengan DPP Nilai Lain (Kode Faktur 04 / 05)
        $inv04 = $invoices->where('ppn', '>', 0)->whereIn('kode_ppn', ['04', '05']);
        $ia2HargaJual = (int) round($inv04->sum('jumlah_harga'));
        $ia2DppNilaiLain = (int) round($ia2HargaJual * 11 / 12);
        $ia2Ppn = (int) round($inv04->sum('ppn'));

        // I.A.4: Dipungut sendiri lainnya (Kode Faktur 01, 09, 10, atau kosong namun ada PPN)
        $inv01 = $invoices->where('ppn', '>', 0)->filter(function ($inv) {
            return !in_array($inv->kode_ppn, ['02', '03', '04', '05', '07', '08'], true);
        });
        $ia4HargaJual = (int) round($inv01->sum('jumlah_harga'));
        $ia4Dpp = $ia4HargaJual;
        $ia4Ppn = (int) round($inv01->sum('ppn'));

        // I.A.6: Dipungut oleh Pemungut PPN (Kode Faktur 02 dan 03 - Instansi Pemerintah / BUMN)
        $inv0203 = $invoices->where('ppn', '>', 0)->whereIn('kode_ppn', ['02', '03']);
        $ia6HargaJual = (int) round($inv0203->sum('jumlah_harga'));
        $ia6DppNilaiLain = (int) round($ia6HargaJual * 11 / 12);
        $ia6Ppn = (int) round($inv0203->sum('ppn'));

        // I.B: Penyerahan tidak terutang PPN
        $invNoPpn = $invoices->filter(fn ($inv) => (float) ($inv->ppn ?? 0) <= 0);
        $ibTidakTerutang = (int) round($invNoPpn->sum('jumlah_harga'));

        // 2. Data Pembelian Vendor (Pajak Masukan - Bagian II)
        $pembelians = Pembelian::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->where('status', '!=', 'Batal')
            ->get();

        $pembelianPpn = $pembelians->filter(fn ($p) => (float) ($p->ppn_nominal ?? 0) > 0);
        $iibHargaPerolehan = (int) round($pembelianPpn->sum(function ($p) {
            return (float) $p->jumlah_harga - (float) ($p->ppn_nominal ?? 0) + (float) ($p->pph_nominal ?? 0);
        }));
        $iibDppNilaiLain = (int) round($iibHargaPerolehan * 11 / 12);
        $iibPpnMasukan = (int) round($pembelianPpn->sum('ppn_nominal'));

        $pembelianNonPpn = $pembelians->filter(fn ($p) => (float) ($p->ppn_nominal ?? 0) <= 0);
        $iihPerolehanNonKredit = (int) round($pembelianNonPpn->sum('jumlah_harga'));

        // 3. Cek Kompensasi Kelebihan PPN Masa Sebelumnya (II.E)
        $prevDate = Carbon::createFromDate($tahun, $bulan, 1)->subMonth();
        $prevSpt = SptMasa::where('masa_pajak_tahun', $prevDate->year)
            ->where('masa_pajak_bulan', $prevDate->month)
            ->whereIn('jenis_pajak', ['SPT Masa PPN', 'PPN Masa'])
            ->where('status_kb_lb', 'Lebih Bayar')
            ->orderByDesc('id')
            ->first();

        $iieKompensasiSebelumnya = $prevSpt ? (int) ($prevSpt->nominal_lebih_bayar ?? 0) : 0;

        // Jatuh tempo SPT Masa PPN = akhir bulan berikutnya
        $jatuhTempoPpn = Carbon::createFromDate($tahun, $bulan, 1)->addMonth()->endOfMonth()->toDateString();

        return response()->json([
            'success' => true,
            'jatuh_tempo_ppn' => $jatuhTempoPpn,
            'jumlah_invoice' => $invoices->count(),
            'jumlah_pembelian' => $pembelians->count(),
            'data' => [
                'ia2_harga_jual'   => $ia2HargaJual,
                'ia2_dpp'          => $ia2DppNilaiLain,
                'ia2_ppn'          => $ia2Ppn,
                'ia4_harga_jual'   => $ia4HargaJual,
                'ia4_dpp'          => $ia4Dpp,
                'ia4_ppn'          => $ia4Ppn,
                'ia6_harga_jual'   => $ia6HargaJual,
                'ia6_dpp'          => $ia6DppNilaiLain,
                'ia6_ppn'          => $ia6Ppn,
                'ib_tidak_terutang'=> $ibTidakTerutang,
                'iib_harga'        => $iibHargaPerolehan,
                'iib_dpp'          => $iibDppNilaiLain,
                'iib_ppn'          => $iibPpnMasukan,
                'iie_kompensasi'   => $iieKompensasiSebelumnya,
                'iih_non_kredit'   => $iihPerolehanNonKredit,
            ],
        ]);
    }

    private function cleanCurrency($val): int
    {
        $clean = preg_replace('/[^0-9]/', '', (string) $val);
        return $clean !== '' ? (int) $clean : 0;
    }

    private function extractRincianPpnFromRequest(Request $request): array
    {
        if ($request->has('simple_ppn_keluaran') || $request->has('simple_ppn_masukan')) {
            $iiiaPpnDipungutSendiri = $this->cleanCurrency($request->input('simple_ppn_keluaran', 0));
            $ia6Ppn                 = $this->cleanCurrency($request->input('simple_ppn_pemungut', 0));
            $iigPajakMasukanKredit  = $this->cleanCurrency($request->input('simple_ppn_masukan', 0));
            $omzetInput             = $this->cleanCurrency($request->input('simple_omzet', 0));

            $ia6Harga = $ia6Ppn > 0 ? (int) round($ia6Ppn / 0.11) : 0;
            $ia6Dpp   = $ia6Harga > 0 ? (int) round($ia6Harga * 11 / 12) : 0;

            $ia2Ppn   = $iiiaPpnDipungutSendiri;
            $ia2Harga = $ia2Ppn > 0 ? (int) round($ia2Ppn / 0.11) : 0;
            $ia2Dpp   = $ia2Harga > 0 ? (int) round($ia2Harga * 11 / 12) : 0;

            $jumlahPenyerahanTerutang = $omzetInput > 0 ? $omzetInput : ($ia2Harga + $ia6Harga);
            $jumlahSeluruhPenyerahan  = $jumlahPenyerahanTerutang;
            $jumlahPpnKeluaranTotal   = $ia2Ppn + $ia6Ppn;

            $iibPpn   = $iigPajakMasukanKredit;
            $iibHarga = $iibPpn > 0 ? (int) round($iibPpn / 0.11) : 0;
            $iibDpp   = $iibHarga > 0 ? (int) round($iibHarga * 11 / 12) : 0;

            $ia4Harga = 0;
            $ia4Dpp = 0;
            $ia4Ppn = 0;
            $ibTidakTerutang = 0;
            $iicHarga = 0;
            $iicDpp = 0;
            $iicPpn = 0;
            $iieKompensasi = 0;
            $iihNonKredit = 0;
            $iijJumlahPerolehan = $iibHarga;
            $iiibPpnDisetorDimuka = 0;
            $iiicPajakMasukan = $iigPajakMasukanKredit;
            $iiieSelisih = $iiiaPpnDipungutSendiri - $iiicPajakMasukan;
        } else {
            // Bagian I: Penyerahan Barang dan Jasa
            $ia2Harga = $this->cleanCurrency($request->input('ia2_harga_jual', 0));
            $ia2Dpp   = $this->cleanCurrency($request->input('ia2_dpp', 0));
            $ia2Ppn   = $this->cleanCurrency($request->input('ia2_ppn', 0));

            $ia4Harga = $this->cleanCurrency($request->input('ia4_harga_jual', 0));
            $ia4Dpp   = $this->cleanCurrency($request->input('ia4_dpp', 0));
            $ia4Ppn   = $this->cleanCurrency($request->input('ia4_ppn', 0));

            $ia6Harga = $this->cleanCurrency($request->input('ia6_harga_jual', 0));
            $ia6Dpp   = $this->cleanCurrency($request->input('ia6_dpp', 0));
            $ia6Ppn   = $this->cleanCurrency($request->input('ia6_ppn', 0));

            $ibTidakTerutang = $this->cleanCurrency($request->input('ib_tidak_terutang', 0));

            $jumlahPenyerahanTerutang = $ia2Harga + $ia4Harga + $ia6Harga;
            $jumlahSeluruhPenyerahan  = $jumlahPenyerahanTerutang + $ibTidakTerutang;
            $jumlahPpnKeluaranTotal   = $ia2Ppn + $ia4Ppn + $ia6Ppn;

            // Bagian II: Perolehan Barang dan Jasa (Pajak Masukan)
            $iibHarga      = $this->cleanCurrency($request->input('iib_harga', 0));
            $iibDpp        = $this->cleanCurrency($request->input('iib_dpp', 0));
            $iibPpn        = $this->cleanCurrency($request->input('iib_ppn', 0));
            $iicHarga      = $this->cleanCurrency($request->input('iic_harga', 0));
            $iicDpp        = $this->cleanCurrency($request->input('iic_dpp', 0));
            $iicPpn        = $this->cleanCurrency($request->input('iic_ppn', 0));
            $iieKompensasi = $this->cleanCurrency($request->input('iie_kompensasi', 0));
            $iihNonKredit  = $this->cleanCurrency($request->input('iih_non_kredit', 0));

            $iigPajakMasukanKredit = $iibPpn + $iicPpn + $iieKompensasi;
            $iijJumlahPerolehan    = $iibHarga + $iicHarga + $iihNonKredit;

            // Bagian III: Penghitungan PPN Kurang Bayar / Lebih Bayar
            $iiiaPpnDipungutSendiri = $ia2Ppn + $ia4Ppn;
            $iiibPpnDisetorDimuka   = $this->cleanCurrency($request->input('iiib_disetor_dimuka', 0));
            $iiicPajakMasukan       = $iigPajakMasukanKredit;
            $iiieSelisih            = $iiiaPpnDipungutSendiri - $iiibPpnDisetorDimuka - $iiicPajakMasukan;
        }

        if ($iiieSelisih > 0) {
            $statusKbLb = 'Kurang Bayar';
            $nominalKurangBayar = $iiieSelisih;
            $nominalLebihBayar = 0;
        } elseif ($iiieSelisih < 0) {
            $statusKbLb = 'Lebih Bayar';
            $nominalKurangBayar = 0;
            $nominalLebihBayar = abs($iiieSelisih);
        } else {
            $statusKbLb = 'Nihil';
            $nominalKurangBayar = 0;
            $nominalLebihBayar = 0;
        }

        return [
            'status_kb_lb'         => $statusKbLb,
            'nominal_kurang_bayar' => $nominalKurangBayar,
            'nominal_lebih_bayar'  => $nominalLebihBayar,
            'total_dpp_penyerahan' => $jumlahSeluruhPenyerahan,
            'rincian'              => [
                'ia2_harga_jual'            => $ia2Harga,
                'ia2_dpp'                   => $ia2Dpp,
                'ia2_ppn'                   => $ia2Ppn,
                'ia4_harga_jual'            => $ia4Harga,
                'ia4_dpp'                   => $ia4Dpp,
                'ia4_ppn'                   => $ia4Ppn,
                'ia6_harga_jual'            => $ia6Harga,
                'ia6_dpp'                   => $ia6Dpp,
                'ia6_ppn'                   => $ia6Ppn,
                'ia_jumlah_harga'           => $jumlahPenyerahanTerutang,
                'ia_jumlah_ppn'             => $jumlahPpnKeluaranTotal,
                'ib_tidak_terutang'         => $ibTidakTerutang,
                'ic_seluruh_penyerahan'     => $jumlahSeluruhPenyerahan,
                'iib_harga'                 => $iibHarga,
                'iib_dpp'                   => $iibDpp,
                'iib_ppn'                   => $iibPpn,
                'iic_harga'                 => $iicHarga,
                'iic_dpp'                   => $iicDpp,
                'iic_ppn'                   => $iicPpn,
                'iie_kompensasi'            => $iieKompensasi,
                'iig_pajak_masukan'         => $iigPajakMasukanKredit,
                'iih_non_kredit'            => $iihNonKredit,
                'iij_jumlah_perolehan'      => $iijJumlahPerolehan,
                'iiia_ppn_dipungut_sendiri' => $iiiaPpnDipungutSendiri,
                'iiib_disetor_dimuka'       => $iiibPpnDisetorDimuka,
                'iiic_pajak_masukan'        => $iiicPajakMasukan,
                'iiie_selisih'              => $iiieSelisih,
            ],
        ];
    }

    public function store(Request $request)
    {
        $jenisPajak = $request->input('jenis_pajak', 'SPT Masa PPN');
        $isPpnInduk = in_array($jenisPajak, ['SPT Masa PPN', 'PPN Masa'], true);

        if ($request->has('dpp')) {
            $request->merge(['dpp' => $this->cleanCurrency($request->dpp)]);
        }
        if ($request->has('nominal_pajak')) {
            $request->merge(['nominal_pajak' => $this->cleanCurrency($request->nominal_pajak)]);
        }

        $rules = [
            'masa_pajak_bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'masa_pajak_tahun' => ['required', 'integer', 'min:2020', 'max:2099'],
            'pembetulan_ke'    => ['nullable', 'integer', 'min:0', 'max:99'],
            'jenis_pajak'      => ['required', 'string', 'max:100'],
            'nomor_billing'    => ['nullable', 'string', 'max:100'],
            'tgl_billing'      => ['nullable', 'date'],
            'tgl_jatuh_tempo'  => ['required', 'date'],
            'tgl_lapor'        => ['nullable', 'date'],
            'dpp'              => ['nullable', 'numeric', 'min:0'],
            'tarif_persen'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nominal_pajak'    => $isPpnInduk ? ['nullable', 'integer', 'min:0'] : ['required', 'integer', 'min:1'],
            'tgl_bayar'        => ['nullable', 'date'],
            'ntpn'             => ['nullable', 'string', 'max:100'],
            'bukti_bayar'      => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,webp', 'max:10240'],
            'keterangan'       => ['nullable', 'string', 'max:1000'],
        ];

        $request->validate($rules, [
            'masa_pajak_bulan.required' => 'Bulan masa pajak wajib dipilih.',
            'masa_pajak_tahun.required' => 'Tahun masa pajak wajib diisi.',
            'jenis_pajak.required'      => 'Jenis pajak wajib dipilih.',
            'tgl_jatuh_tempo.required'  => 'Tanggal jatuh tempo penyampaian/pembayaran wajib diisi.',
            'nominal_pajak.required'    => 'Nominal kewajiban pajak wajib diisi.',
            'nominal_pajak.min'         => 'Nominal kewajiban pajak minimal Rp 1.',
            'bukti_bayar.mimes'         => 'Dokumen SPT / bukti bayar harus berupa file PDF, JPG, PNG, atau WEBP.',
            'bukti_bayar.max'           => 'Ukuran dokumen maksimal 10 MB.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $uploadDir = public_path('uploads/spt_masa');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }
            $fileName = 'spt_' . date('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $buktiPath = 'uploads/spt_masa/' . $fileName;
        }

        $pembetulanKe = (int) $request->input('pembetulan_ke', 0);
        $tglLapor = $request->input('tgl_lapor') ?: null;

        if ($isPpnInduk) {
            $ppnCalc = $this->extractRincianPpnFromRequest($request);
            $statusKbLb = $ppnCalc['status_kb_lb'];
            $dppVal = $ppnCalc['total_dpp_penyerahan'];
            $tarifVal = 11.0;
            $nominalKurangBayar = $ppnCalc['nominal_kurang_bayar'];
            $nominalLebihBayar = $ppnCalc['nominal_lebih_bayar'];
            $rincianSpt = $ppnCalc['rincian'];
            $opsiLebihBayar = $statusKbLb === 'Lebih Bayar'
                ? ($request->input('opsi_lebih_bayar') ?: 'Dikompensasikan')
                : null;

            if ($statusKbLb === 'Lebih Bayar') {
                $status = $opsiLebihBayar === 'Dikompensasikan' ? 'Dikompensasikan' : 'Restitusi';
                $tglBayar = null;
            } elseif ($statusKbLb === 'Nihil') {
                $status = 'Nihil';
                $tglBayar = null;
            } else {
                // Kurang Bayar
                $status = $request->input('status', 'Belum Dibayar');
                $tglBayar = $request->input('tgl_bayar') ?: null;
                if ($status === 'Lunas' && !$tglBayar) {
                    $tglBayar = now()->toDateString();
                } elseif ($tglBayar && $status !== 'Lunas') {
                    $status = 'Lunas';
                } elseif ($status !== 'Lunas' && Carbon::today()->gt(Carbon::parse($request->tgl_jatuh_tempo))) {
                    $status = 'Terlambat';
                }
            }
        } else {
            $statusKbLb = 'Kurang Bayar';
            $dppVal = (int) ($request->dpp ?? 0);
            $tarifVal = (float) ($request->tarif_persen ?? 0);
            $nominalKurangBayar = (int) $request->nominal_pajak;
            $nominalLebihBayar = 0;
            $opsiLebihBayar = null;
            $rincianSpt = null;

            $status = $request->input('status', 'Belum Dibayar');
            $tglBayar = $request->input('tgl_bayar') ?: null;

            if ($status === 'Lunas' && !$tglBayar) {
                $tglBayar = now()->toDateString();
            } elseif ($tglBayar && $status !== 'Lunas') {
                $status = 'Lunas';
            } elseif ($status !== 'Lunas' && Carbon::today()->gt(Carbon::parse($request->tgl_jatuh_tempo))) {
                $status = 'Terlambat';
            }
        }

        $nomorSpt = SptMasa::generateNomorSpt((int) $request->masa_pajak_tahun, (int) $request->masa_pajak_bulan);

        SptMasa::create([
            'nomor_spt'           => $nomorSpt,
            'masa_pajak_bulan'    => (int) $request->masa_pajak_bulan,
            'masa_pajak_tahun'    => (int) $request->masa_pajak_tahun,
            'pembetulan_ke'       => $pembetulanKe,
            'jenis_pajak'         => $jenisPajak,
            'nomor_billing'       => $request->nomor_billing,
            'tgl_billing'         => $request->tgl_billing ?: null,
            'tgl_jatuh_tempo'     => $request->tgl_jatuh_tempo,
            'dpp'                 => $dppVal,
            'tarif_persen'        => $tarifVal,
            'nominal_pajak'       => $nominalKurangBayar,
            'status_kb_lb'        => $statusKbLb,
            'nominal_lebih_bayar' => $nominalLebihBayar,
            'opsi_lebih_bayar'    => $opsiLebihBayar,
            'tgl_bayar'           => $status === 'Lunas' ? $tglBayar : null,
            'tgl_lapor'           => $tglLapor,
            'ntpn'                => $request->ntpn,
            'status'              => $status,
            'bukti_bayar'         => $buktiPath,
            'keterangan'          => $request->keterangan,
            'rincian_spt'         => $rincianSpt,
            'approval'            => 'Lock',
            'approved_at'         => null,
            'created_by'          => Auth::id(),
        ]);

        Alert::success('Berhasil', 'Data SPT Masa berhasil disimpan.');
        return redirect()->route('spt-masa.index');
    }

    public function update(Request $request, $id)
    {
        $spt = SptMasa::findOrFail($id);

        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $spt->approval !== 'Unlock') {
            Alert::error('Akses Terkunci', 'Akses edit SPT Masa terkunci. Silakan minta persetujuan (request) ke Owner terlebih dahulu.');
            return redirect()->route('spt-masa.index');
        }

        $jenisPajak = $request->input('jenis_pajak', $spt->jenis_pajak);
        $isPpnInduk = in_array($jenisPajak, ['SPT Masa PPN', 'PPN Masa'], true);

        if ($request->has('dpp')) {
            $request->merge(['dpp' => $this->cleanCurrency($request->dpp)]);
        }
        if ($request->has('nominal_pajak')) {
            $request->merge(['nominal_pajak' => $this->cleanCurrency($request->nominal_pajak)]);
        }

        $request->validate([
            'masa_pajak_bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'masa_pajak_tahun' => ['required', 'integer', 'min:2020', 'max:2099'],
            'pembetulan_ke'    => ['nullable', 'integer', 'min:0', 'max:99'],
            'jenis_pajak'      => ['required', 'string', 'max:100'],
            'nomor_billing'    => ['nullable', 'string', 'max:100'],
            'tgl_billing'      => ['nullable', 'date'],
            'tgl_jatuh_tempo'  => ['required', 'date'],
            'tgl_lapor'        => ['nullable', 'date'],
            'dpp'              => ['nullable', 'numeric', 'min:0'],
            'tarif_persen'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nominal_pajak'    => $isPpnInduk ? ['nullable', 'integer', 'min:0'] : ['required', 'integer', 'min:1'],
            'tgl_bayar'        => ['nullable', 'date'],
            'ntpn'             => ['nullable', 'string', 'max:100'],
            'bukti_bayar'      => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,webp', 'max:10240'],
            'keterangan'       => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('bukti_bayar')) {
            if ($spt->bukti_bayar && File::exists(public_path($spt->bukti_bayar))) {
                File::delete(public_path($spt->bukti_bayar));
            }
            $file = $request->file('bukti_bayar');
            $uploadDir = public_path('uploads/spt_masa');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }
            $fileName = 'spt_' . date('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $spt->bukti_bayar = 'uploads/spt_masa/' . $fileName;
        }

        $pembetulanKe = (int) $request->input('pembetulan_ke', $spt->pembetulan_ke ?? 0);
        $tglLapor = $request->input('tgl_lapor') ?: null;

        if ($isPpnInduk) {
            $ppnCalc = $this->extractRincianPpnFromRequest($request);
            $statusKbLb = $ppnCalc['status_kb_lb'];
            $dppVal = $ppnCalc['total_dpp_penyerahan'];
            $tarifVal = 11.0;
            $nominalKurangBayar = $ppnCalc['nominal_kurang_bayar'];
            $nominalLebihBayar = $ppnCalc['nominal_lebih_bayar'];
            $rincianSpt = $ppnCalc['rincian'];
            $opsiLebihBayar = $statusKbLb === 'Lebih Bayar'
                ? ($request->input('opsi_lebih_bayar') ?: 'Dikompensasikan')
                : null;

            if ($statusKbLb === 'Lebih Bayar') {
                $status = $opsiLebihBayar === 'Dikompensasikan' ? 'Dikompensasikan' : 'Restitusi';
                $tglBayar = null;
            } elseif ($statusKbLb === 'Nihil') {
                $status = 'Nihil';
                $tglBayar = null;
            } else {
                $status = $request->input('status', 'Belum Dibayar');
                $tglBayar = $request->input('tgl_bayar') ?: null;
                if ($status === 'Lunas' && !$tglBayar) {
                    $tglBayar = $spt->tgl_bayar ? $spt->tgl_bayar->toDateString() : now()->toDateString();
                } elseif ($status !== 'Lunas') {
                    $tglBayar = null;
                    if (Carbon::today()->gt(Carbon::parse($request->tgl_jatuh_tempo))) {
                        $status = 'Terlambat';
                    }
                }
            }
        } else {
            $statusKbLb = 'Kurang Bayar';
            $dppVal = (int) ($request->dpp ?? 0);
            $tarifVal = (float) ($request->tarif_persen ?? 0);
            $nominalKurangBayar = (int) $request->nominal_pajak;
            $nominalLebihBayar = 0;
            $opsiLebihBayar = null;
            $rincianSpt = null;

            $status = $request->input('status', 'Belum Dibayar');
            $tglBayar = $request->input('tgl_bayar') ?: null;

            if ($status === 'Lunas' && !$tglBayar) {
                $tglBayar = $spt->tgl_bayar ? $spt->tgl_bayar->toDateString() : now()->toDateString();
            } elseif ($status !== 'Lunas') {
                $tglBayar = null;
                if (Carbon::today()->gt(Carbon::parse($request->tgl_jatuh_tempo))) {
                    $status = 'Terlambat';
                }
            }
        }

        $spt->update([
            'masa_pajak_bulan'    => (int) $request->masa_pajak_bulan,
            'masa_pajak_tahun'    => (int) $request->masa_pajak_tahun,
            'pembetulan_ke'       => $pembetulanKe,
            'jenis_pajak'         => $jenisPajak,
            'nomor_billing'       => $request->nomor_billing,
            'tgl_billing'         => $request->tgl_billing ?: null,
            'tgl_jatuh_tempo'     => $request->tgl_jatuh_tempo,
            'dpp'                 => $dppVal,
            'tarif_persen'        => $tarifVal,
            'nominal_pajak'       => $nominalKurangBayar,
            'status_kb_lb'        => $statusKbLb,
            'nominal_lebih_bayar' => $nominalLebihBayar,
            'opsi_lebih_bayar'    => $opsiLebihBayar,
            'tgl_bayar'           => $tglBayar,
            'tgl_lapor'           => $tglLapor,
            'ntpn'                => $request->ntpn,
            'status'              => $status,
            'bukti_bayar'         => $spt->bukti_bayar,
            'keterangan'          => $request->keterangan,
            'rincian_spt'         => $rincianSpt,
            'approval'            => 'Lock',
            'approved_at'         => null,
        ]);

        Alert::success('Berhasil', 'Data SPT Masa berhasil diperbarui dan akses telah dikunci kembali.');
        return redirect()->route('spt-masa.index');
    }

    public function requestUnlock($id)
    {
        $spt = SptMasa::findOrFail($id);
        if ($spt->approval === 'Unlock') {
            Alert::info('Info', 'Akses SPT Masa ini sudah terbuka.');
            return redirect()->back();
        }

        $spt->update([
            'approval' => 'Pending',
        ]);

        Alert::success('Permintaan Terkirim', 'Permintaan izin buka kunci (edit/hapus) untuk SPT #' . $spt->nomor_spt . ' telah dikirim ke Owner.');
        return redirect()->back();
    }

    public function unlock($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik (Owner) yang dapat membuka kunci SPT Masa.');
            return redirect()->back();
        }

        $spt = SptMasa::findOrFail($id);
        $spt->update([
            'approval'    => 'Unlock',
            'approved_at' => now(),
        ]);

        Alert::success('Akses Dibuka', 'Akses edit/hapus untuk SPT #' . $spt->nomor_spt . ' berhasil dibuka untuk Admin.');
        return redirect()->back();
    }

    public function lock($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik (Owner) yang dapat mengunci SPT Masa.');
            return redirect()->back();
        }

        $spt = SptMasa::findOrFail($id);
        $spt->update([
            'approval'    => 'Lock',
            'approved_at' => null,
        ]);

        Alert::success('Akses Dikunci', 'Akses edit/hapus untuk SPT #' . $spt->nomor_spt . ' berhasil dikunci kembali.');
        return redirect()->back();
    }

    public function bayar(Request $request, $id)
    {
        $spt = SptMasa::findOrFail($id);

        $request->validate([
            'tgl_bayar'     => ['required', 'date'],
            'nomor_billing' => ['nullable', 'string', 'max:100'],
            'ntpn'          => ['nullable', 'string', 'max:100'],
            'bukti_bayar'   => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,webp', 'max:10240'],
            'keterangan'    => ['nullable', 'string', 'max:1000'],
        ], [
            'tgl_bayar.required' => 'Tanggal pembayaran wajib diisi.',
            'bukti_bayar.mimes'  => 'Bukti pembayaran harus berupa file PDF, JPG, PNG, atau WEBP.',
            'bukti_bayar.max'    => 'Ukuran bukti pembayaran maksimal 10 MB.',
        ]);

        if ($request->hasFile('bukti_bayar')) {
            if ($spt->bukti_bayar && File::exists(public_path($spt->bukti_bayar))) {
                File::delete(public_path($spt->bukti_bayar));
            }
            $file = $request->file('bukti_bayar');
            $uploadDir = public_path('uploads/spt_masa');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }
            $fileName = 'spt_' . date('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $spt->bukti_bayar = 'uploads/spt_masa/' . $fileName;
        }

        $spt->update([
            'tgl_bayar'     => $request->tgl_bayar,
            'nomor_billing' => $request->filled('nomor_billing') ? $request->nomor_billing : $spt->nomor_billing,
            'ntpn'          => $request->filled('ntpn') ? $request->ntpn : $spt->ntpn,
            'status'        => 'Lunas',
            'bukti_bayar'   => $spt->bukti_bayar,
            'keterangan'    => $request->filled('keterangan') ? $request->keterangan : $spt->keterangan,
        ]);

        Alert::success('Lunas', 'Pembayaran SPT Masa berhasil dicatat dan dihitung sebagai beban pajak.');
        return redirect()->route('spt-masa.index');
    }

    public function destroy($id)
    {
        $spt = SptMasa::findOrFail($id);
        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $spt->approval !== 'Unlock') {
            Alert::error('Akses Terkunci', 'Akses hapus SPT Masa terkunci. Silakan minta persetujuan (request) ke Owner terlebih dahulu.');
            return redirect()->route('spt-masa.index');
        }

        $spt->delete();

        Alert::success('Berhasil', 'Data SPT Masa berhasil dihapus.');
        return redirect()->route('spt-masa.index');
    }
}
