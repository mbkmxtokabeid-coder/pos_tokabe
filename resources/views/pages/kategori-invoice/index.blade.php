@extends('layout.template')

@section('head')
<style>
    /* ==========================================================================
       KATEGORI INVOICE — MODERN DARK THEME DESIGN
       ========================================================================== */
    .kategori-header-badge {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #818CF8;
        background: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.25);
        padding: 3px 10px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-create-category {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        color: #FFFFFF !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        height: 40px !important;
        padding: 0 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35) !important;
        transition: all 0.2s cubic-bezier(0.2, 0, 0, 1) !important;
    }
    .btn-create-category:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.45) !important;
        background: linear-gradient(135deg, #4F46E5 0%, #4338CA 100%) !important;
        color: #FFFFFF !important;
    }

    /* KPI Summary Cards */
    .kpi-category-card {
        background: #141A23 !important;
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
        border-radius: 12px !important;
        padding: 16px 20px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
        transition: transform 0.2s ease, border-color 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .kpi-category-card:hover {
        transform: translateY(-2px);
        border-color: rgba(99, 102, 241, 0.35) !important;
    }
    .kpi-category-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
        font-size: 22px;
    }

    /* Filter Box */
    .filter-card-custom {
        background: #141A23 !important;
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2) !important;
    }
    .filter-card-custom .form-control,
    .filter-card-custom .form-select,
    .modal-dark-custom .form-control,
    .modal-dark-custom .form-select {
        background-color: #0F1520 !important;
        border: 1px solid #233044 !important;
        border-radius: 8px !important;
        color: #F8FAFC !important;
        font-size: 13px !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .filter-card-custom .form-control:focus,
    .filter-card-custom .form-select:focus,
    .modal-dark-custom .form-control:focus,
    .modal-dark-custom .form-select:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
        outline: none !important;
    }
    .filter-card-custom .form-control::placeholder {
        color: #64748B !important;
    }

    /* Table Container & Table */
    .table-card-custom {
        background: #141A23 !important;
        border: 1px solid rgba(255, 255, 255, 0.07) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        overflow: hidden;
    }
    .table-card-custom .card-header {
        background: #121822 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        padding: 16px 20px !important;
    }

    .table-category {
        margin-bottom: 0 !important;
        width: 100%;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }
    .table-category thead th {
        background: #101620 !important;
        color: #94A3B8 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        font-weight: 600 !important;
        font-size: 11.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        padding: 13px 16px !important;
        white-space: nowrap;
        vertical-align: middle !important;
    }
    .table-category tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-category tbody tr:hover {
        background-color: #172130 !important;
    }
    .table-category tbody td {
        border: none !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding: 14px 16px !important;
        vertical-align: middle !important;
        font-size: 13px !important;
        color: #CBD5E1 !important;
        background: transparent !important;
    }

    /* Custom Badges & Tags */
    .badge-prefix-code {
        font-family: var(--font-mono, "JetBrains Mono", Consolas, monospace);
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: #A5B4FC;
        background: rgba(99, 102, 241, 0.14);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: 6px;
        padding: 3px 8px;
        display: inline-block;
    }

    .format-code-pill {
        font-family: var(--font-mono, "JetBrains Mono", Consolas, monospace);
        font-size: 12px;
        font-weight: 600;
        color: #93C5FD;
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(148, 163, 184, 0.25);
        border-radius: 5px;
        padding: 2px 7px;
        display: inline-block;
        letter-spacing: 0.02em;
    }

    .badge-reset-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 6px;
        white-space: nowrap;
    }
    .badge-reset--yearly {
        color: #60A5FA;
        background: rgba(59, 130, 246, 0.12);
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    .badge-reset--monthly {
        color: #C084FC;
        background: rgba(168, 85, 247, 0.12);
        border: 1px solid rgba(168, 85, 247, 0.3);
    }
    .badge-reset--never {
        color: #94A3B8;
        background: rgba(148, 163, 184, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    .badge-used-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #CBD5E1;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        padding: 4px 10px;
        white-space: nowrap;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 9999px;
        white-space: nowrap;
    }
    .badge-status-pill .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-status-pill--active {
        color: #34D399;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.35);
    }
    .badge-status-pill--active .status-dot {
        background-color: #34D399;
        box-shadow: 0 0 6px rgba(52, 211, 153, 0.6);
    }
    .badge-status-pill--inactive {
        color: #94A3B8;
        background: rgba(148, 163, 184, 0.1);
        border: 1px solid rgba(148, 163, 184, 0.25);
    }
    .badge-status-pill--inactive .status-dot {
        background-color: #94A3B8;
    }

    /* Action Buttons in Table */
    .btn-action-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        color: #CBD5E1;
        cursor: pointer;
        transition: all 0.15s ease;
        padding: 0;
        font-size: 14px;
    }
    .btn-action-icon:hover {
        transform: translateY(-1px);
    }
    .btn-action-icon--toggle:hover {
        background: rgba(245, 158, 11, 0.15);
        border-color: rgba(245, 158, 11, 0.4);
        color: #FBBF24;
    }
    .btn-action-icon--toggle-on:hover {
        background: rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.4);
        color: #34D399;
    }
    .btn-action-icon--edit:hover {
        background: rgba(99, 102, 241, 0.18);
        border-color: rgba(99, 102, 241, 0.45);
        color: #A5B4FC;
    }
    .btn-action-icon--delete:hover {
        background: rgba(239, 68, 68, 0.18);
        border-color: rgba(239, 68, 68, 0.45);
        color: #F87171;
    }

    /* Modals & Tokens */
    .modal-dark-custom .modal-content {
        background-color: #141A23 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6) !important;
    }
    .modal-dark-custom .modal-header {
        background-color: #121822 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07) !important;
        padding: 16px 22px !important;
    }
    .modal-dark-custom .modal-footer {
        background-color: #121822 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.07) !important;
        padding: 14px 22px !important;
    }

    .chip-token {
        background-color: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: #A5B4FC;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 11.5px;
        font-family: var(--font-mono, "JetBrains Mono", Consolas, monospace);
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
    }
    .chip-token:hover {
        background-color: #6366F1;
        color: #FFFFFF;
        border-color: #6366F1;
        transform: translateY(-1px);
    }

    .chip-preset {
        background-color: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #CBD5E1;
        border-radius: 6px;
        padding: 3px 9px;
        font-size: 11.5px;
        font-family: var(--font-mono, "JetBrains Mono", Consolas, monospace);
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
    }
    .chip-preset:hover {
        background-color: rgba(99, 102, 241, 0.2);
        color: #A5B4FC;
        border-color: rgba(99, 102, 241, 0.4);
    }

    .preview-box {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(6, 78, 59, 0.12) 100%);
        border: 1px solid rgba(16, 185, 129, 0.35);
        border-radius: 10px;
        padding: 12px 18px;
    }

    /* Modal Ergonomic & Unslopped Craft */
    .modal-xl-custom {
        max-width: 1100px !important;
    }
    .modal-card-box {
        background: #0F1622;
        border: 1px solid #1E293B;
        border-radius: 8px;
        padding: 16px;
    }
    .modal-section-title {
        font-size: 13px;
        font-weight: 600;
        color: #F1F5F9;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .format-choice-card {
        display: flex;
        align-items: flex-start;
        padding: 10px 12px;
        background: #0A0E17;
        border: 1px solid #1E293B;
        border-radius: 6px;
        cursor: pointer;
        transition: border-color 0.15s ease, background-color 0.15s ease;
        height: 100%;
        margin-bottom: 0;
    }
    .format-choice-card:hover {
        background: #121A28;
        border-color: #334155;
    }
    .format-choice-card:has(input:checked) {
        background: #141E30;
        border-color: #4F46E5;
    }
    .modal-dark-custom .form-control-dark,
    .modal-dark-custom .form-select-dark {
        background-color: #0A0E17 !important;
        border: 1px solid #233044 !important;
        border-radius: 6px !important;
        color: #F8FAFC !important;
        font-size: 13px !important;
        padding: 8px 12px !important;
        transition: border-color 0.15s ease;
    }
    .modal-dark-custom .form-control-dark:focus,
    .modal-dark-custom .form-select-dark:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        outline: none;
    }
    .chip-token-btn {
        background: #162032;
        border: 1px solid #27374D;
        color: #93C5FD;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 11px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background-color 0.15s ease, color 0.15s ease;
        user-select: none;
    }
    .chip-token-btn:hover {
        background: #2563EB;
        color: #FFFFFF;
        border-color: #2563EB;
    }
    .chip-preset-btn {
        background: #111827;
        border: 1px solid #1F2937;
        color: #9CA3AF;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 11px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background-color 0.15s ease, color 0.15s ease;
        user-select: none;
    }
    .chip-preset-btn:hover {
        background: #1F2937;
        color: #E2E8F0;
        border-color: #374151;
    }
    .live-preview-card {
        background: #091219;
        border: 1px solid #164E63;
        border-left: 3px solid #10B981;
        border-radius: 6px;
        padding: 12px 14px;
    }
    .preview-number-display {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 17px;
        font-weight: 700;
        color: #34D399;
        letter-spacing: 0.04em;
        font-variant-numeric: tabular-nums;
    }
    /* Authentic Miniature Invoice Print Sheet */
    .invoice-mockup-wrapper {
        background: #090D14;
        border: 1px solid #1E293B;
        border-radius: 8px;
        padding: 12px;
    }
    .invoice-mockup-sheet {
        background: #FFFFFF;
        color: #0F172A;
        border-radius: 6px;
        padding: 14px 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
        font-size: 11px;
        line-height: 1.35;
        border: 1px solid #E2E8F0;
    }
    .invoice-mockup-sheet .mockup-title {
        font-size: 13px;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: 0.03em;
        line-height: 1.2;
    }
    .invoice-mockup-sheet .mockup-subtitle {
        font-size: 10px;
        color: #64748B;
        margin-top: 1px;
    }
    .invoice-mockup-sheet .mockup-pill-num {
        display: inline-block;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11px;
        font-weight: 700;
        color: #065F46;
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        border-radius: 4px;
        padding: 2px 7px;
    }
    .invoice-mockup-sheet .mockup-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
        font-size: 10px;
    }
    .invoice-mockup-sheet .mockup-table th {
        background: #F8FAFC;
        color: #475569;
        font-weight: 600;
        padding: 4px 6px;
        border-bottom: 1px solid #E2E8F0;
    }
    .invoice-mockup-sheet .mockup-table td {
        padding: 4px 6px;
        color: #334155;
        border-bottom: 1px solid #F1F5F9;
    }
    .invoice-mockup-sheet .mockup-footer-note {
        margin-top: 8px;
        padding-top: 6px;
        border-top: 1px dashed #CBD5E1;
        font-size: 9.5px;
        color: #64748B;
        font-style: italic;
    }

