<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetController extends Controller
{
    function index()
    {
        $budgets = Budget::orderByDesc('updated_at')->get();
        foreach ($budgets as $budget) {
            $budget->formattedAnggaran = number_format($budget->anggaran, 0, ',', ',');
        }
        return view('pages.anggaran.daftar-anggaran', compact('budgets'));
    }

    function tambah()
    {
        $kategori = KategoriBarang::pluck('nama_kategori', 'id');
        return view('pages.anggaran.tambah-anggaran', compact('kategori'));
    }
    function store(Request $request)
    {
        $request->validate([
            'nama_anggaran' => 'required|string|max:191',
            'kategori'      => 'required|exists:kategori_barang,id',
            'anggaran'      => 'required',
            'tanggal'       => 'required|date',
        ], [
            'nama_anggaran.required' => 'Nama anggaran wajib diisi.',
            'kategori.required'      => 'Kategori wajib dipilih.',
            'anggaran.required'      => 'Nominal anggaran wajib diisi.',
            'tanggal.required'       => 'Tanggal alokasi wajib diisi.',
        ]);

        $anggaran = intval(str_replace(',', '', $request->anggaran));
        Budget::create([
            'nama_budget' => $request->nama_anggaran,
            'kategori_id' => $request->kategori,
            'anggaran'    => $anggaran,
            'tanggal'     => $request->tanggal,
        ]);
        Alert::success('Anggaran Berhasil Ditambah');
        return redirect(route('budget.list'));
    }

    function edit($id)
    {
        $budget = Budget::findOrFail($id);
        $kategori = KategoriBarang::pluck('nama_kategori', 'id');
        return view('pages.anggaran.edit-anggaran', compact('budget', 'kategori'));
    }

    function update(Request $request, $id)
    {
        $data = Budget::findOrFail($id);
        $request->validate([
            'nama_anggaran' => 'required|string|max:191',
            'kategori'      => 'required|exists:kategori_barang,id',
            'anggaran'      => 'required',
            'tanggal'       => 'required|date',
        ], [
            'nama_anggaran.required' => 'Nama anggaran wajib diisi.',
            'kategori.required'      => 'Kategori wajib dipilih.',
            'anggaran.required'      => 'Nominal anggaran wajib diisi.',
            'tanggal.required'       => 'Tanggal alokasi wajib diisi.',
        ]);

        $anggaran = intval(str_replace(',', '', $request->anggaran));
        $data->update([
            'nama_budget' => $request->nama_anggaran,
            'kategori_id' => $request->kategori,
            'anggaran'    => $anggaran,
            'tanggal'     => $request->tanggal,
        ]);
        Alert::success('Anggaran Berhasil Diubah');
        return redirect(route('budget.list'));
    }
    function delete($id)
    {
        $budget = Budget::findOrFail($id);
        $budget->delete();
        Alert::success('Budget Berhasil dihapus');
        return redirect(route('budget.list'));
    }
}
