<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use DateTimeZone;
use App\Models\Spj;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;




use Illuminate\Http\Request;

class SpjController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        
        $spj = Spj::orderByDesc('tanggal_tugas')->get(); // Ambil semua data SPJ

        // Total biaya & jumlah SPJ yang sudah Terbayar (All-Time)
        $totalTerbayar = Spj::where('status', 'Terbayar')->sum('biaya_bahan_bakar');
        $jumlahTerbayar = Spj::where('status', 'Terbayar')->count();

        // Total biaya & jumlah SPJ yang Belum Bayar (All-Time)
        $totalBelumBayar = Spj::where('status', 'Belum Bayar')->sum('biaya_bahan_bakar');
        $jumlahBelumBayar = Spj::where('status', 'Belum Bayar')->count();

        // Alias variabel lama untuk kompatibilitas
        $totalTahunIni = $totalTerbayar;
        $jumlahSpjTahunIni = $jumlahTerbayar;
        $totalBulanIni = $totalBelumBayar;
        $jumlahSpjBulanIni = $jumlahBelumBayar;

        return view('pages.SPJ.daftar-spj', compact(
            'spj',
            'totalTerbayar',
            'jumlahTerbayar',
            'totalBelumBayar',
            'jumlahBelumBayar',
            'totalTahunIni',
            'jumlahSpjTahunIni',
            'totalBulanIni',
            'jumlahSpjBulanIni'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $tanggal = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); // Get the current year
        $month = $tanggal->format('m'); // Get the current month
        $day = $tanggal->format('d');
        $today = sprintf('%s/%s/%s', $day, $month, $year);

        return view('pages.SPJ.tambah-spj',compact('today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'spj' => 'required|string|in:Tokabe,Personal',
            'nomor_spj' => 'required|string|unique:spj,nomor_spj',
            'tanggal_tugas' => 'required|date_format:d/m/Y',
            'waktu_berangkat' => 'nullable|date_format:H:i',
            'tujuan' => 'required|string|max:255',
            'nama_pemberi_tugas' => 'required|string|max:255',
            'nama_kurir' => 'required|string|max:255',
            'biaya_bahan_bakar' => 'nullable|numeric|min:0',
            'jarak_tempuh' => 'nullable|numeric|min:0',
            'deskripsi_barang' => 'nullable|string',
            'deskripsi_tugas' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'jam_kembali' => 'nullable|date_format:H:i',
            'status' => 'required|in:Terbayar,Belum Bayar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ubah format tanggal tugas dari d/m/Y ke Y-m-d
        $tanggalTugas = \DateTime::createFromFormat('d/m/Y', $request->tanggal_tugas);
        if (!$tanggalTugas) {
            return redirect()->back()
                ->withErrors(['tanggal_tugas' => 'Format tanggal tidak valid'])
                ->withInput();
        }

        // Simpan data ke database
        $spj = new Spj();
        $spj->perusahaan = $request->spj;
        $spj->nomor_spj = $request->nomor_spj;
        $spj->tanggal_tugas = $tanggalTugas->format('Y-m-d');
        $spj->waktu_berangkat = $request->waktu_berangkat;
        $spj->tujuan = $request->tujuan;
        $spj->nama_pemberi_tugas = $request->nama_pemberi_tugas;
        $spj->nama_kurir = $request->nama_kurir;
        $spj->biaya_bahan_bakar = $request->biaya_bahan_bakar ?? 0;
        $spj->jarak_tempuh = $request->jarak_tempuh ?? 0;
        $spj->deskripsi_barang = $request->deskripsi_barang;
        $spj->deskripsi_tugas = $request->deskripsi_tugas;
        $spj->keterangan_tambahan = $request->keterangan_tambahan;
        $spj->jam_kembali = $request->jam_kembali;
        $spj->status = $request->status;

        $spj->save();

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        Alert::success('Berhasil', 'SPJ berhasil ditambahkan');
        return redirect('/daftar-spj')->with('success', 'SPJ berhasil ditambahkan.');
 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $spj = Spj::findOrFail($id);
        $tanggal = Carbon::now(new DateTimeZone('Asia/Jakarta'));
        $year = $tanggal->format('Y'); // Get the current year
        $month = $tanggal->format('m'); // Get the current month
        $day = $tanggal->format('d');
        $today = sprintf('%s/%s/%s', $day, $month, $year);

       
        return view('pages.SPJ.edit-spj', compact('spj', 'today'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $spj = Spj::findOrFail($id);
            $validator = Validator::make($request->all(), [
            'spj' => 'required|string|in:Tokabe,Personal',
            'nomor_spj' => 'required|string',
            'tanggal_tugas' => 'required|date_format:d/m/Y',
            'waktu_berangkat' => 'nullable|date_format:H:i',
            'tujuan' => 'required|string|max:255',
            'nama_pemberi_tugas' => 'required|string|max:255',
            'nama_kurir' => 'required|string|max:255',
            'biaya_bahan_bakar' => 'nullable|numeric|min:0',
            'jarak_tempuh' => 'nullable|numeric|min:0',
            'deskripsi_barang' => 'nullable|string',
            'deskripsi_tugas' => 'nullable|string',
            'keterangan_tambahan' => 'nullable|string',
            'jam_kembali' => 'nullable|date_format:H:i',
            'status' => 'required|in:Terbayar,Belum Bayar',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ubah format tanggal tugas dari d/m/Y ke Y-m-d
        $tanggalTugas = \DateTime::createFromFormat('d/m/Y', $request->tanggal_tugas);
        if (!$tanggalTugas) {
            return redirect()->back()
                ->withErrors(['tanggal_tugas' => 'Format tanggal tidak valid'])
                ->withInput();
        }

        $spj->perusahaan = $request->spj;
        $spj->nomor_spj = $request->nomor_spj;
        $spj->tanggal_tugas = $tanggalTugas->format('Y-m-d');
        $spj->waktu_berangkat = $request->waktu_berangkat;
        $spj->tujuan = $request->tujuan;
        $spj->nama_pemberi_tugas = $request->nama_pemberi_tugas;
        $spj->nama_kurir = $request->nama_kurir;
        $spj->biaya_bahan_bakar = $request->biaya_bahan_bakar ?? 0;
        $spj->jarak_tempuh = $request->jarak_tempuh ?? 0;
        $spj->deskripsi_barang = $request->deskripsi_barang;
        $spj->deskripsi_tugas = $request->deskripsi_tugas;
        $spj->keterangan_tambahan = $request->keterangan_tambahan;
        $spj->jam_kembali = $request->jam_kembali;
        $spj->status = $request->status;

        $spj->update();
        Alert::success('Spj berhasil diupdate');
        return redirect('/daftar-spj');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spj = Spj::findOrFail($id);
        
        $spj->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus');
        return redirect('daftar-spj');
    }



    public function generateNumber(Request $request)
    {
        $spj = $request->query('spj');

        $kodeMap = [
            'Tokabe' => 'TKB',
            'Personal' => 'PRS',
        ];

        if (!isset($kodeMap[$spj])) {
            return response()->json(['error' => 'Invalid SPJ type'], 400);
        }

        $kode = $kodeMap[$spj];

        $bulan = date('m');
        $tahun = date('Y');

        $prefix = "/SPJ/{$kode}/{$bulan}/{$tahun}";

        $lastNomor = Spj::where('nomor_spj', 'like', "%{$prefix}")
            ->orderBy('nomor_spj', 'desc')
            ->value('nomor_spj');

        if ($lastNomor) {
            $parts = explode('/', $lastNomor);
            $lastNumber = (int)$parts[0];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $nextNumberPadded = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $nomorBaru = "{$nextNumberPadded}/SPJ/{$kode}/{$bulan}/{$tahun}";

        return response()->json(['nomor_spj' => $nomorBaru]);
    }

    public function cetak($id)
    {
        Carbon::setLocale('id');
        $current_date=Carbon::now()->format('d-m-Y'); 
        $spj = Spj::findOrFail($id);
        $spj->tanggal_tugas = Carbon::parse($spj->tanggal_tugas)->format('d-m-y');
        $spj->waktu_berangkat = Carbon::parse($spj->waktu_berangkat)->format('H:i');
        $spj->jam_kembali = Carbon::parse($spj->jam_kembali)->format('H:i');
        $spj->biaya_bahan_bakar = number_format($spj->biaya_bahan_bakar, 0, ',', '.');
        return view('pages.SPJ.cetak-spj', compact('spj','current_date'));
    }

    public function lihat($id)
    {
        Carbon::setLocale('id');
        $current_date=Carbon::now()->format('d-m-Y'); 
        $spj = Spj::findOrFail($id);
        $spj->tanggal_tugas = Carbon::parse($spj->tanggal_tugas)->format('d-m-y');
        $spj->waktu_berangkat = Carbon::parse($spj->waktu_berangkat)->format('H:i');
        $spj->jam_kembali = Carbon::parse($spj->jam_kembali)->format('H:i');
        $spj->biaya_bahan_bakar = number_format($spj->biaya_bahan_bakar, 0, ',', '.');
        return view('pages.SPJ.lihat-spj', compact('spj','current_date'));
    }


}
