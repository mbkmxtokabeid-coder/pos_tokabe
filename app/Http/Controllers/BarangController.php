<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class BarangController extends Controller
{
    public function index()
    {
        // Menambahkan filter is_active = 1
        $barang = Barang::where('is_active', 1)
            ->select('id', 'jenis_barang', 'kode_barang', 'kategori_id', 'stok', 'harga_modal', 'harga_jual', 'is_material_required', 'created_at', 'updated_at')
            ->get()
            ->map(function ($item) {
                $item->stok = max(0, $item->stok);
                $item->updated_at = Carbon::parse($item->updated_at)->addHours(7)->format('d F Y H:i:s');
                return $item;
            });

        foreach ($barang as $brg) {
            $brg->formattedModal = number_format(intval($brg->harga_modal), 0, ',', ',');
            $brg->formattedJual = number_format(intval($brg->harga_jual), 0, ',', ',');
        }

        $kategori = Barang::join('kategori_barang', 'barang.kategori_id', '=', 'kategori_barang.id')
            ->pluck('kategori_barang.nama_kategori', 'barang.kategori_id');
        
        $kategori_brg = KategoriBarang::where('id', '!=', 5)->get();

        $barang = $barang->sortBy(function ($barang) {
            return $barang->kategori_item->nama_kategori ?? '';
        });

        return view('pages.barang.daftar-barang', [
            'barang' => $barang,
            'kategori' => $kategori,
            'kategoriBrg' => $kategori_brg,
        ]);
    }

    public function create()
    {
        $kategori = KategoriBarang::all();
        return view('pages.barang.tambah-barang', compact('kategori'));
    }

    public function store(Request $request)
    {
        $hargaJual = $request->harga_jual;
        $hargaModal = $request->harga_modal;
        $noComaJual = str_replace(',', '', $hargaJual);
        $noComaModal = str_replace(',', '', $hargaModal);
        $noComaStok = str_replace(',', '', $request->stok);

        Barang::create([
            'kategori_id' => $request->kategori_id,
            'jenis_barang' => $request->jenis_barang,
            'kode_barang' => $request->kode_barang,
            'stok' => $noComaStok,
            'harga_modal' => $noComaModal,
            'harga_jual' => $noComaJual,
            'is_active' => 1, // Default aktif saat dibuat
        ]);

        Alert::success('Barang Berhasil ditambahkan');
        return redirect('listBarang');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        Alert::success('Barang Berhasil dihapus');
        return redirect('listBarang');
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        $kategori = KategoriBarang::where('id', $barang->kategori_id)->value('nama_kategori');
        
        if ($barang->stok < 0) {
            $barang->stok = 0;
        }
        return view('pages.barang.update-stok', compact('barang', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        if ($request->stok < 0) {
            Alert::error('Stok tidak boleh dibawah 0');
            return redirect()->back();
        }

        $stok = intval(str_replace(',', '', $request->stok));
        $hargaModal = intval(str_replace(',', '', $request->harga_modal));
        $hargaJual = intval(str_replace(',', '', $request->harga_jual));

        if ($request->filled('jenis')) {
            $barang->jenis_barang = $request->jenis;
        } elseif ($request->filled('jenis_barang')) {
            $barang->jenis_barang = $request->jenis_barang;
        }
        $barang->stok = $stok;
        $barang->harga_modal = $hargaModal;
        $barang->harga_jual = $hargaJual;
        $barang->update();

        Alert::success('Stok Berhasil di Update');
        return redirect('listBarang');
    }

    
    public function dataBarang($kategori_id)
    {
        // Menambahkan filter is_active = 1
        $totalPotensiPenjualan = Barang::where('is_active', 1)
            ->where('kategori_id', $kategori_id)
            ->selectRaw('SUM(CASE WHEN stok < 0 THEN 0 ELSE stok END * harga_jual) as total_potensi_penjualan')
            ->selectRaw('SUM(CASE WHEN stok < 0 THEN 0 ELSE stok END) as total_stok')
            ->first();

        if ($totalPotensiPenjualan) {
            $totalPotensiPenjualan->total_potensi_penjualan /= 1000000;
        }
        return response()->json($totalPotensiPenjualan);
    }

    public function potensiProfit($kategori_id)
    {
        // Menambahkan filter is_active = 1
        $totalPotensiProfit = Barang::where('is_active', 1)
            ->where('kategori_id', $kategori_id)
            ->selectRaw('SUM(CASE WHEN stok < 0 THEN 0 ELSE stok END * (harga_jual - harga_modal)) as total_profit')
            ->first();

        if ($totalPotensiProfit) {
            $totalPotensiProfit->total_profit /= 1000000;
        }
        return response()->json($totalPotensiProfit);
    }

    public function toggleMaterial(Request $request, $id)
    {
        try {
            $barang = Barang::find($id);
            if (!$barang) {
                return response()->json(['status' => 'error', 'message' => 'Barang tidak ditemukan'], 404);
            }
            $barang->is_material_required = $request->input('is_material_required', 0);
            $barang->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status material berhasil diperbarui',
                'is_material_required' => $barang->is_material_required
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}