</style>
@endsection

@section('content')
@include('sweetalert::alert')
<div class="page-content">
    <div class="container-fluid">
        
        {{-- Page Header --}}
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="kategori-header-badge">
                        <i class="ri-settings-4-line"></i> Master Data
                    </span>
                </div>
                <h3 class="mb-1 text-white fw-bold fs-22">Kategori Invoice</h3>
                <p class="text-muted fs-13 mb-0">Kelola jenis kategori invoice, format penomoran otomatis, dan pengaturan tampilan dokumen cetak.</p>
            </div>
            <div>
                <button type="button" class="btn-create-category" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                    <i class="ri-add-circle-line fs-17"></i>Tambah Kategori Invoice
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; border-radius: 10px;">
                <strong class="d-flex align-items-center gap-2 mb-1"><i class="ri-error-warning-line fs-18"></i> Terdapat kesalahan input:</strong>
                <ul class="mb-0 ps-3 fs-13">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- KPI Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="kpi-category-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-semibold fs-11 text-muted d-block mb-1">Total Kategori</span>
                        <div class="fs-24 fw-bold text-white lh-1">{{ $totalKategori }}</div>
                        <span class="text-muted fs-12 mt-1 d-block">Kategori invoice terdaftar</span>
                    </div>
                    <div class="kpi-category-icon" style="background: rgba(99, 102, 241, 0.14); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25);">
                        <i class="ri-folders-line"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="kpi-category-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-semibold fs-11 text-muted d-block mb-1">Kategori Aktif</span>
                        <div class="fs-24 fw-bold lh-1" style="color: #34D399;">{{ $totalAktif }}</div>
                        <span class="text-muted fs-12 mt-1 d-block">Siap digunakan pada invoice</span>
                    </div>
                    <div class="kpi-category-icon" style="background: rgba(16, 185, 129, 0.14); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25);">
                        <i class="ri-checkbox-circle-line"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="kpi-category-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-uppercase fw-semibold fs-11 text-muted d-block mb-1">Kategori Non-Aktif</span>
                        <div class="fs-24 fw-bold lh-1" style="color: #94A3B8;">{{ $totalNonAktif }}</div>
                        <span class="text-muted fs-12 mt-1 d-block">Dinonaktifkan sementara</span>
                    </div>
                    <div class="kpi-category-icon" style="background: rgba(148, 163, 184, 0.12); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.25);">
                        <i class="ri-pause-circle-line"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="card filter-card-custom mb-4">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('kategori-invoice.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fs-12 text-muted mb-1">Cari Kategori</label>
                        <div class="position-relative">
                            <input type="text" name="search" class="form-control" style="height: 38px; padding-left: 36px;" placeholder="Cari nama kategori, kode prefix, atau pola nomor..." value="{{ $search }}">
                            <i class="ri-search-line position-absolute text-muted" style="left: 12px; top: 10px; font-size: 15px;"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-12 text-muted mb-1">Status Kategori</label>
                        <select name="status" class="form-select" style="height: 38px;">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn w-100 fw-semibold text-white d-inline-flex align-items-center justify-content-center gap-1" style="height: 38px; background: #6366F1; border-color: #6366F1; border-radius: 8px;">
                            <i class="ri-filter-3-line"></i> Filter
                        </button>
                        <a href="{{ route('kategori-invoice.index') }}" class="btn d-inline-flex align-items-center justify-content-center" style="height: 38px; width: 38px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); color: #CBD5E1; border-radius: 8px;" title="Reset Filter">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Kategori Invoice --}}
        <div class="card table-card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-folder-settings-line text-primary fs-18"></i>
                    <h5 class="card-title mb-0 fs-15 fw-semibold text-white">Daftar Kategori Invoice</h5>
                </div>
                <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: #A5B4FC; border: 1px solid rgba(99, 102, 241, 0.25); font-size: 12px; font-weight: 600; padding: 5px 12px; border-radius: 9999px;">
                    {{ $kategoris->count() }} Kategori
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table-category">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="min-width: 200px;">Nama Kategori</th>
                                <th class="text-center" style="width: 110px;">Kode Prefix</th>
                                <th style="min-width: 240px;">Pola & Format Nomor</th>
                                <th class="text-center" style="width: 130px;">Reset Nomor</th>
                                <th style="min-width: 220px;">Tampilan Dokumen</th>
                                <th class="text-center" style="width: 120px;">Digunakan</th>
                                <th class="text-center" style="width: 105px;">Status</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $kat)
                                @php
                                    $tpl = $kat->template_settings ?? [];
                                    $previewNo = $kat->generateNomorInvoice();
                                @endphp
                                <tr>
                                    <td class="text-center text-muted fw-semibold" style="font-variant-numeric: tabular-nums;">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-white fs-13">{{ $kat->nama_kategori }}</div>
                                        @if($kat->keterangan)
                                            <div class="fs-12 text-muted mt-0.5">{{ $kat->keterangan }}</div>
                                        @else
                                            <div class="fs-12 text-muted mt-0.5" style="opacity: 0.6;">Tanpa keterangan tambahan</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-prefix-code">
                                            {{ $kat->prefix ?: 'INV' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($kat->use_custom_format)
                                            <div>
                                                <span class="format-code-pill">{{ $kat->format_nomor }}</span>
                                            </div>
                                        @else
                                            <div class="fs-12 text-muted">Format Standar Sistem</div>
                                        @endif
                                        <div class="d-flex align-items-center gap-1 mt-1 fs-11">
                                            <span class="text-muted">Contoh:</span>
                                            <span style="color: #34D399; font-family: monospace; font-weight: 600;">{{ $previewNo }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($kat->reset_periode === 'yearly')
                                            <span class="badge-reset-pill badge-reset--yearly">
                                                <i class="ri-calendar-line"></i> Reset Tahunan
                                            </span>
                                        @elseif($kat->reset_periode === 'monthly')
                                            <span class="badge-reset-pill badge-reset--monthly">
                                                <i class="ri-calendar-2-line"></i> Reset Bulanan
                                            </span>
                                        @else
                                            <span class="badge-reset-pill badge-reset--never">
                                                <i class="ri-forbid-line"></i> Tidak Reset
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fs-12">
                                            <span class="text-muted">Judul:</span>
                                            <strong class="text-white ms-1">{{ $tpl['judul_invoice'] ?? 'INVOICE' }}</strong>
                                        </div>
                                        <div class="fs-11 text-muted mt-0.5">
                                            <span>Penerima: <span style="color: #E2E8F0;">{{ $tpl['label_penerima'] ?? 'Kepada Yth.' }}</span></span>
                                            <span class="mx-1">&bull;</span>
                                            <span>NPWP: <span style="color: #E2E8F0;">{{ !empty($tpl['tampilkan_npwp']) ? 'Ya' : 'Tidak' }}</span></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-used-count">
                                            <i class="ri-file-text-line text-primary"></i>
                                            <strong class="text-white">{{ $kat->invoices_count }}</strong>
                                            <span class="text-muted">Invoice</span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($kat->is_active)
                                            <span class="badge-status-pill badge-status-pill--active">
                                                <span class="status-dot"></span> Aktif
                                            </span>
                                        @else
                                            <span class="badge-status-pill badge-status-pill--inactive">
                                                <span class="status-dot"></span> Non-Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center gap-1.5">
                                            {{-- Toggle Status --}}
                                            <form action="{{ route('kategori-invoice.toggle-status', $kat->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                <button type="submit" class="btn-action-icon {{ $kat->is_active ? 'btn-action-icon--toggle' : 'btn-action-icon--toggle-on' }}"
                                                        title="{{ $kat->is_active ? 'Nonaktifkan Kategori' : 'Aktifkan Kategori' }}">
                                                    <i class="{{ $kat->is_active ? 'ri-pause-circle-line' : 'ri-play-circle-line' }}"></i>
                                                </button>
                                            </form>

                                            {{-- Edit --}}
                                            <button type="button" class="btn-action-icon btn-action-icon--edit"
                                                    onclick="openModalEditKategori({{ json_encode([
                                                        'id' => $kat->id,
                                                        'nama_kategori' => $kat->nama_kategori,
                                                        'keterangan' => $kat->keterangan,
                                                        'use_custom_format' => $kat->use_custom_format,
                                                        'prefix' => $kat->prefix,
                                                        'format_nomor' => $kat->format_nomor,
                                                        'padding_digit' => $kat->padding_digit,
                                                        'reset_periode' => $kat->reset_periode,
                                                        'is_active' => $kat->is_active,
                                                        'judul_invoice' => $tpl['judul_invoice'] ?? 'INVOICE',
                                                        'subjudul_invoice' => $tpl['subjudul_invoice'] ?? '',
                                                        'label_penerima' => $tpl['label_penerima'] ?? 'Kepada Yth.',
                                                        'tampilkan_npwp' => $tpl['tampilkan_npwp'] ?? true,
                                                        'tampilkan_termin' => $tpl['tampilkan_termin'] ?? true,
                                                        'catatan_kaki' => $tpl['catatan_kaki'] ?? '',
                                                    ]) }})"
                                                    title="Edit Kategori">
                                                <i class="ri-edit-line"></i>
                                            </button>

                                            {{-- Delete --}}
                                            <form action="{{ route('kategori-invoice.destroy', $kat->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori invoice ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-icon btn-action-icon--delete" title="Hapus Kategori">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px; background: rgba(99, 102, 241, 0.1); color: #818CF8;">
                                                <i class="ri-folder-open-line fs-24"></i>
                                            </div>
                                            <h6 class="text-white fw-semibold mb-1">Belum Ada Kategori Invoice</h6>
                                            <p class="text-muted fs-12 mb-3">Tambahkan kategori invoice pertama untuk mengatur pola penomoran khusus.</p>
                                            <button type="button" class="btn-create-category" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                                                <i class="ri-add-line fs-16"></i> Tambah Kategori
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI INVOICE --}}
<div class="modal fade modal-dark-custom" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 680px;">
        <div class="modal-content">
            <form action="{{ route('kategori-invoice.store') }}" method="POST">
                @csrf
                <div class="modal-header d-flex align-items-center justify-content-between py-3 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #1E293B; border: 1px solid #334155; color: #94A3B8;">
                            <i class="ri-add-line fs-18"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-white fw-semibold mb-0 fs-15">Tambah Kategori Invoice</h5>
                            <span class="text-muted fs-11">Atur nama kategori dan format pola penomoran invoice</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                
                <div class="modal-body p-4" style="max-height: calc(85vh - 130px); overflow-y: auto;">
                    <input type="hidden" name="use_custom_format" value="1">

                    {{-- Card 1: Identitas Kategori --}}
                    <div class="modal-card-box mb-3">
                        <h6 class="modal-section-title mb-2.5">
                            <i class="ri-folder-2-line text-muted"></i> Informasi Kategori
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_kategori" class="form-control form-control-dark" placeholder="Contoh: Invoice Jasa Iklan" required>
                                <span class="text-muted fs-11 d-block mt-0.5">Label pada menu pembuatan invoice</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Keterangan
                                </label>
                                <input type="text" name="keterangan" class="form-control form-control-dark" placeholder="Deskripsi singkat penggunaan">
                                <span class="text-muted fs-11 d-block mt-0.5">Catatan opsional untuk staf</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Pengaturan Format Nomor --}}
                    <div class="modal-card-box mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                            <h6 class="modal-section-title">
                                <i class="ri-hashtag text-muted"></i> Pengaturan Penomoran
                            </h6>
                            <span class="badge bg-dark text-muted border border-secondary-subtle fs-11 fw-normal">Dinamis</span>
                        </div>

                        {{-- Parameter: Prefix, Digit, Reset --}}
                        <div class="row g-2 mb-2.5">
                            <div class="col-4">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Prefix (<code class="text-primary">{PREFIX}</code>)
                                </label>
                                <input type="text" name="prefix" id="add_prefix" class="form-control form-control-dark text-center fw-bold" value="INV" placeholder="INV" oninput="updateLivePreview('add')">
                            </div>
                            <div class="col-4">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Digit Urut (<code class="text-primary">{NUMBER}</code>)
                                </label>
                                <select name="padding_digit" id="add_padding" class="form-select form-select-dark" onchange="updateLivePreview('add')">
                                    <option value="3" selected>3 Digit (001)</option>
                                    <option value="4">4 Digit (0001)</option>
                                    <option value="2">2 Digit (01)</option>
                                    <option value="5">5 Digit (00001)</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label fs-12 fw-medium text-white mb-1">Periode Reset</label>
                                <select name="reset_periode" id="add_reset_periode" class="form-select form-select-dark" onchange="updateLivePreview('add')">
                                    <option value="yearly" selected>Reset Tahunan</option>
                                    <option value="monthly">Reset Bulanan</option>
                                    <option value="never">Tidak Pernah</option>
                                </select>
                            </div>
                        </div>

                        {{-- Pola Input & Token Chips --}}
                        <div>
                            <label class="form-label fs-12 fw-medium text-white mb-1 d-flex align-items-center justify-content-between">
                                <span>Susunan Pola Nomor</span>
                                <small class="text-muted">Klik variabel di bawah untuk menyisipkan</small>
                            </label>
                            <input type="text" name="format_nomor" id="add_format_nomor" class="form-control form-control-dark fw-bold fs-13 text-white" value="{PREFIX}/{YEAR}/{NUMBER}" oninput="updateLivePreview('add')" style="letter-spacing: 0.04em; font-family: ui-monospace, monospace;">

                            {{-- Variabel Dinamis Chips --}}
                            <div class="mt-2 pt-1">
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="fs-11 text-muted me-1"><i class="ri-code-line me-0.5"></i>Variabel:</span>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('add', '{PREFIX}')">{PREFIX}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('add', '{NUMBER}')">{NUMBER}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('add', '{MONTH}')">{MONTH}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('add', '{ROMAN_MONTH}')">{ROMAN_MONTH}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('add', '{YEAR}')">{YEAR}</button>
                                </div>
                            </div>

                            {{-- Preset Populer Chips --}}
                            <div class="mt-2">
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="fs-11 text-muted me-1"><i class="ri-bookmark-line me-0.5"></i>Preset:</span>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('add', 'INV', '{PREFIX}/{YEAR}/{NUMBER}')">INV/2026/001</button>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('add', 'IKL', '{PREFIX}/{NUMBER}/{YEAR}')">IKL/001/2026</button>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('add', 'UMKM', '{NUMBER}/INV/{PREFIX}/{YEAR}')">001/INV/UMKM/2026</button>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('add', 'TKB', '{NUMBER}/{PREFIX}/{ROMAN_MONTH}/{YEAR}')">001/TKB/XI/2026</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Live Preview Box --}}
                    <div class="live-preview-card">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fs-11 text-muted d-flex align-items-center gap-1.5 fw-medium">
                                <i class="ri-eye-line text-success"></i> Pratinjau Nomor Pertama:
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle fs-10" id="add_preview_status_badge">Aktif</span>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between mt-1">
                            <div class="preview-number-display" id="add_preview_result">INV/2026/001</div>
                            <small class="text-muted fs-11" id="add_preview_hint">Nomor akan bertambah urut</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex align-items-center justify-content-between py-2.5 px-4">
                    <span class="text-muted fs-11">Pola nomor otomatis terpasang pada invoice baru</span>
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <button type="button" class="btn btn-secondary px-3 fs-13" style="background: #1E293B; border-color: #334155; color: #CBD5E1;" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3.5 fs-13 fw-medium">
                            <i class="ri-check-line fs-16"></i> Simpan Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KATEGORI INVOICE --}}
