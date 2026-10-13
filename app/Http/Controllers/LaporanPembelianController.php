<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembelianExport;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // Jika tidak ada permintaan lihat atau download, tampilkan halaman form filter
        if (!$request->boolean('lihat') && !$request->boolean('download')) {
            $vendors = Vendor::orderBy('nama_vendor')->get();
            $tahun = Pembelian::query()
                ->whereNotNull('tanggal')
                ->selectRaw('YEAR(tanggal) as tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun');

            if ($tahun->isEmpty()) {
                $tahun = collect([now()->year]);
            }

            return view('pages.laporan.laporan-pembelian', compact('vendors', 'tahun'));
        }

        $query = Pembelian::with('vendor', 'barang')->orderByDesc('tanggal');
        $tipe = $request->input('tipe', 'all');

        $vendorName = 'Semua Vendor';
        if ($request->filled('vendor') && $request->vendor !== 'semua') {
            $query->where('id_vendor', $request->vendor);
            $v = Vendor::find($request->vendor);
            if ($v) {
                $vendorName = $v->nama_vendor;
            }
        }

        $statusLabel = 'Semua Status';
        if ($request->filled('status') && in_array($request->status, ['Lunas', 'Belum Lunas'], true)) {
            $query->where('status', $request->status);
            $statusLabel = $request->status;
        }

        $periodeLabel = 'Seluruh Periode';

        if ($tipe === 'custom') {
            $tglMulai = $request->input('tanggal_mulai');
            $tglAkhir = $request->input('tanggal_akhir', $tglMulai);

            if ($tglMulai) {
                if ($tglAkhir && $tglAkhir !== $tglMulai) {
                    $query->whereBetween('tanggal', [$tglMulai, $tglAkhir]);
                    $periodeLabel = Carbon::parse($tglMulai)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($tglAkhir)->translatedFormat('d M Y');
                } else {
                    $query->whereDate('tanggal', $tglMulai);
                    $periodeLabel = Carbon::parse($tglMulai)->translatedFormat('d M Y');
                }
            }
        } elseif ($tipe === 'monthly') {
            $bulan = $request->input('bulan', now()->month);
            $tahun = $request->input('tahun', now()->year);
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $periodeLabel = Carbon::create()->month((int) $bulan)->translatedFormat('F') . ' ' . $tahun;
        } elseif ($tipe === 'yearly') {
            $tahun = $request->input('tahun', now()->year);
            $query->whereYear('tanggal', $tahun);
            $periodeLabel = 'Tahun ' . $tahun;
        }

        $pembelian = $query->get();

        $grandTotalTransaksi = $pembelian->sum('jumlah_harga');
        $grandTotalTerbayar  = $pembelian->sum('terbayar');
        $grandTotalSisa      = $pembelian->sum('sisa');

        if ($request->boolean('download')) {
            $safeVendor = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $vendorName);
            $safePeriode = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $periodeLabel);
            $filename = "Laporan Pembelian {$safeVendor} {$safePeriode}.xlsx";

            return Excel::download(
                new PembelianExport($pembelian, $vendorName, $periodeLabel, $statusLabel, $grandTotalTransaksi, $grandTotalTerbayar, $grandTotalSisa),
                $filename
            );
        }

        return view('pages.laporan.laporan-pembelian-hasil', compact(
            'pembelian',
            'vendorName',
            'periodeLabel',
            'statusLabel',
            'tipe',
            'grandTotalTransaksi',
            'grandTotalTerbayar',
            'grandTotalSisa'
        ));
    }
}
