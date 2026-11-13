@extends('layout.template')

@section('head')
<style>
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
    .num-tabular {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
    }
    .badge-spt-lunas {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-spt-kompensasi {
        background-color: rgba(99, 102, 241, 0.14) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.35) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-spt-menunggu {
        background-color: rgba(56, 189, 248, 0.12) !important;
        color: #38BDF8 !important;
        border: 1px solid rgba(56, 189, 248, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-spt-belum {
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #FCD34D !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-spt-terlambat {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #F87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .btn-primary-brand {
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
        gap: 6px !important;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
        transition: all 120ms ease !important;
    }
    .btn-primary-brand:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        color: #FFFFFF !important;
    }
    /* SPT Action Hamburger & Floating Dropdown Menu */
    .btn-spt-action-menu {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #161B22;
        border: 1px solid #282E37;
        border-radius: 8px;
        color: #94A3B8;
        cursor: pointer;
        padding: 0;
        transition: all 140ms ease;
    }
    .btn-spt-action-menu:hover,
    .btn-spt-action-menu.active,
    .btn-spt-action-menu[aria-expanded="true"] {
        background-color: #21262D;
        border-color: #384252;
        color: #F8FAFC;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
    }
    .spt-action-dropdown {
        position: relative;
        display: inline-block;
    }
    .spt-dropdown-menu {
        display: none;
        position: fixed;
        z-index: 1060;
        min-width: 205px;
        background-color: #161B22;
        border: 1px solid #282E37;
        border-radius: 10px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.65), 0 2px 6px rgba(0, 0, 0, 0.4);
        padding: 6px;
        text-align: left;
        backdrop-filter: blur(8px);
    }
    .spt-dropdown-menu.show {
        display: block;
        animation: sptDropdownFadeIn 120ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes sptDropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-4px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .spt-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 7px 10px;
        font-size: 12.5px;
        font-weight: 500;
        color: #CBD5E1;
        background: transparent;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
        transition: all 120ms ease;
        white-space: nowrap;
    }
    .spt-dropdown-item:hover {
        background-color: #21262D;
        color: #FFFFFF;
    }
    .spt-dropdown-item.text-danger {
        color: #F87171 !important;
    }
    .spt-dropdown-item.text-danger:hover {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #FCA5A5 !important;
    }
    .spt-dropdown-divider {
        height: 1px;
        background-color: #232A35;
        margin: 5px 0;
    }
    .spt-item-icon-box {
        width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        flex-shrink: 0;
        font-size: 14px;
    }
    /* Induk SPT Detail Input Table (Matching Lihat Detail) */
    .spt-induk-input-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #232A35;
        border-radius: 8px;
        overflow: hidden;
        background-color: #111622;
    }
    .spt-induk-input-table thead th {
        background-color: #181E26 !important;
        color: #94A3B8 !important;
        font-size: 10.5px !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 8px 10px !important;
        border-bottom: 1px solid #282E37 !important;
        white-space: nowrap;
    }
    .spt-induk-input-table tbody td {
        padding: 6px 8px !important;
        vertical-align: middle !important;
        font-size: 12px !important;
        color: #CBD5E1 !important;
        border-bottom: 1px solid #1E2532 !important;
    }
    .spt-induk-input-table tbody tr.spt-total-row td {
        background-color: #181E26 !important;
        border-bottom: none !important;
        padding: 8px 10px !important;
    }
    .spt-cell-input {
        background-color: #0B0F19 !important;
        border: 1px solid #293246 !important;
        color: #F8FAFC !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        padding: 5px 8px !important;
        text-align: right;
        font-variant-numeric: tabular-nums;
        width: 100%;
        min-width: 105px;
        transition: border-color 140ms ease, box-shadow 140ms ease;
    }
    .spt-cell-input:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
        outline: none !important;
    }
    .modal-dark-custom .modal-dialog-scrollable {
        max-height: calc(100vh - 2rem) !important;
    }
    .modal-dark-custom .modal-content {
        background-color: #0D111D !important;
        border: 1px solid #1E263B !important;
        border-radius: 14px !important;
        color: #F8F9FA !important;
        box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.75) !important;
        max-height: calc(100vh - 2rem) !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .modal-dark-custom .modal-content > form {
        display: flex !important;
        flex-direction: column !important;
        flex: 1 1 auto !important;
        min-height: 0 !important;
        max-height: calc(100vh - 2rem) !important;
        overflow: hidden !important;
        margin: 0 !important;
    }
    .modal-dark-custom .modal-header {
        background-color: #0D111D !important;
        border-bottom: 1px solid #1B2234 !important;
        padding: 16px 26px !important;
        flex-shrink: 0 !important;
    }
    .modal-dark-custom .modal-body {
        background-color: #0D111D !important;
        padding: 20px 26px !important;
        flex: 1 1 auto !important;
        min-height: 0 !important;
        max-height: calc(100vh - 175px) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    }
    .modal-dark-custom .modal-body::-webkit-scrollbar {
        width: 7px;
    }
    .modal-dark-custom .modal-body::-webkit-scrollbar-track {
        background: #0D111D;
    }
    .modal-dark-custom .modal-body::-webkit-scrollbar-thumb {
        background: #28334E;
        border-radius: 999px;
    }
    .modal-dark-custom .modal-body::-webkit-scrollbar-thumb:hover {
        background: #3B4B72;
    }
    .modal-dark-custom .modal-footer {
        background-color: #0D111D !important;
        border-top: 1px solid #1B2234 !important;
        padding: 14px 26px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-shrink: 0 !important;
    }
    .spt-modal-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background-color: #171E33;
        border: 1px solid #263152;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #818CF8;
        font-size: 20px;
        flex-shrink: 0;
    }
    .spt-modal-title {
        font-size: 17px;
        font-weight: 700;
        color: #F8FAFC;
        margin: 0 0 2px 0;
        line-height: 1.25;
    }
    .spt-modal-subtitle {
        font-size: 12.5px;
        color: #64748B;
        margin: 0;
    }
    .spt-btn-close {
        background: transparent;
        border: none;
        color: #94A3B8;
        font-size: 20px;
        line-height: 1;
        padding: 4px;
        border-radius: 6px;
        cursor: pointer;
        transition: color 120ms ease, background-color 120ms ease;
    }
    .spt-btn-close:hover {
        color: #F8FAFC;
        background-color: rgba(255, 255, 255, 0.06);
    }
    .spt-section-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        margin-top: 18px;
    }
    .spt-section-divider:first-child {
        margin-top: 0;
    }
    .spt-section-divider__text {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #818CF8;
        white-space: nowrap;
    }
    .spt-section-divider__line {
        flex: 1;
        height: 1px;
        background-color: #1B2234;
    }
    .spt-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #CBD5E1;
        margin-bottom: 5px;
    }
    .spt-label .req {
        color: #F87171;
        margin-left: 2px;
    }
    .modal-dark-custom .form-control,
    .modal-dark-custom .form-select {
        background-color: #131826 !important;
        border: 1px solid #242D42 !important;
        border-radius: 8px !important;
        color: #F1F5F9 !important;
        font-size: 13px !important;
        min-height: 40px;
        padding: 7px 12px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease, background-color 150ms ease;
    }
    .modal-dark-custom textarea.form-control {
        min-height: 72px;
        padding: 10px 12px !important;
        resize: vertical;
    }
    .modal-dark-custom .form-control::placeholder {
        color: #475569 !important;
    }
    .modal-dark-custom .form-control:focus,
    .modal-dark-custom .form-select:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.22) !important;
        background-color: #161C2E !important;
        color: #FFFFFF !important;
    }
    .filter-bar .form-control,
    .filter-bar .form-select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        font-size: 13.5px !important;
    }
    .spt-input-group {
        display: flex;
        align-items: stretch;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #242D42;
        background-color: #131826;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .spt-input-group:focus-within {
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.22);
    }
    .spt-input-group .spt-addon {
        background-color: #1A2133;
        color: #94A3B8;
        font-size: 12.5px;
        font-weight: 600;
        padding: 0 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
    }
    .spt-input-group .form-control {
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background-color: transparent !important;
    }
    .spt-input-group--highlight {
        border-color: #3D487A;
        background-color: #151B2E;
    }
    .spt-input-group--highlight .spt-addon {
        background-color: #242B4D;
        color: #C7D2FE;
    }
    .spt-subcard {
        background-color: #111624;
        border: 1px solid #1E273D;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 12px;
    }
    .spt-subcard__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
    }
    .spt-subcard__title {
        font-size: 12.5px;
        font-weight: 700;
        color: #E2E8F0;
        margin: 0;
    }
    .spt-subcard__code {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 5px;
        background-color: rgba(99, 102, 241, 0.14);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }
    .spt-summary-strip {
        background-color: #151C2F;
        border: 1px solid #263252;
        border-radius: 9px;
        padding: 10px 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .spt-iii-box {
        background: linear-gradient(180deg, #131A2E 0%, #0F1525 100%);
        border: 1px solid #293659;
        border-radius: 12px;
        padding: 16px 18px;
    }
    .spt-iii-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 7px 0;
        border-bottom: 1px dashed #1E2942;
        font-size: 13px;
    }
    .spt-iii-row:last-child {
        border-bottom: none;
    }
    .spt-segmented {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px;
        background-color: #111623;
        border: 1px solid #242D42;
        border-radius: 10px;
        padding: 4px;
        min-height: 44px;
    }
    .spt-segmented__btn {
        background: transparent;
        border: none;
        border-radius: 7px;
        color: #94A3B8;
        font-size: 12.5px;
        font-weight: 500;
        padding: 6px 10px;
        cursor: pointer;
        transition: all 140ms ease;
    }
    .spt-segmented__btn.is-active {
        background-color: #273252;
        color: #FFFFFF;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    .spt-dropzone {
        border: 1px dashed #2E3852;
        border-radius: 10px;
        background-color: #111623;
        padding: 10px 16px;
        min-height: 58px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        transition: border-color 150ms ease, background-color 150ms ease;
        margin: 0;
    }
    .spt-dropzone:hover,
    .spt-dropzone.is-dragover {
        border-color: #6366F1;
        background-color: rgba(99, 102, 241, 0.06);
    }
    .spt-dropzone__icon {
        font-size: 20px;
        color: #94A3B8;
        flex-shrink: 0;
    }
    .spt-dropzone__title {
        font-size: 13px;
        font-weight: 600;
        color: #E2E8F0;
        line-height: 1.3;
    }
    .spt-dropzone__sub {
        font-size: 11.5px;
        color: #64748B;
        margin-top: 2px;
    }
    .btn-spt-cancel {
        background-color: #131826;
        border: 1px solid #2A334B;
        color: #E2E8F0;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        height: 40px;
        padding: 0 20px;
        transition: all 120ms ease;
    }
    .btn-spt-cancel:hover {
        background-color: #1C2336;
        border-color: #3B4766;
        color: #FFFFFF;
    }
    .btn-spt-submit {
        background-color: #5850EC;
        border: 1px solid transparent;
        color: #FFFFFF;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        height: 40px;
        padding: 0 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(88, 80, 236, 0.35);
        transition: all 120ms ease;
    }
    .btn-spt-submit:hover {
        background-color: #4F46E5;
        color: #FFFFFF;
        transform: translateY(-1px);
    }
    .btn-autofill-spt {
        background-color: rgba(56, 189, 248, 0.12);
        border: 1px solid rgba(56, 189, 248, 0.35);
        color: #38BDF8;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 120ms ease;
    }
    .btn-autofill-spt:hover {
        background-color: rgba(56, 189, 248, 0.2);
        color: #7DD3FC;
    }
</style>
@endsection

@section('content')
@include('sweetalert::alert')
<div class="page-content">
    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-sm-0 text-white fw-bold">Manajemen SPT Masa (PPN Induk &amp; PPh)</h4>
                <p class="text-muted fs-13 mb-0 mt-1">Kelola pelaporan SPT Masa PPN (Induk Coretax DJP: Penyerahan, Perolehan, Kurang/Lebih Bayar) serta kewajiban PPh bulanan</p>
            </div>
            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <button type="button" class="btn btn-outline-light btn-sm px-3" style="height: 38px; border-radius: 8px; border-color: #383E45;" data-bs-toggle="collapse" data-bs-target="#collapseRekapTahunan" aria-expanded="false">
                    <i class="ri-bar-chart-grouped-line me-1"></i>Rekapitulasi {{ $rekapTahun }}
                </button>
                <button type="button" class="btn-primary-brand" data-bs-toggle="modal" data-bs-target="#modalTambahSpt">
                    <i class="ri-add-line fs-16"></i>Input SPT Masa
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; border-radius: 10px;">
                <strong class="d-flex align-items-center gap-1.5"><i class="ri-error-warning-line fs-18"></i> Terdapat kesalahan input:</strong>
                <ul class="mb-0 mt-1 ps-3 fs-13">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- 4 KPI Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold text-white fs-20 num-tabular">Rp {{ number_format($totalKewajibanDicatat, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted">Total Pajak Kurang Bayar</p>
                                <small class="text-muted fs-11">Kewajiban setor pajak tercatat</small>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(245, 158, 11, 0.12); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.25);">
                                <i class="ri-file-list-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: #34D399;">Rp {{ number_format($totalSudahDibayar, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted">Pajak Sudah Disetor (Lunas)</p>
                                <small class="fs-11" style="color: #34D399;">Tercatat sebagai Beban Kas Keluar</small>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(16, 185, 129, 0.12); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <i class="ri-checkbox-circle-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: #818CF8;">Rp {{ number_format($totalLebihBayar ?? 0, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted">Total PPN Lebih Bayar</p>
                                <small class="fs-11" style="color: #A5B4FC;">Dikompensasikan / Restitusi (III.E)</small>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(99, 102, 241, 0.14); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.3);">
                                <i class="ri-exchange-dollar-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: {{ $totalBelumDibayar > 0 ? '#FB7185' : '#94A3B8' }};">Rp {{ number_format($totalBelumDibayar, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted">Sisa Belum Disetor</p>
                                <small class="text-muted fs-11">{{ $jumlahTerlambat }} SPT melewati jatuh tempo</small>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(244, 63, 94, 0.12); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.25);">
                                <i class="ri-alarm-warning-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rekapitulasi Bulanan & Tahunan SPT Masa --}}
        <div class="collapse mb-4" id="collapseRekapTahunan">
            <div class="card table-container-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fs-15 fw-semibold text-white">Rekapitulasi SPT Masa Tahun {{ $rekapTahun }}</h5>
                        <small class="text-muted fs-12">Ringkasan Kurang Bayar, Sudah Disetor, dan Lebih Bayar (Kompensasi) per bulan</small>
                    </div>
                    <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25);">
                        Tahun {{ $rekapTahun }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Masa Pajak</th>
                                    <th class="text-center">Jumlah SPT</th>
                                    <th class="text-end">Kurang Bayar</th>
                                    <th class="text-end">Sudah Disetor (Lunas)</th>
                                    <th class="text-end">Lebih Bayar (Kompensasi)</th>
                                    <th class="text-end">Belum Disetor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sumSptCount = 0;
                                    $sumKewajiban = 0;
                                    $sumLunas = 0;
                                    $sumLebih = 0;
                                    $sumBelum = 0;
                                @endphp
                                @foreach($rekapBulanan as $rk)
                                    @php
                                        $sumSptCount += $rk['total_spt'];
                                        $sumKewajiban += $rk['total_kewajiban'];
                                        $sumLunas += $rk['total_lunas'];
                                        $sumLebih += ($rk['total_lebih_bayar'] ?? 0);
                                        $sumBelum += $rk['total_belum_lunas'];
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold text-white">{{ $rk['nama_bulan'] }} {{ $rekapTahun }}</td>
                                        <td class="text-center">{{ $rk['total_spt'] }}</td>
                                        <td class="text-end num-tabular">Rp {{ number_format($rk['total_kewajiban'], 0, ',', '.') }}</td>
                                        <td class="text-end num-tabular" style="color: #34D399;">Rp {{ number_format($rk['total_lunas'], 0, ',', '.') }}</td>
                                        <td class="text-end num-tabular" style="color: #A5B4FC;">Rp {{ number_format($rk['total_lebih_bayar'] ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end num-tabular" style="color: {{ $rk['total_belum_lunas'] > 0 ? '#FCD34D' : '#94A3B8' }};">Rp {{ number_format($rk['total_belum_lunas'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background: #181E26;">
                                    <td class="fw-bold text-white">TOTAL TAHUN {{ $rekapTahun }}</td>
                                    <td class="text-center fw-bold text-white">{{ $sumSptCount }}</td>
                                    <td class="text-end fw-bold text-white num-tabular">Rp {{ number_format($sumKewajiban, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold num-tabular" style="color: #34D399;">Rp {{ number_format($sumLunas, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold num-tabular" style="color: #A5B4FC;">Rp {{ number_format($sumLebih, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold num-tabular" style="color: #FCD34D;">Rp {{ number_format($sumBelum, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="card table-container-card mb-4 filter-bar">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('spt-masa.index') }}" class="row g-2 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fs-12 text-muted mb-1">Pencarian</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari No. SPT, Kode Billing, NTPN..." value="{{ $search }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label fs-12 text-muted mb-1">Masa Pajak (Bulan)</label>
                        <select name="masa_pajak_bulan" class="form-select form-select-sm">
                            <option value="all" {{ $filterBulan === 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @foreach($bulanNames as $mNum => $mName)
                                <option value="{{ $mNum }}" {{ (string)$filterBulan === (string)$mNum ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fs-12 text-muted mb-1">Tahun Pajak</label>
                        <select name="masa_pajak_tahun" class="form-select form-select-sm">
                            <option value="all" {{ $filterTahun === 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ (string)$filterTahun === (string)$yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fs-12 text-muted mb-1">Jenis Pajak</label>
                        <select name="jenis_pajak" class="form-select form-select-sm">
                            <option value="all" {{ $filterJenis === 'all' ? 'selected' : '' }}>Semua Jenis Pajak</option>
                            @foreach($jenisPajakList as $jp)
                                <option value="{{ $jp }}" {{ $filterJenis === $jp ? 'selected' : '' }}>{{ $jp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fs-12 text-muted mb-1">Status SPT</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="all" {{ $filterStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Dikompensasikan" {{ $filterStatus === 'Dikompensasikan' ? 'selected' : '' }}>Dikompensasikan (Lebih Bayar)</option>
                            <option value="Lunas" {{ $filterStatus === 'Lunas' ? 'selected' : '' }}>Lunas (Sudah Disetor)</option>
                            <option value="Belum Dibayar" {{ $filterStatus === 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="Nihil" {{ $filterStatus === 'Nihil' ? 'selected' : '' }}>Nihil</option>
                            <option value="Terlambat" {{ $filterStatus === 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-12 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm w-100" style="height: 34px; background: #6366F1; border-color: #6366F1;" title="Terapkan Filter">
                            <i class="ri-filter-3-line"></i>
                        </button>
                        <a href="{{ route('spt-masa.index') }}" class="btn btn-secondary btn-sm" style="height: 34px;" title="Reset Filter">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @php
            $roleUserSpt = Auth::user()->role ?? '';
            $isOwnerSpt  = $roleUserSpt === 'Pemilik';
            $isAdminSpt  = $roleUserSpt === 'Admin';
            $pendingSptRequests = $sptList->where('approval', 'Pending');
        @endphp

        @if($isOwnerSpt && $pendingSptRequests->count() > 0)
            <div class="alert d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 py-2.5 px-3" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35); color: #FCD34D; border-radius: 10px;">
                <div class="d-flex align-items-center gap-2 fs-13">
                    <i class="ri-notification-badge-line fs-18"></i>
                    <span>Terdapat <strong>{{ $pendingSptRequests->count() }} permintaan buka kunci (Edit/Hapus)</strong> SPT Masa dari Admin yang menunggu persetujuan Anda.</span>
                </div>
            </div>
        @endif

        {{-- Tabel Daftar SPT Masa --}}
        <div class="card table-container-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-title mb-0 fs-15 fw-semibold text-white">Daftar Pelaporan &amp; Kewajiban SPT Masa</h5>
                    <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25);">
                        {{ $sptList->count() }} Data
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <thead>
                            <tr>
                                <th class="text-center text-nowrap" style="width: 50px; white-space: nowrap;">NO.</th>
                                <th class="text-nowrap" style="white-space: nowrap; min-width: 140px;">NO. SPT</th>
                                <th class="text-nowrap" style="white-space: nowrap;">MASA PAJAK</th>
                                <th class="text-nowrap" style="white-space: nowrap;">JENIS &amp; RINGKASAN SPT</th>
                                <th class="text-nowrap" style="white-space: nowrap;">PENYERAHAN (I) &amp; PEROLEHAN (II)</th>
                                <th class="text-end text-nowrap" style="white-space: nowrap;">STATUS KB / (LB) (III.E)</th>
                                <th class="text-center text-nowrap" style="white-space: nowrap;">JATUH TEMPO &amp; TGL LAPOR</th>
                                <th class="text-center text-nowrap" style="white-space: nowrap;">STATUS</th>
                                <th class="text-center text-nowrap" style="width: 110px; white-space: nowrap;">AKSES EDIT</th>
                                <th class="text-center text-nowrap" style="width: 65px; white-space: nowrap;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sptList as $item)
                                @php
                                    $rincian = $item->rincian_spt ?? [];
                                    $isPpn = in_array($item->jenis_pajak, ['SPT Masa PPN', 'PPN Masa'], true);
                                    $kbLb = $item->status_kb_lb ?: 'Kurang Bayar';
                                    $approvalState = $item->approval ?: 'Lock';
                                    $isUnlocked = $approvalState === 'Unlock';
                                    $isPending  = $approvalState === 'Pending';
                                    $canManage  = !$isAdminSpt || $isUnlocked;
                                @endphp
                                <tr>
                                    {{-- 1. NO. (Row index number only) --}}
                                    <td class="text-center text-muted fs-12 num-tabular text-nowrap" style="width: 50px; white-space: nowrap;">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- 2. NO. SPT (Distinct single-line monospace column) --}}
                                    <td class="text-nowrap" style="white-space: nowrap;">
                                        <span class="font-mono font-monospace fw-semibold text-white fs-12 text-nowrap" style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; letter-spacing: 0.3px;">
                                            {{ $item->nomor_spt }}
                                        </span>
                                    </td>

                                    {{-- 3. MASA PAJAK --}}
                                    <td class="text-nowrap" style="white-space: nowrap;">
                                        <span class="fw-semibold text-white d-block">{{ $item->masa_pajak_label }}</span>
                                        <span class="badge mt-1 py-0.5 px-1.5 fs-10" style="background: rgba(148, 163, 184, 0.12); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.25);">
                                            {{ $item->pembetulan_label }}
                                        </span>
                                    </td>

                                    {{-- 4. JENIS & RINGKASAN SPT --}}
                                    <td>
                                        <div class="fw-semibold text-white">{{ $item->jenis_pajak }}</div>
                                        @if($item->nomor_billing)
                                            <small class="text-muted fs-11 d-block">Billing: <span class="text-white num-tabular">{{ $item->nomor_billing }}</span></small>
                                        @endif
                                        @if($item->ntpn)
                                            <span class="badge mt-1 py-0.5 px-1.5 fs-10" style="background: rgba(16, 185, 129, 0.12); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25);">
                                                NTPN: {{ $item->ntpn }}
                                            </span>
                                        @endif
                                        @if($item->keterangan)
                                            <small class="d-block text-muted fs-11 text-truncate" style="max-width: 220px;" title="{{ $item->keterangan }}">
                                                {{ $item->keterangan }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- 5. PENYERAHAN & PEROLEHAN --}}
                                    <td class="text-nowrap" style="white-space: nowrap;">
                                        @if($isPpn && !empty($rincian))
                                            <div class="fs-12">
                                                <span class="text-muted">I.C Penyerahan:</span>
                                                <span class="text-white fw-semibold num-tabular">Rp {{ number_format($rincian['ic_seluruh_penyerahan'] ?? 0, 0, ',', '.') }}</span>
                                                <small class="text-info ms-1">(PPN: Rp {{ number_format($rincian['ia_jumlah_ppn'] ?? 0, 0, ',', '.') }})</small>
                                            </div>
                                            <div class="fs-12 mt-0.5">
                                                <span class="text-muted">II.J Perolehan:</span>
                                                <span class="text-white fw-semibold num-tabular">Rp {{ number_format($rincian['iij_jumlah_perolehan'] ?? 0, 0, ',', '.') }}</span>
                                                <small style="color: #A5B4FC;" class="ms-1">(PM II.G: Rp {{ number_format($rincian['iig_pajak_masukan'] ?? 0, 0, ',', '.') }})</small>
                                            </div>
                                        @else
                                            <div class="fs-12">
                                                <span class="text-muted">DPP:</span>
                                                <span class="text-white fw-semibold num-tabular">Rp {{ number_format($item->dpp, 0, ',', '.') }}</span>
                                                @if($item->tarif_persen > 0)
                                                    <small class="text-muted">({{ rtrim(rtrim(number_format($item->tarif_persen, 2, ',', '.'), '0'), ',') }}%)</small>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 6. STATUS KB / (LB) --}}
                                    <td class="text-end num-tabular text-nowrap" style="white-space: nowrap;">
                                        @if($kbLb === 'Lebih Bayar')
                                            <div class="fw-bold fs-14" style="color: #A5B4FC;">
                                                (Rp {{ number_format($item->nominal_lebih_bayar, 0, ',', '.') }})
                                            </div>
                                            <small class="fs-11" style="color: #818CF8;">
                                                Lebih Bayar &bull; {{ $item->opsi_lebih_bayar ?: 'Dikompensasikan' }}
                                            </small>
                                        @elseif($kbLb === 'Nihil')
                                            <div class="fw-bold text-muted fs-14">Rp 0</div>
                                            <small class="text-muted fs-11">SPT Nihil</small>
                                        @else
                                            <div class="fw-bold text-white fs-14">
                                                Rp {{ number_format($item->nominal_pajak, 0, ',', '.') }}
                                            </div>
                                            <small class="text-warning fs-11">Kurang Bayar</small>
                                        @endif
                                    </td>

                                    {{-- 7. JATUH TEMPO & TGL LAPOR --}}
                                    <td class="text-center fs-12 text-nowrap" style="white-space: nowrap;">
                                        <div><span class="text-muted">JTO:</span> {{ optional($item->tgl_jatuh_tempo)->translatedFormat('d M Y') ?: '-' }}</div>
                                        @if($item->tgl_lapor)
                                            <div style="color: #38BDF8;"><span class="text-muted">Lapor:</span> {{ $item->tgl_lapor->translatedFormat('d M Y') }}</div>
                                        @elseif($item->tgl_bayar)
                                            <div style="color: #34D399;"><span class="text-muted">Bayar:</span> {{ $item->tgl_bayar->translatedFormat('d M Y') }}</div>
                                        @endif
                                    </td>

                                    {{-- 8. STATUS --}}
                                    <td class="text-center text-nowrap" style="white-space: nowrap;">
                                        @php $st = $item->effective_status; @endphp
                                        @if($st === 'Lunas')
                                            <span class="badge-spt-lunas"><i class="ri-checkbox-circle-line me-1"></i>Lunas</span>
                                        @elseif($st === 'Dikompensasikan')
                                            <span class="badge-spt-kompensasi"><i class="ri-Indeterminate-circle-line me-1"></i>Dikompensasikan</span>
                                        @elseif($st === 'Restitusi')
                                            <span class="badge-spt-menunggu"><i class="ri-refund-2-line me-1"></i>Restitusi</span>
                                        @elseif($st === 'Nihil')
                                            <span class="badge-spt-menunggu"><i class="ri-check-line me-1"></i>Nihil</span>
                                        @elseif($st === 'Terlambat')
                                            <span class="badge-spt-terlambat"><i class="ri-error-warning-line me-1"></i>Terlambat</span>
                                        @else
                                            <span class="badge-spt-belum"><i class="ri-bill-line me-1"></i>Belum Dibayar</span>
                                        @endif
                                    </td>

                                    {{-- 9. AKSES EDIT --}}
                                    <td class="text-center text-nowrap" style="white-space: nowrap;">
                                        @if($isUnlocked)
                                            <span class="badge py-1 px-2 fs-11 fw-semibold" style="background: rgba(16, 185, 129, 0.14); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);" title="Akses edit/hapus terbuka (akan terkunci kembali setelah diedit)">
                                                <i class="ri-lock-unlock-line me-0.5"></i>Terbuka
                                            </span>
                                        @elseif($isPending)
                                            <span class="badge py-1 px-2 fs-11 fw-semibold" style="background: rgba(245, 158, 11, 0.15); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.35);" title="Menunggu persetujuan Owner">
                                                <i class="ri-time-line me-0.5"></i>Menunggu ACC
                                            </span>
                                        @else
                                            <span class="badge py-1 px-2 fs-11 fw-semibold" style="background: rgba(148, 163, 184, 0.1); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.25);" title="Akses edit/hapus terkunci untuk Admin">
                                                <i class="ri-lock-line me-0.5" style="color: #F59E0B;"></i>Terkunci
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 10. AKSI (Hamburger Menu & Floating Dropdown) --}}
                                    <td class="text-center text-nowrap" style="width: 65px; white-space: nowrap;">
                                        <div class="spt-action-dropdown">
                                            <button type="button" class="btn-spt-action-menu" onclick="toggleSptActionMenu(this, event)" aria-expanded="false" title="Menu Aksi">
                                                <i class="ri-menu-line"></i>
                                            </button>
                                            <div class="spt-dropdown-menu">
                                                {{-- 1. LIHAT DETAIL (Emerald Accent) --}}
                                                @if($isPpn && !empty($rincian))
                                                    <button type="button" class="spt-dropdown-item"
                                                            onclick="closeAllSptActionMenus(); openModalDetailIndukSpt({{ json_encode([
                                                                'nomor_spt' => $item->nomor_spt,
                                                                'masa_pajak' => $item->masa_pajak_label,
                                                                'pembetulan' => $item->pembetulan_label,
                                                                'tgl_jatuh_tempo' => optional($item->tgl_jatuh_tempo)->translatedFormat('d F Y'),
                                                                'tgl_lapor' => optional($item->tgl_lapor)->translatedFormat('d F Y'),
                                                                'status_kb_lb' => $item->status_kb_lb,
                                                                'nominal_pajak' => $item->nominal_pajak,
                                                                'nominal_lebih_bayar' => $item->nominal_lebih_bayar,
                                                                'opsi_lebih_bayar' => $item->opsi_lebih_bayar,
                                                                'rincian' => $rincian,
                                                            ]) }})">
                                                        <span class="spt-item-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #10B981;">
                                                            <i class="ri-eye-line"></i>
                                                        </span>
                                                        <span>Lihat Detail</span>
                                                    </button>
                                                @endif

                                                {{-- 2. DOKUMEN SPT / BUKTI BAYAR (Sky/Blue Accent) --}}
                                                @if($item->bukti_bayar)
                                                    <a href="{{ asset($item->bukti_bayar) }}" target="_blank" class="spt-dropdown-item" onclick="closeAllSptActionMenus();">
                                                        <span class="spt-item-icon-box" style="background: rgba(56, 189, 248, 0.15); color: #38BDF8;">
                                                            <i class="ri-file-pdf-line"></i>
                                                        </span>
                                                        <span>Dokumen SPT</span>
                                                    </a>
                                                @endif

                                                {{-- 3. KUNCI / BUKA AKSES (Purple Accent) --}}
                                                @if($isOwnerSpt)
                                                    @if($isPending)
                                                        <form action="{{ route('spt-masa.unlock', $item->id) }}" method="POST" class="m-0 p-0">
                                                            @csrf
                                                            <button type="submit" class="spt-dropdown-item w-100 text-start" onclick="closeAllSptActionMenus();">
                                                                <span class="spt-item-icon-box" style="background: rgba(168, 85, 247, 0.15); color: #C084FC;">
                                                                    <i class="ri-lock-unlock-line"></i>
                                                                </span>
                                                                <span>Setujui Buka Kunci (ACC)</span>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('spt-masa.lock', $item->id) }}" method="POST" class="m-0 p-0">
                                                            @csrf
                                                            <button type="submit" class="spt-dropdown-item w-100 text-start text-danger" onclick="closeAllSptActionMenus();">
                                                                <span class="spt-item-icon-box" style="background: rgba(239, 68, 68, 0.15); color: #F87171;">
                                                                    <i class="ri-close-line"></i>
                                                                </span>
                                                                <span>Tolak Permintaan</span>
                                                            </button>
                                                        </form>
                                                    @elseif($isUnlocked)
                                                        <form action="{{ route('spt-masa.lock', $item->id) }}" method="POST" class="m-0 p-0">
                                                            @csrf
                                                            <button type="submit" class="spt-dropdown-item w-100 text-start" onclick="closeAllSptActionMenus();">
                                                                <span class="spt-item-icon-box" style="background: rgba(168, 85, 247, 0.15); color: #C084FC;">
                                                                    <i class="ri-lock-line"></i>
                                                                </span>
                                                                <span>Kunci Kembali (Lock)</span>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('spt-masa.unlock', $item->id) }}" method="POST" class="m-0 p-0">
                                                            @csrf
                                                            <button type="submit" class="spt-dropdown-item w-100 text-start" onclick="closeAllSptActionMenus();">
                                                                <span class="spt-item-icon-box" style="background: rgba(168, 85, 247, 0.15); color: #C084FC;">
                                                                    <i class="ri-lock-unlock-line"></i>
                                                                </span>
                                                                <span>Buka Akses Admin</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    {{-- ADMIN --}}
                                                    @if($isPending)
                                                        <div class="spt-dropdown-item" style="opacity: 0.7; cursor: default;">
                                                            <span class="spt-item-icon-box" style="background: rgba(245, 158, 11, 0.15); color: #FCD34D;">
                                                                <i class="ri-time-line"></i>
                                                            </span>
                                                            <span>Menunggu ACC Owner</span>
                                                        </div>
                                                    @elseif(!$canManage)
                                                        <form action="{{ route('spt-masa.request-unlock', $item->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Ajukan permintaan ke Owner untuk membuka akses Edit/Hapus pada SPT {{ $item->nomor_spt }}?')">
                                                            @csrf
                                                            <button type="submit" class="spt-dropdown-item w-100 text-start" onclick="closeAllSptActionMenus();">
                                                                <span class="spt-item-icon-box" style="background: rgba(168, 85, 247, 0.15); color: #C084FC;">
                                                                    <i class="ri-key-2-line"></i>
                                                                </span>
                                                                <span>Request Buka Akses</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                {{-- 4. INPUT PEMBAYARAN (Jika Kurang Bayar & Belum Lunas) --}}
                                                @if($kbLb === 'Kurang Bayar' && $item->status !== 'Lunas' && $item->nominal_pajak > 0)
                                                    <button type="button" class="spt-dropdown-item"
                                                            onclick="closeAllSptActionMenus(); openModalBayarSpt({{ json_encode([
                                                                'id' => $item->id,
                                                                'nomor_spt' => $item->nomor_spt,
                                                                'masa_pajak' => $item->masa_pajak_label,
                                                                'jenis_pajak' => $item->jenis_pajak,
                                                                'nominal_pajak' => $item->nominal_pajak,
                                                                'nomor_billing' => $item->nomor_billing,
                                                                'ntpn' => $item->ntpn,
                                                                'keterangan' => $item->keterangan,
                                                            ]) }})">
                                                        <span class="spt-item-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #34D399;">
                                                            <i class="ri-check-double-line"></i>
                                                        </span>
                                                        <span>Input Pembayaran</span>
                                                    </button>
                                                @endif

                                                {{-- 5. EDIT & HAPUS (Jika Can Manage) --}}
                                                @if($canManage)
                                                    <div class="spt-dropdown-divider"></div>

                                                    <button type="button" class="spt-dropdown-item"
                                                            onclick="closeAllSptActionMenus(); openModalEditSpt({{ json_encode([
                                                                'id' => $item->id,
                                                                'masa_pajak_bulan' => $item->masa_pajak_bulan,
                                                                'masa_pajak_tahun' => $item->masa_pajak_tahun,
                                                                'pembetulan_ke' => $item->pembetulan_ke ?? 0,
                                                                'jenis_pajak' => $item->jenis_pajak,
                                                                'nomor_billing' => $item->nomor_billing,
                                                                'tgl_billing' => optional($item->tgl_billing)->format('Y-m-d'),
                                                                'tgl_jatuh_tempo' => optional($item->tgl_jatuh_tempo)->format('Y-m-d'),
                                                                'tgl_lapor' => optional($item->tgl_lapor)->format('Y-m-d'),
                                                                'dpp' => $item->dpp,
                                                                'tarif_persen' => $item->tarif_persen,
                                                                'nominal_pajak' => $item->nominal_pajak,
                                                                'status_kb_lb' => $item->status_kb_lb,
                                                                'nominal_lebih_bayar' => $item->nominal_lebih_bayar,
                                                                'opsi_lebih_bayar' => $item->opsi_lebih_bayar,
                                                                'tgl_bayar' => optional($item->tgl_bayar)->format('Y-m-d'),
                                                                'ntpn' => $item->ntpn,
                                                                'status' => $item->status,
                                                                'keterangan' => $item->keterangan,
                                                                'rincian' => $rincian,
                                                            ]) }})">
                                                        <span class="spt-item-icon-box" style="background: rgba(148, 163, 184, 0.15); color: #E2E8F0;">
                                                            <i class="ri-edit-line"></i>
                                                        </span>
                                                        <span>Edit SPT</span>
                                                    </button>

                                                    <form action="{{ route('spt-masa.destroy', $item->id) }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Yakin ingin menghapus data SPT Masa ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="spt-dropdown-item w-100 text-start text-danger" onclick="closeAllSptActionMenus();">
                                                            <span class="spt-item-icon-box" style="background: rgba(239, 68, 68, 0.15); color: #F87171;">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </span>
                                                            <span>Hapus SPT</span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="ri-inbox-line fs-36 d-block mb-2" style="opacity: 0.5;"></i>
                                        Belum ada data SPT Masa untuk filter yang dipilih.
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