<div class="modal fade modal-dark-custom" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 680px;">
        <div class="modal-content">
            <form id="formEditKategori" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header d-flex align-items-center justify-content-between py-3 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: #1E293B; border: 1px solid #334155; color: #94A3B8;">
                            <i class="ri-edit-line fs-18"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-white fw-semibold mb-0 fs-15">Edit Kategori Invoice</h5>
                            <span class="text-muted fs-11">Perbarui nama atau format pola penomoran invoice</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                
                <div class="modal-body p-4" style="max-height: calc(85vh - 130px); overflow-y: auto;">
                    <input type="hidden" name="use_custom_format" value="1">

                    {{-- Card 1: Identitas Kategori --}}
                    <div class="modal-card-box mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                            <h6 class="modal-section-title">
                                <i class="ri-folder-2-line text-muted"></i> Informasi Kategori
                            </h6>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                                <label class="form-check-label text-white fs-12 cursor-pointer ms-1" for="edit_is_active">
                                    Kategori Aktif
                                </label>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control form-control-dark" required>
                                <span class="text-muted fs-11 d-block mt-0.5">Label pada menu pembuatan invoice</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Keterangan
                                </label>
                                <input type="text" name="keterangan" id="edit_keterangan" class="form-control form-control-dark">
                                <span class="text-muted fs-11 d-block mt-0.5">Catatan opsional untuk staf</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Pengaturan Format Nomor --}}
                    <div class="modal-card-box mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2.5">
                            <h6 class="modal-section-title">
                                <i class="ri-hashtag text-muted"></i> Pengaturan Penomoran
                            </h6>
                            <span class="badge bg-dark text-muted border border-secondary-subtle fs-11 fw-normal">Dinamis</span>
                        </div>

                        {{-- Parameter: Prefix, Digit, Reset --}}
                        <div class="row g-2 mb-2.5">
                            <div class="col-4">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Prefix (<code class="text-primary">{PREFIX}</code>)
                                </label>
                                <input type="text" name="prefix" id="edit_prefix" class="form-control form-control-dark text-center fw-bold" oninput="updateLivePreview('edit')">
                            </div>
                            <div class="col-4">
                                <label class="form-label fs-12 fw-medium text-white mb-1">
                                    Digit Urut (<code class="text-primary">{NUMBER}</code>)
                                </label>
                                <select name="padding_digit" id="edit_padding" class="form-select form-select-dark" onchange="updateLivePreview('edit')">
                                    <option value="3">3 Digit (001)</option>
                                    <option value="4">4 Digit (0001)</option>
                                    <option value="2">2 Digit (01)</option>
                                    <option value="5">5 Digit (00001)</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label fs-12 fw-medium text-white mb-1">Periode Reset</label>
                                <select name="reset_periode" id="edit_reset_periode" class="form-select form-select-dark" onchange="updateLivePreview('edit')">
                                    <option value="yearly">Reset Tahunan</option>
                                    <option value="monthly">Reset Bulanan</option>
                                    <option value="never">Tidak Pernah</option>
                                </select>
                            </div>
                        </div>

                        {{-- Pola Input & Token Chips --}}
                        <div>
                            <label class="form-label fs-12 fw-medium text-white mb-1 d-flex align-items-center justify-content-between">
                                <span>Susunan Pola Nomor</span>
                                <small class="text-muted">Klik variabel di bawah untuk menyisipkan</small>
                            </label>
                            <input type="text" name="format_nomor" id="edit_format_nomor" class="form-control form-control-dark fw-bold fs-13 text-white" oninput="updateLivePreview('edit')" style="letter-spacing: 0.04em; font-family: ui-monospace, monospace;">

                            {{-- Variabel Dinamis Chips --}}
                            <div class="mt-2 pt-1">
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="fs-11 text-muted me-1"><i class="ri-code-line me-0.5"></i>Variabel:</span>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('edit', '{PREFIX}')">{PREFIX}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('edit', '{NUMBER}')">{NUMBER}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('edit', '{MONTH}')">{MONTH}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('edit', '{ROMAN_MONTH}')">{ROMAN_MONTH}</button>
                                    <button type="button" class="chip-token-btn" onclick="insertToken('edit', '{YEAR}')">{YEAR}</button>
                                </div>
                            </div>

                            {{-- Preset Populer Chips --}}
                            <div class="mt-2">
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="fs-11 text-muted me-1"><i class="ri-bookmark-line me-0.5"></i>Preset:</span>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('edit', 'INV', '{PREFIX}/{YEAR}/{NUMBER}')">INV/2026/001</button>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('edit', 'IKL', '{PREFIX}/{NUMBER}/{YEAR}')">IKL/001/2026</button>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('edit', 'UMKM', '{NUMBER}/INV/{PREFIX}/{YEAR}')">001/INV/UMKM/2026</button>
                                    <button type="button" class="chip-preset-btn" onclick="applyPreset('edit', 'TKB', '{NUMBER}/{PREFIX}/{ROMAN_MONTH}/{YEAR}')">001/TKB/XI/2026</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Live Preview Box --}}
                    <div class="live-preview-card">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="fs-11 text-muted d-flex align-items-center gap-1.5 fw-medium">
                                <i class="ri-eye-line text-success"></i> Pratinjau Nomor Berikutnya:
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle fs-10">Aktif</span>
                        </div>
                        <div class="d-flex align-items-baseline justify-content-between mt-1">
                            <div class="preview-number-display" id="edit_preview_result">-</div>
                            <small class="text-muted fs-11">Contoh nomor berikutnya</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex align-items-center justify-content-between py-2.5 px-4">
                    <span class="text-muted fs-11">Format dapat diedit sewaktu-waktu</span>
                    <div class="d-flex align-items-center gap-2 ms-auto">
                        <button type="button" class="btn btn-secondary px-3 fs-13" style="background: #1E293B; border-color: #334155; color: #CBD5E1;" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3.5 fs-13 fw-medium">
                            <i class="ri-check-line fs-16"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('plugins')
