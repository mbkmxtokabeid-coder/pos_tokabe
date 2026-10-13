@extends('layout.template')

@section('head')
<style>
    /* DESIGN.md Compliance: Neutral Charcoal Dark Theme (#161B22) */
    .table-container-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }
    .table-container-card .card-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 18px 24px !important;
    }
    .btn-create-rek {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-create-rek:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        color: #FFFFFF !important;
    }

    /* Table Styles */
    .table-rekening-custom {
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }
    .table-rekening-custom thead th {
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
        padding: 13px 16px !important;
        white-space: nowrap;
    }
    .table-rekening-custom tbody tr {
        border-bottom: 1px solid #21262D !important;
        transition: background-color 150ms ease !important;
    }
    .table-rekening-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    .table-rekening-custom tbody td {
        border: none !important;
        border-bottom: 1px solid #21262D !important;
        padding: 13px 16px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #CBD5E1 !important;
    }
    .table-rekening-custom tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Cell Badges */
    .badge-bank {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: rgba(14, 165, 233, 0.12) !important;
        color: #38BDF8 !important;
        border: 1px solid rgba(14, 165, 233, 0.25) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
    }
    .badge-status-aktif {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-status-nonaktif {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #F87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .code-format-invoice {
        background-color: #0D1117 !important;
        color: #CBD5E1 !important;
        border: 1px solid #21262D !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        font-size: 12px !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        display: inline-block;
    }
    .btn-action-icon {
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #383E45;
        background-color: #212529;
        color: #CBD5E1;
        transition: all 150ms ease;
    }
    .btn-action-icon:hover {
        background-color: #2D333B;
        color: #FFFFFF;
        border-color: #475569;
    }
    .btn-action-icon--edit:hover {
        border-color: #6366F1;
        color: #A5B4FC;
    }
    .btn-action-icon--toggle:hover {
        border-color: #F59E0B;
        color: #FCD34D;
    }
    .btn-action-icon--delete:hover {
        border-color: #EF4444;
        color: #F87171;
    }

    /* Modal Styling (DESIGN.md §15.3) */
    .modal-custom-rek {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 14px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6) !important;
        overflow: hidden;
    }
    .modal-custom-rek .modal-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 18px 24px !important;
    }
    .modal-custom-rek .modal-body {
        background-color: #161B22 !important;
        padding: 24px !important;
    }
    .modal-custom-rek .modal-footer {
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

    /* Modal Form Controls (DESIGN.md §10) */
    .form-label-rek {
        font-size: 11.5px !important;
        font-weight: 600 !important;
        color: #94A3B8 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px !important;
        display: block;
    }
    .modal-custom-rek .form-control {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        padding: 9px 13px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .modal-custom-rek .form-control:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
        outline: none !important;
        color: #FFFFFF !important;
    }
    .modal-custom-rek .form-control::placeholder {
        color: #64748B !important;
    }

    /* Live Preview Box */
    .preview-box-rek {
        background-color: #181E26 !important;
        border: 1px solid #282E37 !important;
        border-left: 4px solid #6366F1 !important;
        border-radius: 10px !important;
        padding: 14px 16px !important;
    }
    .preview-code-container {
        background-color: #0D1117 !important;
        border: 1px solid #21262D !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
        font-size: 13.5px !important;
        color: #38BDF8 !important;
        word-break: break-all;
    }

    /* Status Radio Pills */
    .status-radio-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .status-radio-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #383E45;
        background-color: #212529;
        color: #94A3B8;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 150ms ease;
        margin-bottom: 0;
        user-select: none;
    }
    .status-radio-input:checked + .status-radio-label--aktif {
        background-color: rgba(16, 185, 129, 0.12) !important;
        border-color: rgba(16, 185, 129, 0.4) !important;
        color: #34D399 !important;
    }
    .status-radio-input:checked + .status-radio-label--nonaktif {
        background-color: rgba(239, 68, 68, 0.12) !important;
        border-color: rgba(239, 68, 68, 0.4) !important;
        color: #F87171 !important;
    }

    /* Buttons */
    .btn-submit-rek {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 20px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
    }
    .btn-submit-rek:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
    }
    .btn-reset-rek {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
    }
    .btn-reset-rek:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }
    .btn-modal-cancel {
        display: inline-flex;
        align-items: center;
        background-color: transparent !important;
        border: 1px solid #383E45 !important;
        color: #94A3B8 !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
    }
    .btn-modal-cancel:hover {
        background-color: #212529 !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Daftar Rekening Bank</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Invoice Tokabe</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Kelola daftar rekening bank yang digunakan untuk pembayaran invoice Tokabe</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if (Auth::user()->role == 'Pemilik')
                        <button type="button" class="btn-create-rek" data-bs-toggle="modal" data-bs-target="#modalTambahRekening">
                            <i class="ri-add-line fs-18"></i> Tambah Rekening
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Content --}}
        <div class="row">
            <div class="col-12">
                <div class="table-container-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-wallet-3-line text-primary fs-18"></i>
                            <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Semua Rekening Bank</h5>
                        </div>
                        <span style="display:inline-flex;align-items:center;padding:2px 10px;font-size:11.5px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.25);">
                            {{ isset($rekening) ? $rekening->count() : 0 }} Rekening
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-rekening-custom align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">No</th>
                                        <th>Nama Bank</th>
                                        <th>Atas Nama (A/N)</th>
                                        <th>Nomor Rekening</th>
                                        <th>Format Invoice</th>
                                        <th>Keterangan</th>
                                        <th class="text-center" style="width: 110px;">Status</th>
                                        <th class="text-center" style="width: 130px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($rekening) && $rekening->count() > 0)
                                        @foreach ($rekening as $rek)
                                        <tr>
                                            <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge-bank">
                                                    <i class="ri-bank-line"></i>{{ $rek->nama_bank }}
                                                </span>
                                            </td>
                                            <td style="color: #F1F5F9; font-weight: 600;">{{ $rek->atas_nama }}</td>
                                            <td>
                                                <span style="font-family: monospace; font-weight: 700; color: #38BDF8; letter-spacing: 0.3px;">{{ $rek->nomor_rekening }}</span>
                                            </td>
                                            <td>
                                                <span class="code-format-invoice">
                                                    : {{ $rek->formatted }}
                                                </span>
                                            </td>
                                            <td style="color: #94A3B8;">{{ $rek->keterangan ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($rek->status == 'Aktif')
                                                    <span class="badge-status-aktif">
                                                        <i class="ri-checkbox-circle-line"></i>Aktif
                                                    </span>
                                                @else
                                                    <span class="badge-status-nonaktif">
                                                        <i class="ri-close-circle-line"></i>Non Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('rekening.edit', $rek->id) }}" class="btn-action-icon btn-action-icon--edit" title="Edit Rekening">
                                                        <i class="ri-edit-line fs-15"></i>
                                                    </a>
                                                    <a href="{{ route('rekening.status', $rek->id) }}" 
                                                       class="btn-action-icon btn-action-icon--toggle" 
                                                       title="{{ $rek->status == 'Aktif' ? 'Non-aktifkan' : 'Aktifkan' }}">
                                                        @if ($rek->status == 'Aktif')
                                                            <i class="ri-forbid-line fs-15"></i>
                                                        @else
                                                            <i class="ri-checkbox-circle-line fs-15 text-success"></i>
                                                        @endif
                                                    </a>
                                                    <button type="button" class="btn-action-icon btn-action-icon--delete" onclick="confirmDelete('{{ $rek->id }}', '{{ $rek->nama_bank }} - {{ $rek->nomor_rekening }}')" title="Hapus Rekening">
                                                        <i class="ri-delete-bin-line fs-15"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $rek->id }}" action="{{ route('rekening.destroy', $rek->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="ri-wallet-3-line fs-36 d-block mb-2" style="opacity: 0.4;"></i>
                                                Belum ada data rekening bank. Silakan klik tombol <strong>Tambah Rekening</strong>.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Rekening (Pop-Up) --}}
<div class="modal fade" id="modalTambahRekening" tabindex="-1" aria-labelledby="modalTambahRekeningLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-custom-rek">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="modal-icon-badge">
                        <i class="ri-bank-card-line fs-18 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fs-16 fw-semibold text-white mb-0" id="modalTambahRekeningLabel">Tambah Rekening Bank Baru</h5>
                        <p class="mb-0 text-muted fs-12">Rekening ini akan otomatis tersedia untuk format pembayaran invoice pelanggan</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('rekening.store') }}" class="needs-validation" id="formTambahRekening">
                @csrf
                <div class="modal-body p-4">
                    {{-- Live Preview Box --}}
                    <div class="preview-box-rek mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="ri-eye-line text-primary fs-16"></i>
                            <span style="font-size: 13px; font-weight: 600; color: #E2E8F0;">Format Tampilan Invoice (Preview):</span>
                        </div>
                        <div class="preview-code-container" id="previewRekening">
                            : BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999
                        </div>
                        <small style="color: #94A3B8; font-size: 12px; margin-top: 6px; display: block;">
                            *Format teks di atas akan tercetak otomatis pada bagian rekening/pembayaran invoice pelanggan.
                        </small>
                    </div>

                    <div class="row g-3">
                        {{-- Nama Bank --}}
                        <div class="col-md-6">
                            <label class="form-label-rek" for="nama_bank">Nama Bank <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_bank') is-invalid @enderror" 
                                   id="nama_bank" 
                                   name="nama_bank" 
                                   placeholder="Contoh: BSI, BCA, BNI, Mandiri" 
                                   value="{{ old('nama_bank') }}" 
                                   list="bankOptions"
                                   required>
                            <datalist id="bankOptions">
                                <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                <option value="BCA">Bank Central Asia (BCA)</option>
                                <option value="BNI">Bank Negara Indonesia (BNI)</option>
                                <option value="Bank Mandiri">Bank Mandiri</option>
                                <option value="BRI">Bank Rakyat Indonesia (BRI)</option>
                                <option value="Bank Permata">Bank Permata</option>
                                <option value="Bank Danamon">Bank Danamon</option>
                                <option value="CIMB Niaga">CIMB Niaga</option>
                            </datalist>
                            @error('nama_bank')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Atas Nama (A/N) --}}
                        <div class="col-md-6">
                            <label class="form-label-rek" for="atas_nama">Atas Nama (A/N) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('atas_nama') is-invalid @enderror" 
                                   id="atas_nama" 
                                   name="atas_nama" 
                                   placeholder="Contoh: PT. Total Karya Berkah" 
                                   value="{{ old('atas_nama') }}" 
                                   required>
                            @error('atas_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor Rekening --}}
                        <div class="col-md-6">
                            <label class="form-label-rek" for="nomor_rekening">Nomor Rekening (No. Rek) <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nomor_rekening') is-invalid @enderror" 
                                   id="nomor_rekening" 
                                   name="nomor_rekening" 
                                   placeholder="Contoh: 3557999999" 
                                   value="{{ old('nomor_rekening') }}" 
                                   required>
                            @error('nomor_rekening')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label-rek">Status Rekening <span class="text-danger">*</span></label>
                            <div class="status-radio-group pt-1">
                                <div>
                                    <input class="d-none status-radio-input" type="radio" name="status" id="statusAktif" value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'checked' : '' }} required>
                                    <label class="status-radio-label status-radio-label--aktif" for="statusAktif">
                                        <i class="ri-checkbox-circle-line"></i> Aktif
                                    </label>
                                </div>
                                <div>
                                    <input class="d-none status-radio-input" type="radio" name="status" id="statusNonAktif" value="Non Aktif" {{ old('status') == 'Non Aktif' ? 'checked' : '' }} required>
                                    <label class="status-radio-label status-radio-label--nonaktif" for="statusNonAktif">
                                        <i class="ri-close-circle-line"></i> Non Aktif
                                    </label>
                                </div>
                            </div>
                            @error('status')
                                <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Keterangan / Catatan --}}
                        <div class="col-12">
                            <label class="form-label-rek" for="keterangan">Keterangan (Opsional)</label>
                            <input type="text" 
                                   class="form-control @error('keterangan') is-invalid @enderror" 
                                   id="keterangan" 
                                   name="keterangan" 
                                   placeholder="Contoh: Rekening Utama Pembayaran Invoice, Rekening Cabang, dll." 
                                   value="{{ old('keterangan') }}">
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-end gap-2 p-3">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="reset" class="btn-reset-rek" onclick="setTimeout(updatePreview, 50)">
                        <i class="ri-refresh-line"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit-rek">
                        <i class="ri-save-3-line"></i> Simpan Rekening
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script>
    function updatePreview() {
        const bank = document.getElementById('nama_bank')?.value.trim() || 'BSI';
        const an = document.getElementById('atas_nama')?.value.trim() || 'PT. Total Karya Berkah';
        const rek = document.getElementById('nomor_rekening')?.value.trim() || '3557999999';

        const previewEl = document.getElementById('previewRekening');
        if (previewEl) {
            previewEl.textContent = `: ${bank} | A/N : ${an} | No. Rek : ${rek}`;
        }
    }

    document.getElementById('nama_bank')?.addEventListener('input', updatePreview);
    document.getElementById('atas_nama')?.addEventListener('input', updatePreview);
    document.getElementById('nomor_rekening')?.addEventListener('input', updatePreview);

    // Initial preview check
    updatePreview();

    // Auto-open modal on page load
    document.addEventListener("DOMContentLoaded", function () {
        var modalEl = document.getElementById('modalTambahRekening');
        if (modalEl) {
            var modalTambah = new bootstrap.Modal(modalEl);
            modalTambah.show();
        }
    });

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Rekening Bank?',
            text: "Apakah Anda yakin ingin menghapus rekening " + name + "? Tindakan ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#212529',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