{{-- ======================================================================= --}}
{{-- MODAL TAMBAH SPT MASA (VERSI RINGKAS & MUDAH)                           --}}
{{-- ======================================================================= --}}
<div class="modal fade modal-dark-custom" id="modalTambahSpt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 860px;">
        <div class="modal-content">
            <form action="{{ route('spt-masa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="spt-modal-icon-box">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                        <div>
                            <h5 class="spt-modal-title">Input SPT Masa</h5>
                            <p class="spt-modal-subtitle">Catat pelaporan SPT Masa PPN atau PPh bulanan dengan cepat dan praktis.</p>
                        </div>
                    </div>
                    <button type="button" class="spt-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- KOTAK AUTO-FILL DARI FILE PDF CORETAX --}}
                    <div class="spt-pdf-upload-card mb-3" id="dropzone_spt_pdf_add" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(56, 189, 248, 0.08) 100%); border: 1.5px dashed rgba(99, 102, 241, 0.45); border-radius: 10px; padding: 12px 14px; transition: all 0.2s ease;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: rgba(99, 102, 241, 0.2); color: #A5B4FC; font-size: 20px;">
                                    <i class="ri-file-pdf-2-line"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white fs-13 d-flex align-items-center gap-1.5">
                                        <span>Import Otomatis dari PDF CoreTax DJP</span>
                                        <span class="badge" style="background: #10B981; color: #FFFFFF; font-size: 10px; font-weight: 600; padding: 2px 6px;">AUTO-DETECT</span>
                                    </div>
                                    <div class="text-muted fs-11 mt-0.5" id="add_pdf_autofill_status">
                                        Upload file PDF SPT Masa Induk hasil download dari CoreTax, semua kolom form akan terisi otomatis.
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <label for="add_spt_pdf_autofill_input" class="btn btn-sm px-3 py-1.5 fs-12 fw-semibold mb-0" style="background: #6366F1; color: #FFFFFF; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 6px rgba(99, 102, 241, 0.4);">
                                    <i class="ri-upload-cloud-line fs-14"></i> <span>Pilih File PDF</span>
                                </label>
                                <input type="file" id="add_spt_pdf_autofill_input" accept=".pdf,application/pdf" class="d-none" onchange="handleCoreTaxPdfUpload(this, 'add')">
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 1: PERIODE & JENIS PAJAK --}}
                    <div class="spt-section-divider">
                        <span class="spt-section-divider__text">1. PERIODE &amp; JENIS SPT MASA</span>
                        <span class="spt-section-divider__line"></span>
                        <button type="button" class="btn-autofill-spt" id="btn_autofill_add" onclick="tarikDataTransaksi('add')">
                            <i class="ri-magic-line"></i> Isi Otomatis dari Invoice &amp; Pembelian
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="spt-label">Jenis SPT Masa <span class="req">*</span></label>
                            <select name="jenis_pajak" id="add_jenis_pajak" class="form-select" required onchange="handleJenisPajakMode('add')">
                                @foreach($jenisPajakList as $jp)
                                    <option value="{{ $jp }}">{{ $jp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="spt-label">Bulan Pajak <span class="req">*</span></label>
                            <select name="masa_pajak_bulan" id="add_masa_pajak_bulan" class="form-select" required onchange="updateDefaultJatuhTempoPpn('add')">
                                @foreach($bulanNames as $mNum => $mName)
                                    <option value="{{ $mNum }}" {{ $mNum == 1 ? 'selected' : '' }}>{{ $mName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="spt-label">Tahun <span class="req">*</span></label>
                            <input type="number" name="masa_pajak_tahun" id="add_masa_pajak_tahun" class="form-control" value="{{ $currentYear }}" min="2020" max="2099" required onchange="updateDefaultJatuhTempoPpn('add')">
                        </div>
                        <div class="col-md-3">
                            <label class="spt-label">Status SPT</label>
                            <select name="pembetulan_ke" id="add_pembetulan_ke" class="form-select">
                                <option value="0">NORMAL</option>
                                <option value="1">Pembetulan Ke-1</option>
                                <option value="2">Pembetulan Ke-2</option>
                                <option value="3">Pembetulan Ke-3</option>
                            </select>
                        </div>
                    </div>

                    {{-- ================================================================= --}}
                    {{-- BLOK SPT MASA PPN (SESUAI LIHAT DETAIL + OPSIONAL RINGKAS)        --}}
                    {{-- ================================================================= --}}
                    <div id="add_ppn_induk_container">
                        <input type="hidden" name="ppn_input_mode" id="add_ppn_input_mode" value="detail">

                        <div class="spt-section-divider">
                            <span class="spt-section-divider__text">2. RINCIAN INDUK SPT MASA PPN</span>
                            <span class="spt-section-divider__line"></span>
                        </div>

                        {{-- MODE DETAIL INDUK (100% SESUAI MODAL LIHAT DETAIL) --}}
                        <div id="add_ppn_detail_wrap">
                            {{-- BAGIAN I: PENYERAHAN --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fs-11 fw-bold text-uppercase" style="color: #818CF8; letter-spacing: 0.5px;">I. PENYERAHAN BARANG DAN JASA (PAJAK KELUARAN)</span>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="spt-induk-input-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 240px;">Uraian Penyerahan</th>
                                            <th class="text-end" style="width: 145px;">Harga Jual / DPP (Rp)</th>
                                            <th class="text-end" style="width: 145px;">DPP Nilai Lain (Rp)</th>
                                            <th class="text-end" style="width: 145px;">PPN (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>I.A.2 Dipungut Sendiri DPP Nilai Lain (Kode 04 &amp; 05)</td>
                                            <td><input type="text" name="ia2_harga_jual" id="add_ia2_harga_jual" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="ia2_dpp" id="add_ia2_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="ia2_ppn" id="add_ia2_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                        </tr>
                                        <tr>
                                            <td>I.A.4 Dipungut Sendiri Lainnya (Kode 01, 09 &amp; 10)</td>
                                            <td><input type="text" name="ia4_harga_jual" id="add_ia4_harga_jual" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="ia4_dpp" id="add_ia4_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="ia4_ppn" id="add_ia4_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                        </tr>
                                        <tr>
                                            <td>I.A.6 Dipungut oleh Pemungut PPN (Kode 02 &amp; 03)</td>
                                            <td><input type="text" name="ia6_harga_jual" id="add_ia6_harga_jual" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="ia6_dpp" id="add_ia6_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="ia6_ppn" id="add_ia6_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                        </tr>
                                        <tr>
                                            <td>I.B Penyerahan Tidak Terutang PPN</td>
                                            <td><input type="text" name="ib_tidak_terutang" id="add_ib_tidak_terutang" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end text-muted">-</td>
                                        </tr>
                                        <tr class="spt-total-row">
                                            <td class="fw-bold text-white">I.C Jumlah Seluruh Penyerahan (I.A + I.B)</td>
                                            <td class="text-end fw-bold text-white num-tabular" id="add_sum_ic_total">0</td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end fw-bold text-info num-tabular" id="add_sum_ia_ppn">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- BAGIAN II: PEROLEHAN --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fs-11 fw-bold text-uppercase" style="color: #818CF8; letter-spacing: 0.5px;">II. PEROLEHAN BARANG DAN JASA (PAJAK MASUKAN)</span>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="spt-induk-input-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 240px;">Uraian Perolehan</th>
                                            <th class="text-end" style="width: 145px;">Harga Perolehan (Rp)</th>
                                            <th class="text-end" style="width: 145px;">DPP Nilai Lain (Rp)</th>
                                            <th class="text-end" style="width: 145px;">PPN Masukan (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>II.B Perolehan Dalam Negeri DPP Nilai Lain Dapat Dikreditkan (Kode 04 &amp; 05)</td>
                                            <td><input type="text" name="iib_harga" id="add_iib_harga" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="iib_dpp" id="add_iib_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="iib_ppn" id="add_iib_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                        </tr>
                                        <tr>
                                            <td>II.C Perolehan Dalam Negeri Selain DPP Nilai Lain (Kode 01, 09 &amp; 10)</td>
                                            <td><input type="text" name="iic_harga" id="add_iic_harga" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="iic_dpp" id="add_iic_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td><input type="text" name="iic_ppn" id="add_iic_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                        </tr>
                                        <tr>
                                            <td>II.E Kompensasi Kelebihan Pajak Masukan Masa Sebelumnya</td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end text-muted">-</td>
                                            <td><input type="text" name="iie_kompensasi" id="add_iie_kompensasi" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                        </tr>
                                        <tr>
                                            <td>II.H / II.I Perolehan Tidak Dikreditkan / Tidak Terutang PPN</td>
                                            <td><input type="text" name="iih_non_kredit" id="add_iih_non_kredit" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')"></td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end text-muted">-</td>
                                        </tr>
                                        <tr class="spt-total-row">
                                            <td class="fw-bold text-white">II.G / II.J Jumlah Perolehan &amp; Pajak Masukan Dapat Diperhitungkan</td>
                                            <td class="text-end fw-bold text-white num-tabular" id="add_sum_iij_total">0</td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end fw-bold num-tabular" style="color: #A5B4FC;" id="add_sum_iig_ppn">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- BAGIAN III.B INPUT PPN DISETOR DIMUKA --}}
                            <div class="row g-2 align-items-center mb-3 px-2 py-2 rounded" style="background: #111622; border: 1px solid #232A35;">
                                <div class="col-md-8">
                                    <span class="fs-12 text-muted">III.B. PPN disetor dimuka dalam Masa Pajak yang sama <span class="opacity-75">(jika ada)</span></span>
                                </div>
                                <div class="col-md-4">
                                    <div class="spt-input-group">
                                        <span class="spt-addon" style="padding: 4px 8px; font-size: 11px;">Rp</span>
                                        <input type="text" name="iiib_disetor_dimuka" id="add_iiib_disetor_dimuka" class="form-control num-tabular text-end py-1 fs-12" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('add')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOTAK HASIL OTOMATIS (BAGIAN III) --}}
                        <div class="spt-iii-box">
                            <div class="spt-iii-row mb-1">
                                <span class="text-muted fs-12">III.A. Pajak Keluaran yang harus dipungut sendiri (I.A.2 + I.A.4)</span>
                                <span class="fw-semibold text-white num-tabular fs-13" id="add_sum_iiia">Rp 0</span>
                            </div>
                            <div class="spt-iii-row mb-2">
                                <span class="text-muted fs-12">III.C. Pajak Masukan yang dapat diperhitungkan (II.G)</span>
                                <span class="fw-semibold num-tabular fs-13" style="color: #A5B4FC;" id="add_sum_iiic">Rp 0</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2" style="border-top: 1px solid #232A35;">
                                <div>
                                    <span class="fw-bold text-white d-block fs-13">III.E. Hasil Perhitungan SPT PPN (III.A &minus; III.B &minus; III.C)</span>
                                    <small id="add_label_status_kblb" class="fw-semibold" style="color: #94A3B8;">SPT NIHIL (Tidak ada pajak kurang/lebih bayar)</small>
                                </div>
                                <span class="fw-bold fs-20 num-tabular" id="add_label_iiie" style="color: #F8FAFC;">Rp 0</span>
                            </div>

                            {{-- Muncul otomatis jika Lebih Bayar --}}
                            <div id="add_iiih_box" class="mt-3 pt-2" style="border-top: 1px dashed #293659; display: none;">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <span class="fs-12 fw-semibold text-white">Kelebihan PPN diminta untuk:</span>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="opsi_lebih_bayar" id="add_opsi_lebih_bayar" class="form-select">
                                            <option value="Dikompensasikan" selected>Dikompensasikan ke Masa Pajak berikutnya</option>
                                            <option value="Restitusi Pemeriksaan">Restitusi (Pengembalian Pajak)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================================= --}}
                    {{-- BLOK KHUSUS PPh MASA (DPP x TARIF SEDERHANA)                      --}}
                    {{-- ================================================================= --}}
                    <div id="add_pph_simple_container" style="display: none;">
                        <div class="spt-section-divider">
                            <span class="spt-section-divider__text">2. PERHITUNGAN PAJAK (PPh)</span>
                            <span class="spt-section-divider__line"></span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4" id="add_dpp_group">
                                <label class="spt-label">Dasar Pengenaan Pajak (DPP)</label>
                                <div class="spt-input-group">
                                    <span class="spt-addon">Rp</span>
                                    <input type="text" name="dpp" id="add_dpp" class="form-control" placeholder="0" oninput="formatRibuanInput(this); hitungNominalPajakSimple('add')">
                                </div>
                            </div>
                            <div class="col-md-4" id="add_tarif_group">
                                <label class="spt-label">Tarif Pajak</label>
                                <div class="spt-input-group">
                                    <input type="number" name="tarif_persen" id="add_tarif" class="form-control" step="0.01" min="0" max="100" placeholder="0" oninput="hitungNominalPajakSimple('add')">
                                    <span class="spt-addon">%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="spt-label">Nominal Pajak Harus Dibayar <span class="req">*</span></label>
                                <div class="spt-input-group spt-input-group--highlight">
                                    <span class="spt-addon">Rp</span>
                                    <input type="text" name="nominal_pajak" id="add_nominal_pajak" class="form-control fw-bold text-white" value="0" oninput="formatRibuanInput(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: DOKUMEN & STATUS --}}
                    <div class="spt-section-divider">
                        <span class="spt-section-divider__text">3. DOKUMEN SPT &amp; KETERANGAN</span>
                        <span class="spt-section-divider__line"></span>
                    </div>
                    <div class="row g-3 align-items-start">
                        <div class="col-md-4">
                            <label class="spt-label">Batas Jatuh Tempo <span class="req">*</span></label>
                            <input type="date" name="tgl_jatuh_tempo" id="add_tgl_jatuh_tempo" class="form-control" value="{{ date('Y-02-28') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="spt-label">Tanggal Lapor SPT</label>
                            <input type="date" name="tgl_lapor" id="add_tgl_lapor" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4 add-kurang-bayar-field">
                            <label class="spt-label">Kode Billing (Opsional)</label>
                            <input type="text" name="nomor_billing" class="form-control" placeholder="012345678901234">
                        </div>

                        <div class="col-md-6 add-kurang-bayar-field">
                            <label class="spt-label">Status Pembayaran Pajak</label>
                            <input type="hidden" name="status" id="add_status" value="Belum Dibayar">
                            <div class="spt-segmented" role="group">
                                <button type="button" class="spt-segmented__btn is-active" data-status-btn="Belum Dibayar" onclick="setAddPaymentStatus('Belum Dibayar')">
                                    Belum Dibayar
                                </button>
                                <button type="button" class="spt-segmented__btn" data-status-btn="Lunas" onclick="setAddPaymentStatus('Lunas')">
                                    Sudah Dibayar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="spt-label">Upload File PDF SPT Masa / Bukti Lapor</label>
                            <label class="spt-dropzone" id="add_dropzone" for="add_bukti_bayar">
                                <i class="ri-upload-2-line spt-dropzone__icon"></i>
                                <div class="overflow-hidden">
                                    <div class="spt-dropzone__title text-truncate" id="add_bukti_title">Pilih file PDF SPT atau seret ke sini</div>
                                    <div class="spt-dropzone__sub" id="add_bukti_sub">PDF, JPG, atau PNG (Maks. 10 MB)</div>
                                </div>
                                <input type="file" name="bukti_bayar" id="add_bukti_bayar" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.webp" onchange="handleSptFileSelect(this, 'add_bukti_title', 'add_bukti_sub')">
                            </label>
                        </div>

                        <div class="col-md-6 add-payment-field" style="display: none;">
                            <label class="spt-label">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar" id="add_tgl_bayar" class="form-control">
                        </div>
                        <div class="col-md-6 add-payment-field" style="display: none;">
                            <label class="spt-label">NTPN (Opsional)</label>
                            <input type="text" name="ntpn" class="form-control" placeholder="Nomor Transaksi Penerimaan Negara">
                        </div>

                        <div class="col-12">
                            <label class="spt-label">Catatan (Opsional)</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Catatan masa pajak atau keterangan tambahan...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="spt-required-note"><span class="text-danger">*</span> Wajib diisi</div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-spt-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-spt-submit"><i class="ri-save-3-line fs-16"></i>Simpan SPT Masa</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- MODAL DETAIL RINGKASAN INDUK SPT MASA PPN (SEPERTI CETAKAN PDF DJP)     --}}
{{-- ======================================================================= --}}
<div class="modal fade modal-dark-custom" id="modalDetailIndukSpt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 820px;">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="spt-modal-icon-box">
                        <i class="ri-file-search-line"></i>
                    </div>
                    <div>
                        <h5 class="spt-modal-title" id="detail_spt_title">SURAT PEMBERITAHUAN MASA PPN (INDUK)</h5>
                        <p class="spt-modal-subtitle" id="detail_spt_subtitle">PT TOTAL KARYA BERKAH &bull; NPWP: 0407052034124000</p>
                    </div>
                </div>
                <button type="button" class="spt-btn-close" data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="spt-section-divider">
                    <span class="spt-section-divider__text">I. PENYERAHAN BARANG DAN JASA (PAJAK KELUARAN)</span>
                    <span class="spt-section-divider__line"></span>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-custom">
                        <thead>
                            <tr>
                                <th>Uraian Penyerahan</th>
                                <th class="text-end">Harga Jual / DPP (Rp)</th>
                                <th class="text-end">DPP Nilai Lain (Rp)</th>
                                <th class="text-end">PPN (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>I.A.2 Dipungut Sendiri DPP Nilai Lain (Kode 04 &amp; 05)</td>
                                <td class="text-end num-tabular" id="det_ia2_harga">0</td>
                                <td class="text-end num-tabular" id="det_ia2_dpp">0</td>
                                <td class="text-end num-tabular" id="det_ia2_ppn">0</td>
                            </tr>
                            <tr>
                                <td>I.A.4 Dipungut Sendiri Lainnya (Kode 01, 09 &amp; 10)</td>
                                <td class="text-end num-tabular" id="det_ia4_harga">0</td>
                                <td class="text-end num-tabular" id="det_ia4_dpp">0</td>
                                <td class="text-end num-tabular" id="det_ia4_ppn">0</td>
                            </tr>
                            <tr>
                                <td>I.A.6 Dipungut oleh Pemungut PPN (Kode 02 &amp; 03)</td>
                                <td class="text-end num-tabular" id="det_ia6_harga">0</td>
                                <td class="text-end num-tabular" id="det_ia6_dpp">0</td>
                                <td class="text-end num-tabular" id="det_ia6_ppn">0</td>
                            </tr>
                            <tr>
                                <td>I.B Penyerahan Tidak Terutang PPN</td>
                                <td class="text-end num-tabular" id="det_ib_harga">0</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end text-muted">-</td>
                            </tr>
                            <tr style="background: #181E26;">
                                <td class="fw-bold text-white">I.C Jumlah Seluruh Penyerahan (I.A + I.B)</td>
                                <td class="text-end fw-bold text-white num-tabular" id="det_ic_total">0</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end fw-bold text-info num-tabular" id="det_ia_ppn_total">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="spt-section-divider">
                    <span class="spt-section-divider__text">II. PEROLEHAN BARANG DAN JASA (PAJAK MASUKAN)</span>
                    <span class="spt-section-divider__line"></span>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-custom">
                        <thead>
                            <tr>
                                <th>Uraian Perolehan</th>
                                <th class="text-end">Harga Perolehan (Rp)</th>
                                <th class="text-end">DPP Nilai Lain (Rp)</th>
                                <th class="text-end">PPN Masukan (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>II.B Perolehan Dalam Negeri DPP Nilai Lain Dapat Dikreditkan (Kode 04 &amp; 05)</td>
                                <td class="text-end num-tabular" id="det_iib_harga">0</td>
                                <td class="text-end num-tabular" id="det_iib_dpp">0</td>
                                <td class="text-end num-tabular" id="det_iib_ppn">0</td>
                            </tr>
                            <tr>
                                <td>II.C Perolehan Dalam Negeri Selain DPP Nilai Lain (Kode 01, 09 &amp; 10)</td>
                                <td class="text-end num-tabular" id="det_iic_harga">0</td>
                                <td class="text-end num-tabular" id="det_iic_dpp">0</td>
                                <td class="text-end num-tabular" id="det_iic_ppn">0</td>
                            </tr>
                            <tr>
                                <td>II.E Kompensasi Kelebihan Pajak Masukan Masa Sebelumnya</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end num-tabular" id="det_iie_ppn">0</td>
                            </tr>
                            <tr>
                                <td>II.H / II.I Perolehan Tidak Dikreditkan / Tidak Terutang PPN</td>
                                <td class="text-end num-tabular" id="det_iih_harga">0</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end text-muted">-</td>
                            </tr>
                            <tr style="background: #181E26;">
                                <td class="fw-bold text-white">II.G / II.J Jumlah Perolehan &amp; Pajak Masukan Dapat Diperhitungkan</td>
                                <td class="text-end fw-bold text-white num-tabular" id="det_iij_total">0</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end fw-bold num-tabular" style="color: #A5B4FC;" id="det_iig_ppn_total">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="spt-section-divider">
                    <span class="spt-section-divider__text">III. PENGHITUNGAN PPN KURANG BAYAR / LEBIH BAYAR</span>
                    <span class="spt-section-divider__line"></span>
                </div>
                <div class="spt-iii-box">
                    <div class="spt-iii-row">
                        <span class="text-muted">III.A. Pajak Keluaran yang harus dipungut sendiri (I.A.2 + I.A.4)</span>
                        <span class="fw-bold text-white num-tabular" id="det_iiia">Rp 0</span>
                    </div>
                    <div class="spt-iii-row">
                        <span class="text-muted">III.B. PPN disetor dimuka dalam Masa Pajak yang sama</span>
                        <span class="fw-bold text-white num-tabular" id="det_iiib">Rp 0</span>
                    </div>
                    <div class="spt-iii-row">
                        <span class="text-muted">III.C. Pajak Masukan yang dapat diperhitungkan (II.G)</span>
                        <span class="fw-bold num-tabular" style="color: #A5B4FC;" id="det_iiic">Rp 0</span>
                    </div>
                    <div class="spt-iii-row pt-2 mt-1" style="border-top: 1px solid #2D3B5E;">
                        <span class="fw-bold text-white" id="det_iiie_label">III.E. PPN Kurang atau (Lebih) Bayar</span>
                        <span class="fw-bold fs-16 num-tabular" id="det_iiie_val">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn-spt-cancel" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PELUNASAN / BAYAR SPT MASA --}}
<div class="modal fade modal-dark-custom" id="modalBayarSpt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formBayarSpt" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-white fw-semibold"><i class="ri-checkbox-circle-line me-1 text-success"></i>Konfirmasi Pembayaran SPT Masa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="p-3 mb-3 rounded" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25);">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted fs-12">Nomor SPT / Masa:</span>
                            <span class="text-white fw-semibold fs-13" id="bayar_info_masa">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted fs-12">Jenis Pajak:</span>
                            <span class="text-white fw-semibold fs-13" id="bayar_info_jenis">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted fs-12">Nominal Dibayarkan:</span>
                            <span class="fw-bold fs-15" style="color: #34D399;" id="bayar_info_nominal">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fs-13 text-white">Tanggal Pembayaran <span class="text-danger">*</span></label>
                        <input type="date" name="tgl_bayar" id="bayar_tgl_bayar" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13 text-white">Nomor / Kode Billing</label>
                        <input type="text" name="nomor_billing" id="bayar_nomor_billing" class="form-control" placeholder="Kode Billing">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13 text-white">NTPN (Nomor Transaksi Penerimaan Negara)</label>
                        <input type="text" name="ntpn" id="bayar_ntpn" class="form-control" placeholder="Contoh: 1A2B3C4D5E6F7G8H">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13 text-white">Unggah Bukti Pembayaran (BPN / Bukti Setor)</label>
                        <input type="file" name="bukti_bayar" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp">
                        <small class="text-muted fs-11">Format PDF, JPG, PNG maks. 10 MB</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fs-13 text-white">Catatan Pembayaran</label>
                        <input type="text" name="keterangan" id="bayar_keterangan" class="form-control" placeholder="Keterangan pelunasan...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-semibold"><i class="ri-check-double-line me-1"></i>Simpan &amp; Tandai Lunas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================================= --}}
{{-- MODAL EDIT SPT MASA (VERSI RINGKAS & MUDAH)                             --}}
{{-- ======================================================================= --}}
<div class="modal fade modal-dark-custom" id="modalEditSpt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" style="max-width: 860px;">
        <div class="modal-content">
            <form id="formEditSpt" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="spt-modal-icon-box">
                            <i class="ri-edit-line"></i>
                        </div>
                        <div>
                            <h5 class="spt-modal-title">Edit Data SPT Masa</h5>
                            <p class="spt-modal-subtitle">Perbarui rincian SPT Masa PPN atau PPh bulanan.</p>
                        </div>
                    </div>
                    <button type="button" class="spt-btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- KOTAK AUTO-FILL DARI FILE PDF CORETAX --}}
                    <div class="spt-pdf-upload-card mb-3" id="dropzone_spt_pdf_edit" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(56, 189, 248, 0.08) 100%); border: 1.5px dashed rgba(99, 102, 241, 0.45); border-radius: 10px; padding: 12px 14px; transition: all 0.2s ease;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; background: rgba(99, 102, 241, 0.2); color: #A5B4FC; font-size: 20px;">
                                    <i class="ri-file-pdf-2-line"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white fs-13 d-flex align-items-center gap-1.5">
                                        <span>Update Otomatis dari PDF CoreTax DJP</span>
                                        <span class="badge" style="background: #10B981; color: #FFFFFF; font-size: 10px; font-weight: 600; padding: 2px 6px;">AUTO-DETECT</span>
                                    </div>
                                    <div class="text-muted fs-11 mt-0.5" id="edit_pdf_autofill_status">
                                        Upload file PDF SPT Masa Induk terbaru untuk memperbarui seluruh isian formulir.
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <label for="edit_spt_pdf_autofill_input" class="btn btn-sm px-3 py-1.5 fs-12 fw-semibold mb-0" style="background: #6366F1; color: #FFFFFF; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 6px rgba(99, 102, 241, 0.4);">
                                    <i class="ri-upload-cloud-line fs-14"></i> <span>Pilih File PDF</span>
                                </label>
                                <input type="file" id="edit_spt_pdf_autofill_input" accept=".pdf,application/pdf" class="d-none" onchange="handleCoreTaxPdfUpload(this, 'edit')">
                            </div>
                        </div>
                    </div>

                    <div class="spt-section-divider">
                        <span class="spt-section-divider__text">1. PERIODE &amp; JENIS SPT MASA</span>
                        <span class="spt-section-divider__line"></span>
                        <button type="button" class="btn-autofill-spt" id="btn_autofill_edit" onclick="tarikDataTransaksi('edit')">
                            <i class="ri-magic-line"></i> Isi Otomatis dari Invoice &amp; Pembelian
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="spt-label">Jenis SPT Masa <span class="req">*</span></label>
                            <select name="jenis_pajak" id="edit_jenis_pajak" class="form-select" required onchange="handleJenisPajakMode('edit')">
                                @foreach($jenisPajakList as $jp)
                                    <option value="{{ $jp }}">{{ $jp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="spt-label">Bulan Pajak <span class="req">*</span></label>
                            <select name="masa_pajak_bulan" id="edit_masa_pajak_bulan" class="form-select" required>
                                @foreach($bulanNames as $mNum => $mName)
                                    <option value="{{ $mNum }}">{{ $mName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="spt-label">Tahun <span class="req">*</span></label>
                            <input type="number" name="masa_pajak_tahun" id="edit_masa_pajak_tahun" class="form-control" min="2020" max="2099" required>
                        </div>
                        <div class="col-md-3">
                            <label class="spt-label">Status SPT</label>
                            <select name="pembetulan_ke" id="edit_pembetulan_ke" class="form-select">
                                <option value="0">NORMAL</option>
                                <option value="1">Pembetulan Ke-1</option>
                                <option value="2">Pembetulan Ke-2</option>
                                <option value="3">Pembetulan Ke-3</option>
                            </select>
                        </div>
                    </div>

                    {{-- BLOK SPT MASA PPN EDIT (SESUAI LIHAT DETAIL) --}}
                    <div id="edit_ppn_induk_container">
                        <input type="hidden" name="ppn_input_mode" id="edit_ppn_input_mode" value="detail">

                        <div class="spt-section-divider">
                            <span class="spt-section-divider__text">2. RINCIAN INDUK SPT MASA PPN</span>
                            <span class="spt-section-divider__line"></span>
                        </div>

                        {{-- MODE DETAIL INDUK (100% SESUAI MODAL LIHAT DETAIL) --}}
                        <div id="edit_ppn_detail_wrap">
                            {{-- BAGIAN I: PENYERAHAN --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fs-11 fw-bold text-uppercase" style="color: #818CF8; letter-spacing: 0.5px;">I. PENYERAHAN BARANG DAN JASA (PAJAK KELUARAN)</span>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="spt-induk-input-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 240px;">Uraian Penyerahan</th>
                                            <th class="text-end" style="width: 145px;">Harga Jual / DPP (Rp)</th>
                                            <th class="text-end" style="width: 145px;">DPP Nilai Lain (Rp)</th>
                                            <th class="text-end" style="width: 145px;">PPN (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>I.A.2 Dipungut Sendiri DPP Nilai Lain (Kode 04 &amp; 05)</td>
                                            <td><input type="text" name="ia2_harga_jual" id="edit_ia2_harga_jual" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="ia2_dpp" id="edit_ia2_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="ia2_ppn" id="edit_ia2_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                        </tr>
                                        <tr>
                                            <td>I.A.4 Dipungut Sendiri Lainnya (Kode 01, 09 &amp; 10)</td>
                                            <td><input type="text" name="ia4_harga_jual" id="edit_ia4_harga_jual" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="ia4_dpp" id="edit_ia4_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="ia4_ppn" id="edit_ia4_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                        </tr>
                                        <tr>
                                            <td>I.A.6 Dipungut oleh Pemungut PPN (Kode 02 &amp; 03)</td>
                                            <td><input type="text" name="ia6_harga_jual" id="edit_ia6_harga_jual" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="ia6_dpp" id="edit_ia6_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="ia6_ppn" id="edit_ia6_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                        </tr>
                                        <tr>
                                            <td>I.B Penyerahan Tidak Terutang PPN</td>
                                            <td><input type="text" name="ib_tidak_terutang" id="edit_ib_tidak_terutang" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end text-muted">-</td>
                                        </tr>
                                        <tr class="spt-total-row">
                                            <td class="fw-bold text-white">I.C Jumlah Seluruh Penyerahan (I.A + I.B)</td>
                                            <td class="text-end fw-bold text-white num-tabular" id="edit_sum_ic_total">0</td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end fw-bold text-info num-tabular" id="edit_sum_ia_ppn">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- BAGIAN II: PEROLEHAN --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fs-11 fw-bold text-uppercase" style="color: #818CF8; letter-spacing: 0.5px;">II. PEROLEHAN BARANG DAN JASA (PAJAK MASUKAN)</span>
                            </div>
                            <div class="table-responsive mb-3">
                                <table class="spt-induk-input-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 240px;">Uraian Perolehan</th>
                                            <th class="text-end" style="width: 145px;">Harga Perolehan (Rp)</th>
                                            <th class="text-end" style="width: 145px;">DPP Nilai Lain (Rp)</th>
                                            <th class="text-end" style="width: 145px;">PPN Masukan (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>II.B Perolehan Dalam Negeri DPP Nilai Lain Dapat Dikreditkan (Kode 04 &amp; 05)</td>
                                            <td><input type="text" name="iib_harga" id="edit_iib_harga" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="iib_dpp" id="edit_iib_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="iib_ppn" id="edit_iib_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                        </tr>
                                        <tr>
                                            <td>II.C Perolehan Dalam Negeri Selain DPP Nilai Lain (Kode 01, 09 &amp; 10)</td>
                                            <td><input type="text" name="iic_harga" id="edit_iic_harga" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="iic_dpp" id="edit_iic_dpp" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td><input type="text" name="iic_ppn" id="edit_iic_ppn" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                        </tr>
                                        <tr>
                                            <td>II.E Kompensasi Kelebihan Pajak Masukan Masa Sebelumnya</td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end text-muted">-</td>
                                            <td><input type="text" name="iie_kompensasi" id="edit_iie_kompensasi" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                        </tr>
                                        <tr>
                                            <td>II.H / II.I Perolehan Tidak Dikreditkan / Tidak Terutang PPN</td>
                                            <td><input type="text" name="iih_non_kredit" id="edit_iih_non_kredit" class="spt-cell-input" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')"></td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end text-muted">-</td>
                                        </tr>
                                        <tr class="spt-total-row">
                                            <td class="fw-bold text-white">II.G / II.J Jumlah Perolehan &amp; Pajak Masukan Dapat Diperhitungkan</td>
                                            <td class="text-end fw-bold text-white num-tabular" id="edit_sum_iij_total">0</td>
                                            <td class="text-end text-muted">-</td>
                                            <td class="text-end fw-bold num-tabular" style="color: #A5B4FC;" id="edit_sum_iig_ppn">0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- BAGIAN III.B INPUT PPN DISETOR DIMUKA --}}
                            <div class="row g-2 align-items-center mb-3 px-2 py-2 rounded" style="background: #111622; border: 1px solid #232A35;">
                                <div class="col-md-8">
                                    <span class="fs-12 text-muted">III.B. PPN disetor dimuka dalam Masa Pajak yang sama <span class="opacity-75">(jika ada)</span></span>
                                </div>
                                <div class="col-md-4">
                                    <div class="spt-input-group">
                                        <span class="spt-addon" style="padding: 4px 8px; font-size: 11px;">Rp</span>
                                        <input type="text" name="iiib_disetor_dimuka" id="edit_iiib_disetor_dimuka" class="form-control num-tabular text-end py-1 fs-12" placeholder="0" oninput="formatRibuanInput(this); hitungIndukPpn('edit')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="spt-iii-box">
                            <div class="spt-iii-row mb-1">
                                <span class="text-muted fs-12">III.A. Pajak Keluaran yang harus dipungut sendiri (I.A.2 + I.A.4)</span>
                                <span class="fw-semibold text-white num-tabular fs-13" id="edit_sum_iiia">Rp 0</span>
                            </div>
                            <div class="spt-iii-row mb-2">
                                <span class="text-muted fs-12">III.C. Pajak Masukan yang dapat diperhitungkan (II.G)</span>
                                <span class="fw-semibold num-tabular fs-13" style="color: #A5B4FC;" id="edit_sum_iiic">Rp 0</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2" style="border-top: 1px solid #232A35;">
                                <div>
                                    <span class="fw-bold text-white d-block fs-13">III.E. Hasil Perhitungan SPT PPN (III.A &minus; III.B &minus; III.C)</span>
                                    <small id="edit_label_status_kblb" class="fw-semibold" style="color: #94A3B8;">SPT NIHIL</small>
                                </div>
                                <span class="fw-bold fs-20 num-tabular" id="edit_label_iiie" style="color: #F8FAFC;">Rp 0</span>
                            </div>

                            <div id="edit_iiih_box" class="mt-3 pt-2" style="border-top: 1px dashed #293659; display: none;">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <span class="fs-12 fw-semibold text-white">Kelebihan PPN diminta untuk:</span>
                                    </div>
                                    <div class="col-md-7">
                                        <select name="opsi_lebih_bayar" id="edit_opsi_lebih_bayar" class="form-select">
                                            <option value="Dikompensasikan">Dikompensasikan ke Masa Pajak berikutnya</option>
                                            <option value="Restitusi Pemeriksaan">Restitusi (Pengembalian Pajak)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BLOK PPh SIMPLE EDIT --}}
                    <div id="edit_pph_simple_container" style="display: none;">
                        <div class="spt-section-divider">
                            <span class="spt-section-divider__text">2. PERHITUNGAN PAJAK (PPh)</span>
                            <span class="spt-section-divider__line"></span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="spt-label">Dasar Pengenaan Pajak (DPP)</label>
                                <div class="spt-input-group">
                                    <span class="spt-addon">Rp</span>
                                    <input type="text" name="dpp" id="edit_dpp" class="form-control" placeholder="0" oninput="formatRibuanInput(this); hitungNominalPajakSimple('edit')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="spt-label">Tarif Pajak</label>
                                <div class="spt-input-group">
                                    <input type="number" name="tarif_persen" id="edit_tarif" class="form-control" step="0.01" min="0" max="100" placeholder="0" oninput="hitungNominalPajakSimple('edit')">
                                    <span class="spt-addon">%</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="spt-label">Nominal Pajak Harus Dibayar <span class="req">*</span></label>
                                <div class="spt-input-group spt-input-group--highlight">
                                    <span class="spt-addon">Rp</span>
                                    <input type="text" name="nominal_pajak" id="edit_nominal_pajak" class="form-control fw-bold text-white" placeholder="0" oninput="formatRibuanInput(this)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="spt-section-divider">
                        <span class="spt-section-divider__text">3. DOKUMEN SPT &amp; KETERANGAN</span>
                        <span class="spt-section-divider__line"></span>
                    </div>
                    <div class="row g-3 align-items-start">
                        <div class="col-md-4">
                            <label class="spt-label">Batas Jatuh Tempo <span class="req">*</span></label>
                            <input type="date" name="tgl_jatuh_tempo" id="edit_tgl_jatuh_tempo" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="spt-label">Tanggal Lapor SPT</label>
                            <input type="date" name="tgl_lapor" id="edit_tgl_lapor" class="form-control">
                        </div>
                        <div class="col-md-4 edit-kurang-bayar-field">
                            <label class="spt-label">Kode Billing (Opsional)</label>
                            <input type="text" name="nomor_billing" id="edit_nomor_billing" class="form-control" placeholder="012345678901234">
                        </div>
                        <div class="col-md-6 edit-kurang-bayar-field">
                            <label class="spt-label">Status Pembayaran</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="Belum Dibayar">Belum Dibayar</option>
                                <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                                <option value="Lunas">Sudah Dibayar (Lunas)</option>
                                <option value="Terlambat">Terlambat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="spt-label">Ganti File PDF SPT / Bukti Lapor</label>
                            <label class="spt-dropzone" id="edit_dropzone" for="edit_bukti_bayar">
                                <i class="ri-upload-2-line spt-dropzone__icon"></i>
                                <div class="overflow-hidden">
                                    <div class="spt-dropzone__title text-truncate" id="edit_bukti_title">Pilih file atau seret ke sini</div>
                                    <div class="spt-dropzone__sub" id="edit_bukti_sub">PDF, JPG, atau PNG (Maks. 10 MB)</div>
                                </div>
                                <input type="file" name="bukti_bayar" id="edit_bukti_bayar" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.webp" onchange="handleSptFileSelect(this, 'edit_bukti_title', 'edit_bukti_sub')">
                            </label>
                        </div>
                        <div class="col-md-6 edit-kurang-bayar-field">
                            <label class="spt-label">Tanggal Bayar</label>
                            <input type="date" name="tgl_bayar" id="edit_tgl_bayar" class="form-control">
                        </div>
                        <div class="col-md-6 edit-kurang-bayar-field">
                            <label class="spt-label">NTPN (Opsional)</label>
                            <input type="text" name="ntpn" id="edit_ntpn" class="form-control" placeholder="Nomor Transaksi Penerimaan Negara">
                        </div>
                        <div class="col-12">
                            <label class="spt-label">Catatan (Opsional)</label>
                            <input type="text" name="keterangan" id="edit_keterangan" class="form-control" placeholder="Catatan masa pajak atau keterangan tambahan...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="spt-required-note"><span class="text-danger">*</span> Wajib diisi</div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-spt-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-spt-submit"><i class="ri-save-3-line fs-16"></i>Perbarui Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script src="{{ asset('libs/pdfjs/pdf.min.js') }}"></script>
<script>
    if (window.pdfjsLib) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('libs/pdfjs/pdf.worker.min.js') }}";
    }

    const BULAN_INDONESIA_MAP = {
        'januari': 1, 'februari': 2, 'maret': 3, 'april': 4, 'mei': 5, 'juni': 6,
        'juli': 7, 'agustus': 8, 'september': 9, 'oktober': 10, 'november': 11, 'desember': 12,
        'jan': 1, 'feb': 2, 'mar': 3, 'apr': 4, 'may': 5, 'jun': 6, 'jul': 7, 'aug': 8, 'agu': 8, 'sep': 9, 'okt': 10, 'oct': 10, 'nov': 11, 'des': 12, 'dec': 12
    };

    function parseIndonesianDateToYmd(str) {
        if (!str) return '';
        const match = str.trim().match(/(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/);
        if (!match) return '';
        const d = String(match[1]).padStart(2, '0');
        const mStr = match[2].toLowerCase();
        const m = BULAN_INDONESIA_MAP[mStr];
        if (!m) return '';
        const mPadded = String(m).padStart(2, '0');
        const y = match[3];
        return `${y}-${mPadded}-${d}`;
    }

    async function parseCoreTaxSptPdf(file) {
        if (!window.pdfjsLib) {
            throw new Error('Library PDF.js belum dimuat.');
        }
        const arrayBuffer = await file.arrayBuffer();
        const typedArray = new Uint8Array(arrayBuffer);
        const pdfDoc = await pdfjsLib.getDocument({ data: typedArray }).promise;

        let allLines = [];
        for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
            const page = await pdfDoc.getPage(pageNum);
            const textContent = await page.getTextContent();
            const lines = textContent.items.map(item => (item.str || '').trim()).filter(Boolean);
            allLines = allLines.concat(lines);
        }
        const fullText = allLines.join('\n');

        // 1. Masa Pajak (Bulan & Tahun)
        const masaMatch = fullText.match(/Masa\s+Pajak[\s\S]*?(Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember)\s+(\d{4})/i);
        const bulanNama = masaMatch ? masaMatch[1] : '';
        const bulan = bulanNama ? (BULAN_INDONESIA_MAP[bulanNama.toLowerCase()] || 1) : 1;
        const tahun = masaMatch ? parseInt(masaMatch[2], 10) : new Date().getFullYear();

        // 2. Status Pembetulan (NORMAL vs PEMBETULAN)
        let pembetulanKe = 0;
        const namaPkpIdx = allLines.findIndex(l => /NAMA\s+PKP/i.test(l));
        const statusToken = namaPkpIdx > 0 ? allLines[namaPkpIdx - 1] : '';
        if (/PEMBETULAN/i.test(statusToken)) {
            pembetulanKe = 1;
            const pNumMatch = fullText.match(/Pembetulan\s+ke-?\s*(\d+)/i);
            if (pNumMatch) {
                pembetulanKe = parseInt(pNumMatch[1], 10) || 1;
            }
        }

        // 3. Pajak Masukan (II.G / III.C)
        let pajakMasukan = 0;
        const pmMatch = fullText.match(/Pajak\s+Masukan\s+yang\s+dapat\s+diperhitungkan\s*\(II\.G\)\s*\n?([0-9\.\-]+)/i);
        if (pmMatch) {
            pajakMasukan = Math.abs(parseInt(pmMatch[1].replace(/[^0-9\-]/g, ''), 10) || 0);
        }

        // 4. Pajak Keluaran Dipungut Sendiri (III.A)
        let pajakKeluaran = 0;
        const pkMatch = fullText.match(/Pajak\s+Keluaran\s+yang\s+harus\s+dipungut\s+sendiri[^\n]*\n?([0-9\.\-]+)/i);
        if (pkMatch) {
            pajakKeluaran = Math.abs(parseInt(pkMatch[1].replace(/[^0-9\-]/g, ''), 10) || 0);
        }

        // 5. PPN Dipungut Pemungut (I.A.6)
        let ppnPemungut = 0;
        const pemungutMatch = fullText.match(/dipungut\s+oleh\s+Pemungut\s+PPN[\s\S]*?(\d[\d\.]*)\s*\n(\d[\d\.]*)\s*\n(\d[\d\.]*)/i);
        if (pemungutMatch) {
            ppnPemungut = Math.abs(parseInt(pemungutMatch[3].replace(/[^0-9\-]/g, ''), 10) || 0);
        }

        // 6. Total Omzet (I.C)
        let totalOmzet = 0;
        const omzetMatch = fullText.match(/Jumlah\s+seluruh\s+penyerahan\s+barang\s+dan\s+jasa\s*\(I\.A\s*\+\s*I\.B\)\s*\n?([0-9\.\-]+)/i);
        if (omzetMatch) {
            totalOmzet = Math.abs(parseInt(omzetMatch[1].replace(/[^0-9\-]/g, ''), 10) || 0);
        }

        // 7. Selisih Kurang/Lebih Bayar (III.E)
        let selisihPpn = 0;
        const kblbMatch = fullText.match(/PPN\s+kurang\s+atau\s+\(lebih\)\s+bayar\s*\(III\.A\s*-\s*III\.B\s*-\s*III\.C\s*-\s*III\.D\)\s*\n?([0-9\.\-]+)/i);
        if (kblbMatch) {
            selisihPpn = parseInt(kblbMatch[1].replace(/[^0-9\-]/g, ''), 10) || 0;
        }

        // 8. Tanggal Jatuh Tempo Penyampaian
        let tglJatuhTempo = '';
        const jtMatch = fullText.match(/Jatuh\s+tempo\s+penyampaian\s*:\s*([^\n\r]+)/i);
        if (jtMatch) {
            tglJatuhTempo = parseIndonesianDateToYmd(jtMatch[1]);
        }

        // 9. Tanggal Lapor / TTD
        let tglLapor = '';
        const ttdMatch = fullText.match(/KOTA\s+[^,]+,\s*(\d{1,2}\s+[A-Za-z]+\s+\d{4})/i);
        if (ttdMatch) {
            tglLapor = parseIndonesianDateToYmd(ttdMatch[1]);
        }

        // 10. Nomor SPT / Dokumen (misal nomor DN di filename atau text)
        let nomorSpt = '';
        const dnMatch = (file.name || '').match(/(DN\w+)/i) || fullText.match(/(DN\d{10,}\w*)/i);
        if (dnMatch) {
            nomorSpt = dnMatch[1];
        }

        // 11. Rincian Detail Induk SPT (Bagian I, II, III)
        const parseNum = (s) => Math.abs(parseInt((s || '0').replace(/[^0-9\-]/g, ''), 10) || 0);
        const ia2M  = fullText.match(/04 dan 05\)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)/i);
        const ia4M  = fullText.match(/Kode 01, 09 dan 10\)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)/i);
        const ia6M  = fullText.match(/Kode 02 dan 03\)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)/i);
        const ibM   = fullText.match(/Penyerahan barang\/jasa yang tidak terutang PPN\s*\n([0-9\.\-]+)/i);
        const iibM  = fullText.match(/Perolehan BKP\/JKP dari dalam negeri dengan DPP Nilai Lain[\s\S]*?04 dan 05\)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)/i);
        const iicM  = fullText.match(/Kode 01, 09, dan 10\)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)\s*\n([0-9\.\-]+)/i);
        const iieM  = fullText.match(/Kompensasi kelebihan Pajak Masukan\s*\n([0-9\.\-]+)/i);
        const iihM  = fullText.match(/Pajak Masukannya tidak dikreditkan[\s\S]*?mendapat fasilitas\s*\n([0-9\.\-]+)/i);
        const iiibM = fullText.match(/PPN disetor dimuka dalam Masa Pajak yang sama\s*\n([0-9\.\-]+)/i);

        return {
            bulan,
            bulanNama,
            tahun,
            pembetulanKe,
            pajakKeluaran,
            pajakMasukan,
            ppnPemungut,
            totalOmzet,
            selisihPpn,
            tglJatuhTempo,
            tglLapor,
            nomorSpt,
            ia2_harga_jual: ia2M ? parseNum(ia2M[1]) : 0,
            ia2_dpp: ia2M ? parseNum(ia2M[2]) : 0,
            ia2_ppn: ia2M ? parseNum(ia2M[3]) : 0,
            ia4_harga_jual: ia4M ? parseNum(ia4M[1]) : 0,
            ia4_dpp: ia4M ? parseNum(ia4M[2]) : 0,
            ia4_ppn: ia4M ? parseNum(ia4M[3]) : 0,
            ia6_harga_jual: ia6M ? parseNum(ia6M[1]) : 0,
            ia6_dpp: ia6M ? parseNum(ia6M[2]) : 0,
            ia6_ppn: ia6M ? parseNum(ia6M[3]) : 0,
            ib_tidak_terutang: ibM ? parseNum(ibM[1]) : 0,
            iib_harga: iibM ? parseNum(iibM[1]) : 0,
            iib_dpp: iibM ? parseNum(iibM[2]) : 0,
            iib_ppn: iibM ? parseNum(iibM[3]) : 0,
            iic_harga: iicM ? parseNum(iicM[1]) : 0,
            iic_dpp: iicM ? parseNum(iicM[2]) : 0,
            iic_ppn: iicM ? parseNum(iicM[3]) : 0,
            iie_kompensasi: iieM ? parseNum(iieM[1]) : 0,
            iih_non_kredit: iihM ? parseNum(iihM[1]) : 0,
            iiib_disetor_dimuka: iiibM ? parseNum(iiibM[1]) : 0
        };
    }

    async function handleCoreTaxPdfFile(file, prefix, syncToBukti = true) {
        if (!file || !file.name.toLowerCase().endsWith('.pdf')) {
            return;
        }

        const statusEl = document.getElementById(`${prefix}_pdf_autofill_status`);
        if (statusEl) {
            statusEl.innerHTML = `<span style="color: #FCD34D;"><i class="ri-loader-4-line ri-spin me-1"></i> Sedang membaca dan mengekstrak data dari file PDF CoreTax...</span>`;
        }

        try {
            const data = await parseCoreTaxSptPdf(file);

            // 1. Jenis Pajak -> SPT Masa PPN
            const elJenis = document.getElementById(`${prefix}_jenis_pajak`);
            if (elJenis) {
                elJenis.value = 'SPT Masa PPN';
                handleJenisPajakMode(prefix);
            }

            // 2. Bulan & Tahun
            const elBulan = document.getElementById(`${prefix}_masa_pajak_bulan`);
            if (elBulan && data.bulan) elBulan.value = data.bulan;

            const elTahun = document.getElementById(`${prefix}_masa_pajak_tahun`);
            if (elTahun && data.tahun) elTahun.value = data.tahun;

            // 3. Pembetulan
            const elPembetulan = document.getElementById(`${prefix}_pembetulan_ke`);
            if (elPembetulan) elPembetulan.value = data.pembetulanKe;

            // 4. Populate Detail Induk SPT (Bagian I, II, III)
            setFormattedVal(`${prefix}_ia2_harga_jual`, data.ia2_harga_jual || 0);
            setFormattedVal(`${prefix}_ia2_dpp`, data.ia2_dpp || 0);
            setFormattedVal(`${prefix}_ia2_ppn`, data.ia2_ppn || 0);
            setFormattedVal(`${prefix}_ia4_harga_jual`, data.ia4_harga_jual || 0);
            setFormattedVal(`${prefix}_ia4_dpp`, data.ia4_dpp || 0);
            setFormattedVal(`${prefix}_ia4_ppn`, data.ia4_ppn || 0);
            setFormattedVal(`${prefix}_ia6_harga_jual`, data.ia6_harga_jual || 0);
            setFormattedVal(`${prefix}_ia6_dpp`, data.ia6_dpp || 0);
            setFormattedVal(`${prefix}_ia6_ppn`, data.ia6_ppn || 0);
            setFormattedVal(`${prefix}_ib_tidak_terutang`, data.ib_tidak_terutang || 0);

            setFormattedVal(`${prefix}_iib_harga`, data.iib_harga || 0);
            setFormattedVal(`${prefix}_iib_dpp`, data.iib_dpp || 0);
            setFormattedVal(`${prefix}_iib_ppn`, data.iib_ppn || 0);
            setFormattedVal(`${prefix}_iic_harga`, data.iic_harga || 0);
            setFormattedVal(`${prefix}_iic_dpp`, data.iic_dpp || 0);
            setFormattedVal(`${prefix}_iic_ppn`, data.iic_ppn || 0);
            setFormattedVal(`${prefix}_iie_kompensasi`, data.iie_kompensasi || 0);
            setFormattedVal(`${prefix}_iih_non_kredit`, data.iih_non_kredit || 0);
            setFormattedVal(`${prefix}_iiib_disetor_dimuka`, data.iiib_disetor_dimuka || 0);

            // 5. Jatuh Tempo
            const elJt = document.getElementById(`${prefix}_tgl_jatuh_tempo`);
            if (elJt && data.tglJatuhTempo) elJt.value = data.tglJatuhTempo;

            // 6. Tanggal Lapor
            const elLapor = document.getElementById(`${prefix}_tgl_lapor`);
            if (elLapor && data.tglLapor) elLapor.value = data.tglLapor;

            // 7. Nomor SPT jika ada
            if (data.nomorSpt) {
                const elNomor = document.getElementById(`${prefix}_nomor_spt`);
                if (elNomor && !elNomor.value) elNomor.value = data.nomorSpt;
            }

            // Hitung otomatis Induk PPN
            hitungIndukPpn(prefix);

            // Sinkronkan ke input file bukti_bayar agar terupload ke server saat submit
            if (syncToBukti) {
                const buktiInput = document.getElementById(`${prefix}_bukti_bayar`);
                if (buktiInput) {
                    try {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        buktiInput.files = dt.files;
                        handleSptFileSelect(buktiInput, `${prefix}_bukti_title`, `${prefix}_bukti_sub`);
                    } catch (e) {
                        console.warn('DataTransfer sync error:', e);
                    }
                }
            }

            // Update status text
            const statusLabel = data.selisihPpn < 0 ? `Lebih Bayar Rp ${Math.abs(data.selisihPpn).toLocaleString('id-ID')} (Kompensasi)` : (data.selisihPpn > 0 ? `Kurang Bayar Rp ${data.selisihPpn.toLocaleString('id-ID')}` : 'Nihil');
            const pembetulanLabel = data.pembetulanKe > 0 ? `Pembetulan Ke-${data.pembetulanKe}` : 'NORMAL';

            if (statusEl) {
                statusEl.innerHTML = `<span style="color: #34D399; font-weight: 600;"><i class="ri-checkbox-circle-fill me-1"></i> Berhasil! Masa <b>${data.bulanNama} ${data.tahun} (${pembetulanLabel})</b> — ${statusLabel}. File PDF siap disimpan.</span>`;
            }

            // Tampilkan notifikasi toast sukses
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `PDF CoreTax Berhasil Dibaca!`,
                    text: `Masa ${data.bulanNama} ${data.tahun} (${pembetulanLabel}) • ${statusLabel}`,
                    showConfirmButton: false,
                    timer: 4500,
                    timerProgressBar: true,
                    background: '#0D111D',
                    color: '#F8F9FA'
                });
            }
        } catch (error) {
            console.error('Error saat membaca PDF CoreTax:', error);
            if (statusEl) {
                statusEl.innerHTML = `<span style="color: #F87171;"><i class="ri-error-warning-line me-1"></i> Gagal mengekstrak data dari file PDF: ${error.message}</span>`;
            }
        }
    }

    function handleCoreTaxPdfUpload(input, prefix) {
        if (input.files && input.files.length > 0) {
            handleCoreTaxPdfFile(input.files[0], prefix, true);
        }
    }

    function formatRibuanInput(input) {
        let raw = (input.value || '').toString().replace(/\D/g, '');
        if (!raw) {
            input.value = '';
            return 0;
        }
        let num = parseInt(raw, 10);
        input.value = num.toLocaleString('id-ID');
        return num;
    }

    function parseRibuanVal(val) {
        if (!val) return 0;
        return parseInt(val.toString().replace(/\D/g, ''), 10) || 0;
    }

    function setFormattedVal(id, num) {
        const el = document.getElementById(id);
        if (!el) return;
        const n = Math.round(Number(num) || 0);
        el.value = n > 0 ? n.toLocaleString('id-ID') : '0';
    }

    /**
     * Hitung otomatis selisih PPN berdasarkan Detail Induk SPT
     */
    function hitungIndukPpn(prefix) {
        const val = (id) => parseRibuanVal(document.getElementById(`${prefix}_${id}`)?.value);

        const ia2Hj = val('ia2_harga_jual'), ia2Ppn = val('ia2_ppn');
        const ia4Hj = val('ia4_harga_jual'), ia4Ppn = val('ia4_ppn');
        const ia6Hj = val('ia6_harga_jual'), ia6Ppn = val('ia6_ppn');
        const ib    = val('ib_tidak_terutang');

        const iibH  = val('iib_harga'), iibPpn = val('iib_ppn');
        const iicH  = val('iic_harga'), iicPpn = val('iic_ppn');
        const iie   = val('iie_kompensasi');
        const iih   = val('iih_non_kredit');
        const iiib  = val('iiib_disetor_dimuka');

        const icTotal     = ia2Hj + ia4Hj + ia6Hj + ib;
        const iaPpnTotal  = ia2Ppn + ia4Ppn + ia6Ppn;
        const iijTotal    = iibH + iicH + iih;
        const iigPpnTotal = iibPpn + iicPpn + iie;

        const iiia = ia2Ppn + ia4Ppn;
        const iiic = iigPpnTotal;
        const iiieSelisih = iiia - iiib - iiic;

        const setText = (id, text) => {
            const el = document.getElementById(`${prefix}_${id}`);
            if (el) el.textContent = text;
        };

        setText('sum_ic_total', icTotal.toLocaleString('id-ID'));
        setText('sum_ia_ppn', iaPpnTotal.toLocaleString('id-ID'));
        setText('sum_iij_total', iijTotal.toLocaleString('id-ID'));
        setText('sum_iig_ppn', iigPpnTotal.toLocaleString('id-ID'));
        setText('sum_iiia', 'Rp ' + iiia.toLocaleString('id-ID'));
        setText('sum_iiic', 'Rp ' + iiic.toLocaleString('id-ID'));

        const labelIiie = document.getElementById(`${prefix}_label_iiie`);
        const labelKbLb = document.getElementById(`${prefix}_label_status_kblb`);
        const boxIiih   = document.getElementById(`${prefix}_iiih_box`);
        const kbFields  = document.querySelectorAll(`.${prefix}-kurang-bayar-field`);

        if (iiieSelisih < 0) {
            const lb = Math.abs(iiieSelisih);
            if (labelIiie) {
                labelIiie.textContent = '-Rp ' + lb.toLocaleString('id-ID');
                labelIiie.style.color = '#A5B4FC';
            }
            if (labelKbLb) {
                labelKbLb.textContent = 'LEBIH BAYAR (Rp ' + lb.toLocaleString('id-ID') + ') — Tidak ada tagihan kurang bayar';
                labelKbLb.style.color = '#818CF8';
            }
            if (boxIiih) boxIiih.style.display = 'block';
            kbFields.forEach(el => el.style.display = 'none');
        } else if (iiieSelisih > 0) {
            if (labelIiie) {
                labelIiie.textContent = 'Rp ' + iiieSelisih.toLocaleString('id-ID');
                labelIiie.style.color = '#FCD34D';
            }
            if (labelKbLb) {
                labelKbLb.textContent = 'KURANG BAYAR — Wajib disetor menggunakan Kode Billing';
                labelKbLb.style.color = '#FCD34D';
            }
            if (boxIiih) boxIiih.style.display = 'none';
            kbFields.forEach(el => el.style.display = 'block');
        } else {
            if (labelIiie) {
                labelIiie.textContent = 'Rp 0';
                labelIiie.style.color = '#94A3B8';
            }
            if (labelKbLb) {
                labelKbLb.textContent = 'SPT NIHIL (Tidak ada pajak kurang/lebih bayar)';
                labelKbLb.style.color = '#94A3B8';
            }
            if (boxIiih) boxIiih.style.display = 'none';
            kbFields.forEach(el => el.style.display = 'none');
        }
    }

    function hitungNominalPajakSimple(prefix) {
        const dppEl = document.getElementById(prefix + '_dpp');
        const tarifEl = document.getElementById(prefix + '_tarif');
        const nominalEl = document.getElementById(prefix + '_nominal_pajak');
        if (!dppEl || !tarifEl || !nominalEl) return;

        const dpp = parseRibuanVal(dppEl.value);
        const tarif = parseFloat(tarifEl.value) || 0;

        if (dpp > 0 && tarif > 0) {
            const hasil = Math.round(dpp * tarif / 100);
            nominalEl.value = hasil.toLocaleString('id-ID');
        }
    }

    function updateDefaultJatuhTempoPpn(prefix) {
        const jenis = document.getElementById(`${prefix}_jenis_pajak`)?.value;
        const bulan = parseInt(document.getElementById(`${prefix}_masa_pajak_bulan`)?.value || '1', 10);
        const tahun = parseInt(document.getElementById(`${prefix}_masa_pajak_tahun`)?.value || '{{ $currentYear }}', 10);
        const jtoEl = document.getElementById(`${prefix}_tgl_jatuh_tempo`);
        if (!jtoEl) return;

        if (jenis === 'SPT Masa PPN' || jenis === 'PPN Masa') {
            let nextMonth = bulan + 1;
            let nextYear = tahun;
            if (nextMonth > 12) {
                nextMonth = 1;
                nextYear += 1;
            }
            const lastDay = new Date(nextYear, nextMonth, 0).getDate();
            jtoEl.value = `${nextYear}-${String(nextMonth).padStart(2, '0')}-${String(lastDay).padStart(2, '0')}`;
        } else {
            let nextMonth = bulan + 1;
            let nextYear = tahun;
            if (nextMonth > 12) {
                nextMonth = 1;
                nextYear += 1;
            }
            jtoEl.value = `${nextYear}-${String(nextMonth).padStart(2, '0')}-15`;
        }
    }

    function handleJenisPajakMode(prefix) {
        const jenis = document.getElementById(`${prefix}_jenis_pajak`)?.value;
        const ppnContainer = document.getElementById(`${prefix}_ppn_induk_container`);
        const pphContainer = document.getElementById(`${prefix}_pph_simple_container`);
        const autofillBtn  = document.getElementById(`btn_autofill_${prefix}`);
        const kbFields     = document.querySelectorAll(`.${prefix}-kurang-bayar-field`);

        const isPpn = (jenis === 'SPT Masa PPN' || jenis === 'PPN Masa');

        if (ppnContainer) ppnContainer.style.display = isPpn ? 'block' : 'none';
        if (pphContainer) pphContainer.style.display = isPpn ? 'none' : 'block';
        if (autofillBtn) autofillBtn.style.display = isPpn ? 'inline-flex' : 'none';

        if (isPpn) {
            hitungIndukPpn(prefix);
        } else {
            kbFields.forEach(el => el.style.display = 'block');
            const tarifEl = document.getElementById(`${prefix}_tarif`);
            if (tarifEl) {
                if (jenis === 'PPh Pasal 23') tarifEl.value = '2';
                else if (jenis === 'PPh Final UMKM (0,5%)') tarifEl.value = '0.5';
                else if (jenis === 'PPh Pasal 4 Ayat (2) / Final') tarifEl.value = '10';
                else if (jenis === 'PPh Pasal 25') tarifEl.value = '0';
            }
            hitungNominalPajakSimple(prefix);
        }

        if (prefix === 'add') {
            updateDefaultJatuhTempoPpn('add');
        }
    }

    function tarikDataTransaksi(prefix) {
        const bulan = document.getElementById(`${prefix}_masa_pajak_bulan`)?.value;
        const tahun = document.getElementById(`${prefix}_masa_pajak_tahun`)?.value;
        const btn = document.getElementById(`btn_autofill_${prefix}`);
        if (!bulan || !tahun) return;

        const origHtml = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line"></i> Mengisi otomatis...';
        }

        fetch(`{{ route('spt-masa.tarik-data') }}?bulan=${bulan}&tahun=${tahun}`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const d = res.data;

                    // Populate detailed Induk fields
                    setFormattedVal(`${prefix}_ia2_harga_jual`, Number(d.ia2_harga_jual) || 0);
                    setFormattedVal(`${prefix}_ia2_dpp`, Number(d.ia2_dpp) || 0);
                    setFormattedVal(`${prefix}_ia2_ppn`, Number(d.ia2_ppn) || 0);
                    setFormattedVal(`${prefix}_ia4_harga_jual`, Number(d.ia4_harga_jual) || 0);
                    setFormattedVal(`${prefix}_ia4_dpp`, Number(d.ia4_dpp) || 0);
                    setFormattedVal(`${prefix}_ia4_ppn`, Number(d.ia4_ppn) || 0);
                    setFormattedVal(`${prefix}_ia6_harga_jual`, Number(d.ia6_harga_jual) || 0);
                    setFormattedVal(`${prefix}_ia6_dpp`, Number(d.ia6_dpp) || 0);
                    setFormattedVal(`${prefix}_ia6_ppn`, Number(d.ia6_ppn) || 0);
                    setFormattedVal(`${prefix}_ib_tidak_terutang`, Number(d.ib_tidak_terutang) || 0);

                    setFormattedVal(`${prefix}_iib_harga`, Number(d.iib_harga) || 0);
                    setFormattedVal(`${prefix}_iib_dpp`, Number(d.iib_dpp) || 0);
                    setFormattedVal(`${prefix}_iib_ppn`, Number(d.iib_ppn) || 0);
                    setFormattedVal(`${prefix}_iic_harga`, Number(d.iic_harga) || 0);
                    setFormattedVal(`${prefix}_iic_dpp`, Number(d.iic_dpp) || 0);
                    setFormattedVal(`${prefix}_iic_ppn`, Number(d.iic_ppn) || 0);
                    setFormattedVal(`${prefix}_iie_kompensasi`, Number(d.iie_kompensasi) || 0);
                    setFormattedVal(`${prefix}_iih_non_kredit`, Number(d.iih_non_kredit) || 0);
                    setFormattedVal(`${prefix}_iiib_disetor_dimuka`, Number(d.iiib_disetor_dimuka) || 0);

                    if (res.jatuh_tempo_ppn) {
                        const jtoEl = document.getElementById(`${prefix}_tgl_jatuh_tempo`);
                        if (jtoEl) jtoEl.value = res.jatuh_tempo_ppn;
                    }

                    hitungIndukPpn(prefix);
                }
            })
            .catch(() => {})
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origHtml;
                }
            });
    }

    function setAddPaymentStatus(statusVal) {
        const statusInput = document.getElementById('add_status');
        if (statusInput) {
            statusInput.value = statusVal;
        }
        document.querySelectorAll('[data-status-btn]').forEach(function(btn) {
            if (btn.getAttribute('data-status-btn') === statusVal) {
                btn.classList.add('is-active');
            } else {
                btn.classList.remove('is-active');
            }
        });
        const fields = document.querySelectorAll('.add-payment-field');
        const tglBayar = document.getElementById('add_tgl_bayar');
        fields.forEach(function(el) {
            el.style.display = (statusVal === 'Lunas') ? 'block' : 'none';
        });
        if (statusVal === 'Lunas' && tglBayar && !tglBayar.value) {
            tglBayar.value = '{{ date("Y-m-d") }}';
        }
    }

    function handleSptFileSelect(input, titleId, subId) {
        const titleEl = document.getElementById(titleId);
        const subEl = document.getElementById(subId);
        if (!titleEl) return;
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            titleEl.textContent = file.name;
            titleEl.style.color = '#A5B4FC';
            if (subEl) {
                const kb = (file.size / 1024).toFixed(1);
                subEl.textContent = kb + ' KB — Klik untuk mengganti file';
            }

            // Auto-parse jika file yang diunggah berformat PDF CoreTax
            const isAdd = input.id && input.id.indexOf('add') !== -1;
            const prefix = isAdd ? 'add' : 'edit';
            if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                if (typeof handleCoreTaxPdfFile === 'function') {
                    handleCoreTaxPdfFile(file, prefix, false);
                }
            }
        } else {
            titleEl.textContent = 'Pilih file PDF SPT atau seret ke sini';
            titleEl.style.color = '#E2E8F0';
            if (subEl) {
                subEl.textContent = 'PDF, JPG, atau PNG (Maks. 10 MB)';
            }
        }
    }

    function openModalDetailIndukSpt(data) {
        const r = data.rincian || {};
        const fmt = (v) => Number(v || 0).toLocaleString('id-ID');

        document.getElementById('detail_spt_title').textContent = `INDUK SPT MASA PPN — ${data.masa_pajak} (${data.pembetulan})`;
        document.getElementById('detail_spt_subtitle').textContent = `No. SPT: ${data.nomor_spt || '-'} • Jatuh Tempo: ${data.tgl_jatuh_tempo || '-'} • Tgl Lapor: ${data.tgl_lapor || '-'}`;

        document.getElementById('det_ia2_harga').textContent = fmt(r.ia2_harga_jual);
        document.getElementById('det_ia2_dpp').textContent   = fmt(r.ia2_dpp);
        document.getElementById('det_ia2_ppn').textContent   = fmt(r.ia2_ppn);

        document.getElementById('det_ia4_harga').textContent = fmt(r.ia4_harga_jual);
        document.getElementById('det_ia4_dpp').textContent   = fmt(r.ia4_dpp);
        document.getElementById('det_ia4_ppn').textContent   = fmt(r.ia4_ppn);

        document.getElementById('det_ia6_harga').textContent = fmt(r.ia6_harga_jual);
        document.getElementById('det_ia6_dpp').textContent   = fmt(r.ia6_dpp);
        document.getElementById('det_ia6_ppn').textContent   = fmt(r.ia6_ppn);

        document.getElementById('det_ib_harga').textContent     = fmt(r.ib_tidak_terutang);
        document.getElementById('det_ic_total').textContent     = fmt(r.ic_seluruh_penyerahan);
        document.getElementById('det_ia_ppn_total').textContent = fmt(r.ia_jumlah_ppn);

        document.getElementById('det_iib_harga').textContent    = fmt(r.iib_harga);
        document.getElementById('det_iib_dpp').textContent      = fmt(r.iib_dpp);
        document.getElementById('det_iib_ppn').textContent      = fmt(r.iib_ppn);

        document.getElementById('det_iic_harga').textContent    = fmt(r.iic_harga);
        document.getElementById('det_iic_dpp').textContent      = fmt(r.iic_dpp);
        document.getElementById('det_iic_ppn').textContent      = fmt(r.iic_ppn);

        document.getElementById('det_iie_ppn').textContent      = fmt(r.iie_kompensasi);
        document.getElementById('det_iih_harga').textContent    = fmt(r.iih_non_kredit);
        document.getElementById('det_iij_total').textContent    = fmt(r.iij_jumlah_perolehan);
        document.getElementById('det_iig_ppn_total').textContent= fmt(r.iig_pajak_masukan);

        document.getElementById('det_iiia').textContent = 'Rp ' + fmt(r.iiia_ppn_dipungut_sendiri);
        document.getElementById('det_iiib').textContent = 'Rp ' + fmt(r.iiib_disetor_dimuka);
        document.getElementById('det_iiic').textContent = 'Rp ' + fmt(r.iiic_pajak_masukan);

        const iiieValEl = document.getElementById('det_iiie_val');
        const iiieLblEl = document.getElementById('det_iiie_label');
        const selisih = Number(r.iiie_selisih || 0);
        if (selisih < 0) {
            iiieValEl.textContent = '-Rp ' + fmt(Math.abs(selisih));
            iiieValEl.style.color = '#A5B4FC';
            iiieLblEl.textContent = `III.E. PPN Lebih Bayar — Diminta untuk: ${data.opsi_lebih_bayar || 'Dikompensasikan'}`;
        } else if (selisih > 0) {
            iiieValEl.textContent = 'Rp ' + fmt(selisih);
            iiieValEl.style.color = '#FCD34D';
            iiieLblEl.textContent = 'III.E. PPN Kurang Bayar';
        } else {
            iiieValEl.textContent = 'Rp 0';
            iiieValEl.style.color = '#94A3B8';
            iiieLblEl.textContent = 'III.E. PPN Nihil';
        }

        const modal = new bootstrap.Modal(document.getElementById('modalDetailIndukSpt'));
        modal.show();
    }

    function openModalBayarSpt(data) {
        const form = document.getElementById('formBayarSpt');
        form.action = '{{ url("/spt-masa") }}/' + data.id + '/bayar';
        document.getElementById('bayar_info_masa').textContent = (data.nomor_spt || '-') + ' (' + data.masa_pajak + ')';
        document.getElementById('bayar_info_jenis').textContent = data.jenis_pajak;
        document.getElementById('bayar_info_nominal').textContent = 'Rp ' + Number(data.nominal_pajak || 0).toLocaleString('id-ID');
        document.getElementById('bayar_nomor_billing').value = data.nomor_billing || '';
        document.getElementById('bayar_ntpn').value = data.ntpn || '';
        document.getElementById('bayar_keterangan').value = data.keterangan || '';
        const modal = new bootstrap.Modal(document.getElementById('modalBayarSpt'));
        modal.show();
    }

    function openModalEditSpt(data) {
        const form = document.getElementById('formEditSpt');
        form.action = '{{ url("/spt-masa") }}/' + data.id;

        const jenisVal = (data.jenis_pajak === 'PPN Masa') ? 'SPT Masa PPN' : data.jenis_pajak;
        document.getElementById('edit_jenis_pajak').value = jenisVal;
        document.getElementById('edit_masa_pajak_bulan').value = data.masa_pajak_bulan;
        document.getElementById('edit_masa_pajak_tahun').value = data.masa_pajak_tahun;
        document.getElementById('edit_pembetulan_ke').value = data.pembetulan_ke || 0;
        document.getElementById('edit_tgl_lapor').value = data.tgl_lapor || '';
        document.getElementById('edit_nomor_billing').value = data.nomor_billing || '';
        document.getElementById('edit_tgl_jatuh_tempo').value = data.tgl_jatuh_tempo || '';
        document.getElementById('edit_dpp').value = data.dpp ? Number(data.dpp).toLocaleString('id-ID') : '';
        document.getElementById('edit_tarif').value = data.tarif_persen || 0;
        document.getElementById('edit_nominal_pajak').value = Number(data.nominal_pajak || 0).toLocaleString('id-ID');
        document.getElementById('edit_status').value = ['Belum Dibayar', 'Menunggu Pembayaran', 'Lunas', 'Terlambat'].includes(data.status) ? data.status : 'Belum Dibayar';
        document.getElementById('edit_tgl_bayar').value = data.tgl_bayar || '';
        document.getElementById('edit_ntpn').value = data.ntpn || '';
        document.getElementById('edit_keterangan').value = data.keterangan || '';
        if (data.opsi_lebih_bayar) {
            document.getElementById('edit_opsi_lebih_bayar').value = data.opsi_lebih_bayar;
        }

        const r = data.rincian || {};
        setFormattedVal('edit_ia2_harga_jual', Number(r.ia2_harga_jual || 0));
        setFormattedVal('edit_ia2_dpp', Number(r.ia2_dpp || 0));
        setFormattedVal('edit_ia2_ppn', Number(r.ia2_ppn || 0));
        setFormattedVal('edit_ia4_harga_jual', Number(r.ia4_harga_jual || 0));
        setFormattedVal('edit_ia4_dpp', Number(r.ia4_dpp || 0));
        setFormattedVal('edit_ia4_ppn', Number(r.ia4_ppn || 0));
        setFormattedVal('edit_ia6_harga_jual', Number(r.ia6_harga_jual || 0));
        setFormattedVal('edit_ia6_dpp', Number(r.ia6_dpp || 0));
        setFormattedVal('edit_ia6_ppn', Number(r.ia6_ppn || 0));
        setFormattedVal('edit_ib_tidak_terutang', Number(r.ib_tidak_terutang || 0));

        setFormattedVal('edit_iib_harga', Number(r.iib_harga || 0));
        setFormattedVal('edit_iib_dpp', Number(r.iib_dpp || 0));
        setFormattedVal('edit_iib_ppn', Number(r.iib_ppn || 0));
        setFormattedVal('edit_iic_harga', Number(r.iic_harga || 0));
        setFormattedVal('edit_iic_dpp', Number(r.iic_dpp || 0));
        setFormattedVal('edit_iic_ppn', Number(r.iic_ppn || 0));
        setFormattedVal('edit_iie_kompensasi', Number(r.iie_kompensasi || 0));
        setFormattedVal('edit_iih_non_kredit', Number(r.iih_non_kredit || 0));
        setFormattedVal('edit_iiib_disetor_dimuka', Number(r.iiib_disetor_dimuka || 0));

        handleJenisPajakMode('edit');

        const modal = new bootstrap.Modal(document.getElementById('modalEditSpt'));
        modal.show();
    }

    // =========================================================================
    // SPT TABLE ACTION HAMBURGER DROPDOWN CONTROLLER
    // =========================================================================
    function toggleSptActionMenu(btn, event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const container = btn.closest('.spt-action-dropdown');
        if (!container) return;
        const menu = container.querySelector('.spt-dropdown-menu');
        if (!menu) return;

        const isAlreadyOpen = menu.classList.contains('show');

        // Close any currently open menus
        closeAllSptActionMenus();

        if (!isAlreadyOpen) {
            menu.classList.add('show');
            btn.classList.add('active');
            btn.setAttribute('aria-expanded', 'true');

            // Floating viewport positioning (immune to table overflow / card clipping)
            const rect = btn.getBoundingClientRect();
            menu.style.position = 'fixed';
            menu.style.right = (window.innerWidth - rect.right) + 'px';
            menu.style.left = 'auto';
            menu.style.zIndex = '99999';

            const menuHeight = menu.offsetHeight || 190;
            const spaceBelow = window.innerHeight - rect.bottom;

            if (spaceBelow < menuHeight && rect.top > menuHeight) {
                menu.style.top = Math.max(10, rect.top - menuHeight - 4) + 'px';
                menu.style.bottom = 'auto';
            } else {
                menu.style.top = (rect.bottom + 4) + 'px';
                menu.style.bottom = 'auto';
            }
        }
    }

    function closeAllSptActionMenus() {
        document.querySelectorAll('.spt-dropdown-menu.show').forEach(function(m) {
            m.classList.remove('show');
            m.style.position = '';
            m.style.top = '';
            m.style.bottom = '';
            m.style.right = '';
            m.style.left = '';
            m.style.zIndex = '';
        });
        document.querySelectorAll('.btn-spt-action-menu.active').forEach(function(b) {
            b.classList.remove('active');
            b.setAttribute('aria-expanded', 'false');
        });
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.spt-action-dropdown') && !e.target.closest('.spt-dropdown-menu')) {
            closeAllSptActionMenus();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllSptActionMenus();
        }
    });

    window.addEventListener('scroll', closeAllSptActionMenus, true);
    window.addEventListener('resize', closeAllSptActionMenus);

    document.addEventListener('DOMContentLoaded', function() {
        handleJenisPajakMode('add');

        // Drag & Drop untuk Bukti Bayar Dropzones
        document.querySelectorAll('.spt-dropzone').forEach(function(zone) {
            const fileInput = zone.querySelector('input[type="file"]');
            if (!fileInput) return;

            ['dragenter', 'dragover'].forEach(function(evtName) {
                zone.addEventListener(evtName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.add('is-dragover');
                });
            });

            ['dragleave', 'drop'].forEach(function(evtName) {
                zone.addEventListener(evtName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    zone.classList.remove('is-dragover');
                });
            });

            zone.addEventListener('drop', function(e) {
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        });

        // Drag & Drop untuk Card CoreTax PDF Auto-Fill
        document.querySelectorAll('.spt-pdf-upload-card').forEach(function(card) {
            const isAdd = card.id && card.id.indexOf('add') !== -1;
            const prefix = isAdd ? 'add' : 'edit';
            const fileInput = document.getElementById(`${prefix}_spt_pdf_autofill_input`);

            ['dragenter', 'dragover'].forEach(function(evtName) {
                card.addEventListener(evtName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    card.style.borderColor = '#10B981';
                    card.style.background = 'rgba(16, 185, 129, 0.12)';
                });
            });

            ['dragleave', 'drop'].forEach(function(evtName) {
                card.addEventListener(evtName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    card.style.borderColor = 'rgba(99, 102, 241, 0.45)';
                    card.style.background = 'linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(56, 189, 248, 0.08) 100%)';
                });
            });

            card.addEventListener('drop', function(e) {
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if (fileInput) {
                        fileInput.files = e.dataTransfer.files;
                    }
                    if (typeof handleCoreTaxPdfFile === 'function') {
                        handleCoreTaxPdfFile(file, prefix, true);
                    }
                }
            });
        });
    });
</script>
@endsection