<script>
    const currentYearStr = '{{ date("Y") }}';
    const currentMonthStr = '{{ date("m") }}';
    const romanMonths = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
    const currentRomanStr = romanMonths[new Date().getMonth()] || 'I';

    function insertToken(mode, token) {
        const input = document.getElementById(mode + '_format_nomor');
        if (!input) return;
        const start = input.selectionStart || input.value.length;
        const end = input.selectionEnd || input.value.length;
        const text = input.value;
        input.value = text.substring(0, start) + token + text.substring(end);
        input.focus();
        updateLivePreview(mode);
    }

    function applyPreset(mode, prefix, pattern) {
        const prefEl = document.getElementById(mode + '_prefix');
        if (prefEl) prefEl.value = prefix;
        const fmtEl = document.getElementById(mode + '_format_nomor');
        if (fmtEl) fmtEl.value = pattern;
        updateLivePreview(mode);
    }

    function updateLivePreview(mode) {
        const prefix = (document.getElementById(mode + '_prefix')?.value || 'INV').trim().toUpperCase();
        const pad = parseInt(document.getElementById(mode + '_padding')?.value || '3', 10);
        const numStr = '1'.padStart(pad, '0');
        const pattern = document.getElementById(mode + '_format_nomor')?.value || '{PREFIX}/{YEAR}/{NUMBER}';

        const result = pattern
            .replace(/{PREFIX}/g, prefix)
            .replace(/{NUMBER}/g, numStr)
            .replace(/{MONTH}/g, currentMonthStr)
            .replace(/{ROMAN_MONTH}/g, currentRomanStr)
            .replace(/{YEAR}/g, currentYearStr);

        const el = document.getElementById(mode + '_preview_result');
        if (el) el.textContent = result;
    }

    function openModalEditKategori(data) {
        const form = document.getElementById('formEditKategori');
        form.action = '{{ url("/kategori-invoice") }}/' + data.id;
        document.getElementById('edit_nama_kategori').value = data.nama_kategori || '';
        document.getElementById('edit_keterangan').value = data.keterangan || '';
        document.getElementById('edit_prefix').value = data.prefix || 'INV';
        document.getElementById('edit_padding').value = data.padding_digit || 3;
        document.getElementById('edit_reset_periode').value = data.reset_periode || 'yearly';
        document.getElementById('edit_format_nomor').value = data.format_nomor || '{PREFIX}/{YEAR}/{NUMBER}';
        document.getElementById('edit_is_active').checked = !!data.is_active;

        updateLivePreview('edit');
        const modal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateLivePreview('add');
    });
</script>
@endsection