@extends('layout.template')

@section('head')
<style>
    /* ==========================================================================
       MANAJEMEN ITEM STYLESHEET — DESIGN.md
       - §4 Mode Gelap & Canvas Surface (#161B22)
       - §9 Button (Brand Primary, Danger Tonal)
       - §10 Form & Input
       - §13 Tabel
       - §14 Badge Status
       - §15.3 Modal Konfirmasi & Pop-up
       ========================================================================== */

    /* Card styling */
    .card-item-manage {
        background-color: var(--bg-surface, #161B22) !important;
        border: 1px solid var(--border-subtle, #282E37) !important;
        border-radius: var(--radius-lg, 12px) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25) !important;
        overflow: hidden;
    }

    .card-item-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 18px 24px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-item-header .card-title {
        font-size: 15px;
        font-weight: 600;
        color: #F8F9FA !important;
        letter-spacing: -0.01em;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-item-header .card-title i {
        color: var(--brand, #6366F1);
        font-size: 18px;
    }

    /* Badges */
    .badge-count-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 24px;
        padding: 0 10px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 9999px;
        background-color: rgba(99, 102, 241, 0.12);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.3);
        font-variant-numeric: tabular-nums;
    }

    /* Header Create Button */
    .btn-create-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 18px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }

    .btn-create-item:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45);
        color: #FFFFFF !important;
    }

    /* Modal Styling (DESIGN.md §15.3) */
    .modal-custom-item {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
        overflow: hidden;
    }

    .modal-custom-item .modal-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 18px 24px !important;
    }

    .modal-custom-item .modal-body {
        background-color: #161B22 !important;
        padding: 24px !important;
    }

    .modal-custom-item .modal-footer {
        background-color: #181E26 !important;
        border-top: 1px solid #282E37 !important;
        padding: 16px 24px !important;
    }

    .modal-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background-color: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Form Inputs */
    .item-form-label {
        font-size: 11.5px !important;
        font-weight: 600 !important;
        color: #94A3B8 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px;
        display: block;
    }

    .item-input-text {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        font-size: 14px !important;
        height: 42px;
        padding: 0 14px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease, background-color 150ms ease !important;
    }

    .item-input-text::placeholder {
        color: #64748B !important;
    }

    .item-input-text:hover:not(:disabled) {
        border-color: #4E5661 !important;
    }

    .item-input-text:focus {
        outline: none !important;
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
        background-color: #262B32 !important;
        color: #FFFFFF !important;
    }

    /* Radio Tile Cards */
    .tax-radio-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .tax-radio-card {
        position: relative;
        display: flex;
        align-items: center;
        padding: 11px 14px;
        border-radius: 8px;
        border: 1px solid #2B3037;
        background-color: #181C22;
        cursor: pointer;
        transition: all 140ms ease;
        margin-bottom: 0;
        user-select: none;
    }

    .tax-radio-card:hover {
        background-color: #1E232B;
        border-color: #383E45;
    }

    .tax-radio-card input[type="radio"] {
        accent-color: #6366F1;
        width: 17px;
        height: 17px;
        margin: 0;
        cursor: pointer;
        flex-shrink: 0;
    }

    .tax-radio-card.active,
    .tax-radio-card:has(input[type="radio"]:checked) {
        background-color: rgba(99, 102, 241, 0.08);
        border-color: #6366F1;
        box-shadow: 0 0 0 1px #6366F1;
    }

    .tax-radio-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-left: 12px;
        cursor: pointer;
        margin-bottom: 0;
    }

    .tax-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #F8F9FA;
    }

    .tax-badge-desc {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 9999px;
        line-height: 1;
    }

    .tax-badge--ppn {
        background-color: rgba(59, 130, 246, 0.12);
        color: #93C5FD;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .tax-badge--pph {
        background-color: rgba(245, 158, 11, 0.12);
        color: #FCD34D;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .tax-badge--ppn_pph {
        background-color: rgba(99, 102, 241, 0.12);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .tax-badge--tanpa_pajak {
        background-color: rgba(148, 163, 184, 0.1);
        color: #CBD5E1;
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    /* Modal Buttons */
    .btn-submit-item {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        height: 38px !important;
        padding: 0 20px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
        transition: all 120ms ease !important;
        cursor: pointer;
    }

    .btn-submit-item:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
    }

    .btn-modal-cancel {
        display: inline-flex;
        align-items: center;
        background-color: transparent !important;
        border: 1px solid #383E45 !important;
        color: #94A3B8 !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        height: 38px !important;
        padding: 0 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
    }

    .btn-modal-cancel:hover {
        background-color: #212529 !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }

    /* Table Item Styling (DESIGN.md §13) */
    .item-table-wrapper {
        border: 1px solid #282E37;
        border-radius: 8px;
        overflow: hidden;
    }

    .item-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .item-table thead tr th {
        background-color: #181E26 !important;
        color: var(--text-tertiary, #94A3B8) !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 13px 18px !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        vertical-align: middle;
        white-space: nowrap;
    }

    .item-table tbody tr {
        border-bottom: 1px solid #21262D !important;
        transition: background-color 120ms ease;
    }

    .item-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.025) !important;
    }

    .item-table tbody tr td {
        padding: 13px 18px !important;
        border-bottom: 1px solid #21262D !important;
        color: #CBD5E1;
        vertical-align: middle;
        background-color: transparent !important;
        font-size: 13.5px;
    }

    .item-table tbody tr:last-child td {
        border-bottom: none !important;
    }

    .item-table .col-no {
        color: #878A99;
        font-size: 13px;
        font-variant-numeric: tabular-nums;
        font-weight: 500;
    }

    .item-table .col-name {
        color: #F8F9FA !important;
        font-weight: 600;
        font-size: 14px;
    }

    /* Table Status Badge (DESIGN.md §14.1 Pill + Dot) */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 24px;
        padding: 0 10px;
        font-size: 12px;
        font-weight: 500;
        border-radius: 9999px;
        line-height: 1;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
        flex-shrink: 0;
    }

    .status-badge--info {
        color: #93C5FD;
        background-color: rgba(59, 130, 246, 0.12);
        border-color: rgba(59, 130, 246, 0.35);
    }

    .status-badge--warning {
        color: #FCD34D;
        background-color: rgba(245, 158, 11, 0.12);
        border-color: rgba(245, 158, 11, 0.35);
    }

    .status-badge--brand {
        color: #A5B4FC;
        background-color: rgba(99, 102, 241, 0.12);
        border-color: rgba(99, 102, 241, 0.35);
    }

    .status-badge--neutral {
        color: #CBD5E1;
        background-color: rgba(148, 163, 184, 0.1);
        border-color: rgba(148, 163, 184, 0.25);
    }

    /* Action Buttons (DESIGN.md §9.2) */
    .btn-edit-row {
        background-color: rgba(99, 102, 241, 0.12) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        border-radius: 6px !important;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 15px;
        transition: all 120ms ease !important;
        cursor: pointer;
    }

    .btn-edit-row:hover {
        background-color: rgba(99, 102, 241, 0.25) !important;
        color: #FFFFFF !important;
        border-color: #6366F1 !important;
        transform: translateY(-1px);
    }

    .btn-edit-row:active {
        transform: scale(0.96);
    }

    .btn-delete-row {
        background-color: var(--danger-subtle, rgba(239, 68, 68, 0.12)) !important;
        color: var(--danger-text, #FCA5A5) !important;
        border: 1px solid var(--danger-border, rgba(239, 68, 68, 0.3)) !important;
        border-radius: 6px !important;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-size: 15px;
        transition: all 120ms ease !important;
        cursor: pointer;
    }

    .btn-delete-row:hover {
        background-color: rgba(239, 68, 68, 0.25) !important;
        color: #FFFFFF !important;
        border-color: rgba(239, 68, 68, 0.6) !important;
        transform: translateY(-1px);
    }

    .btn-delete-row:active {
        transform: scale(0.96);
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Manajemen Item</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Invoice Item</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Kelola master item pekerjaan dan ketentuan pajak untuk transaksi invoice</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-create-item" data-bs-toggle="modal" data-bs-target="#modalTambahItem">
                            <i class="ri-add-line fs-18"></i> Tambah Item Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: rgba(16, 185, 129, 0.12); border-color: rgba(16, 185, 129, 0.3); color: #6EE7B7;">
                <i class="ri-checkbox-circle-line me-1 align-middle fs-16"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background-color: rgba(239, 68, 68, 0.12); border-color: rgba(239, 68, 68, 0.3); color: #FCA5A5;">
                <i class="ri-error-warning-line me-1 align-middle fs-16"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Tabel Daftar Item (Full Width) -->
            <div class="col-12">
                <div class="card card-item-manage">
                    <div class="card-item-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-list-check-2 text-primary fs-18"></i>
                            <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Daftar Item</h5>
                        </div>
                        <span class="badge-count-pill">{{ count($items) }} Total Item</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive item-table-wrapper">
                            <table class="item-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">No</th>
                                        <th>Nama Item</th>
                                        <th style="width: 200px;">Jenis Pajak</th>
                                        <th style="width: 100px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $index => $item)
                                        <tr>
                                            <td class="col-no text-center">{{ $index + 1 }}</td>
                                            <td class="col-name">{{ $item->nama_item }}</td>
                                            <td>
                                                @if ($item->jenis_pajak == 'ppn')
                                                    <span class="status-badge status-badge--info">PPN</span>
                                                @elseif ($item->jenis_pajak == 'pph')
                                                    <span class="status-badge status-badge--warning">PPh</span>
                                                @elseif ($item->jenis_pajak == 'ppn_pph')
                                                    <span class="status-badge status-badge--brand">PPN & PPh</span>
                                                @else
                                                    <span class="status-badge status-badge--neutral">Tanpa Pajak</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <button type="button" 
                                                            class="btn-edit-row btn-trigger-edit" 
                                                            data-id="{{ $item->id }}" 
                                                            data-name="{{ $item->nama_item }}" 
                                                            data-pajak="{{ $item->jenis_pajak }}" 
                                                            data-action="{{ route('item.update', $item->id) }}"
                                                            title="Edit Item">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <form id="form-delete-{{ $item->id }}" action="{{ route('item.destroy', $item->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn-delete-row btn-confirm-delete" data-id="{{ $item->id }}" data-name="{{ $item->nama_item }}" title="Hapus Item">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="ri-inbox-archive-line fs-36 d-block text-secondary mb-2 opacity-50"></i>
                                                Belum ada item yang terdaftar. Klik tombol <strong>Tambah Item Baru</strong> di atas untuk menambahkan item.
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
    </div>
</div>

{{-- Modal Tambah Item (Pop-Up) --}}
<div class="modal fade" id="modalTambahItem" tabindex="-1" aria-labelledby="modalTambahItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content modal-custom-item">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-icon-badge">
                        <i class="ri-add-circle-line fs-18 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fs-16 fw-semibold text-white mb-0" id="modalTambahItemLabel">Tambah Item Baru</h5>
                        <p class="mb-0 text-muted fs-12">Daftarkan nama item/pekerjaan beserta ketentuan pajaknya</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('item.store') }}" method="POST" id="formTambahItem">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama-item" class="item-form-label">Nama Item <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control item-input-text @error('nama_item') is-invalid @enderror"
                            id="nama-item"
                            name="nama_item"
                            placeholder="Masukkan nama item/pekerjaan"
                            value="{{ old('nama_item') }}"
                            maxlength="255"
                            required
                        >
                        @error('nama_item')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label class="item-form-label">Pilihan Pajak <span class="text-danger">*</span></label>
                        @php
                            $taxOptions = [
                                'ppn' => ['label' => 'PPN', 'desc' => 'Pajak Pertambahan Nilai (11%)', 'badgeClass' => 'tax-badge--ppn'],
                                'pph' => ['label' => 'PPh', 'desc' => 'Pajak Penghasilan (2%)', 'badgeClass' => 'tax-badge--pph'],
                                'ppn_pph' => ['label' => 'PPN dan PPh', 'desc' => 'Dikenakan PPN (11%) & PPh (2%)', 'badgeClass' => 'tax-badge--ppn_pph'],
                                'tanpa_pajak' => ['label' => 'Tanpa Pajak', 'desc' => 'Tidak dikenakan pajak (0%)', 'badgeClass' => 'tax-badge--tanpa_pajak'],
                            ];
                        @endphp

                        <div class="tax-radio-group">
                            @foreach ($taxOptions as $value => $meta)
                                <label class="tax-radio-card {{ old('jenis_pajak', 'tanpa_pajak') === $value ? 'active' : '' }}" for="jenis-pajak-{{ $value }}">
                                    <input
                                        type="radio"
                                        name="jenis_pajak"
                                        id="jenis-pajak-{{ $value }}"
                                        value="{{ $value }}"
                                        {{ old('jenis_pajak', 'tanpa_pajak') === $value ? 'checked' : '' }}
                                        required
                                    >
                                    <div class="tax-radio-label">
                                        <span class="tax-title">{{ $meta['label'] }}</span>
                                        <span class="tax-badge-desc {{ $meta['badgeClass'] }}">{{ $meta['desc'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('jenis_pajak')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-end gap-2 p-3">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-submit-item">
                        <i class="ri-save-3-line fs-16"></i> Simpan Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Item (Pop-Up) --}}
<div class="modal fade" id="modalEditItem" tabindex="-1" aria-labelledby="modalEditItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content modal-custom-item">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-icon-badge">
                        <i class="ri-edit-line fs-18 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fs-16 fw-semibold text-white mb-0" id="modalEditItemLabel">Edit Item</h5>
                        <p class="mb-0 text-muted fs-12">Perbarui nama item/pekerjaan atau ketentuan pajaknya</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="formEditItem">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit-nama-item" class="item-form-label">Nama Item <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control item-input-text"
                            id="edit-nama-item"
                            name="nama_item"
                            placeholder="Masukkan nama item/pekerjaan"
                            maxlength="255"
                            required
                        >
                    </div>

                    <div class="mb-2">
                        <label class="item-form-label">Pilihan Pajak <span class="text-danger">*</span></label>
                        <div class="tax-radio-group">
                            @foreach ($taxOptions as $value => $meta)
                                <label class="tax-radio-card" for="edit-jenis-pajak-{{ $value }}" id="card-edit-pajak-{{ $value }}">
                                    <input
                                        type="radio"
                                        name="jenis_pajak"
                                        id="edit-jenis-pajak-{{ $value }}"
                                        value="{{ $value }}"
                                        required
                                    >
                                    <div class="tax-radio-label">
                                        <span class="tax-title">{{ $meta['label'] }}</span>
                                        <span class="tax-badge-desc {{ $meta['badgeClass'] }}">{{ $meta['desc'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-end gap-2 p-3">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-submit-item">
                        <i class="ri-save-3-line fs-16"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Interaktivitas visual radio card tax di Modal Tambah & Edit
    document.querySelectorAll('.tax-radio-card input[type="radio"]').forEach(input => {
        input.addEventListener('change', function () {
            const container = this.closest('.tax-radio-group');
            if (container) {
                container.querySelectorAll('.tax-radio-card').forEach(card => card.classList.remove('active'));
            }
            if (this.checked) {
                this.closest('.tax-radio-card')?.classList.add('active');
            }
        });
    });

    // Handler tombol Edit (Pop-Up Modal Edit)
    const editButtons = document.querySelectorAll('.btn-trigger-edit');
    const formEdit = document.getElementById('formEditItem');
    const editNamaInput = document.getElementById('edit-nama-item');
    const modalEditEl = document.getElementById('modalEditItem');
    const modalEdit = modalEditEl ? new bootstrap.Modal(modalEditEl) : null;

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const pajak = this.getAttribute('data-pajak');
            const actionUrl = this.getAttribute('data-action');

            if (formEdit && editNamaInput) {
                formEdit.setAttribute('action', actionUrl);
                editNamaInput.value = name;

                document.querySelectorAll('#modalEditItem input[name="jenis_pajak"]').forEach(radio => {
                    radio.checked = (radio.value === pajak);
                });

                document.querySelectorAll('#modalEditItem .tax-radio-card').forEach(card => {
                    card.classList.remove('active');
                });

                const activeCard = document.getElementById(`card-edit-pajak-${pajak}`);
                if (activeCard) {
                    activeCard.classList.add('active');
                }

                if (modalEdit) {
                    modalEdit.show();
                }
            }
        });
    });

    // Auto-open modal tambah jika terdapat error validasi
    @if($errors->any())
        var modalEl = document.getElementById('modalTambahItem');
        if (modalEl) {
            var modalTambah = new bootstrap.Modal(modalEl);
            modalTambah.show();
        }
    @endif

    // Konfirmasi hapus dengan SweetAlert2 Dark Theme
    const deleteButtons = document.querySelectorAll('.btn-confirm-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const itemId = this.getAttribute('data-id');
            const itemName = this.getAttribute('data-name');
            const targetForm = document.getElementById(`form-delete-${itemId}`);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Item?',
                    text: `Apakah Anda yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#212529',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        targetForm.submit();
                    }
                });
            } else {
                if (confirm(`Apakah Anda yakin ingin menghapus item "${itemName}"?`)) {
                    targetForm.submit();
                }
            }
        });
    });
});
</script>
@endsection
