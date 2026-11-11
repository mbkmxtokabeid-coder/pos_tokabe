<?php

namespace App\Http\Controllers;

use App\Models\KategoriInvoice;
use App\Models\PenjualanTokabe;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $statusFilter = $request->query('status', 'all');

        $query = KategoriInvoice::withCount('invoices');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'LIKE', "%{$search}%")
                    ->orWhere('prefix', 'LIKE', "%{$search}%")
                    ->orWhere('format_nomor', 'LIKE', "%{$search}%")
                    ->orWhere('keterangan', 'LIKE', "%{$search}%");
            });
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $kategoris = $query->orderByDesc('is_active')->orderBy('nama_kategori')->get();

        $totalKategori = KategoriInvoice::count();
        $totalAktif = KategoriInvoice::where('is_active', true)->count();
        $totalNonAktif = KategoriInvoice::where('is_active', false)->count();

        return view('pages.kategori-invoice.index', compact(
            'kategoris',
            'totalKategori',
            'totalAktif',
            'totalNonAktif',
            'search',
            'statusFilter'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori'     => ['required', 'string', 'max:120'],
            'keterangan'        => ['nullable', 'string', 'max:1000'],
            'use_custom_format' => ['nullable', 'boolean'],
            'prefix'            => ['nullable', 'string', 'max:40'],
            'format_nomor'      => ['nullable', 'string', 'max:150'],
            'padding_digit'     => ['nullable', 'integer', 'min:1', 'max:8'],
            'reset_periode'     => ['required', 'in:yearly,monthly,never'],
            'judul_invoice'     => ['nullable', 'string', 'max:120'],
            'subjudul_invoice'  => ['nullable', 'string', 'max:180'],
            'label_penerima'    => ['nullable', 'string', 'max:100'],
            'catatan_kaki'      => ['nullable', 'string', 'max:500'],
            'is_active'         => ['nullable', 'boolean'],
        ], [
            'nama_kategori.required' => 'Nama kategori invoice wajib diisi.',
        ]);

        $useCustom = (bool) $request->input('use_custom_format', true);
        $formatNomor = $useCustom
            ? (trim((string) $request->input('format_nomor')) ?: '{PREFIX}/{YEAR}/{NUMBER}')
            : '{NUMBER}/INV-TKB/{MONTH}/{YEAR}';

        $templateSettings = [
            'judul_invoice'    => trim((string) $request->input('judul_invoice', 'INVOICE')) ?: 'INVOICE',
            'subjudul_invoice' => trim((string) $request->input('subjudul_invoice', '')),
            'label_penerima'   => trim((string) $request->input('label_penerima', 'Kepada Yth.')) ?: 'Kepada Yth.',
            'tampilkan_npwp'   => $request->boolean('tampilkan_npwp', true),
            'tampilkan_termin' => $request->boolean('tampilkan_termin', true),
            'catatan_kaki'     => trim((string) $request->input('catatan_kaki', '')),
        ];

        $newKategori = KategoriInvoice::create([
            'nama_kategori'     => $request->nama_kategori,
            'keterangan'        => $request->keterangan,
            'use_custom_format' => $useCustom,
            'prefix'            => strtoupper(trim((string) ($request->prefix ?: 'INV'))),
            'format_nomor'      => $formatNomor,
            'padding_digit'     => (int) ($request->padding_digit ?: 3),
            'reset_periode'     => $request->reset_periode,
            'template_settings' => $templateSettings,
            'is_active'         => $request->boolean('is_active', true),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status'        => 'success',
                'message'       => 'Kategori invoice baru berhasil ditambahkan.',
                'kategori'      => $newKategori,
                'invoiceNumber' => $newKategori->generateNomorInvoice(),
            ]);
        }

        Alert::success('Berhasil', 'Kategori invoice baru berhasil ditambahkan.');
        return redirect()->route('kategori-invoice.index');
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriInvoice::findOrFail($id);

        $request->validate([
            'nama_kategori'     => ['required', 'string', 'max:120'],
            'keterangan'        => ['nullable', 'string', 'max:1000'],
            'use_custom_format' => ['nullable', 'boolean'],
            'prefix'            => ['nullable', 'string', 'max:40'],
            'format_nomor'      => ['nullable', 'string', 'max:150'],
            'padding_digit'     => ['nullable', 'integer', 'min:1', 'max:8'],
            'reset_periode'     => ['required', 'in:yearly,monthly,never'],
            'judul_invoice'     => ['nullable', 'string', 'max:120'],
            'subjudul_invoice'  => ['nullable', 'string', 'max:180'],
            'label_penerima'    => ['nullable', 'string', 'max:100'],
            'catatan_kaki'      => ['nullable', 'string', 'max:500'],
            'is_active'         => ['nullable', 'boolean'],
        ]);

        $useCustom = (bool) $request->input('use_custom_format', true);
        $formatNomor = $useCustom
            ? (trim((string) $request->input('format_nomor')) ?: '{PREFIX}/{YEAR}/{NUMBER}')
            : '{NUMBER}/INV-TKB/{MONTH}/{YEAR}';

        $templateSettings = [
            'judul_invoice'    => trim((string) $request->input('judul_invoice', 'INVOICE')) ?: 'INVOICE',
            'subjudul_invoice' => trim((string) $request->input('subjudul_invoice', '')),
            'label_penerima'   => trim((string) $request->input('label_penerima', 'Kepada Yth.')) ?: 'Kepada Yth.',
            'tampilkan_npwp'   => $request->boolean('tampilkan_npwp', true),
            'tampilkan_termin' => $request->boolean('tampilkan_termin', true),
            'catatan_kaki'     => trim((string) $request->input('catatan_kaki', '')),
        ];

        $kategori->update([
            'nama_kategori'     => $request->nama_kategori,
            'keterangan'        => $request->keterangan,
            'use_custom_format' => $useCustom,
            'prefix'            => strtoupper(trim((string) ($request->prefix ?: 'INV'))),
            'format_nomor'      => $formatNomor,
            'padding_digit'     => (int) ($request->padding_digit ?: 3),
            'reset_periode'     => $request->reset_periode,
            'template_settings' => $templateSettings,
            'is_active'         => $request->boolean('is_active', true),
        ]);

        Alert::success('Berhasil', 'Kategori invoice berhasil diperbarui.');
        return redirect()->route('kategori-invoice.index');
    }

    public function toggleStatus($id)
    {
        $kategori = KategoriInvoice::findOrFail($id);
        $kategori->is_active = !$kategori->is_active;
        $kategori->save();

        $statusText = $kategori->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Kategori \"{$kategori->nama_kategori}\" berhasil {$statusText}.");
        return redirect()->route('kategori-invoice.index');
    }

    public function destroy($id)
    {
        $kategori = KategoriInvoice::findOrFail($id);

        // Lepaskan relasi pada invoice yang menggunakan kategori ini agar tidak merusak histori invoice
        PenjualanTokabe::withTrashed()
            ->where('kategori_invoice_id', $kategori->id)
            ->update(['kategori_invoice_id' => null]);

        $kategori->delete();

        Alert::success('Berhasil', 'Kategori invoice berhasil dihapus.');
        return redirect()->route('kategori-invoice.index');
    }

    public function generateNumberAjax(Request $request)
    {
        $kategoriId = $request->query('kategori_invoice_id', $request->query('kategori_id'));
        $tanggal = $request->query('tanggal', $request->query('date', date('Y-m-d')));

        if ($kategoriId) {
            $kategori = KategoriInvoice::find($kategoriId);
            if ($kategori) {
                return response()->json([
                    'status'            => 'success',
                    'invoiceNumber'     => $kategori->generateNomorInvoice($tanggal),
                    'nomor_invoice'     => $kategori->generateNomorInvoice($tanggal),
                    'prefix'            => $kategori->prefix ?: 'TKB',
                    'kategori'          => $kategori->nama_kategori,
                    'template_settings' => $kategori->template_settings,
                ]);
            }
        }

        // Fallback ke penomoran standar sistem Tokabe
        $defaultKategori = new KategoriInvoice([
            'use_custom_format' => false,
            'prefix'            => 'TKB',
            'format_nomor'      => '{NUMBER}/{PREFIX}/{ROMAN_MONTH}/{YEAR}',
            'padding_digit'     => 3,
            'reset_periode'     => 'monthly',
        ]);

        $noInvoice = $defaultKategori->generateNomorInvoice($tanggal);

        return response()->json([
            'status'        => 'success',
            'invoiceNumber' => $noInvoice,
            'nomor_invoice' => $noInvoice,
            'prefix'        => 'TKB',
        ]);
    }
}

