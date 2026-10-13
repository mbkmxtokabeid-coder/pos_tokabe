<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::orderBy('nama_item')->get();

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
