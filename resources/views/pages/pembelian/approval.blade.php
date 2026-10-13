@extends('layout.template')

@section('head')
<style>
    .approval-card {
        background-color: #212529;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
    }
    .approval-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    .approval-value {
        font-size: 1rem;
        color: #f8fafc;
        font-weight: 500;
    }
    .item-table th {
        background-color: rgba(255, 255, 255, 0.04);
        color: #cbd5e1;
        font-size: 0.8rem;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.6rem 0.75rem;
    }
    .item-table td {
        padding: 0.6rem 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        color: #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-10">
                <div class="card approval-card shadow-lg">
                    <div class="card-header border-bottom border-secondary border-opacity-25 py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning bg-opacity-25 text-warning p-2 rounded-circle">
                                <i class="ri-lock-unlock-line fs-18"></i>
                            </span>
                            <h5 class="card-title mb-0 text-white">Konfirmasi Persetujuan Edit Pembelian</h5>
                        </div>
                        <span class="badge bg-soft-info text-info fs-12 px-3 py-1">Khusus Owner</span>
                    </div>

                    <div class="card-body p-4">
                        <div class="alert alert-soft-primary d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="ri-information-line fs-20 flex-shrink-0"></i>
                            <div class="fs-13">
                                Admin meminta izin untuk mengubah data riwayat transaksi pembelian ini. Klik <strong>Setujui & Buka Kunci</strong> agar admin dapat melakukan perubahan.
                            </div>
                        </div>

                        {{-- Ringkasan Informasi Utama --}}
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-10">
                                    <div class="approval-label">No. Invoice Vendor</div>
                                    <div class="approval-value fw-bold text-primary">{{ $pembelian->nomor_inv }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-10">
                                    <div class="approval-label">Nama Vendor</div>
                                    <div class="approval-value fw-semibold text-white">{{ $pembelian->vendor->nama_vendor ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-10">
                                    <div class="approval-label">Tanggal Transaksi</div>
                                    <div class="approval-value">{{ optional($pembelian->tanggal)->translatedFormat('d F Y') ?: '-' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded bg-dark bg-opacity-50 border border-secondary border-opacity-10">
                                    <div class="approval-label">Jatuh Tempo</div>
                                    <div class="approval-value">{{ optional($pembelian->effective_tanggal_jto)->translatedFormat('d F Y') ?: '-' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Rincian Item --}}
                        <div class="mb-4">
                            <h6 class="text-white mb-2 fs-13 text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Rincian Barang / Jasa</h6>
                            <div class="table-responsive rounded border border-secondary border-opacity-25">
                                <table class="table table-sm item-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Deskripsi Item</th>
                                            <th class="text-center" style="width: 80px;">Qty</th>
                                            <th class="text-end" style="width: 140px;">Harga Satuan</th>
                                            <th class="text-end" style="width: 150px;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pembelian->barang as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-medium text-white">{{ $item->deskripsi }}</div>
                                                @if($item->satuan)
                                                    <small class="text-muted">Satuan: {{ $item->satuan }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-end">Rp {{ number_format($item->harga_barang, 0, ',', '.') }}</td>
                                            <td class="text-end fw-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada data item.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Ringkasan Biaya --}}
                        <div class="p-3 rounded bg-dark bg-opacity-75 border border-secondary border-opacity-25 mb-4">
                            <div class="d-flex justify-content-between mb-2 fs-14">
                                <span class="text-muted">Jenis Invoice:</span>
                                <span class="fw-medium text-white">{{ $pembelian->jenis_invoice }}</span>
                            </div>
                            @if($pembelian->ppn_persen > 0)
                            <div class="d-flex justify-content-between mb-2 fs-14">
                                <span class="text-muted">PPN ({{ $pembelian->ppn_persen }}%):</span>
                                <span class="text-white">Rp {{ number_format($pembelian->ppn_nominal, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if($pembelian->pph_persen > 0)
                            <div class="d-flex justify-content-between mb-2 fs-14">
                                <span class="text-muted">PPh ({{ $pembelian->pph_persen }}%):</span>
                                <span class="text-white">Rp {{ number_format($pembelian->pph_nominal, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2 fs-15 border-top border-secondary border-opacity-25 pt-2">
                                <span class="fw-bold text-white">Total Tagihan:</span>
                                <span class="fw-bold fs-16" style="color: #60a5fa;">Rp {{ number_format($pembelian->jumlah_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 fs-14">
                                <span class="text-muted">Terbayar:</span>
                                <span class="fw-medium" style="color: #34d399;">Rp {{ number_format($pembelian->terbayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-14">
                                <span class="text-muted">Sisa Tagihan:</span>
                                <span class="fw-medium" style="color: {{ $pembelian->sisa > 0 ? '#fb7185' : '#94a3b8' }};">Rp {{ number_format($pembelian->sisa, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex flex-column gap-2">
                            <form action="{{ route('pembelian.unlock', $pembelian->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 py-2 fs-15 fw-semibold shadow-sm">
                                    <i class="ri-lock-unlock-line align-middle me-1"></i>Setujui & Buka Kunci Pembelian
                                </button>
                            </form>

                            <a href="{{ route('pembelian.vendor.show', $pembelian->id_vendor) }}" class="btn btn-soft-secondary w-100 py-2 fs-14">
                                <i class="ri-arrow-left-line align-middle me-1"></i>Kembali ke Riwayat Vendor
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
