<?php

namespace App\Http\Controllers;

use App\Models\BebanBelanja;
use App\Models\Budget;
use App\Models\KategoriBeban;
use App\Models\Rekening;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class BebanBelanjaController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();
        $today = $now->copy()->toDateString();

        // Auto-lock beban belanja yang sudah dibuka (Unlock) lebih dari 6 jam
        BebanBelanja::where('approval', 'Unlock')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<', now()->subHours(6))
            ->update(['approval' => 'Lock', 'approved_at' => null]);

        // 1. KPI Statistics (Bulan Berjalan & Hari Ini)
        $totalBulanIni = BebanBelanja::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->sum('nominal');
        $totalTransaksiBulanIni = BebanBelanja::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();
        $totalHariIni = BebanBelanja::whereDate('tanggal', $today)->sum('nominal');

        $topCategory = BebanBelanja::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->join('kategori_beban', 'beban_belanja.kategori_id', '=', 'kategori_beban.id')
            ->select('kategori_beban.nama_kategori', DB::raw('SUM(beban_belanja.nominal) as total_nominal'))
            ->groupBy('kategori_beban.id', 'kategori_beban.nama_kategori')
            ->orderByDesc('total_nominal')
            ->first();

        $kategoriTerbesar = $topCategory ? $topCategory->nama_kategori . ' (Rp ' . number_format($topCategory->total_nominal, 0, ',', '.') . ')' : '-';

        // 2. Query Builder dengan Filter
        $query = BebanBelanja::with(['kategori', 'anggaran', 'rekening', 'admin']);

        // Default: bulan ini jika tidak ada filter kustom atau pilih periode
        $filterMode = $request->query('filter_mode', 'this_month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($filterMode === 'this_month') {
            $query->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
            $startDate = $startOfMonth;
            $endDate = $endOfMonth;
        } elseif ($filterMode === 'today') {
            $query->whereDate('tanggal', $today);
            $startDate = $today;
            $endDate = $today;
        } elseif ($filterMode === 'custom' && $startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }
        // if filter_mode == 'all', no date constraint

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('anggaran_id')) {
            if ($request->anggaran_id === 'none') {
                $query->whereNull('anggaran_id');
            } else {
                $query->where('anggaran_id', $request->anggaran_id);
            }
        }

        if ($request->filled('rekening_id')) {
            $query->where('rekening_id', $request->rekening_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_beban', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_beban', 'LIKE', "%{$search}%")
                    ->orWhere('penerima', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        // Hitung total dari hasil filter
        $totalFiltered = (clone $query)->sum('nominal');

        $bebanList = $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(15)->withQueryString();

        // 3. Data Pendukung Form
        $kategoris = KategoriBeban::withCount('bebanBelanja')->orderBy('nama_kategori')->get();
        $budgets = Budget::orderBy('tanggal', 'desc')->get();
        $rekenings = Rekening::where('status', 'Aktif')->get();
        $autoNomor = BebanBelanja::generateNomorBeban();

        return view('pages.beban_belanja.index', compact(
            'bebanList',
            'kategoris',
            'budgets',
            'rekenings',
            'autoNomor',
            'totalBulanIni',
            'totalTransaksiBulanIni',
            'totalHariIni',
            'kategoriTerbesar',
            'totalFiltered',
            'filterMode',
            'startDate',
            'endDate'
        ));
    }

    public function store(Request $request)
    {
        // Bersihkan nominal dari format ribuan
        if ($request->has('nominal')) {
            $cleanNominal = preg_replace('/[^0-9]/', '', (string)$request->nominal);
            $request->merge(['nominal' => $cleanNominal !== '' ? (int)$cleanNominal : 0]);
        }

        $request->validate([
            'tanggal'     => ['required', 'date'],
            'kategori_id' => ['required', 'exists:kategori_beban,id'],
            'nama_beban'  => ['required', 'string', 'max:255'],
            'nominal'     => ['required', 'integer', 'min:1'],
            'anggaran_id' => ['nullable', 'exists:budget,id'],
            'rekening_id' => ['nullable', 'exists:rekening,id'],
            'penerima'    => ['nullable', 'string', 'max:150'],
            'keterangan'  => ['nullable', 'string', 'max:1000'],
            'bukti_struk' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,webp', 'max:5120'],
        ], [
            'tanggal.required'     => 'Tanggal belanja wajib diisi.',
            'kategori_id.required' => 'Kategori beban wajib dipilih.',
            'nama_beban.required'  => 'Deskripsi belanja wajib diisi.',
            'nominal.required'     => 'Nominal pengeluaran wajib diisi.',
            'nominal.min'          => 'Nominal pengeluaran minimal Rp 1.',
            'bukti_struk.mimes'    => 'Bukti struk harus berupa file gambar (JPG, PNG, WEBP) atau PDF.',
            'bukti_struk.max'      => 'Ukuran bukti struk maksimal 5 MB.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_struk')) {
            $file = $request->file('bukti_struk');
            $uploadDir = public_path('uploads/beban_belanja');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }
            $fileName = 'struk_' . date('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $buktiPath = 'uploads/beban_belanja/' . $fileName;
        }

        $nomorBeban = BebanBelanja::generateNomorBeban($request->tanggal);

        DB::transaction(function () use ($request, $buktiPath, $nomorBeban) {
            BebanBelanja::create([
                'nomor_beban' => $nomorBeban,
                'tanggal'     => $request->tanggal,
                'kategori_id' => $request->kategori_id,
                'nama_beban'  => $request->nama_beban,
                'nominal'     => $request->nominal,
                'anggaran_id' => $request->filled('anggaran_id') ? $request->anggaran_id : null,
                'rekening_id' => $request->rekening_id ?: null,
                'penerima'    => $request->penerima,
                'bukti_struk' => $buktiPath,
                'keterangan'  => $request->keterangan,
                'admin_id'    => Auth::id(),
                'approval'    => 'Lock',
                'approved_at' => null,
            ]);

            if ($request->filled('anggaran_id')) {
                $this->adjustBudget($request->anggaran_id, -$request->nominal);
            }
        });

        Alert::success('Berhasil', 'Pengeluaran beban belanja berhasil dicatat.');
        return redirect()->route('beban-belanja.index');
    }

    public function update(Request $request, $id)
    {
        $beban = BebanBelanja::findOrFail($id);

        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $beban->approval !== 'Unlock') {
            Alert::error('Akses Terkunci', 'Akses edit beban belanja terkunci. Silakan minta persetujuan (request) ke Owner terlebih dahulu.');
            return redirect()->route('beban-belanja.index');
        }

        if ($request->has('nominal')) {
            $cleanNominal = preg_replace('/[^0-9]/', '', (string)$request->nominal);
            $request->merge(['nominal' => $cleanNominal !== '' ? (int)$cleanNominal : 0]);
        }

        $request->validate([
            'tanggal'     => ['required', 'date'],
            'kategori_id' => ['required', 'exists:kategori_beban,id'],
            'nama_beban'  => ['required', 'string', 'max:255'],
            'nominal'     => ['required', 'integer', 'min:1'],
            'anggaran_id' => ['nullable', 'exists:budget,id'],
            'rekening_id' => ['nullable', 'exists:rekening,id'],
            'penerima'    => ['nullable', 'string', 'max:150'],
            'keterangan'  => ['nullable', 'string', 'max:1000'],
            'bukti_struk' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,webp', 'max:5120'],
        ], [
            'tanggal.required'     => 'Tanggal belanja wajib diisi.',
            'kategori_id.required' => 'Kategori beban wajib dipilih.',
            'nama_beban.required'  => 'Deskripsi belanja wajib diisi.',
            'nominal.required'     => 'Nominal pengeluaran wajib diisi.',
            'nominal.min'          => 'Nominal pengeluaran minimal Rp 1.',
            'bukti_struk.mimes'    => 'Bukti struk harus berupa file gambar (JPG, PNG, WEBP) atau PDF.',
            'bukti_struk.max'      => 'Ukuran bukti struk maksimal 5 MB.',
        ]);

        if ($request->hasFile('bukti_struk')) {
            // Hapus file lama jika ada
            if ($beban->bukti_struk && File::exists(public_path($beban->bukti_struk))) {
                File::delete(public_path($beban->bukti_struk));
            }

            $file = $request->file('bukti_struk');
            $uploadDir = public_path('uploads/beban_belanja');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }
            $fileName = 'struk_' . date('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $beban->bukti_struk = 'uploads/beban_belanja/' . $fileName;
        }

        DB::transaction(function () use ($request, $beban) {
            $oldAnggaranId = $beban->anggaran_id;
            $oldNominal = (int)$beban->nominal;
            $newAnggaranId = $request->filled('anggaran_id') ? $request->anggaran_id : null;
            $newNominal = (int)$request->nominal;

            // Kembalikan saldo anggaran sebelumnya jika ada
            if ($oldAnggaranId) {
                $this->adjustBudget($oldAnggaranId, $oldNominal);
            }

            // Potong saldo anggaran baru jika dipilih
            if ($newAnggaranId) {
                $this->adjustBudget($newAnggaranId, -$newNominal);
            }

            $beban->tanggal     = $request->tanggal;
            $beban->kategori_id = $request->kategori_id;
            $beban->nama_beban  = $request->nama_beban;
            $beban->nominal     = $newNominal;
            $beban->anggaran_id = $newAnggaranId;
            $beban->rekening_id = $request->rekening_id ?: null;
            $beban->penerima    = $request->penerima;
            $beban->keterangan  = $request->keterangan;
            $beban->approval    = 'Lock';
            $beban->approved_at = null;
            $beban->save();
        });

        Alert::success('Berhasil', 'Data beban belanja berhasil diperbarui dan akses telah dikunci kembali.');
        return redirect()->route('beban-belanja.index');
    }

    public function requestUnlock($id)
    {
        $beban = BebanBelanja::findOrFail($id);
        if ($beban->approval === 'Unlock') {
            Alert::info('Info', 'Akses beban belanja ini sudah terbuka.');
            return redirect()->back();
        }

        $beban->update([
            'approval' => 'Pending',
        ]);

        Alert::success('Permintaan Terkirim', 'Permintaan izin buka kunci (edit/hapus) untuk #' . $beban->nomor_beban . ' telah dikirim ke Owner.');
        return redirect()->back();
    }

    public function unlock($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik (Owner) yang dapat membuka kunci beban belanja.');
            return redirect()->back();
        }

        $beban = BebanBelanja::findOrFail($id);
        $beban->update([
            'approval'    => 'Unlock',
            'approved_at' => now(),
        ]);

        Alert::success('Akses Dibuka', 'Akses edit/hapus untuk #' . $beban->nomor_beban . ' berhasil dibuka untuk Admin.');
        return redirect()->back();
    }

    public function lock($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            Alert::error('Akses Ditolak', 'Hanya Pemilik (Owner) yang dapat mengunci beban belanja.');
            return redirect()->back();
        }

        $beban = BebanBelanja::findOrFail($id);
        $beban->update([
            'approval'    => 'Lock',
            'approved_at' => null,
        ]);

        Alert::success('Akses Dikunci', 'Akses edit/hapus untuk #' . $beban->nomor_beban . ' berhasil dikunci kembali.');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $beban = BebanBelanja::findOrFail($id);

        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $beban->approval !== 'Unlock') {
            Alert::error('Akses Terkunci', 'Akses hapus beban belanja terkunci. Silakan minta persetujuan (request) ke Owner terlebih dahulu.');
            return redirect()->route('beban-belanja.index');
        }

        DB::transaction(function () use ($beban) {
            if ($beban->anggaran_id) {
                $this->adjustBudget($beban->anggaran_id, (int)$beban->nominal);
            }
            $beban->delete();
        });

        Alert::success('Berhasil', 'Data beban belanja berhasil dihapus.');
        return redirect()->route('beban-belanja.index');
    }

    private function adjustBudget($id, $amount): void
    {
        if ($id && $amount != 0) {
            try {
                $budget = Budget::lockForUpdate()->find($id);
                if ($budget) {
                    $budget->anggaran += $amount;
                    $budget->save();
                }
            } catch (\Throwable $e) {
                Log::error("Gagal menyesuaikan anggaran ID {$id}: " . $e->getMessage());
            }
        }
    }

    public function export(Request $request)
    {
        return $this->exportPdf($request);
    }

    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');
        $query = BebanBelanja::with(['kategori', 'anggaran', 'rekening', 'admin']);

        $filterMode = $request->query('filter_mode', 'this_month');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if ($filterMode === 'this_month') {
            $now = Carbon::now();
            $query->whereBetween('tanggal', [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()]);
            $periodeLabel = 'Bulan Ini (' . Carbon::now()->translatedFormat('F Y') . ')';
        } elseif ($filterMode === 'today') {
            $query->whereDate('tanggal', Carbon::now()->toDateString());
            $periodeLabel = 'Hari Ini (' . Carbon::now()->translatedFormat('d F Y') . ')';
        } elseif ($filterMode === 'custom' && $startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
            $periodeLabel = Carbon::parse($startDate)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($endDate)->translatedFormat('d M Y');
        } else {
            $periodeLabel = 'Semua Riwayat Belanja';
        }

        $kategoriLabel = 'Semua Kategori';
        if ($request->filled('kategori_id')) {
            $kat = KategoriBeban::find($request->kategori_id);
            if ($kat) {
                $kategoriLabel = $kat->nama_kategori;
            }
        }

        $anggaranLabel = 'Semua Anggaran';
        if ($request->filled('anggaran_id')) {
            if ($request->anggaran_id === 'none') {
                $query->whereNull('anggaran_id');
                $anggaranLabel = 'Non-Anggaran (Operasional Mandiri)';
            } else {
                $query->where('anggaran_id', $request->anggaran_id);
                $bgt = Budget::find($request->anggaran_id);
                if ($bgt) {
                    $anggaranLabel = $bgt->nama_budget;
                }
            }
        }

        if ($request->filled('rekening_id')) {
            $query->where('rekening_id', $request->rekening_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_beban', 'LIKE', "%{$search}%")
                    ->orWhere('nomor_beban', 'LIKE', "%{$search}%")
                    ->orWhere('penerima', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        $records = $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();
        $totalNominal = $records->sum('nominal');
        $totalTransaksi = $records->count();

        $tanggalCetak = Carbon::now()->translatedFormat('d F Y, H:i') . ' WIB';
        $adminCetak = Auth::user() ? (Auth::user()->nama ?: Auth::user()->name) : 'Admin Tokabe';
        $roleCetak = Auth::user() ? (Auth::user()->role ?: 'Staff') : 'Staff Tokabe';

        $logoPath = public_path('images/LogoTKB.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

        $pdf = Pdf::loadView('pages.beban_belanja.pdf', compact(
            'records',
            'totalNominal',
            'totalTransaksi',
            'periodeLabel',
            'kategoriLabel',
            'anggaranLabel',
            'tanggalCetak',
            'adminCetak',
            'roleCetak',
            'logoBase64'
        ))->setPaper('a4', 'landscape');

        $filename = 'Laporan_Beban_Belanja_' . date('Ymd_His') . '.pdf';

        if ($request->has('download') && $request->download == '1') {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
}
