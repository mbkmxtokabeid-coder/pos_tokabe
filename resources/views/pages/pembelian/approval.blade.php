@extends('layout.template')

@section('head')
<style>
    /* Charcoal Premium Design Tokens (DESIGN.md §2, §4 & §12) */
    .approval-page-container {
        color: #F0F6FC;
    }

    /* Cards & Containers */
    .app-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3) !important;
        overflow: hidden;
    }
    .app-card-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 14px 20px !important;
    }
    .app-card-body {
        padding: 20px !important;
    }

    /* Meta Strip (Unified compact metadata) */
    .meta-strip {
        background-color: #13171F;
        border-bottom: 1px solid #232A35;
        padding: 14px 20px;
    }
    .meta-col {
        border-right: 1px solid #232A35;
    }
    @media (max-width: 767.98px) {
        .meta-col {
            border-right: none;
            border-bottom: 1px solid #232A35;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
    }
    .meta-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #8B949E;
        font-weight: 600;
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .meta-value {
        font-size: 14px;
        font-weight: 600;
        color: #F0F6FC;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Tables */
    .app-table {
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100%;
    }
    .app-table thead th {
        background-color: #181E26 !important;
        color: #8B949E !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        padding: 12px 18px !important;
        white-space: nowrap;
    }
    .app-table tbody tr {
        border-bottom: 1px solid #21262D !important;
        transition: background-color 0.15s ease;
    }
    .app-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    .app-table tbody td {
        border: none !important;
        border-bottom: 1px solid #21262D !important;
        padding: 12px 18px !important;
        color: #CBD5E1 !important;
        vertical-align: middle;
        font-size: 13px;
    }
    .app-table tbody tr:last-child td {
        border-bottom: none !important;
    }
    .font-num {
        font-variant-numeric: tabular-nums;
        font-family: var(--font-mono, "JetBrains Mono", ui-monospace, monospace);
    }

    /* Right Column: Sticky Summary Panel */
    .sticky-panel {
        position: sticky;
        top: 80px;
        z-index: 10;
    }

    .financial-hero {
        background: linear-gradient(180deg, rgba(30, 41, 59, 0.5) 0%, rgba(15, 23, 42, 0.5) 100%);
        border: 1px solid #282E37;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .financial-hero-label {
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94A3B8;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .financial-hero-val {
        font-size: 24px;
        font-weight: 700;
        color: #60A5FA;
        letter-spacing: -0.02em;
    }

    .fin-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        font-size: 13px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }
    .fin-item-row:last-child {
        border-bottom: none;
    }

    /* Progress Bar */
    .custom-progress-track {
        height: 6px;
        background-color: #21262D;
        border-radius: 6px;
        overflow: hidden;
        margin: 8px 0;
    }
    .custom-progress-bar {
        background: linear-gradient(90deg, #10B981 0%, #34D399 100%);
        height: 100%;
        transition: width 0.3s ease;
    }

    /* Notice Box (High contrast, crystal clear text) */
    .notice-box {
        background-color: rgba(245, 158, 11, 0.08) !important;
        border: 1px solid rgba(245, 158, 11, 0.25) !important;
        border-radius: 10px !important;
        padding: 14px !important;
        margin-bottom: 16px !important;
    }
    .notice-box-title {
        color: #FCD34D !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        margin-bottom: 4px !important;
    }
    .notice-box-desc {
        color: #CBD5E1 !important;
        font-size: 12px !important;
        line-height: 1.5 !important;
        margin-bottom: 0 !important;
    }

    /* Buttons */
    .btn-approve-unlock {
        background-color: #059669 !important;
        border: 1px solid #10B981 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        border-radius: 8px !important;
        padding: 10px 16px !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25) !important;
        transition: all 0.15s ease !important;
    }
    .btn-approve-unlock:hover {
        background-color: #047857 !important;
        border-color: #059669 !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4) !important;
        color: #FFFFFF !important;
        transform: translateY(-1px);
    }

    .btn-back-vendor {
        background-color: transparent !important;
        border: 1px solid #334155 !important;
        color: #94A3B8 !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        border-radius: 8px !important;
        padding: 9px 16px !important;
        transition: all 0.15s ease !important;
    }
    .btn-back-vendor:hover {
        background-color: #1F2937 !important;
        border-color: #475569 !important;
        color: #F0F6FC !important;
    }

    /* Badges */
    .pill-badge {
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .pill-cicil {
        background-color: rgba(56, 189, 248, 0.12);
        color: #38BDF8;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }
    .pill-lunas {
        background-color: rgba(16, 185, 129, 0.12);
        color: #34D399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .pill-belum-lunas {
        background-color: rgba(245, 158, 11, 0.12);
        color: #FCD34D;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .pill-locked {
        background-color: rgba(148, 163, 184, 0.1);
        color: #94A3B8;
        border: 1px solid rgba(148, 163, 184, 0.25);
    }
    .pill-owner {
        background-color: rgba(99, 102, 241, 0.12);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }
</style>
@endsection

@section('content')
<div class="page-content approval-page-container">
    <div class="container-fluid">
        
        {{-- Breadcrumb / Back Navigation --}}
        <div class="mb-3">
            <a href="{{ route('pembelian.vendor.show', $pembelian->id_vendor) }}" class="text-muted d-inline-flex align-items-center gap-1 fs-13 text-decoration-none" style="transition: color 0.15s ease;" onmouseover="this.style.color='#60A5FA'" onmouseout="this.style.color='#94A3B8'">
                <i class="ri-arrow-left-line"></i> Kembali ke Riwayat Vendor
            </a>
        </div>

        {{-- Page Header Bar --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h3 class="mb-0 text-white fw-bold">
                        Invoice #{{ $pembelian->nomor_inv }}
                    </h3>
                    <span class="text-muted fs-15">&bull;</span>
                    <span class="fs-15 fw-semibold text-white d-flex align-items-center gap-1">
                        <i class="ri-store-2-line text-info"></i> {{ $pembelian->vendor->nama_vendor ?? '-' }}
                    </span>
                </div>
                <div class="text-muted fs-13">
                    Konfirmasi Persetujuan Edit Pembelian & Peninjauan Transaksi
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($pembelian->status === 'Cicil')
                    @php
                        $approvedCount = $pembelian->pembayaran ? $pembelian->pembayaran->where('status_approval', 'Approved')->count() : 0;
                    @endphp
                    <span class="pill-badge pill-cicil">
                        <i class="ri-time-line"></i> Termin ({{ $approvedCount }}/5)
                    </span>
                @elseif($pembelian->status === 'Lunas')
                    <span class="pill-badge pill-lunas">
                        <i class="ri-checkbox-circle-line"></i> Lunas
                    </span>
                @else
                    <span class="pill-badge pill-belum-lunas">
                        <i class="ri-close-circle-line"></i> Belum Lunas
                    </span>
                @endif

                <span class="pill-badge pill-locked">
                    <i class="ri-lock-line"></i> Terkunci
                </span>
                <span class="pill-badge pill-owner">
                    <i class="ri-shield-user-line"></i> Owner
                </span>
            </div>
        </div>

        {{-- Pending Installment Alert Banner (If Any) --}}
        @php
            $pendingList = $pembelian->pembayaran ? $pembelian->pembayaran->where('status_approval', 'Pending') : collect();
        @endphp
        @if($pendingList->isNotEmpty())
            @foreach($pendingList as $pPending)
            <div class="app-card mb-4" style="border-left: 4px solid #F59E0B !important;">
                <div class="app-card-body p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2.5 rounded-circle" style="background-color: rgba(245, 158, 11, 0.15); color: #F59E0B;">
                                <i class="ri-wallet-3-line fs-20"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <h6 class="text-white fw-bold mb-0">Pengajuan Termin Ke-{{ $pPending->cicilan_ke }} Menunggu Persetujuan</h6>
                                    <span class="badge bg-warning bg-opacity-20 text-warning px-2 py-0.5 fs-11">Pending Approval</span>
                                </div>
                                <div class="text-muted fs-12 mt-1">
                                    Nominal: <strong class="text-white font-num fs-13">Rp {{ number_format($pPending->jumlah_bayar, 0, ',', '.') }}</strong>
                                    &bull; Sumber: <span class="text-info">{{ $pPending->anggaran->nama_anggaran ?? 'Kas Default' }}</span>
                                    &bull; Tanggal: <span>{{ optional($pPending->tanggal_bayar)->translatedFormat('d M Y') }}</span>
                                    &bull; Diajukan: <span class="text-white">{{ optional($pPending->user)->name ?: 'Admin' }}</span>
                                    @if($pPending->catatan)
                                        &bull; <em>"{{ $pPending->catatan }}"</em>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('pembelian.cicilan.approve', $pPending->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success px-3 py-1.5 fs-12 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                                    <i class="ri-check-line"></i> Setujui Termin
                                </button>
                            </form>
                            <form action="{{ route('pembelian.cicilan.reject', $pPending->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak termin ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1.5 fs-12 fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="ri-close-line"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <div class="row g-4">
            {{-- Left Column: Master Purchase Details & Tables --}}
            <div class="col-lg-8">
                
                {{-- Master Invoice Card: Compact Metadata Strip + Items Table --}}
                <div class="app-card mb-4">
                    {{-- 4-Column Metadata Strip --}}
                    <div class="meta-strip">
                        <div class="row g-3">
                            <div class="col-md-3 col-6 meta-col">
                                <div class="meta-label">
                                    <i class="ri-hashtag text-info"></i> No. Invoice
                                </div>
                                <div class="meta-value text-info font-num">
                                    {{ $pembelian->nomor_inv }}
                                </div>
                            </div>

                            <div class="col-md-3 col-6 meta-col">
                                <div class="meta-label">
                                    <i class="ri-calendar-line text-warning"></i> Tanggal
                                </div>
                                <div class="meta-value">
                                    {{ optional($pembelian->tanggal)->translatedFormat('d M Y') ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-3 col-6 meta-col">
                                <div class="meta-label">
                                    <i class="ri-time-line text-danger"></i> Jatuh Tempo
                                </div>
                                <div class="meta-value">
                                    {{ optional($pembelian->effective_tanggal_jto)->translatedFormat('d M Y') ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="meta-label">
                                    <i class="ri-file-text-line text-primary"></i> Jenis Invoice
                                </div>
                                <div class="meta-value">
                                    {{ $pembelian->jenis_invoice }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Items Table Header --}}
                    <div class="app-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-shopping-cart-2-line text-primary fs-15"></i>
                            <h6 class="mb-0 text-white fw-semibold fs-13">Rincian Barang / Jasa</h6>
                        </div>
                        <span class="badge bg-secondary bg-opacity-25 text-light fs-11 px-2 py-0.5">
                            {{ $pembelian->barang ? $pembelian->barang->count() : 0 }} Item
                        </span>
                    </div>

                    {{-- Items Table Body --}}
                    <div class="table-responsive">
                        <table class="app-table">
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
                                        <div class="fw-semibold text-white">{{ $item->deskripsi }}</div>
                                        @if($item->satuan)
                                            <span class="text-muted fs-11">Satuan: {{ $item->satuan }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center font-num text-white">{{ $item->qty }}</td>
                                    <td class="text-end font-num text-muted">Rp {{ number_format($item->harga_barang, 0, ',', '.') }}</td>
                                    <td class="text-end font-num fw-semibold text-white">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Tidak ada rincian item.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Riwayat Pembayaran Cicilan (Fully visible & clean) --}}
                @if($pembelian->pembayaran && $pembelian->pembayaran->isNotEmpty())
                <div class="app-card mb-4">
                    <div class="app-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-history-line text-warning fs-15"></i>
                            <h6 class="mb-0 text-white fw-semibold fs-13">Riwayat Pembayaran Termin</h6>
                        </div>
                        <span class="badge bg-secondary bg-opacity-25 text-light fs-11 px-2 py-0.5">
                            {{ $pembelian->pembayaran->count() }} Pembayaran Terdata
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="app-table">
                            <thead>
                                <tr>
                                    <th>Tahap</th>
                                    <th>Tanggal</th>
                                    <th>Sumber Kas</th>
                                    <th class="text-end">Nominal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 130px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->pembayaran as $pby)
                                <tr>
                                    <td class="fw-semibold text-white">
                                        Termin Ke-{{ $pby->cicilan_ke }}
                                    </td>
                                    <td class="text-muted fs-12">
                                        {{ optional($pby->tanggal_bayar)->translatedFormat('d M Y') ?: '-' }}
                                    </td>
                                    <td class="text-muted fs-12">
                                        {{ $pby->anggaran->nama_anggaran ?? '-' }}
                                    </td>
                                    <td class="text-end font-num fw-semibold text-white">
                                        Rp {{ number_format($pby->jumlah_bayar, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($pby->status_approval === 'Approved')
                                            <span class="pill-badge pill-lunas fs-11 py-0.5">Disetujui</span>
                                        @elseif($pby->status_approval === 'Pending')
                                            <span class="pill-badge pill-belum-lunas fs-11 py-0.5">Menunggu Approval</span>
                                        @elseif(str_contains($pby->catatan ?? '', '[Dibatalkan Owner]'))
                                            <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-50 fs-11">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-25 fs-11">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($pby->status_approval === 'Pending')
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <form action="{{ route('pembelian.cicilan.approve', $pby->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success py-1 px-2 fs-11 fw-semibold" title="Setujui">
                                                        <i class="ri-check-line"></i> Setuju
                                                    </button>
                                                </form>
                                                <form action="{{ route('pembelian.cicilan.reject', $pby->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak termin ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-2 fs-11 fw-semibold" title="Tolak">
                                                        <i class="ri-close-line"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($pby->status_approval === 'Approved')
                                            <form action="{{ route('pembelian.cicilan.cancel', $pby->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan Termin Ke-{{ $pby->cicilan_ke }} (Rp {{ number_format($pby->jumlah_bayar, 0, ',', '.') }})? Saldo kas dan sisa tagihan vendor akan dikembalikan.')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 fs-11 fw-semibold" title="Batalkan Termin yang sudah disetujui">
                                                    <i class="ri-arrow-go-back-line"></i> Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted fs-11">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>

            {{-- Right Column: Sticky Summary & Action Card --}}
            <div class="col-lg-4">
                <div class="app-card sticky-panel">
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-shield-check-line text-warning fs-16"></i>
                            <h6 class="mb-0 text-white fw-semibold fs-14">Ringkasan & Aksi</h6>
                        </div>
                    </div>

                    <div class="app-card-body">
                        {{-- Hero Total Tagihan --}}
                        <div class="financial-hero">
                            <div class="financial-hero-label">Total Tagihan</div>
                            <div class="financial-hero-val font-num">
                                Rp {{ number_format($pembelian->jumlah_harga, 0, ',', '.') }}
                            </div>

                            @php
                                $percentPaid = $pembelian->jumlah_harga > 0 ? min(100, round(($pembelian->terbayar / $pembelian->jumlah_harga) * 100)) : 0;
                            @endphp

                            <div class="custom-progress-track">
                                <div class="custom-progress-bar" style="width: {{ $percentPaid }}%;"></div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center text-muted fs-11 mt-1">
                                <span>Terbayar: <strong class="text-success font-num">{{ $percentPaid }}%</strong></span>
                                <span class="font-num text-white">Rp {{ number_format($pembelian->terbayar, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Breakdown Details --}}
                        <div class="mb-3 px-1">
                            <div class="fin-item-row">
                                <span class="text-muted">Terbayar</span>
                                <span class="font-num fw-semibold text-success">
                                    Rp {{ number_format($pembelian->terbayar, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="fin-item-row">
                                <span class="text-muted">Sisa Tagihan</span>
                                <span class="font-num fw-semibold" style="color: {{ $pembelian->sisa > 0 ? '#FB7185' : '#94A3B8' }};">
                                    Rp {{ number_format($pembelian->sisa, 0, ',', '.') }}
                                </span>
                            </div>

                            @if($pembelian->ppn_persen > 0)
                            <div class="fin-item-row">
                                <span class="text-muted">PPN ({{ $pembelian->ppn_persen }}%)</span>
                                <span class="font-num text-white">Rp {{ number_format($pembelian->ppn_nominal, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            @if($pembelian->pph_persen > 0)
                            <div class="fin-item-row">
                                <span class="text-muted">PPh ({{ $pembelian->pph_persen }}%)</span>
                                <span class="font-num text-white">Rp {{ number_format($pembelian->pph_nominal, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Notice Callout (High Contrast, Crystal Clear Text) --}}
                        <div class="notice-box">
                            <div class="d-flex align-items-start gap-2">
                                <i class="ri-information-fill text-warning fs-18 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <div class="notice-box-title">Persetujuan Buka Kunci (Unlock)</div>
                                    <div class="notice-box-desc">
                                        Admin meminta izin untuk mengedit transaksi ini. Klik tombol di bawah agar status pembelian di-Unlock dan admin dapat melakukan penyesuaian data.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-column gap-2 pt-2 border-top border-secondary border-opacity-25">
                            <form action="{{ route('pembelian.unlock', $pembelian->id) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-approve-unlock w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="ri-lock-unlock-line fs-16"></i> Setujui & Buka Kunci Pembelian
                                </button>
                            </form>

                            <a href="{{ route('pembelian.vendor.show', $pembelian->id_vendor) }}" class="btn btn-back-vendor w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="ri-arrow-left-line"></i> Kembali ke Riwayat Vendor
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
