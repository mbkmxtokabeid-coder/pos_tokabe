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
use Illuminate\Support\Facades\DB;

class LaporanCustomController extends Controller
{
    /**
     * Halaman index laporan khusus invoice keluar custom
     */
    public function index()
    {
        Carbon::setLocale('id');
        $perusahaan = collect([2 => 'Total Karya Berkah']);
        $katInv = Invoice::where('perusahaan_id', 2)->pluck('nama_invoice', 'id');
        $distinctPenjualan = PenjualanTokabe::withTrashed()
            ->where('is_custom', 1)
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
            if (!in_array($tanggal->year, $daftarTahun)) {
                $daftarTahun[] = $tanggal->year;
            }
        }

        $yearNow = Carbon::now()->year;
        if (empty($daftarTahun)) {
            $daftarTahun = [$yearNow, $yearNow - 1];
        } else {
            if (!in_array($yearNow, $daftarTahun)) {
                array_unshift($daftarTahun, $yearNow);
            }
            rsort($daftarTahun);
        }

        // Statistik ringkas invoice custom untuk header card
        $totalInvoiceCustom = PenjualanTokabe::where('is_custom', 1)->whereNull('deleted_at')->count();
        $totalNominalCustom = PenjualanTokabe::where('is_custom', 1)->whereNull('deleted_at')->where('status', '!=', 'Batal')->sum('total_pembayaran');
        $totalLunasCustom = PenjualanTokabe::where('is_custom', 1)->whereNull('deleted_at')->where('status', 'Lunas')->sum('total_pembayaran');
        $totalBelumLunasCustom = PenjualanTokabe::where('is_custom', 1)->whereNull('deleted_at')->where('status', 'Belum Lunas')->sum('sisa_pembayaran');

        return view('pages.laporan.daftar-laporan-custom', compact(
            'katInv', 'namaBulan', 'daftarTahun', 'perusahaan',
            'totalInvoiceCustom', 'totalNominalCustom', 'totalLunasCustom', 'totalBelumLunasCustom'
        ));
    }

    /**
     * Mempersiapkan data relasi item dan format PPN/Diskon/Potongan untuk laporan PDF
     */
    private function preparePenjualansForPdf($penjualans)
    {
        foreach ($penjualans as $inv) {
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

    /**
     * Export Laporan Custom Berdasarkan Status Invoice
     */
    public function exportByInvoice(Request $request)
    {
        $namaPerusahaan = 'Total Karya Berkah';
        $status_invoice = $request->status;
        $invoice = Invoice::find($request->invoice);
        $namaInvoice = $invoice ? $invoice->nama_invoice . ' (Custom)' : 'Invoice Custom';

        $query = PenjualanTokabe::withTrashed()
            ->where('is_custom', 1)
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
            Alert::error('Data Invoice Custom tersebut tidak tersedia');
            return redirect()->route('laporan.custom');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan Custom ' . $namaInvoice . ' ' . $status_invoice . ' ' . $tanggal . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf', compact('penjualans', 'namaInvoice', 'status_invoice', 'formatGrandTotal', 'tanggal', 'namaPerusahaan'));
            }
        }
    }

    /**
     * Export Laporan Custom Berdasarkan Rentang Tanggal
     */
    public function exportByDate(Request $request)
    {
        Carbon::setLocale('id');
        $tgl_custom = $request->tgl_custom;
        $invoice = Invoice::find($request->invoice);
        $namaInvoice = $invoice ? $invoice->nama_invoice . ' (Custom)' : 'Invoice Custom';
        $tglMulai = null;
        $tglAkhir = null;
        $tglAkhirFormat = null;
        $namaPerusahaan = 'Total Karya Berkah';

        if (strpos($tgl_custom, ' to ') !== false) {
            $tglArray = explode(' to ', $tgl_custom);
            $tglMulai = Carbon::createFromFormat('d M, Y', trim($tglArray[0]))->toDateString();
            $tglAkhir = Carbon::createFromFormat('d M, Y', trim($tglArray[1]))->toDateString();
        } else {
            try {
                $tglMulai = Carbon::createFromFormat('d M, Y', trim($tgl_custom))->toDateString();
            } catch (\Exception $e) {
                $tglMulai = date('Y-m-d', strtotime(str_replace(',', '', trim($tgl_custom))));
            }
        }

        $query = PenjualanTokabe::withTrashed()->where('is_custom', 1);
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
            Alert::error('Data Invoice Custom pada rentang tanggal tersebut tidak tersedia');
            return redirect()->route('laporan.custom');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan Custom dari tanggal ' . $tglMulai . ' Hingga ' . $tglAkhir . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }

    /**
     * Export Laporan Custom Berdasarkan Bulan
     */
    public function exportByMonth(Request $request)
    {
        $bulanIndonesia = $request->bulan;
        $periode = 'Bulan ' . $request->bulan;
        $invoice = Invoice::find($request->invoice);
        $namaInvoice = $invoice ? $invoice->nama_invoice . ' (Custom)' : 'Invoice Custom';
        $namaPerusahaan = 'Total Karya Berkah';

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
            ->where('is_custom', 1)
            ->whereMonth('tgl_penjualan', $bulan)
            ->whereYear('tgl_penjualan', $tahun);

        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('invoice', $request->invoice);
        }
        $penjualans = $query->get();
        $grandTotal = $penjualans->sum('total_pembayaran');

        if ($penjualans->isEmpty()) {
            Alert::error('Data Laporan Invoice Custom Pada Bulan ' . $request->bulan . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect()->route('laporan.custom');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan Custom ' . $namaInvoice . ' Bulan ' . $request->bulan . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }

    /**
     * Export Laporan Custom Berdasarkan Tahun
     */
    public function exportByYear(Request $request)
    {
        $periode = 'Tahun ' . $request->tahun;
        $invoice = Invoice::find($request->invoice);
        $namaInvoice = $invoice ? $invoice->nama_invoice . ' (Custom)' : 'Invoice Custom';
        $tahun = $request->tahun;
        $namaPerusahaan = 'Total Karya Berkah';

        $query = PenjualanTokabe::withTrashed()
            ->where('is_custom', 1)
            ->whereYear('tgl_penjualan', $tahun);

        if ($request->invoice != 'semua' && !empty($request->invoice)) {
            $query->where('invoice', $request->invoice);
        }
        $penjualans = $query->get();
        $grandTotal = $penjualans->sum('total_pembayaran');

        if ($penjualans->isEmpty()) {
            Alert::error('Data Laporan Invoice Custom ' . $namaInvoice . ' Tahun ' . $tahun . ' tidak tersedia');
            return redirect()->route('laporan.custom');
        } else {
            $tanggal = Carbon::now()->locale('id')->isoFormat('DD MMMM YYYY');
            $exportType = $request->input('export_type', 'pdf');
            if ($exportType == 'excel') {
                return Excel::download(new LaporanExport($penjualans, $namaPerusahaan), 'Laporan Custom ' . $namaInvoice . ' tahun ' . $tahun . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            } else {
                Carbon::setLocale('id');
                $formatGrandTotal = 'Rp.' . number_format($grandTotal, 0, ',', ',');
                $this->preparePenjualansForPdf($penjualans);
                return view('pages.laporan.pdf-non-status', compact('penjualans', 'namaInvoice', 'formatGrandTotal', 'tanggal', 'periode', 'namaPerusahaan'));
            }
        }
    }
}
