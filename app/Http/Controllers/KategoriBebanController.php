<?php

namespace App\Http\Controllers;

use App\Models\KategoriBeban;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriBebanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_beban,nama_kategori'],
            'deskripsi'     => ['nullable', 'string', 'max:255'],
        ], [
            'nama_kategori.required' => 'Nama kategori beban wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori beban tersebut sudah ada.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
            'deskripsi.max'          => 'Deskripsi maksimal 255 karakter.',
        ]);

        KategoriBeban::create([
            'nama_kategori' => trim($request->nama_kategori),
            'deskripsi'     => $request->filled('deskripsi') ? trim($request->deskripsi) : null,
        ]);

        Alert::success('Berhasil', 'Kategori beban baru berhasil ditambahkan.');
        return redirect()->back()
            ->with('success', 'Kategori beban baru berhasil ditambahkan.')
            ->with('open_modal_kategori', true);
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBeban::findOrFail($id);

        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori_beban,nama_kategori,' . $id],
            'deskripsi'     => ['nullable', 'string', 'max:255'],
        ], [
            'nama_kategori.required' => 'Nama kategori beban wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori beban tersebut sudah ada.',
            'nama_kategori.max'      => 'Nama kategori maksimal 100 karakter.',
            'deskripsi.max'          => 'Deskripsi maksimal 255 karakter.',
        ]);

        $kategori->update([
            'nama_kategori' => trim($request->nama_kategori),
            'deskripsi'     => $request->filled('deskripsi') ? trim($request->deskripsi) : null,
        ]);

        Alert::success('Berhasil', 'Kategori beban berhasil diperbarui.');
        return redirect()->back()
            ->with('success', 'Kategori beban berhasil diperbarui.')
            ->with('open_modal_kategori', true);
    }

    public function destroy($id)
    {
        $kategori = KategoriBeban::withCount('bebanBelanja')->findOrFail($id);

        if ($kategori->beban_belanja_count > 0) {
            $msg = "Kategori '{$kategori->nama_kategori}' tidak dapat dihapus karena masih digunakan oleh {$kategori->beban_belanja_count} data beban belanja.";
            Alert::error('Gagal Hapus', $msg);
            return redirect()->back()
                ->with('error', $msg)
                ->with('open_modal_kategori', true);
        }

        $kategori->delete();

        Alert::success('Berhasil', 'Kategori beban berhasil dihapus.');
        return redirect()->back()
            ->with('success', 'Kategori beban berhasil dihapus.')
            ->with('open_modal_kategori', true);
    }
}
