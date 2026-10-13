@extends('layout.template')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<style>
    /* ==========================================================================
       DESIGN.md §12.4 — KPI STATISTIC CARDS
       Neutral dark surface #161B22, border #282E37, tonal icon box, tabular numbers
       ========================================================================== */
    .kpi-card {
        background: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        margin-bottom: 16px;
        transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        border-color: #383E45 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }
    .kpi-card__body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 125px;
    }
    .kpi-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .kpi-card__overline {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94A3B8;
        line-height: 16px;
        margin: 0;
    }
    .kpi-card__icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background-color: var(--kpi-accent-subtle, rgba(99, 102, 241, 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--kpi-accent, #818CF8);
        font-size: 21px;
        border: 1px solid var(--kpi-accent-border, rgba(99, 102, 241, 0.25));
    }
    .kpi-card__value {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
        color: #F8F9FA;
        margin: 0;
        font-variant-numeric: tabular-nums;
    }
    .kpi-card__footer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 10px;
        border-top: 1px solid #232A35;
    }
    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 22px;
        padding: 0 8px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid var(--kpi-accent-border, rgba(99, 102, 241, 0.28));
        background-color: var(--kpi-accent-subtle, rgba(99, 102, 241, 0.12));
        color: var(--kpi-accent-text, #A5B4FC);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .kpi-card__label {
        font-size: 12.5px;
        font-weight: 400;
        color: #94A3B8;
        margin: 0;
    }

    /* KPI Semantics */
    .kpi-card--brand {
        --kpi-accent: #6366F1;
        --kpi-accent-subtle: rgba(99, 102, 241, 0.12);
        --kpi-accent-border: rgba(99, 102, 241, 0.3);
        --kpi-accent-text: #A5B4FC;
    }
    .kpi-card--success {
        --kpi-accent: #10B981;
        --kpi-accent-subtle: rgba(16, 185, 129, 0.12);
        --kpi-accent-border: rgba(16, 185, 129, 0.3);
        --kpi-accent-text: #6EE7B7;
    }
    .kpi-card--danger {
        --kpi-accent: #EF4444;
        --kpi-accent-subtle: rgba(239, 68, 68, 0.12);
        --kpi-accent-border: rgba(239, 68, 68, 0.3);
        --kpi-accent-text: #FCA5A5;
    }

    /* Primary Button: Tambah SPB */
    .btn-create-spb {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        height: 38px !important;
        padding: 0 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.35) !important;
        transition: all 150ms ease !important;
        text-decoration: none !important;
    }
    .btn-create-spb:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        color: #FFFFFF !important;
    }

    /* Table Container & Table Styling (DESIGN.md §13) */
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
        width: 100% !important;
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
        padding: 12px 16px !important;
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
        padding: 13px 16px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #CBD5E1 !important;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Badges (DESIGN.md §14) */
    .badge-spb-selesai {
        display: inline-flex;
        align-items: center;
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-spb-pending {
        display: inline-flex;
        align-items: center;
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #FCA5A5 !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-perusahaan {
        display: inline-flex;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #E2E8F0 !important;
        border: 1px solid #334155 !important;
        font-weight: 500 !important;
        padding: 3px 8px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
    }
    .cell-perusahaan {
        color: #F1F5F9 !important;
        font-weight: 500 !important;
    }
    .cell-phone {
        color: #E2E8F0 !important;
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
    }
    .cell-date {
        color: #CBD5E1 !important;
        font-size: 13px;
    }
    .cell-customer {
        color: #FFFFFF !important;
        font-weight: 600 !important;
    }
    .num-tabular {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
    }

    /* Action button & Dropdown */
    .btn-action-more {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 8px !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 150ms ease !important;
    }
    .btn-action-more:hover {
        background-color: #2D333B !important;
        border-color: #475569 !important;
        color: #FFFFFF !important;
    }
    .dropdown-menu-custom {
        background-color: #181E26 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        padding: 6px !important;
        min-width: 175px !important;
        z-index: 1050 !important;
    }
    .dropdown-menu-custom .dropdown-item {
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        display: flex !important;
        align-items: center !important;
        transition: background-color 120ms ease !important;
    }
    .dropdown-menu-custom .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #FFFFFF !important;
    }
    .dropdown-menu-custom .dropdown-divider {
        border-top: 1px solid #282E37 !important;
        margin: 4px 0 !important;
    }

    /* DataTables Dark Mode Customization */
    .dataTables_wrapper {
        padding: 14px 18px !important;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info {
        color: #94A3B8 !important;
        font-size: 13px !important;
    }
    .dataTables_wrapper .dataTables_length select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
        font-size: 13px !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        margin-left: 8px !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6366F1 !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
    }
    .dataTables_wrapper .pagination .page-item .page-link {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-size: 13px !important;
        border-radius: 6px !important;
        margin: 0 2px !important;
    }
    .dataTables_wrapper .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }
    .dataTables_wrapper .pagination .page-item.active .page-link {
        background-color: #6366F1 !important;
        border-color: #6366F1 !important;
        color: #FFFFFF !important;
    }
    .dataTables_wrapper .pagination .page-item.disabled .page-link {
        opacity: 0.35 !important;
        background-color: transparent !important;
    }

    /* Modal Konfirmasi Hapus (DESIGN.md §15.3) */
    .modal-custom-dark {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
    }
    .modal-icon-danger-box {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background-color: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-modal-cancel {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
        padding: 8px 18px !important;
        font-size: 13.5px !important;
    }
    .btn-modal-cancel:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
    }
    .btn-modal-delete {
        background-color: #EF4444 !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        padding: 8px 18px !important;
        font-size: 13.5px !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35) !important;
    }
    .btn-modal-delete:hover {
        background-color: #DC2626 !important;
        color: #FFFFFF !important;
    }
