@extends('layout.template')

@section('head')
<style>
    /* ==========================================================================
       PEMBELIAN VENDOR STYLESHEET — DESIGN.md
       ========================================================================== */

    /* Top Tambah Vendor Button */
    .btn-add-vendor {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        height: 38px !important;
        padding: 0 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
        transition: all 120ms ease !important;
        cursor: pointer;
    }
    .btn-add-vendor:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35) !important;
    }
    .btn-add-vendor:active {
        background-color: var(--brand-active, #4338CA) !important;
        transform: scale(0.985);
    }

    /* KPI Cards (DESIGN.md §12.4) */
    .metric-card {
        background-color: var(--bg-surface, #12161D) !important;
        border: 1px solid var(--border-subtle, #232A35) !important;
        border-radius: 12px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
        transition: transform 150ms ease, box-shadow 150ms ease, border-color 150ms ease !important;
    }
    .metric-card:hover {
        transform: translateY(-2px) !important;
        border-color: #383E45 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4) !important;
    }
    .metric-overline {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94A3B8;
        margin-bottom: 4px;
    }
    .metric-value {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 0;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
    }
    .metric-icon-box {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        flex-shrink: 0;
        font-size: 20px;
    }

    /* Main Card */
    .card-vendor-main {
        background-color: var(--bg-surface, #12161D) !important;
        border: 1px solid var(--border-subtle, #232A35) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35) !important;
        overflow: hidden;
    }
    .card-vendor-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 14px 20px !important;
    }
    .card-vendor-title {
        font-size: 15px;
        font-weight: 600;
        color: #F8F9FA !important;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-vendor-title i {
        color: var(--brand, #6366F1);
        font-size: 18px;
    }

    /* Badge Count Pill */
    .badge-count-pill {
        display: inline-flex;
        align-items: center;
        height: 24px;
        padding: 0 10px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 9999px;
        background-color: rgba(99, 102, 241, 0.12);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.3);
        font-variant-numeric: tabular-nums;
    }

    /* Filter Controls */
    .vendor-filter-input,
    .vendor-filter-select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        font-size: 13px !important;
        height: 34px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease !important;
    }
    .vendor-filter-input:focus,
    .vendor-filter-select:focus {
        outline: none !important;
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.28) !important;
        background-color: #272C32 !important;
        color: #FFFFFF !important;
    }
    .vendor-filter-input::placeholder {
        color: #878A99 !important;
    }
    .btn-reset-filter {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #94A3B8 !important;
        height: 34px;
        width: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 120ms ease;
    }
    .btn-reset-filter:hover {
        background-color: #2A3037 !important;
        color: #FFFFFF !important;
        border-color: #4A525D !important;
    }

    /* Table Custom (DESIGN.md §13) */
    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-custom thead th {
        background-color: #1A1E24 !important;
        color: #94A3B8 !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        font-weight: 600 !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        padding: 12px 16px !important;
    }
    .table-custom tbody tr {
        transition: background-color 120ms ease;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.025) !important;
    }
    .table-custom tbody tr td {
        padding: 12px 16px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        vertical-align: middle;
        font-size: 14px;
    }
    .table-custom .row-number {
        color: #878A99;
        font-size: 13px;
        font-variant-numeric: tabular-nums;
        font-weight: 500;
    }
    .table-custom .vendor-name {
        color: #F8F9FA !important;
        font-weight: 600;
    }

    /* Action Dropdown Button in Table */
    .btn-action-more {
        background-color: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 120ms ease;
    }
    .btn-action-more:hover,
    .btn-action-more[aria-expanded="true"] {
        background-color: #2A3037 !important;
        border-color: #4A525D !important;
        color: #FFFFFF !important;
    }

    /* Dropdown Menus */
    .dropdown-menu {
        background-color: #161B22 !important;
        border: 1px solid #2D333B !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
        padding: 6px !important;
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

    /* Modals Styling (DESIGN.md §15.3) */
    .modal-vendor-custom .modal-content {
        background-color: #161B22 !important;
        border: 1px solid #2D333B !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
        overflow: hidden;
    }
    .modal-vendor-custom .modal-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 16px 20px !important;
    }
    .modal-vendor-custom .modal-title {
        color: #F8F9FA !important;
        font-size: 16px !important;
        font-weight: 600 !important;
    }
    .modal-vendor-custom .modal-body {
        padding: 20px !important;
    }
    .modal-vendor-custom .modal-body label {
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #CBD5E1 !important;
        margin-bottom: 6px;
    }
    .modal-vendor-custom .form-control {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        font-size: 14px !important;
        height: 42px;
        padding: 0 14px;
        transition: border-color 150ms ease, box-shadow 150ms ease !important;
    }
    .modal-vendor-custom .form-control:focus {
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.28) !important;
        background-color: #272C32 !important;
        color: #FFFFFF !important;
    }
    .modal-vendor-custom .modal-footer {
        background-color: #161B22 !important;
        border-top: 1px solid #232A35 !important;
        padding: 14px 20px !important;
        gap: 10px;
    }
    .btn-cancel-modal {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 8px !important;
        height: 38px !important;
        padding: 0 18px !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        transition: all 120ms ease;
    }
    .btn-cancel-modal:hover {
        background-color: #2A3037 !important;
        color: #FFFFFF !important;
        border-color: #4A525D !important;
    }
    .btn-submit-modal {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        height: 38px !important;
        padding: 0 20px !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
        transition: all 120ms ease;
    }
    .btn-submit-modal:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35) !important;
    }

    /* Modal Hapus Vendor (DESIGN.md §15.3 & Charcoal Dark Theme) */
    #deleteVendorModal .modal-content {
        background-color: #161B22 !important;
        border: 1px solid #2D333B !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
        color: #F1F5F9 !important;
        overflow: hidden;
    }
    #deleteVendorModal .modal-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 16px 20px !important;
    }
    #deleteVendorModal .modal-body {
        background-color: #161B22 !important;
        padding: 20px !important;
    }
    #deleteVendorModal .modal-footer {
        background-color: #181E26 !important;
        border-top: 1px solid #232A35 !important;
        padding: 14px 20px !important;
    }
    .del-modal-radio {
        appearance: none !important;
        -webkit-appearance: none !important;
        width: 19px !important;
        height: 19px !important;
        min-width: 19px !important;
        min-height: 19px !important;
        border-radius: 50% !important;
        border: 2px solid #383E45 !important;
        background-color: #212529 !important;
        cursor: pointer !important;
        display: inline-grid !important;
        place-content: center !important;
        margin-top: 3px !important;
        flex-shrink: 0 !important;
        transition: all 0.2s ease !important;
    }
    .del-modal-radio::before {
        content: "" !important;
        width: 9px !important;
        height: 9px !important;
        border-radius: 50% !important;
        transform: scale(0) !important;
        transition: 120ms transform ease-in-out !important;
        box-shadow: inset 1em 1em #6366F1 !important;
    }
    .del-modal-radio:checked {
        border-color: #6366F1 !important;
        background-color: rgba(99, 102, 241, 0.15) !important;
    }
    .del-modal-radio:checked::before {
        transform: scale(1) !important;
    }
    #action_delete_all:checked {
        border-color: #EF4444 !important;
        background-color: rgba(239, 68, 68, 0.15) !important;
    }
    #action_delete_all:checked::before {
        box-shadow: inset 1em 1em #EF4444 !important;
    }
    .action-choice-card {
        border-radius: 10px !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }
    .action-choice-card:hover {
        transform: translateY(-1px) !important;
    }
    #target_vendor_select {
        background-color: #212529 !important;
        color: #F8FAFC !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
        padding: 5px !important;
        scrollbar-width: thin;
        scrollbar-color: #383E45 #1A1F26;
    }
    #target_vendor_select:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
        outline: none !important;
    }
    #target_vendor_select option {
        padding: 8px 12px !important;
        border-bottom: 1px solid #282E37 !important;
        color: #CBD5E1 !important;
        background: #212529 !important;
        border-radius: 4px !important;
        margin-bottom: 2px;
    }
    #target_vendor_select option:hover {
        background: #2A3038 !important;
        color: #FFFFFF !important;
    }
    #target_vendor_select option:checked {
        background: #4F46E5 !important;
        color: #FFFFFF !important;
        font-weight: 600;
    }
    #btn_confirm_delete_vendor {
        background-color: #DC2626 !important;
        border: 1px solid #EF4444 !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        height: 38px !important;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.35) !important;
        transition: all 120ms ease !important;
    }
    #btn_confirm_delete_vendor:hover {
        background-color: #B91C1C !important;
        border-color: #DC2626 !important;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.45) !important;
        transform: translateY(-1px);
    }
