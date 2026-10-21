<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Exports\LaporanBarangExport;
use App\Models\Invoice;
use App\Models\PenjualanTokabe;

use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Options;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class LaporanController extends Controller
{

    public function index()
    {


        Carbon::setLocale('id');
        $perusahaan = collect([2 => 'Total Karya Berkah']);
        $katInv = Invoice::where('perusahaan_id', 2)->pluck('nama_invoice', 'id');
        $distinctPenjualan = PenjualanTokabe::withTrashed()
            ->where(function($q) { $q->where('is_custom', 0)->orWhereNull('is_custom'); })
            ->distinct()
            ->pluck('tgl_penjualan');
        $namaBulan = [];
        $daftarTahun = [];

        for ($i = 1; $i <= 12; $i++) {
            $tanggal = Carbon::create(null, $i, null);
            $namaBulan[] = $tanggal->monthName;
        }

        foreach ($distinctPenjualan as $tglPenjualan) {
            $tanggal = Carbon::parse($tglPenjualan);

            // Menambahkan tahun jika belum ada di array $daftarTahun
            if (!in_array($tanggal->year, $daftarTahun)) {
                $daftarTahun[] = $tanggal->year;
            }
        }

        return view('pages.laporan.daftar-laporan', compact('katInv', 'namaBulan', 'daftarTahun','perusahaan'));
    }
    /**
     * Mempersiapkan data relasi item dan format PPN/Diskon/Potongan untuk laporan PDF
     */
    private function preparePenjualansForPdf($penjualans)
    {
        foreach ($penjualans as $inv) {
            // Jika invoice di-soft-delete dan status bukan Batal, tandai sebagai Dihapus
            if ($inv instanceof PenjualanTokabe) {
                if ($inv->trashed() && $inv->status !== 'Batal') {
                    $inv->status = 'Dihapus';
                }
            } elseif (isset($inv->deleted_at) && $inv->deleted_at !== null && $inv->status !== 'Batal') {
                $inv->status = 'Dihapus';
            }

            $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
            $inv->formatted_total_pembayaran = 'Rp.' . number_format($inv->total_pembayaran, 0, ',', ',');
            $inv->formatted_total_harga = 'Rp.' . number_format($inv->total_harga, 0, ',', ',');

            $hasPpn = !empty($inv->ppn) && (float)$inv->ppn > 0;
            $hasDiskon = !empty($inv->diskon) && (float)$inv->diskon > 0;
            $hasPotongan = !empty($inv->potongan) && (float)$inv->potongan > 0;
            $hasPph = !empty($inv->pph) && (float)$inv->pph > 0;

            $autoPpnPersen = 0;
            if (!$hasPpn && !$hasDiskon && !$hasPotongan && !$hasPph && $inv->total_pembayaran > $inv->total_harga && $inv->total_harga > 0) {
                $autoPpnNominal = $inv->total_pembayaran - $inv->total_harga;
                $autoPpnPersen = round(($autoPpnNominal / $inv->total_harga) * 100);
                $hasPpn = true;
            }

            if ($hasDiskon) {
                $inv->lain = 'dsc ' . $inv->diskon . '%';
            } elseif ($hasPpn) {
                $ppnVal = !empty($inv->ppn) ? $inv->ppn : $autoPpnPersen;
                $inv->lain = 'ppn ' . $ppnVal . '%';
            } elseif ($hasPph) {
                $inv->lain = 'pph ' . $inv->pph . '%';
            } elseif ($hasPotongan) {
                $inv->lain = 'pot Rp.' . number_format($inv->potongan, 0, ',', '.');
            } else {
                $inv->lain = '-';
            }

            // --- INJEKSI KODE RELASI ITEM ---
            if (isset($inv->id)) {
                $tableRelasi = 'penjualan_jasa_tokabe';
                $fkColumn = 'penjualan_id';

                try {
                    $inv->items = DB::table($tableRelasi)
                        ->leftJoin('barang', $tableRelasi . '.barang_id', '=', 'barang.id')
                        ->leftJoin('items', $tableRelasi . '.item_id', '=', 'items.id')
                        ->where($tableRelasi . '.' . $fkColumn, $inv->id)
                        ->select(DB::raw('COALESCE(items.nama_item, barang.jenis_barang) as jenis_barang'), $tableRelasi . '.deskripsi_item', $tableRelasi . '.qty', $tableRelasi . '.satuan')
                        ->get();
                } catch (\Exception $e) {
                    $inv->items = collect([]);
                }
            } else {
                $inv->items = collect([]);
            }
        }
        return $penjualans;
    }

    // Export ByDate (Custom Date)
    public function exportByDate(Request $request)
    {
        Carbon::setLocale('id');
        $tgl_custom = $request->tgl_custom;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        $tglMulai = null;
        $tglAkhir = null;
        $tglAkhirFormat = null;
        $namaPerusahaan = 'Total Karya Berkah';
        if (strpos($tgl_custom, ' to ') !== false) {
            // Jika terdapat kata "to" dalam string
            $tglArray = explode(' to ', $tgl_custom);
            $tglMulai = Carbon::createFromFormat('d M, Y', trim($tglArray[0]))->toDateString();
            $tglAkhir = Carbon::createFromFormat('d M, Y', trim($tglArray[1]))->toDateString();
        } else {
            // Jika tidak terdapat kata "to" dalam string
            try {
                $tglMulai = Carbon::createFromFormat('d M, Y', trim($tgl_custom))->toDateString();
            } catch (\Exception $e) {
                $tglMulai = date('Y-m-d', strtotime(str_replace(',', '', trim($tgl_custom))));
            }
        }

        $query = PenjualanTokabe::withTrashed()->where(function($q) { $q->where('is_custom', 0)->orWhereNull('is_custom'); });
        if (is_null($tglAkhir)) {
            $query->whereDate('tgl_penjualan', $tglMulai);
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
        } else {
            $query->whereBetween('tgl_penjualan', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59']);
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
            $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');
        }

        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('invoice', $request->invoice);
        }
        $penjualans = $query->get();
        $grandTotal = $penjualans->sum('total_pembayaran');

        $periode = $tglAkhirFormat != null ? 'Tanggal ' . $tglMulaiFormat . ' - ' . $tglAkhirFormat : 'Tanggal ' . $tglMulaiFormat;

        if ($penjualans->isEmpty()) {
            Alert::error('Data pada tanggal tersebut tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');

            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan dari tanggal ' . $tglMulai . ' Hingga tanggal ' . $tglAkhir . ' '  . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }

    // Export ByInvoice
    public function exportByInvoice(Request $request)
    {
        $namaPerusahaan = 'Total Karya Berkah';

        $status_invoice = $request->status;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';

        $query = PenjualanTokabe::withTrashed()
            ->where(function($q) { $q->where('is_custom', 0)->orWhereNull('is_custom'); })
            ->orderByDesc('created_at');
        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('invoice', $request->invoice);
        }
        if ($status_invoice != 'semua' && $status_invoice != 'Semua Status' && !empty($status_invoice)) {
            if ($status_invoice === 'Dihapus') {
                $query->where(function ($q) {
                    $q->whereNotNull('deleted_at')->orWhere('status', 'Dihapus');
                });
            } elseif ($status_invoice === 'Batal') {
                $query->where('status', 'Batal');
            } else {
                $query->where('status', $status_invoice)->whereNull('deleted_at');
            }
        }
        $penjualans = $query->get();

        $grandTotal = $penjualans->sum('total_pembayaran');
        if ($status_invoice == 'semua' || $status_invoice == 'Semua Status' || empty($status_invoice)) {
            $status_invoice = 'Seluruh Status';
        }

        if ($penjualans->isEmpty()) {
            Alert::error('Data Invoice tersebut tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan ' . $namaInvoice . ' ' . $status_invoice . ' ' . $tanggal . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf', compact('penjualans', 'namaInvoice', 'status_invoice', 'formatGrandTotal', 'tanggal', 'namaPerusahaan'));
            }
        }
    }

    // Export berdasarkan bulan pada tahun sekarang
    public function exportByMonth(Request $request)
    {
        $bulanIndonesia = $request->bulan;
        $periode = 'Bulan ' . $request->bulan;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        $namaPerusahaan = 'Total Karya Berkah';

        // Mengonversi nama bulan
        if (is_numeric($bulanIndonesia)) {
            $bulan = str_pad((int)$bulanIndonesia, 2, '0', STR_PAD_LEFT);
        } else {
            $bulanMap = [
                'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'April' => '04',
                'Mei' => '05', 'Juni' => '06', 'Juli' => '07', 'Agustus' => '08',
                'September' => '09', 'Oktober' => '10', 'November' => '11', 'Desember' => '12',
            ];
            $bulan = $bulanMap[$bulanIndonesia] ?? date('m', strtotime($bulanIndonesia));
        }

        $tahun = $request->input('tahun', date('Y'));
        $query = PenjualanTokabe::withTrashed()
            ->where(function($q) { $q->where('is_custom', 0)->orWhereNull('is_custom'); })
            ->whereMonth('tgl_penjualan', $bulan)
            ->whereYear('tgl_penjualan', $tahun);
        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('invoice', $request->invoice);
        }
        $penjualans = $query->get();
        $grandTotal = $penjualans->sum('total_pembayaran');

        if ($penjualans->isEmpty()) {
            Alert::error('Data Laporan Pada Bulan ' . $request->bulan . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan ' . $namaInvoice . ' Bulan ' . $request->bulan . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }

    // Export Laporan Berdasarkan Tahun
    public function exportByYear(Request $request)
    {
        $periode = 'Tahun ' . $request->tahun;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        $tahun = $request->tahun;
        $namaPerusahaan = 'Total Karya Berkah';

        $query = PenjualanTokabe::withTrashed()
            ->where(function($q) { $q->where('is_custom', 0)->orWhereNull('is_custom'); })
            ->whereYear('tgl_penjualan', $tahun);
        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('invoice', $request->invoice);
        }
        $penjualans = $query->get();
        $grandTotal = $penjualans->sum('total_pembayaran');

        if ($penjualans->isEmpty()) {
            Alert::error('Data Laporan ' . $namaInvoice . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan ' . $namaInvoice . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }
    
   public function exportByBarang(Request $request)
    {
        Carbon::setLocale('id');
        $tgl_custom = $request->tgl_custom;
        $invoice = Invoice::find($request->invoice); // Mengambil data invoice berdasarkan ID
        $namaInvoice = $invoice ? $invoice->nama_invoice : 'Semua Invoice';
        $tglMulai = null;
        $tglAkhir = null;
        $tglAkhirFormat = null;
        if (strpos($tgl_custom, ' to ') !== false) {
            // Jika terdapat kata "to" dalam string
            $tglArray = explode(' to ', $tgl_custom);

            $tglMulai = Carbon::createFromFormat('d M, Y', trim($tglArray[0]))->toDateString();
            $tglAkhir = Carbon::createFromFormat('d M, Y', trim($tglArray[1]))->toDateString();
        } else {
            // Jika tidak terdapat kata "to" dalam string
            try {
                $tglMulai = Carbon::createFromFormat('d M, Y', trim($tgl_custom))->toDateString();
            } catch (\Exception $e) {
                $tglMulai = date('Y-m-d', strtotime(str_replace(',', '', trim($tgl_custom))));
            }
        }

        $query = DB::table('penjualan_tokabe')
            ->where(function($q) { $q->where('penjualan_tokabe.is_custom', 0)->orWhereNull('penjualan_tokabe.is_custom'); })
            ->join('penjualan_jasa_tokabe', 'penjualan_tokabe.id', '=', 'penjualan_jasa_tokabe.penjualan_id')
            ->leftJoin('barang', 'penjualan_jasa_tokabe.barang_id', '=', 'barang.id')
            ->leftJoin('items', 'penjualan_jasa_tokabe.item_id', '=', 'items.id')
            ->select(
                'penjualan_tokabe.nomor_invoice',
                'penjualan_tokabe.tgl_penjualan',
                'penjualan_tokabe.customer',
                'penjualan_tokabe.perusahaan',
                'penjualan_tokabe.no_telepon',
                'penjualan_tokabe.deleted_at',
                'penjualan_tokabe.status',
                'penjualan_tokabe.alasan_batal',
                'penjualan_jasa_tokabe.deskripsi_item',
                'penjualan_jasa_tokabe.qty',
                'penjualan_jasa_tokabe.harga',
                'penjualan_jasa_tokabe.jumlah_harga',
                DB::raw('COALESCE(items.nama_item, barang.jenis_barang) as jenis_barang')
            );

        if (is_null($tglAkhir)) {
            $query->whereDate('penjualan_tokabe.tgl_penjualan', $tglMulai);
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
        } else {
            $query->whereBetween('penjualan_tokabe.tgl_penjualan', [$tglMulai . ' 00:00:00', $tglAkhir . ' 23:59:59']);
            $tglMulaiFormat = Carbon::parse($tglMulai)->isoFormat('DD MMMM YYYY');
            $tglAkhirFormat = Carbon::parse($tglAkhir)->isoFormat('DD MMMM YYYY');
        }

        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('penjualan_tokabe.invoice', $request->invoice);
        }

        $penjualans = $query->orderBy('penjualan_tokabe.tgl_penjualan', 'asc')->get();
        $grandTotal = $penjualans->sum('jumlah_harga');

        $periode = $tglAkhirFormat != null ? 'Tanggal ' . $tglMulaiFormat . ' - ' . $tglAkhirFormat : 'Tanggal ' . $tglMulaiFormat;

        if ($penjualans->isEmpty()) {
            Alert::error('Data pada tanggal tersebut tidak tersedia');
            return redirect('daftar-laporan');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');

            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanBarangExport($penjualans), 'Laporan dari tanggal ' . $tglMulai . ' Hingga tanggal ' . $tglAkhir . ' '  . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                // Render tampilan PDF
                foreach ($penjualans as $inv) {
                    if (isset($inv->deleted_at) && $inv->deleted_at !== null && $inv->status !== 'Batal') {
                        $inv->status = 'Dihapus';
                    }
                    $inv->formatted_tgl_penjualan = Carbon::parse($inv->tgl_penjualan)->locale('id')->isoFormat('DD MMMM YYYY');
                    $inv->formatted_harga_barang = 'Rp.' . number_format($inv->harga, 0, ',', ',');

                    $inv->formatted_total_harga = 'Rp.' . number_format($inv->jumlah_harga, 0, ',', ',');
                }
                return view('pages.laporan.pdf-laporan-barang', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode'));
            }
        }
        
    }
}