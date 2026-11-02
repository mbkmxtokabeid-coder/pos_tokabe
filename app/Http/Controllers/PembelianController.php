<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Pembelian;
use App\Models\PembelianBarang;
use App\Models\PembelianPembayaran;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembelianController extends Controller
{
    public function index()
    {
        $vendors = Vendor::withCount('pembelian')
            ->orderByDesc('pembelian_sisa')
            ->orderBy('nama_vendor')
            ->get();

        $totalOutstanding   = $vendors->sum('total_pembelian');
        $totalTerbayar      = $vendors->sum('pembelian_terbayar');
        $totalBelumTerbayar = $vendors->sum('pembelian_sisa');
        $sisaKas            = (float) Budget::sum('anggaran');

        return view('pages.pembelian.index', compact(
            'vendors', 'totalOutstanding', 'totalTerbayar', 'totalBelumTerbayar', 'sisaKas'
        ));
    }

    public function create($id)
    {
        $vendor = Vendor::findOrFail($id);
        $budgets = Budget::orderBy('tanggal', 'desc')->get();

        return view('pages.pembelian.create', compact('vendor', 'budgets'));
    }

    public function store(Request $request)
    {
        if ($request->has('terbayar')) {
            $cleanTerbayar = preg_replace('/[^0-9]/', '', (string)$request->terbayar);
            $request->merge(['terbayar' => $cleanTerbayar !== '' ? (int)$cleanTerbayar : 0]);
        }
        if ($request->has('items') && is_array($request->items)) {
            $items = $request->items;
            foreach ($items as $k => $item) {
                if (isset($item['harga_barang'])) {
                    $cleanHarga = preg_replace('/[^0-9]/', '', (string)$item['harga_barang']);
                    $items[$k]['harga_barang'] = $cleanHarga !== '' ? (int)$cleanHarga : 0;
                }
            }
            $request->merge(['items' => $items]);
        }

        $request->validate([
            'id_vendor'     => ['required', 'exists:vendor,id'],
            'nomor_inv'     => ['required', 'string', 'max:100'],
            'jenis_invoice' => ['required', 'in:Barang,Jasa,Barang dan Jasa,Tanpa Pajak'],
            'ppn_persen'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pph_persen'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tanggal'       => ['required', 'date'],
            'tanggal_jto'   => ['nullable', 'date'],
            'anggaran'      => ['required', 'exists:budget,id'],
            'terbayar'      => ['nullable', 'numeric', 'min:0'],
            'status'        => ['required', 'in:Lunas,Cicil'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.deskripsi'    => ['required', 'string'],
            'items.*.harga_barang' => ['required', 'numeric', 'min:0'],
            'items.*.qty'          => ['required', 'integer', 'min:1'],
            'items.*.satuan'       => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = (int) round(collect($request->items)->sum(function ($item) {
                return (float) ($item['harga_barang'] ?? 0) * (int) ($item['qty'] ?? 0);
            }));

            $jenisInvoice = $request->jenis_invoice;
            $ppnPersen = in_array($jenisInvoice, ['Barang', 'Barang dan Jasa'], true)
                ? (float) ($request->ppn_persen ?? 0) : 0;
            $pphPersen = in_array($jenisInvoice, ['Jasa', 'Barang dan Jasa'], true)
                ? (float) ($request->pph_persen ?? 0) : 0;

            $ppnNominal = (int) round($subtotal * $ppnPersen / 100);
            $pphNominal = (int) round($subtotal * $pphPersen / 100);
            $jumlahHarga = $subtotal + $ppnNominal;

            $rawTerbayar = min((int) round((float) ($request->terbayar ?? 0)), $jumlahHarga);

            if ($request->status === 'Lunas') {
                $terbayar = $jumlahHarga;
                $status = 'Lunas';
            } else {
                $terbayar = $rawTerbayar;
                $status = ($terbayar >= $jumlahHarga && $jumlahHarga > 0) ? 'Lunas' : 'Cicil';
            }
            $sisa = $jumlahHarga - $terbayar;

            $tglJto = $request->tanggal_jto ?: $request->tanggal;

            $pembelian = Pembelian::create([
                'id_vendor'     => $request->id_vendor,
                'nomor_inv'     => $request->nomor_inv,
                'jenis_invoice' => $jenisInvoice,
                'ppn_persen'    => $ppnPersen,
                'ppn_nominal'   => $ppnNominal,
                'pph_persen'    => $pphPersen,
                'pph_nominal'   => $pphNominal,
                'tanggal'       => $request->tanggal,
                'tanggal_jto'   => $tglJto,
                'tgl_jto'       => $tglJto,
                'tgl_lunas'     => $status === 'Lunas' ? now()->toDateString() : null,
                'anggaran'      => $request->anggaran,
                'status'        => $status,
                'jumlah_harga'  => $jumlahHarga,
                'terbayar'      => $terbayar,
                'sisa'          => $sisa,
                'token'         => (string) Str::uuid(),
                'approval'      => 'Lock',
                'approved_at'   => null,
            ]);

            $this->adjustBudget($request->anggaran, -$terbayar);
            $this->adjustVendor($request->id_vendor, $jumlahHarga, $terbayar, $sisa);

            if ($status === 'Cicil' && $terbayar > 0) {
                PembelianPembayaran::create([
                    'pembelian_id'    => $pembelian->id,
                    'cicilan_ke'      => 1,
                    'tanggal_bayar'   => $request->tanggal,
                    'jumlah_bayar'    => $terbayar,
                    'anggaran_id'     => $request->anggaran,
                    'status_approval' => 'Approved',
                    'catatan'         => 'Pembayaran Awal / DP',
                    'user_id'         => Auth::id(),
                    'approved_by'     => (Auth::user() && Auth::user()->role === 'Pemilik') ? Auth::id() : null,
                    'approved_at'     => now(),
                ]);
            }

            foreach ($request->items as $item) {
                $hargaBarang = (int) round((float) ($item['harga_barang'] ?? 0));
                $qty = (int) ($item['qty'] ?? 1);
                PembelianBarang::create([
                    'pembelian_id' => $pembelian->id,
                    'deskripsi'    => $item['deskripsi'],
                    'harga_barang' => $hargaBarang,
                    'qty'          => $qty,
                    'satuan'       => $item['satuan'] ?? 'Pcs',
                    'total'        => $hargaBarang * $qty,
                ]);
            }
        });

        return redirect()->route('pembelian.vendor.show', $request->id_vendor)
            ->with('success', 'Pembelian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pembelian = Pembelian::with('barang')->findOrFail($id);

        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $pembelian->approval !== 'Unlock') {
            return redirect()->route('pembelian.vendor.show', $pembelian->id_vendor)
                ->with('error', 'Akses edit riwayat pembelian terkunci. Silakan minta persetujuan ke Pemilik terlebih dahulu.');
        }

        $vendor = $pembelian->vendor;
        $vendors = Vendor::orderBy('nama_vendor')->get();
        $budgets = Budget::orderByDesc('tanggal')->get();

        return view('pages.pembelian.edit', compact('pembelian', 'vendor', 'vendors', 'budgets'));
    }

    public function update(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $pembelian->approval !== 'Unlock') {
            return redirect()->route('pembelian.vendor.show', $pembelian->id_vendor)
                ->with('error', 'Akses edit riwayat pembelian terkunci. Silakan minta persetujuan ke Pemilik terlebih dahulu.');
        }

        if ($request->has('terbayar')) {
            $cleanTerbayar = preg_replace('/[^0-9]/', '', (string)$request->terbayar);
            $request->merge(['terbayar' => $cleanTerbayar !== '' ? (int)$cleanTerbayar : 0]);
        }
        if ($request->has('items') && is_array($request->items)) {
            $items = $request->items;
            foreach ($items as $k => $item) {
                if (isset($item['harga_barang'])) {
                    $cleanHarga = preg_replace('/[^0-9]/', '', (string)$item['harga_barang']);
                    $items[$k]['harga_barang'] = $cleanHarga !== '' ? (int)$cleanHarga : 0;
                }
            }
            $request->merge(['items' => $items]);
        }

        $request->validate([
            'id_vendor'     => ['required', 'exists:vendor,id'],
            'nomor_inv'     => ['required', 'string', 'max:100'],
            'jenis_invoice' => ['required', 'in:Barang,Jasa,Barang dan Jasa,Tanpa Pajak'],
            'ppn_persen'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pph_persen'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tanggal'       => ['required', 'date'],
            'tanggal_jto'   => ['nullable', 'date'],
            'anggaran'      => ['required', 'exists:budget,id'],
            'terbayar'      => ['nullable', 'numeric', 'min:0'],
            'status'        => ['required', 'in:Lunas,Cicil'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.deskripsi'    => ['required', 'string'],
            'items.*.harga_barang' => ['required', 'numeric', 'min:0'],
            'items.*.qty'          => ['required', 'integer', 'min:1'],
            'items.*.satuan'       => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request, $id) {
            $pembelian = Pembelian::lockForUpdate()->findOrFail($id);
            $subtotal = (int) round(collect($request->items)->sum(function ($item) {
                return (float) ($item['harga_barang'] ?? 0) * (int) ($item['qty'] ?? 0);
            }));

            $jenisInvoice = $request->jenis_invoice;
            $ppnPersen = in_array($jenisInvoice, ['Barang', 'Barang dan Jasa'], true)
                ? (float) ($request->ppn_persen ?? 0) : 0;
            $pphPersen = in_array($jenisInvoice, ['Jasa', 'Barang dan Jasa'], true)
                ? (float) ($request->pph_persen ?? 0) : 0;

            $ppnNominal = (int) round($subtotal * $ppnPersen / 100);
            $pphNominal = (int) round($subtotal * $pphPersen / 100);
            $jumlahHarga = $subtotal + $ppnNominal;

            $rawTerbayar = min((int) round((float) ($request->terbayar ?? 0)), $jumlahHarga);

            if ($request->status === 'Lunas') {
                $terbayar = $jumlahHarga;
                $status = 'Lunas';
            } else {
                $terbayar = $rawTerbayar;
                $status = ($terbayar >= $jumlahHarga && $jumlahHarga > 0) ? 'Lunas' : 'Cicil';
            }
            $sisa = $jumlahHarga - $terbayar;

            $tglJto = $request->tanggal_jto ?: $request->tanggal;

            // Kembalikan alokasi transaksi lama
            $this->adjustBudget($pembelian->anggaran, $pembelian->terbayar);
            $this->adjustVendor($pembelian->id_vendor, -$pembelian->jumlah_harga, -$pembelian->terbayar, -$pembelian->sisa);

            $pembelian->update([
                'id_vendor'     => $request->id_vendor,
                'anggaran'      => $request->anggaran,
                'nomor_inv'     => $request->nomor_inv,
                'jenis_invoice' => $jenisInvoice,
                'ppn_persen'    => $ppnPersen,
                'ppn_nominal'   => $ppnNominal,
                'pph_persen'    => $pphPersen,
                'pph_nominal'   => $pphNominal,
                'tanggal'       => $request->tanggal,
                'tanggal_jto'   => $tglJto,
                'tgl_jto'       => $tglJto,
                'tgl_lunas'     => $status === 'Lunas' ? ($pembelian->tgl_lunas ?: now()->toDateString()) : null,
                'status'        => $status,
                'jumlah_harga'  => $jumlahHarga,
                'terbayar'      => $terbayar,
                'sisa'          => $sisa,
                'approval'      => 'Lock',
                'approved_at'   => null,
            ]);

            // Terapkan alokasi transaksi baru
            $this->adjustBudget($request->anggaran, -$terbayar);
            $this->adjustVendor($request->id_vendor, $jumlahHarga, $terbayar, $sisa);

            if ($status === 'Cicil' && $terbayar > 0) {
                PembelianPembayaran::create([
                    'pembelian_id'    => $pembelian->id,
                    'cicilan_ke'      => 1,
                    'tanggal_bayar'   => $request->tanggal,
                    'jumlah_bayar'    => $terbayar,
                    'anggaran_id'     => $request->anggaran,
                    'status_approval' => 'Approved',
                    'catatan'         => 'Pembayaran Awal / DP',
                    'user_id'         => Auth::id(),
                    'approved_by'     => (Auth::user() && Auth::user()->role === 'Pemilik') ? Auth::id() : null,
                    'approved_at'     => now(),
                ]);
            }

            $pembelian->barang()->delete();
            foreach ($request->items as $item) {
                $hargaBarang = (int) round((float) ($item['harga_barang'] ?? 0));
                $qty = (int) ($item['qty'] ?? 1);
                $pembelian->barang()->create([
                    'deskripsi'    => $item['deskripsi'],
                    'harga_barang' => $hargaBarang,
                    'qty'          => $qty,
                    'satuan'       => $item['satuan'] ?? 'Pcs',
                    'total'        => $hargaBarang * $qty,
                ]);
            }
        });

        return redirect()->route('pembelian.vendor.show', $request->id_vendor)
            ->with('success', 'Pembelian berhasil diperbarui dan status telah terkunci kembali.');
    }

    public function unlock($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Pemilik yang dapat menyetujui unlock pembelian.');
        }

        $pembelian = Pembelian::find($id);
        if (!$pembelian) {
            return redirect()->back()->with('error', 'Data pembelian tidak ditemukan.');
        }

        $pembelian->approval = 'Unlock';
        $pembelian->approved_at = now();
        $pembelian->save();

        return redirect()->route('pembelian.vendor.show', $pembelian->id_vendor)
            ->with('success', 'Akses edit riwayat pembelian #' . $pembelian->nomor_inv . ' berhasil di-Unlock. Admin sekarang dapat mengedit transaksi ini.');
    }

    public function lock($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Pemilik yang dapat me-lock pembelian.');
        }

        $pembelian = Pembelian::find($id);
        if (!$pembelian) {
            return redirect()->back()->with('error', 'Data pembelian tidak ditemukan.');
        }

        $pembelian->approval = 'Lock';
        $pembelian->approved_at = null;
        $pembelian->save();

        return redirect()->back()->with('success', 'Riwayat pembelian #' . $pembelian->nomor_inv . ' berhasil di-Lock kembali.');
    }

    public function approvalPage($id)
    {
        $pembelian = Pembelian::with(['vendor', 'barang', 'pembayaran.anggaran', 'pembayaran.user'])->findOrFail($id);
        return view('pages.pembelian.approval', compact('pembelian'));
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $role = Auth::user()->role ?? null;
        if ($role === 'Admin' && $pembelian->approval !== 'Unlock') {
            return redirect()->back()->with('error', 'Akses hapus riwayat pembelian terkunci. Silakan minta persetujuan ke Pemilik terlebih dahulu.');
        }

        DB::transaction(function () use ($pembelian) {
            $this->adjustBudget($pembelian->anggaran, $pembelian->terbayar);
            $this->adjustVendor($pembelian->id_vendor, -$pembelian->jumlah_harga, -$pembelian->terbayar, -$pembelian->sisa);
            $pembelian->barang()->delete();
            $pembelian->delete();
        });

        return back()->with('success', 'Pembelian berhasil dihapus.');
    }

    public function lunas($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Pemilik yang dapat melunasi transaksi pembelian.');
        }

        DB::transaction(function () use ($id) {
            $pembelian = Pembelian::lockForUpdate()->findOrFail($id);
            $sisa = $pembelian->sisa;

            if ($sisa > 0) {
                $this->adjustBudget($pembelian->anggaran, -$sisa);
                $this->adjustVendor($pembelian->id_vendor, 0, $sisa, -$sisa);

                $approvedCount = $pembelian->pembayaran()->where('status_approval', 'Approved')->count();
                PembelianPembayaran::create([
                    'pembelian_id'    => $pembelian->id,
                    'cicilan_ke'      => min(5, $approvedCount + 1),
                    'tanggal_bayar'   => now()->toDateString(),
                    'jumlah_bayar'    => $sisa,
                    'anggaran_id'     => $pembelian->anggaran,
                    'status_approval' => 'Approved',
                    'catatan'         => 'Pelunasan Penuh',
                    'user_id'         => Auth::id(),
                    'approved_by'     => Auth::id(),
                    'approved_at'     => now(),
                ]);
            }

            $pembelian->update([
                'status'    => 'Lunas',
                'terbayar'  => $pembelian->jumlah_harga,
                'sisa'      => 0,
                'tgl_lunas' => now()->toDateString(),
            ]);
        });

        return back()->with('success', 'Pembelian berhasil dilunasi.');
    }

    public function bayarCicilan(Request $request, $id)
    {
        if ($request->has('jumlah_bayar')) {
            $cleanJumlah = preg_replace('/[^0-9]/', '', (string)$request->jumlah_bayar);
            $request->merge(['jumlah_bayar' => $cleanJumlah !== '' ? (int)$cleanJumlah : 0]);
        }

        $request->validate([
            'tanggal_bayar' => ['required', 'date'],
            'jumlah_bayar'  => ['required', 'numeric', 'min:1'],
            'anggaran_id'   => ['required', 'exists:budget,id'],
            'catatan'       => ['nullable', 'string', 'max:255'],
        ], [
            'tanggal_bayar.required' => 'Tanggal pembayaran wajib diisi.',
            'jumlah_bayar.required'  => 'Nominal cicilan wajib diisi.',
            'jumlah_bayar.min'       => 'Nominal cicilan harus lebih dari 0.',
            'anggaran_id.required'   => 'Sumber kas/anggaran wajib dipilih.',
        ]);

        $role = Auth::user()->role ?? null;
        $isOwner = $role === 'Pemilik';

        return DB::transaction(function () use ($request, $id, $isOwner) {
            $pembelian = Pembelian::lockForUpdate()->findOrFail($id);

            if ($pembelian->status === 'Lunas' || $pembelian->sisa <= 0) {
                return redirect()->back()->with('error', 'Transaksi pembelian ini sudah Lunas.');
            }

            $approvedCount = $pembelian->pembayaran()->where('status_approval', 'Approved')->count();
            $pendingCount  = $pembelian->pembayaran()->where('status_approval', 'Pending')->count();

            if ($pendingCount > 0) {
                return redirect()->back()->with('error', 'Masih ada cicilan yang menunggu persetujuan (Pending Approval) Pemilik. Harap tunggu persetujuan sebelum menambahkan cicilan baru.');
            }

            if ($approvedCount >= 5) {
                return redirect()->back()->with('error', 'Batas maksimal 5 kali cicilan telah tercapai. Sisa tagihan hanya dapat diselesaikan melalui Pelunasan oleh Pemilik.');
            }

            $jumlahBayar = (int) $request->jumlah_bayar;
            if ($jumlahBayar > $pembelian->sisa) {
                return redirect()->back()->with('error', 'Nominal cicilan (Rp ' . number_format($jumlahBayar, 0, ',', '.') . ') melebihi sisa tagihan (Rp ' . number_format($pembelian->sisa, 0, ',', '.') . ').');
            }

            $cicilanKe = $approvedCount + 1;

            if ($isOwner) {
                $newTerbayar = $pembelian->terbayar + $jumlahBayar;
                $newSisa = max(0, $pembelian->jumlah_harga - $newTerbayar);
                $newStatus = ($newSisa <= 0) ? 'Lunas' : 'Cicil';

                $this->adjustBudget($request->anggaran_id, -$jumlahBayar);
                $this->adjustVendor($pembelian->id_vendor, 0, $jumlahBayar, -$jumlahBayar);

                $pembelian->update([
                    'terbayar'  => $newTerbayar,
                    'sisa'      => $newSisa,
                    'status'    => $newStatus,
                    'tgl_lunas' => $newStatus === 'Lunas' ? ($pembelian->tgl_lunas ?: now()->toDateString()) : null,
                ]);

                PembelianPembayaran::create([
                    'pembelian_id'    => $pembelian->id,
                    'cicilan_ke'      => $cicilanKe,
                    'tanggal_bayar'   => $request->tanggal_bayar,
                    'jumlah_bayar'    => $jumlahBayar,
                    'anggaran_id'     => $request->anggaran_id,
                    'status_approval' => 'Approved',
                    'catatan'         => $request->catatan,
                    'user_id'         => Auth::id(),
                    'approved_by'     => Auth::id(),
                    'approved_at'     => now(),
                ]);

                $msg = "Pembayaran Cicilan ke-{$cicilanKe} sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil dicatat dan disetujui.";
                if ($newStatus === 'Lunas') {
                    $msg .= " Pembelian ini kini telah LUNAS!";
                } elseif ($cicilanKe === 5 && $newSisa > 0) {
                    $msg .= " Perhatian: Pembelian telah mencapai batas maksimal 5 kali cicilan. Sisa Rp " . number_format($newSisa, 0, ',', '.') . " harus diselesaikan via pelunasan.";
                }

                return redirect()->back()->with('success', $msg);
            } else {
                PembelianPembayaran::create([
                    'pembelian_id'    => $pembelian->id,
                    'cicilan_ke'      => $cicilanKe,
                    'tanggal_bayar'   => $request->tanggal_bayar,
                    'jumlah_bayar'    => $jumlahBayar,
                    'anggaran_id'     => $request->anggaran_id,
                    'status_approval' => 'Pending',
                    'catatan'         => $request->catatan,
                    'user_id'         => Auth::id(),
                    'approved_by'     => null,
                    'approved_at'     => null,
                ]);

                return redirect()->back()->with('success', "Pengajuan Cicilan ke-{$cicilanKe} sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil dikirim. Menunggu persetujuan (approval) Pemilik.");
            }
        });
    }

    public function approveCicilan($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Pemilik yang dapat menyetujui cicilan.');
        }

        return DB::transaction(function () use ($id) {
            $pembayaran = PembelianPembayaran::lockForUpdate()->findOrFail($id);

            if ($pembayaran->status_approval !== 'Pending') {
                return redirect()->back()->with('error', 'Cicilan ini sudah diproses sebelumnya.');
            }

            $pembelian = Pembelian::lockForUpdate()->findOrFail($pembayaran->pembelian_id);

            $approvedCount = $pembelian->pembayaran()->where('status_approval', 'Approved')->count();
            if ($approvedCount >= 5) {
                return redirect()->back()->with('error', 'Batas maksimal 5 kali cicilan telah tercapai.');
            }

            $jumlahBayar = $pembayaran->jumlah_bayar;
            if ($jumlahBayar > $pembelian->sisa) {
                return redirect()->back()->with('error', 'Nominal cicilan melebihi sisa tagihan.');
            }

            $cicilanKe = $approvedCount + 1;
            $newTerbayar = $pembelian->terbayar + $jumlahBayar;
            $newSisa = max(0, $pembelian->jumlah_harga - $newTerbayar);
            $newStatus = ($newSisa <= 0) ? 'Lunas' : 'Cicil';

            $this->adjustBudget($pembayaran->anggaran_id, -$jumlahBayar);
            $this->adjustVendor($pembelian->id_vendor, 0, $jumlahBayar, -$jumlahBayar);

            $pembelian->update([
                'terbayar'  => $newTerbayar,
                'sisa'      => $newSisa,
                'status'    => $newStatus,
                'tgl_lunas' => $newStatus === 'Lunas' ? ($pembelian->tgl_lunas ?: now()->toDateString()) : null,
            ]);

            $pembayaran->update([
                'cicilan_ke'      => $cicilanKe,
                'status_approval' => 'Approved',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
            ]);

            $msg = "Cicilan ke-{$cicilanKe} sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil disetujui.";
            if ($newStatus === 'Lunas') {
                $msg .= " Pembelian ini kini telah LUNAS!";
            } elseif ($cicilanKe === 5 && $newSisa > 0) {
                $msg .= " Batas maksimal 5 kali cicilan telah tercapai.";
            }

            return redirect()->back()->with('success', $msg);
        });
    }

    public function rejectCicilan($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'Pemilik') {
            return redirect()->back()->with('error', 'Akses ditolak. Hanya Pemilik yang dapat menolak cicilan.');
        }

        $pembayaran = PembelianPembayaran::findOrFail($id);
        if ($pembayaran->status_approval !== 'Pending') {
            return redirect()->back()->with('error', 'Cicilan ini sudah diproses sebelumnya.');
        }

        $pembayaran->update([
            'status_approval' => 'Rejected',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        return redirect()->back()->with('success', "Pengajuan Cicilan ke-{$pembayaran->cicilan_ke} telah ditolak.");
    }

    private function adjustBudget($id, $amount): void
    {
        if ($id && $amount != 0) {
            $budget = Budget::lockForUpdate()->find($id);
            if ($budget) {
                $budget->anggaran += $amount;
                $budget->save();
            }
        }
    }

    private function adjustVendor($id, $total, $terbayar, $sisa): void
    {
        if ($id) {
            $vendor = Vendor::lockForUpdate()->find($id);
            if ($vendor) {
                $vendor->total_pembelian += $total;
                $vendor->pembelian_terbayar += $terbayar;
                $vendor->pembelian_sisa += $sisa;
                $vendor->save();
            }
        }
    }

    public function showVendor($id)
    {
        // Auto lock riwayat pembelian yang di-unlock lebih dari 6 jam (konsisten dengan sistem invoice)
        Pembelian::where('id_vendor', $id)
            ->where('approval', 'Unlock')
            ->whereNotNull('approved_at')
            ->where('approved_at', '<', now()->subHours(6))
            ->update(['approval' => 'Lock', 'approved_at' => null]);

        $vendor = Vendor::findOrFail($id);

        $pembelian = Pembelian::with(['vendor', 'pembayaran.anggaran', 'pembayaran.user', 'pembayaran.approver'])
            ->where('id_vendor', $id)
            ->latest('tanggal')
            ->get();

        $totalTagihan  = $pembelian->sum('jumlah_harga');
        $totalTerbayar = $pembelian->sum('terbayar');
        $totalSisa     = $pembelian->sum('sisa');
        $sisaKas       = (float) Budget::sum('anggaran');
        $budgets       = Budget::orderByDesc('tanggal')->get();

        return view('pages.pembelian.show-vendor', compact(
            'vendor', 'pembelian', 'totalTagihan', 'totalTerbayar', 'totalSisa', 'sisaKas', 'budgets'
        ));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => ['required', 'string', 'max:255', 'unique:vendor,nama_vendor'],
        ]);

        Vendor::create(['nama_vendor' => $request->nama_vendor]);

        return redirect()->route('pembelian.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function updateVendor(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $request->validate([
            'nama_vendor' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('vendor', 'nama_vendor')->ignore($vendor->id)],
        ]);

        $vendor->update(['nama_vendor' => $request->nama_vendor]);

        return redirect()->route('pembelian.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    public function destroyVendor(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $pembelianCount = Pembelian::where('id_vendor', $id)->count();

        if ($pembelianCount > 0) {
            $request->validate([
                'action' => ['required', 'in:transfer,delete_all'],
                'target_vendor_id' => [
                    'required_if:action,transfer',
                    'nullable',
                    'exists:vendor,id',
                    \Illuminate\Validation\Rule::notIn([$id]),
                ],
            ], [
                'action.required' => 'Silakan pilih tindakan untuk riwayat pembelian vendor ini.',
                'action.in' => 'Pilihan tindakan tidak valid.',
                'target_vendor_id.required_if' => 'Silakan pilih vendor tujuan pemindahan transaksi.',
                'target_vendor_id.exists' => 'Vendor tujuan yang dipilih tidak ditemukan.',
                'target_vendor_id.not_in' => 'Vendor tujuan tidak boleh sama dengan vendor yang ingin dihapus.',
            ]);
        }

        $deletedVendorName = $vendor->nama_vendor;
        $targetVendorName = null;

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $vendor, $pembelianCount, &$targetVendorName) {
            if ($pembelianCount > 0) {
                if ($request->action === 'transfer') {
                    $targetVendor = Vendor::lockForUpdate()->findOrFail($request->target_vendor_id);
                    $sourceVendor = Vendor::lockForUpdate()->findOrFail($vendor->id);
                    $targetVendorName = $targetVendor->nama_vendor;

                    // Alihkan semua riwayat transaksi pembelian ke vendor tujuan
                    Pembelian::where('id_vendor', $sourceVendor->id)->update([
                        'id_vendor' => $targetVendor->id,
                    ]);

                    // Akumulasikan total keuangan ke vendor tujuan
                    $targetVendor->total_pembelian += (float) $sourceVendor->total_pembelian;
                    $targetVendor->pembelian_terbayar += (float) $sourceVendor->pembelian_terbayar;
                    $targetVendor->pembelian_sisa += (float) $sourceVendor->pembelian_sisa;
                    $targetVendor->save();

                    // Hapus vendor asal
                    $sourceVendor->delete();
                } elseif ($request->action === 'delete_all') {
                    $sourceVendor = Vendor::lockForUpdate()->findOrFail($vendor->id);
                    $pembelians = Pembelian::where('id_vendor', $sourceVendor->id)->get();

                    foreach ($pembelians as $pembelian) {
                        $this->adjustBudget($pembelian->anggaran, $pembelian->terbayar);
                        $pembelian->barang()->delete();
                        $pembelian->delete();
                    }

                    $sourceVendor->delete();
                }
            } else {
                $vendor->delete();
            }
        });

        if ($pembelianCount > 0 && $request->action === 'transfer') {
            return redirect()->route('pembelian.index')
                ->with('success', "Vendor {$deletedVendorName} berhasil dihapus. Sebanyak {$pembelianCount} transaksi pembelian telah berhasil dipindahkan ke {$targetVendorName}.");
        } elseif ($pembelianCount > 0 && $request->action === 'delete_all') {
            return redirect()->route('pembelian.index')
                ->with('success', "Vendor {$deletedVendorName} beserta {$pembelianCount} transaksi pembelian terkait berhasil dihapus.");
        }

        return redirect()->route('pembelian.index')
            ->with('success', "Vendor {$deletedVendorName} berhasil dihapus.");
    }
}