</style>

{{-- Modal Konfirmasi Hapus Vendor dengan Tampilan Elegan & Searchable Vendor --}}
<div class="modal fade" id="deleteVendorModal" tabindex="-1" aria-labelledby="deleteVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content shadow-lg">
            <form id="deleteVendorForm" method="POST">
                @csrf
                @method('DELETE')
                
                {{-- Header Modal --}}
                <div class="modal-header py-3 px-4">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="background: rgba(239, 68, 68, 0.15); color: #F87171; width: 36px; height: 36px; border: 1px solid rgba(239, 68, 68, 0.25);">
                            <i class="ri-delete-bin-line fs-18"></i>
                        </div>
                        <h5 class="modal-title fs-16 fw-semibold text-white mb-0" id="deleteVendorModalLabel">
                            Konfirmasi Hapus Vendor
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                {{-- Body Modal --}}
                <div class="modal-body p-4">
                    {{-- Nama Vendor --}}
                    <div class="text-center mb-3">
                        <p class="fs-13 mb-2" style="color: #94A3B8 !important;">Apakah Anda yakin ingin menghapus vendor berikut?</p>
                        <div class="d-inline-flex align-items-center gap-2 py-1.5 px-3 rounded-pill shadow-sm" style="background: #212529; border: 1px solid #383E45;">
                            <i class="ri-store-2-fill fs-15" style="color: #818CF8;"></i>
                            <span class="fw-bold fs-14 text-white" id="del_vendor_name_badge">-</span>
                        </div>
                    </div>

                    {{-- Blok Jika Memiliki Riwayat Pembelian --}}
                    <div id="del_section_has_trx" class="d-none">
                        {{-- Ringkasan Transaksi yang Terhubung --}}
                        <div class="p-3 mb-3 rounded" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.28); border-radius: 10px;">
                            <div class="d-flex align-items-start gap-2.5">
                                <i class="ri-alert-fill fs-18 flex-shrink-0 mt-0.5" style="color: #F59E0B;"></i>
                                <div class="fs-13" style="color: #F1F5F9 !important; line-height: 1.5;">
                                    Vendor ini memiliki <span class="badge px-2 py-0.5 fs-12 fw-bold" style="background: rgba(245, 158, 11, 0.2); color: #FCD34D; border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 6px;" id="del_trx_count_text">0 Transaksi</span> dengan total nilai <strong class="text-white fw-bold" id="del_trx_nominal_text">Rp 0</strong>.
                                </div>
                            </div>
                        </div>

                        <label class="form-label fw-semibold fs-11 text-uppercase mb-2" style="letter-spacing: 0.6px; color: #94A3B8 !important;">
                            PILIH PENANGANAN RIWAYAT PEMBELIAN:
                        </label>

                        {{-- Opsi 1: Pindahkan Transaksi (Default) --}}
                        <div class="card mb-3 action-choice-card border" id="card_action_transfer" style="background: rgba(99, 102, 241, 0.07); border-color: #6366F1 !important; box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.35);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-2.5">
                                    <input class="del-modal-radio" type="radio" name="action" id="action_transfer" value="transfer" checked>
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <label class="form-check-label fw-semibold text-white fs-13 mb-0 cursor-pointer" for="action_transfer">
                                                Pindahkan riwayat pembelian ke vendor lain
                                            </label>
                                            <span class="badge px-2 py-0.5 rounded-pill fs-11 fw-semibold" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                Direkomendasikan
                                            </span>
                                        </div>
                                        <p class="fs-12 mb-2" style="color: #94A3B8 !important; line-height: 1.45;">
                                            Semua transaksi pembelian dan tagihan akan dialihkan ke vendor baru yang Anda pilih di bawah.
                                        </p>

                                        {{-- Wadah Searchable Dropdown Vendor Tujuan --}}
                                        <div id="target_vendor_wrapper" class="pt-2" style="border-top: 1px solid #2B3037;">
                                            <div class="d-flex justify-content-between align-items-center mb-1.5">
                                                <label for="target_vendor_search_input" class="form-label fs-12 fw-medium mb-0" style="color: #CBD5E1;">
                                                    Pilih Vendor Tujuan:
                                                </label>
                                                <span id="target_vendor_count_info" class="fs-11 fw-medium" style="color: #818CF8;">
                                                    0 vendor tersedia
                                                </span>
                                            </div>

                                            {{-- Input Pencarian Vendor --}}
                                            <div class="input-group input-group-sm mb-2 shadow-sm" style="border: 1px solid #383E45; border-radius: 8px; overflow: hidden; background: #212529;">
                                                <span class="input-group-text border-0" style="background: #212529; color: #94A3B8; padding-left: 10px; padding-right: 8px;">
                                                    <i class="ri-search-line fs-14"></i>
                                                </span>
                                                <input type="text" id="target_vendor_search_input" class="form-control form-control-sm border-0" 
                                                       placeholder="Ketik untuk mencari nama vendor..." 
                                                       style="background: #212529; color: #F8FAFC; font-size: 13px; box-shadow: none;">
                                                <button class="btn btn-sm border-0" type="button" id="btn_clear_vendor_search" style="display: none; background: #212529; color: #94A3B8; padding-right: 10px;" title="Hapus Pencarian">
                                                    <i class="ri-close-line"></i>
                                                </button>
                                            </div>

                                            {{-- Listbox Vendor Dinamis --}}
                                            <select name="target_vendor_id" id="target_vendor_select" class="form-select form-select-sm shadow-sm" size="5">
                                                {{-- Opsi digenerate oleh JavaScript --}}
                                            </select>
                                            
                                            <div class="d-flex justify-content-between align-items-center mt-1.5">
                                                <small class="fs-11" style="color: #94A3B8;" id="target_vendor_selection_hint">
                                                    Klik salah satu vendor untuk memilih.
                                                </small>
                                                <small class="fs-11 fw-bold text-success d-none" id="selected_vendor_preview">
                                                    <i class="ri-checkbox-circle-fill me-1"></i>Terpilih: <span id="selected_vendor_name_text" class="text-white"></span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Opsi 2: Hapus Sekaligus --}}
                        <div class="card mb-0 action-choice-card border" id="card_action_delete_all" style="background: #181C22; border-color: #2B3037 !important;">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-2.5">
                                    <input class="del-modal-radio" type="radio" name="action" id="action_delete_all" value="delete_all">
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fw-semibold fs-13 mb-1 cursor-pointer" for="action_delete_all" style="color: #F87171 !important;">
                                            Hapus sekaligus vendor beserta seluruh riwayat pembeliannya
                                        </label>
                                        <p class="fs-12 mb-0" style="color: #94A3B8 !important; line-height: 1.45;">
                                            <strong class="text-danger fw-semibold">Peringatan:</strong> Seluruh transaksi pembelian dan detail barang vendor ini akan dihapus permanen. Anggaran kas yang telah terpakai akan dikembalikan ke saldo anggaran.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Blok Jika Belum Ada Transaksi --}}
                    <div id="del_section_no_trx" class="d-none">
                        <div class="p-3 text-center" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 10px;">
                            <i class="ri-checkbox-circle-line fs-26 d-block mb-1" style="color: #10B981;"></i>
                            <span class="fs-13 fw-medium text-white">Vendor ini belum memiliki riwayat pembelian, sehingga aman untuk langsung dihapus.</span>
                        </div>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="modal-footer py-3 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger px-4" id="btn_confirm_delete_vendor">
                        <i class="ri-delete-bin-line me-1.5 align-middle"></i>Ya, Hapus Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Pembelian &mdash; Vendor</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Vendor</a></li>
                    <li class="breadcrumb-item active">Daftar Vendor</li>
                </ol>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <i class="ri-checkbox-circle-line fs-20 me-2 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <i class="ri-error-warning-line fs-20 me-2 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <div class="d-flex align-items-center mb-1">
                    <i class="ri-error-warning-line fs-18 me-2"></i>
                    <strong>Terjadi Kesalahan:</strong>
                </div>
                <ul class="mb-0 ps-3 fs-13">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <div class="mb-3">
            <button type="button" class="btn btn-primary btn-add-vendor" data-bs-toggle="modal" data-bs-target="#tambahVendorModal">
                <i class="ri-add-line me-1 align-middle"></i>Tambah Vendor
            </button>
        </div>

        {{-- Kartu Ringkasan (Elegan, Berwarna Halus, Jelas & Nyaman di Mata) --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="metric-overline">TOTAL PEMBELIAN</p>
                                <h4 class="metric-value text-white">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</h4>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(59, 130, 246, 0.12); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.25);">
                                <i class="ri-file-list-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="metric-overline">TOTAL TERBAYAR</p>
                                <h4 class="metric-value" style="color: #34d399;">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</h4>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(16, 185, 129, 0.12); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <i class="ri-checkbox-circle-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="metric-overline">SISA BELUM DIBAYAR</p>
                                <h4 class="metric-value" style="color: {{ $totalBelumTerbayar > 0 ? '#fb7185' : '#94a3b8' }};">Rp {{ number_format($totalBelumTerbayar, 0, ',', '.') }}</h4>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(244, 63, 94, 0.12); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.25);">
                                <i class="ri-error-warning-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-1.5 mb-1">
                                    <p class="metric-overline mb-0">SISA DUIT KAS</p>
                                    @if(($sisaKas ?? 0) < 0)
                                        <span class="badge py-0 px-1.5 fs-10 fw-semibold rounded-pill" style="background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3);">Minus</span>
                                    @else
                                        <span class="badge py-0 px-1.5 fs-10 fw-semibold rounded-pill" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3);">Tersedia</span>
                                    @endif
                                </div>
                                <h4 class="metric-value" style="color: {{ ($sisaKas ?? 0) >= 0 ? '#38bdf8' : '#fb7185' }};">
                                    @if(($sisaKas ?? 0) < 0)
                                        -Rp {{ number_format(abs($sisaKas ?? 0), 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($sisaKas ?? 0, 0, ',', '.') }}
                                    @endif
                                </h4>
                            </div>
                            <div class="metric-icon-box" style="{{ ($sisaKas ?? 0) >= 0 ? 'background-color: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.25);' : 'background-color: rgba(244, 63, 94, 0.12); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.25);' }}">
                                <i class="ri-wallet-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-vendor-main">
            <div class="card-header card-vendor-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-vendor-title">
                        <i class="ri-store-2-line"></i>Daftar Vendor
                    </h5>
                    <span class="badge-count-pill" id="vendorCountBadge">{{ count($vendors) }} Vendor</span>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                    <!-- Cari Nama Vendor -->
                    <div class="position-relative" style="min-width: 210px;">
                        <input type="text" id="searchVendorInput" class="form-control form-control-sm ps-4 pe-4 vendor-filter-input" placeholder="Cari nama vendor...">
                        <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted fs-13"></i>
                        <button type="button" id="clearVendorSearch" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted p-0 me-2 d-none" style="text-decoration: none;" title="Hapus pencarian">
                            <i class="ri-close-line fs-14"></i>
                        </button>
                    </div>

                    <!-- Filter Status Pembayaran -->
                    <select id="filterStatusVendor" class="form-select form-select-sm vendor-filter-select" style="width: 170px;" title="Filter status tagihan vendor">
                        <option value="all">Semua Status</option>
                        <option value="belum_lunas">Hanya Belum Lunas</option>
                        <option value="lunas">Hanya Sudah Lunas</option>
                    </select>

                    <!-- Urutan / Sort Filter -->
                    <select id="sortVendorSelect" class="form-select form-select-sm vendor-filter-select" style="width: 230px;" title="Urutkan data vendor">
                        <option value="sisa_desc" selected>Sisa Belum Dibayar (Tertinggi)</option>
                        <option value="sisa_asc">Sisa Belum Dibayar (Terendah)</option>
                        <option value="total_desc">Total Pembelian (Tertinggi)</option>
                    </select>

                    <button type="button" class="btn btn-reset-filter" id="btnResetVendorFilter" title="Reset pencarian & filter" onclick="resetVendorFilters()">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th>Nama Vendor</th>
                                <th class="text-end">Total Transaksi (Rp)</th>
                                <th class="text-end">Total Terbayar (Rp)</th>
                                <th class="text-end">Total Sisa (Rp)</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="vendorTableBody">
                        @forelse ($vendors as $vendor)
                            <tr class="vendor-row"
                                data-vendor-name="{{ strtolower($vendor->nama_vendor) }}"
                                data-total-pembelian="{{ (float) $vendor->total_pembelian }}"
                                data-terbayar="{{ (float) $vendor->pembelian_terbayar }}"
                                data-sisa="{{ (float) $vendor->pembelian_sisa }}">
                                <td class="text-center row-number">{{ $loop->iteration }}</td>
                                <td class="vendor-name">{{ $vendor->nama_vendor }}</td>
                                <td class="text-end fw-semibold text-white">{{ number_format($vendor->total_pembelian, 0, ',', '.') }}</td>
                                <td class="text-end fw-medium" style="color: #34d399;">{{ number_format($vendor->pembelian_terbayar, 0, ',', '.') }}</td>
                                <td class="text-end fw-medium" style="color: {{ $vendor->pembelian_sisa > 0 ? '#fb7185' : '#94a3b8' }};">{{ number_format($vendor->pembelian_sisa, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi vendor">
                                            <i class="ri-more-fill fs-16"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('pembelian.vendor.show', $vendor->id) }}">
                                                    <i class="ri-list-check fs-16 align-middle me-2 text-primary"></i>Lihat Transaksi
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item edit-vendor-button"
                                                        data-vendor-id="{{ $vendor->id }}" data-vendor-name="{{ $vendor->nama_vendor }}"
                                                        data-bs-toggle="modal" data-bs-target="#editVendorModal">
                                                    <i class="ri-edit-line fs-16 align-middle me-2 text-muted"></i>Edit Vendor
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger delete-vendor-button"
                                                        data-vendor-id="{{ $vendor->id }}"
                                                        data-vendor-name="{{ $vendor->nama_vendor }}"
                                                        data-pembelian-count="{{ $vendor->pembelian_count ?? 0 }}"
                                                        data-total-pembelian="{{ number_format($vendor->total_pembelian, 0, ',', '.') }}"
                                                        data-pembelian-terbayar="{{ number_format($vendor->pembelian_terbayar, 0, ',', '.') }}"
                                                        data-pembelian-sisa="{{ number_format($vendor->pembelian_sisa, 0, ',', '.') }}">
                                                    <i class="ri-delete-bin-line fs-16 align-middle me-2"></i>Hapus Vendor
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada vendor.</td></tr>
                        @endforelse
                            <tr id="noVendorFoundRow" class="d-none">
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="ri-search-eye-line fs-24 d-block mb-1 text-secondary"></i>
                                    Tidak ada vendor yang cocok dengan kriteria pencarian atau filter ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-vendor-custom" id="tambahVendorModal" tabindex="-1" aria-labelledby="tambahVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('pembelian.vendor.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahVendorModalLabel">Tambah Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label for="nama_vendor_baru" class="form-label">Nama Vendor</label>
                    <input id="nama_vendor_baru" name="nama_vendor" type="text" class="form-control" value="{{ old('nama_vendor') }}" placeholder="Contoh: CV. Berkah Abadi" maxlength="255" required>
                    @error('nama_vendor')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit-modal">Simpan Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-vendor-custom" id="editVendorModal" tabindex="-1" aria-labelledby="editVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editVendorForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="vendor_id" id="edit_vendor_id" value="{{ old('vendor_id') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="editVendorModalLabel">Edit Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <label for="edit_nama_vendor" class="form-label">Nama Vendor</label>
                    <input id="edit_nama_vendor" name="nama_vendor" type="text" class="form-control" value="{{ old('nama_vendor') }}" maxlength="255" required>
                    @error('nama_vendor')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancel-modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit-modal">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script>
    const editVendorUrl = @json(route('pembelian.vendor.update', ['id' => '__VENDOR_ID__']));
    const editVendorForm = document.getElementById('editVendorForm');
    const editVendorId = document.getElementById('edit_vendor_id');
    const editVendorName = document.getElementById('edit_nama_vendor');

    document.querySelectorAll('.edit-vendor-button').forEach((button) => {
        button.addEventListener('click', () => {
            editVendorForm.action = editVendorUrl.replace('__VENDOR_ID__', button.dataset.vendorId);
            editVendorId.value = button.dataset.vendorId;
            editVendorName.value = button.dataset.vendorName;
        });
    });

    @if ($errors->has('nama_vendor'))
        document.addEventListener('DOMContentLoaded', () => {
            @if (old('vendor_id'))
                editVendorForm.action = editVendorUrl.replace('__VENDOR_ID__', @json(old('vendor_id')));
                new bootstrap.Modal(document.getElementById('editVendorModal')).show();
            @else
                new bootstrap.Modal(document.getElementById('tambahVendorModal')).show();
            @endif
        });
    @endif

    // ==============================================================
    // LOGIKA POP-UP HAPUS VENDOR DENGAN LIVE SEARCH & PILIHAN OPSI
    // ==============================================================
    const allVendorsList = @json($vendors->map(function($v) {
        return [
            'id' => $v->id,
            'nama_vendor' => $v->nama_vendor
        ];
    }));

    const destroyVendorBaseUrl = @json(route('pembelian.vendor.destroy', ['id' => '__VENDOR_ID__']));
    const deleteVendorForm = document.getElementById('deleteVendorForm');
    const delVendorNameBadge = document.getElementById('del_vendor_name_badge');
    const delSectionHasTrx = document.getElementById('del_section_has_trx');
    const delSectionNoTrx = document.getElementById('del_section_no_trx');
    const delTrxCountText = document.getElementById('del_trx_count_text');
    const delTrxNominalText = document.getElementById('del_trx_nominal_text');
    const targetVendorSelect = document.getElementById('target_vendor_select');
    const targetVendorWrapper = document.getElementById('target_vendor_wrapper');
    const targetVendorSearchInput = document.getElementById('target_vendor_search_input');
    const targetVendorCountInfo = document.getElementById('target_vendor_count_info');
    const btnClearVendorSearch = document.getElementById('btn_clear_vendor_search');
    const selectedVendorPreview = document.getElementById('selected_vendor_preview');
    const selectedVendorNameText = document.getElementById('selected_vendor_name_text');
    const radioTransfer = document.getElementById('action_transfer');
    const radioDeleteAll = document.getElementById('action_delete_all');
    const cardTransfer = document.getElementById('card_action_transfer');
    const cardDeleteAll = document.getElementById('card_action_delete_all');

    let currentEligibleVendors = [];
    let currentActiveDeleteVendorId = null;

    // Toggle card border & style on radio selection
    function updateDeleteActionUI() {
        if (radioTransfer && radioTransfer.checked) {
            if (cardTransfer) {
                cardTransfer.style.borderColor = '#6366F1';
                cardTransfer.style.backgroundColor = 'rgba(99, 102, 241, 0.07)';
                cardTransfer.style.boxShadow = '0 0 0 1px rgba(99, 102, 241, 0.35)';
            }
            if (cardDeleteAll) {
                cardDeleteAll.style.borderColor = '#2B3037';
                cardDeleteAll.style.backgroundColor = '#181C22';
                cardDeleteAll.style.boxShadow = 'none';
            }
            if (targetVendorWrapper) targetVendorWrapper.style.display = 'block';
            if (targetVendorSelect) targetVendorSelect.required = true;
        } else if (radioDeleteAll && radioDeleteAll.checked) {
            if (cardTransfer) {
                cardTransfer.style.borderColor = '#2B3037';
                cardTransfer.style.backgroundColor = '#181C22';
                cardTransfer.style.boxShadow = 'none';
            }
            if (cardDeleteAll) {
                cardDeleteAll.style.borderColor = '#EF4444';
                cardDeleteAll.style.backgroundColor = 'rgba(239, 68, 68, 0.07)';
                cardDeleteAll.style.boxShadow = '0 0 0 1px rgba(239, 68, 68, 0.35)';
            }
            if (targetVendorWrapper) targetVendorWrapper.style.display = 'none';
            if (targetVendorSelect) {
                targetVendorSelect.required = false;
                targetVendorSelect.value = '';
            }
            if (selectedVendorPreview) selectedVendorPreview.classList.add('d-none');
        }
    }

    // Render & filter list vendor tujuan
    function renderVendorSelectOptions(query = '') {
        if (!targetVendorSelect) return;
        targetVendorSelect.innerHTML = '';
        const q = query.trim().toLowerCase();

        const filtered = currentEligibleVendors.filter(v => {
            return !q || v.nama_vendor.toLowerCase().includes(q);
        });

        if (targetVendorCountInfo) {
            targetVendorCountInfo.textContent = filtered.length + ' vendor tersedia';
        }

        if (filtered.length === 0) {
            const emptyOpt = document.createElement('option');
            emptyOpt.disabled = true;
            emptyOpt.textContent = 'Tidak ada vendor yang cocok dengan "' + query + '"';
            targetVendorSelect.appendChild(emptyOpt);
            return;
        }

        filtered.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = v.nama_vendor;
            if (targetVendorSelect.dataset.selectedId === v.id) {
                opt.selected = true;
            }
            targetVendorSelect.appendChild(opt);
        });
    }

    // Event listener input search vendor
    if (targetVendorSearchInput) {
        targetVendorSearchInput.addEventListener('input', function() {
            const val = this.value;
            if (btnClearVendorSearch) {
                btnClearVendorSearch.style.display = val ? 'block' : 'none';
            }
            renderVendorSelectOptions(val);
        });
    }

    if (btnClearVendorSearch) {
        btnClearVendorSearch.addEventListener('click', function() {
            if (targetVendorSearchInput) {
                targetVendorSearchInput.value = '';
                this.style.display = 'none';
                renderVendorSelectOptions('');
                targetVendorSearchInput.focus();
            }
        });
    }

    // Event listener select vendor change
    if (targetVendorSelect) {
        targetVendorSelect.addEventListener('change', function() {
            const selId = this.value;
            this.dataset.selectedId = selId;
            const chosen = currentEligibleVendors.find(v => v.id === selId);
            if (chosen && selectedVendorPreview && selectedVendorNameText) {
                selectedVendorNameText.textContent = chosen.nama_vendor;
                selectedVendorPreview.classList.remove('d-none');
            } else if (selectedVendorPreview) {
                selectedVendorPreview.classList.add('d-none');
            }
        });
    }

    if (radioTransfer) radioTransfer.addEventListener('change', updateDeleteActionUI);
    if (radioDeleteAll) radioDeleteAll.addEventListener('change', updateDeleteActionUI);

    // Make clicking the card check its radio
    if (cardTransfer) {
        cardTransfer.addEventListener('click', function(e) {
            if (e.target.closest('#target_vendor_wrapper')) return;
            if (radioTransfer) {
                radioTransfer.checked = true;
                updateDeleteActionUI();
            }
        });
    }
    if (cardDeleteAll) {
        cardDeleteAll.addEventListener('click', function() {
            if (radioDeleteAll) {
                radioDeleteAll.checked = true;
                updateDeleteActionUI();
            }
        });
    }

    // Klik tombol aksi "Hapus Vendor" pada tabel
    document.querySelectorAll('.delete-vendor-button').forEach((button) => {
        button.addEventListener('click', () => {
            const vendorId = button.dataset.vendorId;
            const vendorName = button.dataset.vendorName;
            const trxCount = parseInt(button.dataset.pembelianCount || '0', 10);
            const totalNominal = button.dataset.totalPembelian || '0';

            currentActiveDeleteVendorId = vendorId;
            deleteVendorForm.action = destroyVendorBaseUrl.replace('__VENDOR_ID__', vendorId);
            delVendorNameBadge.textContent = vendorName;

            // Reset input pencarian
            if (targetVendorSearchInput) targetVendorSearchInput.value = '';
            if (btnClearVendorSearch) btnClearVendorSearch.style.display = 'none';
            if (selectedVendorPreview) selectedVendorPreview.classList.add('d-none');
            if (targetVendorSelect) targetVendorSelect.dataset.selectedId = '';

            if (trxCount > 0) {
                delSectionHasTrx.classList.remove('d-none');
                delSectionNoTrx.classList.add('d-none');
                delTrxCountText.textContent = trxCount + ' Transaksi Pembelian';
                delTrxNominalText.textContent = 'Rp ' + totalNominal;

                // Ambil daftar vendor selain yang sedang dihapus
                currentEligibleVendors = allVendorsList.filter(v => v.id !== vendorId);
                renderVendorSelectOptions('');

                if (radioTransfer) radioTransfer.checked = true;
                updateDeleteActionUI();
            } else {
                delSectionHasTrx.classList.add('d-none');
                delSectionNoTrx.classList.remove('d-none');
                if (targetVendorSelect) targetVendorSelect.required = false;
            }

            new bootstrap.Modal(document.getElementById('deleteVendorModal')).show();
        });
    });

    // ==========================================
    // Filter dan Pencarian Tabel Vendor
    // ==========================================
    const searchVendorInput = document.getElementById('searchVendorInput');
    const filterStatusVendor = document.getElementById('filterStatusVendor');
    const sortVendorSelect = document.getElementById('sortVendorSelect');
    const clearVendorSearch = document.getElementById('clearVendorSearch');

    function applyVendorFilterAndSort() {
        const searchInput = document.getElementById('searchVendorInput');
        const statusSelect = document.getElementById('filterStatusVendor');
        const sortSelect = document.getElementById('sortVendorSelect');
        const tableBody = document.getElementById('vendorTableBody');
        const noFoundRow = document.getElementById('noVendorFoundRow');
        const badgeCount = document.getElementById('vendorCountBadge');
        const clearBtn = document.getElementById('clearVendorSearch');

        if (!tableBody) return;

        const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const statusFilter = statusSelect ? statusSelect.value : 'all';
        const sortBy = sortSelect ? sortSelect.value : 'sisa_desc';

        if (clearBtn) {
            clearBtn.classList.toggle('d-none', searchTerm === '');
        }

        const rows = Array.from(tableBody.querySelectorAll('tr.vendor-row'));

        let visibleCount = 0;
        rows.forEach(row => {
            const name = (row.dataset.vendorName || '').toLowerCase();
            const sisa = parseFloat(row.dataset.sisa || '0');

            const matchSearch = searchTerm === '' || name.includes(searchTerm);
            let matchStatus = true;
            if (statusFilter === 'belum_lunas') {
                matchStatus = sisa > 0;
            } else if (statusFilter === 'lunas') {
                matchStatus = sisa <= 0;
            }

            const isVisible = matchSearch && matchStatus;
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        // Pengurutan baris tabel
        rows.sort((a, b) => {
            const nameA = a.dataset.vendorName || '';
            const nameB = b.dataset.vendorName || '';
            const sisaA = parseFloat(a.dataset.sisa || '0');
            const sisaB = parseFloat(b.dataset.sisa || '0');
            const totalA = parseFloat(a.dataset.totalPembelian || '0');
            const totalB = parseFloat(b.dataset.totalPembelian || '0');

            if (sortBy === 'sisa_desc') {
                return sisaB - sisaA || nameA.localeCompare(nameB);
            } else if (sortBy === 'sisa_asc') {
                return sisaA - sisaB || nameA.localeCompare(nameB);
            } else if (sortBy === 'total_desc') {
                return totalB - totalA || nameA.localeCompare(nameB);
            }
            return 0;
        });

        let rowNum = 1;
        rows.forEach(row => {
            tableBody.appendChild(row);
            if (row.style.display !== 'none') {
                const numCell = row.querySelector('.row-number');
                if (numCell) numCell.textContent = rowNum++;
            }
        });

        if (noFoundRow) {
            noFoundRow.classList.toggle('d-none', visibleCount > 0);
            tableBody.appendChild(noFoundRow);
        }

        if (badgeCount) {
            badgeCount.textContent = `${visibleCount} Vendor`;
        }
    }

    function resetVendorFilters() {
        const searchInput = document.getElementById('searchVendorInput');
        const statusSelect = document.getElementById('filterStatusVendor');
        const sortSelect = document.getElementById('sortVendorSelect');
        if (searchInput) searchInput.value = '';
        if (statusSelect) statusSelect.value = 'all';
        if (sortSelect) sortSelect.value = 'sisa_desc';
        applyVendorFilterAndSort();
    }

    if (searchVendorInput) {
        searchVendorInput.addEventListener('input', applyVendorFilterAndSort);
    }
    if (filterStatusVendor) {
        filterStatusVendor.addEventListener('change', applyVendorFilterAndSort);
    }
    if (sortVendorSelect) {
        sortVendorSelect.addEventListener('change', applyVendorFilterAndSort);
    }
    if (clearVendorSearch) {
        clearVendorSearch.addEventListener('click', function() {
            if (searchVendorInput) {
                searchVendorInput.value = '';
                searchVendorInput.focus();
            }
            applyVendorFilterAndSort();
        });
    }

    // Inisialisasi awal saat halaman dimuat
    applyVendorFilterAndSort();
</script>
@endsection