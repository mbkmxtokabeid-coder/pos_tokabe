@extends('layout.template')

@section('head')
<style>
    .del-pembelian-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 14px !important;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.45) !important;
        overflow: hidden;
    }
    .del-pembelian-header {
        background: linear-gradient(180deg, rgba(239, 68, 68, 0.08) 0%, #181E26 100%) !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 20px 24px !important;
    }
    .del-alert-banner {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        border-left: 4px solid #EF4444;
        border-radius: 10px;
        padding: 14px 18px;
        color: #FCA5A5;
    }
    .del-meta-box {
        background: #12161D;
        border: 1px solid #232A35;
        border-radius: 10px;
        padding: 14px 16px;
        height: 100%;
    }
    .del-meta-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94A3B8;
        margin-bottom: 4px;
    }
    .del-meta-value {
        font-size: 14.5px;
        font-weight: 600;
        color: #F8FAFC;
    }
    .table-del-items {
        width: 100%;
        border-collapse: collapse;
    }
    .table-del-items th {
        background: #181E26;
        color: #94A3B8;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 10px 14px;
        border-bottom: 1px solid #282E37;
    }
    .table-del-items td {
        padding: 11px 14px;
        border-bottom: 1px solid #21262D;
        color: #E2E8F0;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 900px;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 text-muted fs-13">
                <a href="{{ route('pembelian.vendor.show', $pembelian->id_vendor) }}" class="text-decoration-none text-secondary">
                    <i class="ri-arrow-left-line me-1"></i>Riwayat Pembelian {{ $pembelian->vendor->nama_vendor ?? 'Vendor' }}
                </a>
                <span>/</span>
                <span class="text-danger fw-semibold">Konfirmasi Hapus #{{ $pembelian->nomor_inv }}</span>
            </div>
            <a href="{{ route('pembelian.vendor.show', $pembelian->id_vendor) }}" class="btn btn-sm btn-outline-secondary">
                <i class="ri-close-line me-1"></i>Tutup
            </a>
        </div>

        <div class="card del-pembelian-card">
            <div class="del-pembelian-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                         style="width: 46px; height: 46px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); color: #EF4444;">
                        <i class="ri-delete-bin-7-line fs-22"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-white">Konfirmasi Penghapusan Invoice Masuk (Pembelian)</h5>
                        <p class="mb-0 text-muted fs-12">Tinjau rincian transaksi pembelian sebelum menyetujui penghapusan permanen.</p>
                    </div>
                </div>
                <span class="badge px-3 py-1.5 fs-12 fw-semibold"
                      style="background: {{ $pembelian->status === 'Lunas' ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)' }}; color: {{ $pembelian->status === 'Lunas' ? '#34D399' : '#FCD34D' }}; border: 1px solid {{ $pembelian->status === 'Lunas' ? 'rgba(16,185,129,0.35)' : 'rgba(245,158,11,0.35)' }};">
                    {{ $pembelian->status === 'Lunas' ? 'Lunas' : 'Termin / Belum Lunas' }}
                </span>
            </div>

            <div class="card-body p-4 d-flex flex-column gap-4">
                <div class="del-alert-banner d-flex align-items-start gap-3">
                    <i class="ri-error-warning-fill fs-20 text-danger mt-0.5"></i>
                    <div class="fs-13">
                        <div class="fw-bold text-uppercase mb-1" style="letter-spacing: 0.04em;">Peringatan — Aksi Penghapusan Permanen</div>
                        <div>
                            Menghapus invoice pembelian <strong>#{{ $pembelian->nomor_inv }}</strong> akan menghapus seluruh rincian barang terkait, mengembalikan saldo anggaran sebesar <strong>Rp {{ number_format($pembelian->terbayar, 0, ',', '.') }}</strong>, dan menyesuaikan total tagihan vendor <strong>{{ $pembelian->vendor->nama_vendor ?? '-' }}</strong>.
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="del-meta-box">
                            <div class="del-meta-label">Nomor Invoice Masuk</div>
                            <div class="del-meta-value" style="color: #818CF8; font-family: monospace;">#{{ $pembelian->nomor_inv }}</div>
                            <div class="text-muted fs-12 mt-1">Jenis: {{ $pembelian->jenis_invoice ?? 'Barang' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="del-meta-box">
                            <div class="del-meta-label">Nama Vendor</div>
                            <div class="del-meta-value">{{ $pembelian->vendor->nama_vendor ?? '-' }}</div>
                            <div class="text-muted fs-12 mt-1">Status Akses: {{ $pembelian->approval === 'Unlock' ? 'Terbuka (Unlock)' : 'Terkunci (Lock)' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="del-meta-box">
                            <div class="del-meta-label">Tanggal Pembelian</div>
                            <div class="del-meta-value">{{ optional($pembelian->tanggal)->translatedFormat('d F Y') ?: '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="del-meta-box">
                            <div class="del-meta-label">Tanggal Jatuh Tempo (JTO)</div>
                            <div class="del-meta-value">{{ optional($pembelian->effective_tanggal_jto)->translatedFormat('d F Y') ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-uppercase text-muted fs-12" style="letter-spacing: 0.06em;">
                            <i class="ri-shopping-bag-3-line me-1 text-primary"></i>Rincian Barang / Jasa
                        </span>
                        <span class="badge bg-dark border border-secondary border-opacity-25 text-light">{{ $pembelian->barang->count() }} Item</span>
                    </div>
                    <div class="rounded-3 overflow-hidden" style="border: 1px solid #282E37; background: #12161D;">
                        <table class="table-del-items">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 48px;">No</th>
                                    <th>Deskripsi Barang / Keterangan</th>
                                    <th class="text-end" style="width: 90px;">Qty</th>
                                    <th class="text-end" style="width: 150px;">Harga Satuan</th>
                                    <th class="text-end" style="width: 160px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelian->barang as $brg)
                                    <tr>
                                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                        <td class="fw-medium">{{ $brg->deskripsi ?: '-' }}</td>
                                        <td class="text-end">{{ $brg->qty }} {{ $brg->satuan ?: 'Pcs' }}</td>
                                        <td class="text-end">Rp {{ number_format($brg->harga_barang, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold text-white">Rp {{ number_format($brg->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada rincian barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-3 rounded-3" style="background: #12161D; border: 1px solid #232A35;">
                    @if(($pembelian->ppn_nominal ?? 0) > 0)
                        <div class="d-flex justify-content-between text-muted fs-13 mb-2">
                            <span>PPN ({{ $pembelian->ppn_persen }}%)</span>
                            <span class="text-info fw-semibold">+ Rp {{ number_format($pembelian->ppn_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if(($pembelian->pph_nominal ?? 0) > 0)
                        <div class="d-flex justify-content-between text-muted fs-13 mb-2">
                            <span>PPh ({{ $pembelian->pph_persen }}%)</span>
                            <span class="text-danger fw-semibold">- Rp {{ number_format($pembelian->pph_nominal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25">
                        <span class="fw-bold text-white fs-14">Total Tagihan Pembelian</span>
                        <span class="fw-bold fs-16" style="color: #34D399;">Rp {{ number_format($pembelian->jumlah_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted fs-12 mt-2">
                        <span>Sudah Terbayar: <strong class="text-light">Rp {{ number_format($pembelian->terbayar, 0, ',', '.') }}</strong></span>
                        <span>Sisa Tagihan: <strong style="color: {{ $pembelian->sisa > 0 ? '#FB7185' : '#34D399' }};">Rp {{ number_format($pembelian->sisa, 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-transparent border-top border-secondary border-opacity-25 p-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="{{ route('pembelian.vendor.show', $pembelian->id_vendor) }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i>Batal & Kembali
                </a>

                <form action="{{ route('pembelian.destroy', $pembelian->id) }}" method="POST" id="formConfirmDeletePembelian" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold px-4" onclick="return confirm('Apakah Anda yakin ingin menyetujui dan menghapus permanen invoice pembelian #{{ $pembelian->nomor_inv }}?')">
                        <i class="ri-delete-bin-line me-1"></i>Setujui & Hapus Pembelian Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