</style>
@endsection

@section('content')
{{-- Modal Konfirmasi Hapus (DESIGN.md §15.3) --}}
<div class="modal fade" tabindex="-1" role="dialog" id="deleteModal" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content modal-custom-dark">
            <div class="modal-body text-center p-4">
                <input type="hidden" name="spb_delete_id" id="spb_id">
                <div class="modal-icon-danger-box mx-auto mb-3">
                    <i class="ri-delete-bin-line fs-28 text-danger"></i>
                </div>
                <h5 class="fs-18 fw-semibold text-white mb-2">Hapus Data SPB?</h5>
                <p class="text-muted fs-13 mb-4">Data surat pengantar barang ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" action="{{ url('delete-spb') }}" method="POST" class="m-0">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-modal-delete">
                            <i class="ri-delete-bin-line fs-16 me-1"></i>Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid">
        {{-- Header Halaman & Tombol Tambah SPB --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Daftar SPB</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Surat Pengantar Barang</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Daftar pengiriman barang dan status surat pengantar</p>
                    </div>
                    <div>
                        <a href="{{ url('tambah-spb') }}" class="btn-create-spb">
                            <i class="ri-add-line fs-18"></i>Tambah SPB
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI Statistic Cards (DESIGN.md §12.4) --}}
        <div class="row g-3 mb-4">
            {{-- Card 1: SPB Dibuat --}}
            <div class="col-xl-4 col-md-6">
                <div class="kpi-card kpi-card--brand">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <p class="kpi-card__overline">SPB Dibuat</p>
                            <div class="kpi-card__icon" aria-hidden="true">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                        </div>
                        <p class="kpi-card__value"><span class="counter-value" data-target="{{$jumlahspb}}">{{$jumlahspb}}</span></p>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">{{$jumlahspb}} SPB</span>
                            <p class="kpi-card__label">Total SPB Tercatat</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: SPB Telah Diantar --}}
            <div class="col-xl-4 col-md-6">
                <div class="kpi-card kpi-card--success">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <p class="kpi-card__overline">SPB Telah Diantar</p>
                            <div class="kpi-card__icon" aria-hidden="true">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                        </div>
                        <p class="kpi-card__value"><span class="counter-value" data-target="{{$jumlahSelesai}}">{{$jumlahSelesai}}</span></p>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">{{$jumlahSelesai}} SPB</span>
                            <p class="kpi-card__label">Selesai Terkirim</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: SPB Belum Diantar --}}
            <div class="col-xl-4 col-md-6">
                <div class="kpi-card kpi-card--danger">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <p class="kpi-card__overline">SPB Belum Diantar</p>
                            <div class="kpi-card__icon" aria-hidden="true">
                                <i class="ri-time-line"></i>
                            </div>
                        </div>
                        <p class="kpi-card__value"><span class="counter-value" data-target="{{$jumlahBelumSelesai}}">{{$jumlahBelumSelesai}}</span></p>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">{{$jumlahBelumSelesai}} SPB</span>
                            <p class="kpi-card__label">Menunggu Pengantaran</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Container Card (DESIGN.md §13) --}}
        <div class="row">
            <div class="col-12">
                <div class="table-container-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-truck-line text-primary fs-18"></i>
                            <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Riwayat Surat Pengantar Barang (SPB)</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle mb-0" id="alternative-pagination">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 50px;" class="text-center">No.</th>
                                        <th scope="col">Pengantar Barang</th>
                                        <th scope="col">Tanggal Antar</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Perusahaan</th>
                                        <th scope="col" style="white-space: nowrap;">No. Telepon</th>
                                        <th scope="col" style="white-space: nowrap;">Status</th>
                                        <th scope="col" class="text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($spb as $value)
                                    <tr>
                                        <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge-perusahaan">
                                                @if($value->nama_spb == 1)
                                                    Ikhtiar Berkah
                                                @else
                                                    Total Karya Berkah
                                                @endif
                                            </span>
                                        </td>
                                        <td><span class="cell-date">{{ $value->created_at->isoFormat('DD MMMM YYYY') }}</span></td>
                                        <td><span class="cell-customer">{{ $value->customer }}</span></td>
                                        <td><span class="cell-perusahaan">{{ $value->perusahaan }}</span></td>
                                        <td style="white-space: nowrap;"><span class="cell-phone">{{ $value->nomor_telepon }}</span></td>
                                        <td style="white-space: nowrap;">
                                            @if ($value->status == "Belum diantar")
                                                <span class="badge-spb-pending">
                                                    <i class="ri-time-line me-1"></i>Belum Diantar
                                                </span>
                                            @else
                                                <span class="badge-spb-selesai">
                                                    <i class="ri-checkbox-circle-line me-1"></i>Sudah Diantar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                                    <i class="ri-more-fill fs-16"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('spb.ubah-status', $value->id) }}">
                                                            <i class="ri-check-double-line text-success fs-15 me-2"></i>Sudah Diantar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" target="_blank" href="{{ url('cetak-spb/'.$value->id) }}">
                                                            <i class="ri-printer-line text-info fs-15 me-2"></i>Cetak SPB
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ url('edit-spb/'.$value->id) }}">
                                                            <i class="ri-edit-line text-warning fs-15 me-2"></i>Edit SPB
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" value="{{ $value->id }}" data-url="{{ url('delete-spb/'.$value->id) }}" class="dropdown-item text-danger hapus-btn">
                                                            <i class="ri-delete-bin-line fs-15 me-2 text-danger"></i>Hapus
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('plugins')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('js/halaman/spb.js')}}"></script>
@endsection