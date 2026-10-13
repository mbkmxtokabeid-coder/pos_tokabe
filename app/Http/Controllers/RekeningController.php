<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RekeningController extends Controller
{
    public function index()
    {
        $rekening = Rekening::orderByDesc('created_at')->get();
        return view('pages.rekening.index', compact('rekening'));
    }

    public function create()
    {
        return redirect()->route('rekening.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:150',
            'nomor_rekening' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Non Aktif',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_bank.required' => 'Nama Bank wajib diisi.',
            'atas_nama.required' => 'Atas Nama (A/N) wajib diisi.',
            'nomor_rekening.required' => 'Nomor Rekening wajib diisi.',
            'status.required' => 'Status rekening wajib dipilih.',
        ]);

        Rekening::create([
            'nama_bank' => trim($request->nama_bank),
            'atas_nama' => trim($request->atas_nama),
            'nomor_rekening' => trim($request->nomor_rekening),
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
            'status' => $request->status,
        ]);

        Alert::success('Berhasil', 'Rekening bank berhasil ditambahkan');
        return redirect()->route('rekening.index');
    }

    public function edit($id)
    {
        $rekening = Rekening::findOrFail($id);
        return view('pages.rekening.edit', compact('rekening'));
    }

    public function update(Request $request, $id)
    {
        $rekening = Rekening::findOrFail($id);

        $request->validate([
            'nama_bank' => 'required|string|max:100',
            'atas_nama' => 'required|string|max:150',
            'nomor_rekening' => 'required|string|max:100',
            'status' => 'required|in:Aktif,Non Aktif',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'nama_bank.required' => 'Nama Bank wajib diisi.',
            'atas_nama.required' => 'Atas Nama (A/N) wajib diisi.',
            'nomor_rekening.required' => 'Nomor Rekening wajib diisi.',
            'status.required' => 'Status rekening wajib dipilih.',
        ]);

        $rekening->update([
            'nama_bank' => trim($request->nama_bank),
            'atas_nama' => trim($request->atas_nama),
            'nomor_rekening' => trim($request->nomor_rekening),
            'keterangan' => $request->keterangan ? trim($request->keterangan) : null,
            'status' => $request->status,
        ]);

        Alert::success('Berhasil', 'Data rekening berhasil diperbarui');
        return redirect()->route('rekening.index');
    }

    public function destroy($id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->delete();

        Alert::success('Berhasil', 'Rekening berhasil dihapus');
        return redirect()->route('rekening.index');
    }

    public function toggleStatus($id)
    {
        $rekening = Rekening::findOrFail($id);
        $rekening->status = ($rekening->status === 'Aktif') ? 'Non Aktif' : 'Aktif';
        $rekening->save();

        Alert::success('Berhasil', 'Status rekening berhasil diubah menjadi ' . $rekening->status);
        return redirect()->route('rekening.index');
    }
}
