@extends('layout.template')

@section('head')
<style>
    /* DESIGN.md Compliance: Neutral Charcoal Dark Theme (#161B22) */
    .card-rekening {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        overflow: hidden;
    }
    .card-rekening .card-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 18px 24px !important;
    }
    .card-rekening .card-body {
        padding: 24px !important;
    }
    .card-rekening .card-footer {
        background-color: #181E26 !important;
        border-top: 1px solid #282E37 !important;
        padding: 16px 24px !important;
    }

    /* Form Labels & Controls */
    .form-label-rek {
        font-size: 11.5px !important;
        font-weight: 600 !important;
        color: #94A3B8 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px !important;
        display: block;
    }
    .card-rekening .form-control {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        padding: 9px 13px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .card-rekening .form-control:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
        outline: none !important;
        color: #FFFFFF !important;
    }
    .card-rekening .form-control::placeholder {
        color: #64748B !important;
    }

    /* Live Preview Box */
    .preview-box-rek {
        background-color: #181E26 !important;
        border: 1px solid #282E37 !important;
        border-left: 4px solid #6366F1 !important;
        border-radius: 10px !important;
        padding: 16px 18px !important;
        margin-bottom: 24px;
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
        padding: 9px 20px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
    }
    .btn-submit-rek:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
    }
    .btn-cancel-rek {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 9px 18px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        text-decoration: none;
    }
    .btn-cancel-rek:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }
    .btn-back-rek {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-weight: 500 !important;
        font-size: 12.5px !important;
        padding: 6px 14px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        text-decoration: none;
    }
    .btn-back-rek:hover {
        background-color: #2D333B !important;
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
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Edit Rekening Bank</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Invoice Tokabe</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Perbarui informasi rekening bank untuk pembayaran invoice pelanggan</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0" style="font-size: 13px;">
                            <li class="breadcrumb-item"><a href="{{ route('rekening.index') }}" style="color: #94A3B8;">Rekening</a></li>
                            <li class="breadcrumb-item active" style="color: #CBD5E1;">Edit Rekening</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Content --}}
        <div class="row justify-content-center">
            <div class="col-xxl-9 col-lg-10">
                <div class="card card-rekening mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-edit-line text-primary fs-18"></i>
                            <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Form Edit Rekening</h5>
                        </div>
                        <a href="{{ route('rekening.index') }}" class="btn-back-rek">
                            <i class="ri-arrow-left-line"></i> Kembali ke Daftar
                        </a>
                    </div>

                    <form method="POST" action="{{ route('rekening.update', $rekening->id) }}" class="needs-validation">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            {{-- Live Preview Box --}}
                            <div class="preview-box-rek">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ri-eye-line text-primary fs-16"></i>
                                    <span style="font-size: 13px; font-weight: 600; color: #E2E8F0;">Format Tampilan Invoice (Preview):</span>
                                </div>
                                <div class="preview-code-container" id="previewRekening">
                                    : {{ $rekening->formatted }}
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
                                           value="{{ old('nama_bank', $rekening->nama_bank) }}" 
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
                                           value="{{ old('atas_nama', $rekening->atas_nama) }}" 
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
                                           value="{{ old('nomor_rekening', $rekening->nomor_rekening) }}" 
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
                                            <input class="d-none status-radio-input" type="radio" name="status" id="statusAktif" value="Aktif" {{ old('status', $rekening->status) == 'Aktif' ? 'checked' : '' }} required>
                                            <label class="status-radio-label status-radio-label--aktif" for="statusAktif">
                                                <i class="ri-checkbox-circle-line"></i> Aktif
                                            </label>
                                        </div>
                                        <div>
                                            <input class="d-none status-radio-input" type="radio" name="status" id="statusNonAktif" value="Non Aktif" {{ old('status', $rekening->status) == 'Non Aktif' ? 'checked' : '' }} required>
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
                                           value="{{ old('keterangan', $rekening->keterangan) }}">
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('rekening.index') }}" class="btn-cancel-rek">Batal</a>
                            <button type="submit" class="btn-submit-rek">
                                <i class="ri-save-3-line"></i> Perbarui Rekening
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bankInput = document.getElementById('nama_bank');
        const anInput = document.getElementById('atas_nama');
        const norekInput = document.getElementById('nomor_rekening');
        const previewEl = document.getElementById('previewRekening');

        function updatePreview() {
            const bank = bankInput.value.trim() || 'BSI';
            const an = anInput.value.trim() || 'PT. Total Karya Berkah';
            const norek = norekInput.value.trim() || '3557999999';

            previewEl.textContent = `: ${bank} | A/N : ${an} | No. Rek : ${norek}`;
        }

        bankInput.addEventListener('input', updatePreview);
        anInput.addEventListener('input', updatePreview);
        norekInput.addEventListener('input', updatePreview);

        updatePreview();
    });
</script>
@endsection
