<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index()
    {
        if (Item::count() === 0) {
            $defaultItems = [
                ['nama_item' => 'Barang', 'jenis_pajak' => 'ppn'],
                ['nama_item' => 'Barang dan Jasa', 'jenis_pajak' => 'ppn_pph'],
                ['nama_item' => 'Jasa', 'jenis_pajak' => 'pph'],
                ['nama_item' => 'Jasa Periklanan', 'jenis_pajak' => 'ppn_pph'],
            ];
            foreach ($defaultItems as $row) {
                Item::firstOrCreate(
                    ['nama_item' => $row['nama_item']],
                    ['jenis_pajak' => $row['jenis_pajak']]
                );
            }
        }

        $items = Item::orderBy('id')->get();

        return view('pages.items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_item' => ['required', 'string', 'max:255', 'unique:items,nama_item'],
            'jenis_pajak' => [
                'required',
                Rule::in(['ppn', 'pph', 'ppn_pph', 'tanpa_pajak']),
            ],
        ]);

        Item::create($validated);

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'nama_item' => ['required', 'string', 'max:255', Rule::unique('items', 'nama_item')->ignore($item->id)],
            'jenis_pajak' => [
                'required',
                Rule::in(['ppn', 'pph', 'ppn_pph', 'tanpa_pajak']),
            ],
        ]);

        $item->update($validated);

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}
