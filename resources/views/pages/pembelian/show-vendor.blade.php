@extends('layout.template')

@section('head')
<style>
    /* Surface & KPI Cards (DESIGN.md §4 & §12) */
    .metric-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        border-color: #383E45 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }
    .metric-icon-box {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
    }
    
    /* Table Styling (DESIGN.md §13) */
    .table-container-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        overflow: hidden;
    }
    .table-container-card .card-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 16px 20px !important;
    }
    .table-custom {
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    .table-custom thead th {
        background-color: #181E26 !important;
        color: #94A3B8 !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        font-weight: 600 !important;
        font-size: 11.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 12px 14px !important;
        white-space: nowrap;
    }
    .table-custom tbody tr {
        border-bottom: 1px solid #21262D !important;
        transition: background-color 150ms ease !important;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    .table-custom tbody td {
        border: none !important;
        border-bottom: 1px solid #21262D !important;
        padding: 12px 14px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #CBD5E1 !important;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none !important;
    }
    .num-tabular {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
    }

    /* Badges (DESIGN.md §14) */
    .badge-status-lunas {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-status-belum-lunas {
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #FCD34D !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-status-cicil {
        background-color: rgba(56, 189, 248, 0.12) !important;
        color: #38BDF8 !important;
        border: 1px solid rgba(56, 189, 248, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-access-locked {
        background-color: rgba(148, 163, 184, 0.1) !important;
        color: #94A3B8 !important;
        border: 1px solid rgba(148, 163, 184, 0.25) !important;
        font-weight: 500 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        font-size: 11.5px !important;
    }
    .badge-access-unlocked {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 500 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        font-size: 11.5px !important;
    }

    /* Action Button & Dropdown Menu (DESIGN.md §11) */
    .btn-action-more {
        background-color: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        width: 32px !important;
        height: 32px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        transition: all 120ms ease !important;
    }
    .btn-action-more:hover,
    .btn-action-more[aria-expanded="true"] {
        background-color: #2A3037 !important;
        border-color: #4A525D !important;
        color: #FFFFFF !important;
    }
    .dropdown-menu {
        background-color: #161B22 !important;
        border: 1px solid #2D333B !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
        padding: 6px !important;
        min-width: 190px !important;
    }
    .dropdown-item {
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .dropdown-item:hover {
        background-color: #21262E !important;
        color: #FFFFFF !important;
    }
    .dropdown-item.text-danger:hover {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #F87171 !important;
    }
    .dropdown-divider {
        border-color: #232A35 !important;
    }

    /* Primary Add Button (DESIGN.md §9) */
    .btn-add-pembelian {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        height: 38px !important;
        padding: 0 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
        transition: all 120ms ease !important;
    }
    .btn-add-pembelian:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
        color: #FFFFFF !important;
    }

    /* Modal Styling & Fixed Action Buttons (DESIGN.md §15) */
    .modal-vendor-custom .modal-content {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
        color: #F8F9FA !important;
        overflow: hidden;
    }
    .modal-vendor-custom .modal-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 14px 20px !important;
    }
    .modal-vendor-custom .modal-title {
        color: #F8F9FA !important;
        font-size: 15.5px !important;
        font-weight: 600 !important;
    }
    .modal-vendor-custom .modal-body {
        padding: 16px 20px !important;
        scrollbar-width: thin;
        scrollbar-color: #383E45 #161B22;
    }
    .modal-vendor-custom .modal-body label {
        font-size: 12.5px !important;
        font-weight: 500 !important;
        color: #CBD5E1 !important;
        margin-bottom: 4px;
    }
    .modal-vendor-custom .form-control,
    .modal-vendor-custom .form-select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        font-size: 13.5px !important;
        height: 38px;
        padding: 0 12px;
        transition: border-color 150ms ease, box-shadow 150ms ease !important;
    }
    .modal-vendor-custom .form-control:focus,
    .modal-vendor-custom .form-select:focus {
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.28) !important;
        background-color: #272C32 !important;
        color: #FFFFFF !important;
    }
    .modal-vendor-custom .modal-footer {
        background-color: #181E26 !important;
        border-top: 1px solid #282E37 !important;
        padding: 12px 20px !important;
        gap: 10px;
        position: sticky;
        bottom: 0;
        z-index: 10;
    }
    .btn-cancel-modal {
        background-color: #212529 !important;
        border: 1px solid #475569 !important;
        color: #CBD5E1 !important;
        border-radius: 8px !important;
        height: 38px !important;
        padding: 0 18px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 120ms ease;
    }
    .btn-cancel-modal:hover {
        background-color: #2A3037 !important;
        color: #FFFFFF !important;
        border-color: #64748B !important;
    }
    .btn-submit-modal {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        height: 38px !important;
        padding: 0 20px !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
        transition: all 120ms ease;
    }
    .btn-submit-modal:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
        color: #FFFFFF !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 text-white fw-bold">Daftar Pembelian &mdash; {{ $vendor->nama_vendor }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}" class="text-muted">Vendor</a></li>
                    <li class="breadcrumb-item active text-white">{{ $vendor->nama_vendor }}</li>
                </ol>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6EE7B7; border-radius: 10px;">
                <i class="ri-checkbox-circle-line fs-20 me-2 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; border-radius: 10px;">
                <i class="ri-error-warning-line fs-20 me-2 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('pembelian.create', $vendor->id) }}" class="btn-add-pembelian">
                <i class="ri-add-line me-1 fs-16"></i>Tambah Pembelian
            </a>
        </div>

        {{-- Kartu Ringkasan (KPI Cards) --}}
        <div class="row g-3 mb-4">
            {{-- Total Pembelian --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold text-white fs-20 num-tabular">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Total Pembelian</p>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(99, 102, 241, 0.12); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25);">
                                <i class="ri-file-list-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Terbayar --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: #34D399;">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Total Terbayar</p>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(16, 185, 129, 0.12); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <i class="ri-checkbox-circle-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisa Belum Dibayar --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: {{ $totalSisa > 0 ? '#FB7185' : '#94A3B8' }};">Rp {{ number_format($totalSisa, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Sisa Belum Dibayar</p>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(244, 63, 94, 0.12); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.25);">
                                <i class="ri-error-warning-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisa Duit Kas --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: {{ ($sisaKas ?? 0) >= 0 ? '#38BDF8' : '#FB7185' }};">
                                    @if(($sisaKas ?? 0) < 0)
                                        -Rp {{ number_format(abs($sisaKas ?? 0), 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($sisaKas ?? 0, 0, ',', '.') }}
                                    @endif
                                </h4>
                                <div class="d-flex align-items-center gap-1.5">
                                    <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Sisa Duit Kas</p>
                                    @if(($sisaKas ?? 0) < 0)
                                        <span class="badge py-0 px-1 fs-10 fw-semibold" style="background: rgba(244, 63, 94, 0.15); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.3);">Minus</span>
                                    @else
                                        <span class="badge py-0 px-1 fs-10 fw-semibold" style="background: rgba(56, 189, 248, 0.15); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.3);">Tersedia</span>
                                    @endif
                                </div>
                            </div>
                            <div class="metric-icon-box" style="{{ ($sisaKas ?? 0) >= 0 ? 'background-color: rgba(56, 189, 248, 0.12); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.25);' : 'background-color: rgba(244, 63, 94, 0.12); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.25);' }}">
                                <i class="ri-wallet-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat Pembelian --}}
        <div class="card table-container-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-title mb-0 fs-15 fw-semibold text-white">Riwayat Pembelian &mdash; {{ $vendor->nama_vendor }}</h5>
                    <span class="badge" id="pembelianCountBadge" style="background: rgba(99, 102, 241, 0.12); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25); font-weight: 600; padding: 4px 10px; border-radius: 9999px;">
                        {{ count($pembelian) }} Transaksi
                    </span>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="position-relative" style="min-width: 180px;">
                        <input type="text" id="searchPembelianInput" class="form-control form-control-sm ps-4 pe-4" placeholder="Cari invoice..." style="background-color: #212529; border: 1px solid #383E45; border-radius: 8px; color: #F8F9FA; font-size: 13px;">
                        <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted fs-13"></i>
                    </div>
                    <select id="filterStatusPembelian" class="form-select form-select-sm" style="width: 150px; background-color: #212529; border: 1px solid #383E45; border-radius: 8px; color: #F8F9FA; font-size: 13px;" title="Filter status pembayaran">
                        <option value="all">Semua Status</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Cicil">Termin</option>
                        </select>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th>No. Invoice</th>
                                <th class="text-center">Tanggal Pembelian</th>
                                <th class="text-center">Tanggal JTO</th>
                                <th class="text-center">Status Bayar</th>
                                <th class="text-center" style="width: 120px;">Akses Edit</th>
                                <th class="text-end">Total (Rp)</th>
                                <th class="text-end">Terbayar (Rp)</th>
                                <th class="text-end">Sisa (Rp)</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pembelianTableBody">
                        @forelse ($pembelian as $item)
                            @php
                                $roleUser     = Auth::user()->role ?? '';
                                $isOwner      = $roleUser === 'Pemilik';
                                $isAdmin      = $roleUser === 'Admin';
                                $isUnlocked   = $item->approval === 'Unlock';
                                $canManage    = !$isAdmin || $isUnlocked;

                                // Kontak Owner Tokabe (sesuai modul invoice)
                                $waOwner1 = '628116029999';
                                $waOwner2 = '628116179999';

                                // Pesan Request Unlock ke Owner via WhatsApp
                                $pesanUnlock = urlencode("Halo, saya ingin meminta approval untuk membuka akses riwayat pembelian *#{$item->nomor_inv}* dari vendor *{$vendor->nama_vendor}*. Mohon untuk segera ditinjau.\n\nKlik tautan berikut untuk membuka halaman persetujuan:\n" . url("/pembelian-approval/{$item->id}"));
                            @endphp
                            <tr class="pembelian-row" data-invoice="{{ strtolower($item->nomor_inv) }}" data-status="{{ $item->status }}">
                                <td class="text-center text-muted fs-13 row-number">{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-white fs-13.5">{{ $item->nomor_inv }}</td>
                                <td class="text-center fs-13">{{ optional($item->tanggal)->translatedFormat('d M Y') }}</td>
                                <td class="text-center fs-13">{{ optional($item->effective_tanggal_jto)->translatedFormat('d M Y') ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-1">
                                        @if($item->status === 'Lunas')
                                            <span class="badge-status-lunas">Lunas</span>
                                        @else
                                            <span class="badge-status-cicil">Termin</span>
                                            @if($item->approved_cicilan_count >= 5 && $item->sisa > 0)
                                                <span class="badge py-0.5 px-2 fs-10 fw-semibold" style="background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3);" title="Batas maksimal termin 5x tercapai. Butuh pelunasan dari Pemilik.">
                                                    <i class="ri-alert-line"></i> Maks 5/5
                                                </span>
                                            @else
                                                <span class="badge py-0.5 px-2 fs-10 fw-semibold" style="background: rgba(99, 102, 241, 0.15); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.3);">
                                                    Termin {{ $item->approved_cicilan_count }}/5
                                                </span>
                                            @endif
                                        @endif

                                        @if($item->pending_cicilan_count > 0)
                                            <span class="badge py-0.5 px-1.5 fs-10 fw-semibold" style="background: rgba(245, 158, 11, 0.15); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.3);">
                                                <i class="ri-time-line"></i> 1 Pending
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($isUnlocked)
                                        <span class="badge-access-unlocked" title="Akses edit terbuka">
                                            <i class="ri-lock-unlock-line"></i> Terbuka
                                        </span>
                                    @else
                                        <span class="badge-access-locked" title="Akses edit terkunci">
                                            <i class="ri-lock-line" style="color: #F59E0B;"></i> Terkunci
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-white num-tabular">{{ number_format($item->jumlah_harga, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold num-tabular" style="color: #34D399;">{{ number_format($item->terbayar, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold num-tabular" style="color: {{ $item->sisa > 0 ? '#FB7185' : '#94A3B8' }};">{{ number_format($item->sisa, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                            <i class="ri-more-fill fs-16"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">

                                            {{-- Menu Edit --}}
                                            @if($canManage)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('pembelian.edit', $item->id) }}">
                                                        <i class="ri-edit-line fs-15 text-primary"></i>Edit Pembelian
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <span class="dropdown-item text-muted disabled">
                                                        <i class="ri-lock-line fs-15 text-warning"></i>Edit Terkunci
                                                    </span>
                                                </li>
                                            @endif

                                            {{-- ADMIN: Request Unlock via WA jika status masih Terkunci --}}
                                            @if($isAdmin && !$isUnlocked)
                                                <li>
                                                    <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner1 }}?text={{ $pesanUnlock }}" target="_blank">
                                                        <i class="ri-whatsapp-line fs-15 text-success"></i>Request Unlock (Owner 1)
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner2 }}?text={{ $pesanUnlock }}" target="_blank">
                                                        <i class="ri-whatsapp-line fs-15 text-success"></i>Request Unlock (Owner 2)
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- OWNER: Lock & Unlock langsung --}}
                                            @if($isOwner)
                                                @if($isUnlocked)
                                                    <li>
                                                        <a class="dropdown-item text-warning" href="{{ route('pembelian.lock', $item->id) }}">
                                                            <i class="ri-lock-line fs-15"></i>Kunci Kembali (Lock)
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form action="{{ route('pembelian.unlock', $item->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="ri-lock-unlock-line fs-15"></i>Buka Kunci (Unlock)
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-info" href="{{ route('pembelian.approval.page', $item->id) }}">
                                                            <i class="ri-shield-check-line fs-15"></i>Halaman Approval
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif

                                            {{-- Menu Bayar Termin --}}
                                            @if($item->status !== 'Lunas' && $item->sisa > 0)
                                                @if($item->approved_cicilan_count < 5)
                                                    @if($item->pending_cicilan_count == 0)
                                                        <li>
                                                            <a class="dropdown-item text-primary" href="javascript:void(0)"
                                                               onclick="openModalBayarCicilan('{{ $item->id }}', '{{ $item->nomor_inv }}', {{ $item->sisa }}, {{ $item->approved_cicilan_count + 1 }}, '{{ $item->anggaran }}')">
                                                                <i class="ri-money-dollar-circle-line fs-15 text-primary"></i>Bayar Termin (Ke-{{ $item->approved_cicilan_count + 1 }}/5)
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <span class="dropdown-item text-warning disabled">
                                                                <i class="ri-time-line fs-15 text-warning"></i>Termin Menunggu Approval
                                                            </span>
                                                        </li>
                                                    @endif
                                                @else
                                                    <li>
                                                        <span class="dropdown-item text-danger disabled" title="Batas maksimal termin 5 kali telah tercapai">
                                                            <i class="ri-forbid-line fs-15 text-danger"></i>Maks Termin 5x Tercapai
                                                        </span>
                                                    </li>
                                                @endif
                                            @endif

                                            {{-- Menu Track Record / Riwayat Termin --}}
                                            @if($item->pembayaran->isNotEmpty() || $item->status === 'Cicil')
                                                <li>
                                                    <a class="dropdown-item text-info" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modalRiwayatCicilan_{{ $item->id }}">
                                                        <i class="ri-history-line fs-15 text-info"></i>Track Record Termin ({{ $item->pembayaran->count() }})
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Lunasi (jika belum lunas) --}}
                                            @if($item->status !== 'Lunas')
                                                <li>
                                                    <a class="dropdown-item text-success" href="javascript:void(0)" onclick="confirmLunasi('{{ route('pembelian.lunas', $item->id) }}')">
                                                        <i class="ri-check-line fs-15 text-success"></i>Lunasi
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Hapus (hanya jika memiliki hak kelola) --}}
                                            @if($canManage)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST" onsubmit="event.preventDefault(); confirmHapus(this);">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line fs-15 text-danger"></i>Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="ri-inbox-line fs-36 text-muted mb-2" style="opacity: 0.5;"></i>
                                        <span class="fs-14">Belum ada transaksi pembelian untuk vendor ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

{{-- Modal Bayar Termin --}}
<div class="modal fade modal-vendor-custom" id="modalBayarCicilan" tabindex="-1" aria-labelledby="modalBayarCicilanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 520px;">
        <div class="modal-content shadow-lg">
            <form id="formBayarCicilan" method="POST">
                @csrf
                <div class="modal-header py-3 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="background: rgba(99, 102, 241, 0.15); color: #818CF8; width: 36px; height: 36px; border: 1px solid rgba(99, 102, 241, 0.25);">
                            <i class="ri-wallet-3-line fs-18"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fs-16 fw-semibold text-white mb-0" id="modalBayarCicilanLabel">
                                Pembayaran Termin
                            </h5>
                            <small class="text-muted fs-12" id="modal_cicilan_subtitle">Maksimal 5 Kali Pembayaran Termin</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-3">
                    {{-- Info Ringkas Tagihan --}}
                    <div class="p-2.5 mb-2.5 rounded" style="background: rgba(255, 255, 255, 0.03); border: 1px solid #282E37; border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fs-12 text-muted">No. Invoice:</span>
                            <span class="fs-13 fw-bold text-white" id="modal_cicilan_inv">-</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fs-12 text-muted">Sisa Tagihan:</span>
                            <span class="fs-14 fw-bold" style="color: #FB7185;" id="modal_cicilan_sisa_text">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-12 text-muted">Tahap Pembayaran:</span>
                            <span class="badge py-1 px-2 fs-11 fw-semibold" style="background: rgba(99, 102, 241, 0.15); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.3);" id="modal_cicilan_ke_badge">
                                Termin Ke-1 dari Maks 5x
                            </span>
                        </div>
                    </div>

                    {{-- Warning jika termin ke-5 --}}
                    <div id="modal_cicilan_ke5_warning" class="alert alert-warning py-2 px-3 fs-12 mb-3 d-none" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #FCD34D; border-radius: 8px;">
                        <i class="ri-alert-fill me-1"></i><strong>Perhatian:</strong> Ini adalah termin ke-5 (terakhir). Jika masih ada sisa setelah pembayaran ini, transaksi tidak dapat dicicil lagi dan harus diselesaikan via pelunasan oleh Pemilik.
                    </div>

                    {{-- Notice Role --}}
                    @if((Auth::user()->role ?? null) === 'Admin')
                        <div class="alert alert-info py-2 px-3 fs-12 mb-3" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.25); color: #38BDF8; border-radius: 8px;">
                            <i class="ri-information-line me-1"></i>Sebagai <strong>Admin</strong>, termin ini akan dikirim sebagai pengajuan dan membutuhkan <strong>Approval Pemilik</strong> sebelum kas terpotong.
                        </div>
                    @endif

                    <div class="mb-2.5">
                        <label for="cicilan_tanggal" class="form-label fs-13 text-white fw-medium">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_bayar" id="cicilan_tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-2.5">
                        <label for="cicilan_jumlah" class="form-label fs-13 text-white fw-medium">Nominal Termin (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: #212529; border-color: #383E45; color: #94A3B8;">Rp</span>
                            <input type="text" name="jumlah_bayar" id="cicilan_jumlah" class="form-control fw-bold fs-15 text-white" placeholder="0" required oninput="formatInputRibuan(this); checkCicilanMax(this);">
                        </div>
                        <small class="text-muted fs-11 mt-1 d-block">Maksimal: <span id="modal_cicilan_max_label" style="color: #38BDF8;">Rp 0</span></small>
                    </div>

                    <div class="mb-2.5">
                        <label for="cicilan_anggaran" class="form-label fs-13 text-white fw-medium">Sumber Kas / Anggaran <span class="text-danger">*</span></label>
                        <select name="anggaran_id" id="cicilan_anggaran" class="form-select" required>
                            @foreach(($budgets ?? []) as $b)
                                <option value="{{ $b->id }}">
                                    {{ $b->nama_budget }} &mdash; Sisa Kas: Rp {{ number_format($b->anggaran, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="cicilan_catatan" class="form-label fs-13 text-white fw-medium">Catatan / Keterangan</label>
                        <input type="text" name="catatan" id="cicilan_catatan" class="form-control" placeholder="Contoh: Transfer via BCA / Bukti Ref #123 (opsional)">
                    </div>
                </div>
                <div class="modal-footer py-2.5 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-submit-modal" id="btn_submit_cicilan">
                        @if((Auth::user()->role ?? null) === 'Pemilik')
                            <i class="ri-check-line me-1"></i>Simpan Pembayaran
                        @else
                            <i class="ri-send-plane-line me-1"></i>Simpan & Ajukan Termin
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modals Riwayat Track Record Termin --}}
@foreach($pembelian as $item)
<div class="modal fade" id="modalRiwayatCicilan_{{ $item->id }}" tabindex="-1" aria-labelledby="modalRiwayatLabel_{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content" style="background-color: #161B22; border: 1px solid #282E37; border-radius: 14px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6); color: #F1F5F9; overflow: hidden;">
            <div class="modal-header py-3 px-4" style="background-color: #181E26; border-bottom: 1px solid #232A35;">
                <div class="d-flex align-items-center gap-2.5">
                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="background: rgba(56, 189, 248, 0.15); color: #38BDF8; width: 36px; height: 36px; border: 1px solid rgba(56, 189, 248, 0.25);">
                        <i class="ri-history-line fs-18"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fs-16 fw-semibold text-white mb-0" id="modalRiwayatLabel_{{ $item->id }}">
                            Track Record Termin &mdash; Invoice #{{ $item->nomor_inv }}
                        </h5>
                        <small class="text-muted fs-12">Vendor: {{ $vendor->nama_vendor }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                {{-- KPI Mini Riwayat --}}
                <div class="row g-2 mb-3">
                    <div class="col-sm-3 col-6">
                        <div class="p-2.5 rounded text-center" style="background: #212529; border: 1px solid #383E45;">
                            <span class="fs-11 text-muted d-block text-uppercase fw-semibold">Total Tagihan</span>
                            <span class="fs-13 fw-bold text-white num-tabular">Rp {{ number_format($item->jumlah_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="p-2.5 rounded text-center" style="background: #212529; border: 1px solid #383E45;">
                            <span class="fs-11 text-muted d-block text-uppercase fw-semibold">Total Terbayar</span>
                            <span class="fs-13 fw-bold num-tabular" style="color: #34D399;">Rp {{ number_format($item->terbayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="p-2.5 rounded text-center" style="background: #212529; border: 1px solid #383E45;">
                            <span class="fs-11 text-muted d-block text-uppercase fw-semibold">Sisa Tagihan</span>
                            <span class="fs-13 fw-bold num-tabular" style="color: {{ $item->sisa > 0 ? '#FB7185' : '#94A3B8' }};">Rp {{ number_format($item->sisa, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="p-2.5 rounded text-center" style="background: #212529; border: 1px solid #383E45;">
                            <span class="fs-11 text-muted d-block text-uppercase fw-semibold">Status Termin</span>
                            <span class="fs-13 fw-bold text-info">{{ $item->approved_cicilan_count }}/5 Kali</span>
                        </div>
                    </div>
                </div>

                {{-- Tabel Pembayaran --}}
                <div class="table-responsive rounded border" style="border-color: #282E37 !important;">
                    <table class="table table-hover align-middle mb-0" style="color: #CBD5E1;">
                        <thead style="background: #181E26; font-size: 11.5px; text-transform: uppercase;">
                            <tr>
                                <th class="text-center" style="width: 70px;">Termin</th>
                                <th>Tanggal Bayar</th>
                                <th class="text-end">Nominal (Rp)</th>
                                <th>Kas / Anggaran</th>
                                <th>Dicatat Oleh</th>
                                <th class="text-center">Status</th>
                                <th>Catatan</th>
                                <th class="text-center" style="width: 110px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;">
                            @forelse($item->pembayaran as $pby)
                            <tr>
                                <td class="text-center fw-bold text-white">Ke-{{ $pby->cicilan_ke }}</td>
                                <td>{{ optional($pby->tanggal_bayar)->translatedFormat('d M Y') }}</td>
                                <td class="text-end fw-bold text-white num-tabular">Rp {{ number_format($pby->jumlah_bayar, 0, ',', '.') }}</td>
                                <td class="fs-12 text-muted">{{ optional($pby->anggaran)->nama_anggaran ?: 'Kas Anggaran' }}</td>
                                <td class="fs-12">{{ optional($pby->user)->name ?: 'User' }}</td>
                                <td class="text-center">
                                    @if($pby->status_approval === 'Approved')
                                        <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                            <i class="ri-check-line"></i> Disetujui
                                        </span>
                                    @elseif($pby->status_approval === 'Pending')
                                        <span class="badge" style="background: rgba(245, 158, 11, 0.15); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.3);">
                                            <i class="ri-time-line"></i> Menunggu Approval
                                        </span>
                                    @else
                                        <span class="badge" style="background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.3);">
                                            <i class="ri-close-line"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="fs-12 text-muted">{{ $pby->catatan ?: '-' }}</td>
                                <td class="text-center">
                                    @if($pby->status_approval === 'Pending')
                                        @if($isOwner)
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <form action="{{ route('pembelian.cicilan.approve', $pby->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success py-1 px-2 fs-11" title="Setujui Termin">
                                                        <i class="ri-check-line"></i> Setuju
                                                    </button>
                                                </form>
                                                <form action="{{ route('pembelian.cicilan.reject', $pby->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pengajuan termin ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-2 fs-11" title="Tolak Termin">
                                                        <i class="ri-close-line"></i> Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            @php
                                                $pesanWaCicilan = urlencode("Halo, admin telah mengajukan pembayaran termin ke-{$pby->cicilan_ke} untuk invoice #{$item->nomor_inv} ({$vendor->nama_vendor}) sebesar Rp " . number_format($pby->jumlah_bayar, 0, ',', '.') . ". Mohon persetujuan Pemilik melalui sistem Tokabe.");
                                            @endphp
                                            <a href="https://wa.me/{{ $waOwner1 }}?text={{ $pesanWaCicilan }}" target="_blank" class="btn btn-sm btn-outline-warning py-1 px-2 fs-11" title="Kirim WA ke Pemilik">
                                                <i class="ri-whatsapp-line"></i> Hubungi Owner
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted fs-11">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat pembayaran termin yang tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-4" style="background-color: #161B22; border-top: 1px solid #232A35;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
    </div>
</div>
@endsection

@section('plugins')
<script>
    let currentCicilanMaxSisa = 0;

    function openModalBayarCicilan(pembelianId, nomorInv, sisa, nextCicilanKe, defaultAnggaran) {
        const form = document.getElementById('formBayarCicilan');
        if (!form) return;
        form.action = `/pembelian/${pembelianId}/cicilan`;

        document.getElementById('modal_cicilan_inv').textContent = '#' + nomorInv;
        document.getElementById('modal_cicilan_sisa_text').textContent = 'Rp ' + formatRp(sisa);
        document.getElementById('modal_cicilan_max_label').textContent = 'Rp ' + formatRp(sisa);
        document.getElementById('modal_cicilan_ke_badge').textContent = `Termin Ke-${nextCicilanKe} dari Maksimal 5x`;

        currentCicilanMaxSisa = sisa;

        const warningKe5 = document.getElementById('modal_cicilan_ke5_warning');
        if (warningKe5) {
            warningKe5.classList.toggle('d-none', nextCicilanKe !== 5);
        }

        const jumlahInput = document.getElementById('cicilan_jumlah');
        if (jumlahInput) jumlahInput.value = '';

        const catatanInput = document.getElementById('cicilan_catatan');
        if (catatanInput) catatanInput.value = '';

        const anggaranSelect = document.getElementById('cicilan_anggaran');
        if (anggaranSelect && defaultAnggaran) {
            anggaranSelect.value = defaultAnggaran;
        }

        const modalEl = document.getElementById('modalBayarCicilan');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function checkCicilanMax(input) {
        let val = parseRibuan(input.value);
        if (val > currentCicilanMaxSisa) {
            val = currentCicilanMaxSisa;
            input.value = formatRp(val);
        }
    }

    function formatRp(num) {
        return (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function parseRibuan(val) {
        if (!val) return 0;
        return parseInt(val.toString().replace(/[^0-9]/g, ''), 10) || 0;
    }

    function formatInputRibuan(input) {
        let val = parseRibuan(input.value);
        input.value = val > 0 ? formatRp(val) : '';
    }

    function filterPembelianTable() {
        const searchInput = document.getElementById('searchPembelianInput');
        const statusSelect = document.getElementById('filterStatusPembelian');
        const badgeCount = document.getElementById('pembelianCountBadge');
        if (!statusSelect) return;

        const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const statusFilter = statusSelect.value;
        const rows = document.querySelectorAll('tr.pembelian-row');

        let visibleCount = 0;
        rows.forEach(row => {
            const inv = (row.dataset.invoice || '').toLowerCase();
            const status = row.dataset.status || '';
            const matchSearch = searchTerm === '' || inv.includes(searchTerm);
            const matchStatus = statusFilter === 'all' || status === statusFilter;

            const isVisible = matchSearch && matchStatus;
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) {
                visibleCount++;
                const rowNum = row.querySelector('.row-number');
                if (rowNum) rowNum.textContent = visibleCount;
            }
        });

        if (badgeCount) {
            badgeCount.textContent = visibleCount + ' Transaksi';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchPembelianInput');
        const statusSelect = document.getElementById('filterStatusPembelian');
        if (searchInput) searchInput.addEventListener('input', filterPembelianTable);
        if (statusSelect) statusSelect.addEventListener('change', filterPembelianTable);
    });

    function confirmLunasi(url) {
        Swal.fire({
            title: 'Konfirmasi Pelunasan',
            text: 'Apakah Anda yakin ingin menandai transaksi pembelian ini sebagai Lunas?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Lunas',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#212529',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function confirmHapus(form) {
        Swal.fire({
            title: 'Hapus Pembelian?',
            text: 'Data pembelian dan seluruh rincian barang ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#212529',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection