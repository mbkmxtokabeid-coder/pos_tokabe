@extends('layout.template')

@section('head')
<link rel="stylesheet" href="{{ asset('libs/dropzone/dropzone.css') }}" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* ==========================================================================
       DESIGN.md — FORM & CARD SURFACES (§10 & §12)
       Neutral dark #161B22, border #282E37, inputs #212529, accent #6366F1
       ========================================================================== */
    .card-form-spj {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .card-form-spj .card-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 16px 20px !important;
    }
    .col-form-label {
        color: #CBD5E1 !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
    }
    .form-control, .form-select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        font-size: 13.5px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
        background-color: #262B32 !important;
        color: #FFFFFF !important;
    }
    .form-control[readonly], .form-control:disabled {
        background-color: #1A1F26 !important;
        border-color: #2D333B !important;
        color: #94A3B8 !important;
        cursor: not-allowed;
    }
    .form-control::placeholder, textarea::placeholder {
        color: #94A3B8 !important;
        opacity: 0.65;
    }

    /* Buttons (DESIGN.md §9) */
    .btn-submit-spj {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 14px !important;
        border-radius: 8px !important;
        height: 40px !important;
        padding: 0 24px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
        transition: all 120ms ease !important;
    }
    .btn-submit-spj:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        color: #FFFFFF !important;
    }

    .btn-cancel-spj {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        border-radius: 8px !important;
        height: 40px !important;
        padding: 0 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        text-decoration: none !important;
        transition: all 120ms ease !important;
    }
    .btn-cancel-spj:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }

    .error-help-block {
        color: #F87171 !important;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="page-content">
  <div class="container-fluid">
    {{-- Page Header --}}
    <div class="row mb-3">
      <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2">
          <div>
            <div class="d-flex align-items-center gap-2">
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Edit SPJ</h4>
              <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Perbarui Data</span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Perbarui data surat tugas perjalanan dinas dan status pembayaran</p>
          </div>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="{{ url('/daftar-spj') }}">SPJ</a></li>
              <li class="breadcrumb-item active">Edit SPJ</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    {{-- Form Content Card --}}
    <div class="row justify-content-center">
      <div class="col-xxl-12">
        <div class="card card-form-spj shadow-sm">
          <div class="card-header">
            <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Informasi Penugasan Perjalanan (SPJ)</h5>
          </div>
          <form action="{{ url('/update-spj/'. $spj->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="card-body p-4">
              <div class="row g-4">
                {{-- Left Column --}}
                <div class="col-lg-6">
                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">SPJ <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                      <select class="form-select" name="spj" id="spj-select" required>
                        <option value="">-- Pilih SPJ --</option>
                        <option value="Tokabe" {{ old('spj', $spj->perusahaan) == 'Tokabe' ? 'selected' : '' }}>Tokabe</option>
                        <option value="Personal" {{ old('spj', $spj->perusahaan) == 'Personal' ? 'selected' : '' }}>Personal</option>
                      </select>
                      @error('spj')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Nomor SPJ</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="nomor_spj" id="nomor-spj" placeholder="Nomor SPJ" readonly value="{{ old('nomor_spj', $spj->nomor_spj) }}">
                      @error('nomor_spj')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Tanggal Tugas <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control flatpickr-input" name="tanggal_tugas" data-provider="flatpickr" data-date-format="d/m/Y" placeholder="dd/mm/yyyy" value="{{ \Carbon\Carbon::parse($spj->tanggal_tugas)->format('d/m/Y') }}">
                      @error('tanggal_tugas')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Waktu Berangkat</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="waktu_berangkat" name="waktu_berangkat" placeholder="HH:MM" value="{{ old('waktu_berangkat', $spj->waktu_berangkat) }}">
                      @error('waktu_berangkat')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Tujuan <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="tujuan" placeholder="Tujuan Perjalanan" value="{{ old('tujuan', $spj->tujuan) }}" required>
                      @error('tujuan')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Nama Pemberi Tugas</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="nama_pemberi_tugas" placeholder="Nama Pemberi Tugas" value="{{ old('nama_pemberi_tugas', $spj->nama_pemberi_tugas) }}">
                      @error('nama_pemberi_tugas')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Nama Kurir/Petugas <span class="text-danger">*</span></label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" name="nama_kurir" placeholder="Nama Kurir / Petugas" value="{{ old('nama_kurir', $spj->nama_kurir) }}" required>
                      @error('nama_kurir')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Biaya Bahan Bakar</label>
                    <div class="col-lg-9">
                      <input type="number" step="0.01" class="form-control" name="biaya_bahan_bakar" placeholder="0" value="{{ old('biaya_bahan_bakar', $spj->biaya_bahan_bakar) }}">
                      @error('biaya_bahan_bakar')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-6">
                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Jarak Tempuh (km)</label>
                    <div class="col-lg-9">
                      <input type="number" step="0.01" class="form-control" name="jarak_tempuh" placeholder="0" value="{{ old('jarak_tempuh', $spj->jarak_tempuh) }}">
                      @error('jarak_tempuh')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Deskripsi Barang</label>
                    <div class="col-lg-9">
                      <textarea class="form-control" name="deskripsi_barang" rows="2" placeholder="Deskripsi Barang / Peralatan">{{ old('deskripsi_barang', $spj->deskripsi_barang) }}</textarea>
                      @error('deskripsi_barang')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Deskripsi Tugas</label>
                    <div class="col-lg-9">
                      <textarea class="form-control" name="deskripsi_tugas" rows="2" placeholder="Deskripsi Tugas">{{ old('deskripsi_tugas', $spj->deskripsi_tugas) }}</textarea>
                      @error('deskripsi_tugas')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label">Keterangan Tambahan</label>
                    <div class="col-lg-9">
                      <textarea class="form-control" name="keterangan_tambahan" rows="2" placeholder="Keterangan Tambahan">{{ old('keterangan_tambahan', $spj->keterangan_tambahan) }}</textarea>
                      @error('keterangan_tambahan')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Jam Pulang</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="jam_kembali" name="jam_kembali" placeholder="HH:MM" value="{{ old('jam_kembali', $spj->jam_kembali) }}">
                      @error('jam_kembali')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="mb-3 row align-items-center">
                    <label class="col-lg-3 col-form-label">Status Pembayaran</label>
                    <div class="col-lg-9">
                      <select class="form-select" name="status" required>
                        <option value="Terbayar" {{ old('status', $spj->status) == 'Terbayar' ? 'selected' : '' }}>Terbayar</option>
                        <option value="Belum Bayar" {{ old('status', $spj->status) == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                      </select>
                      @error('status')
                        <span class="error-help-block">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>

              {{-- Bottom Action Buttons --}}
              <div class="d-flex gap-2 justify-content-center mt-4 pt-2">
                <button type="submit" class="btn btn-submit-spj">
                  <i class="ri-save-line fs-16"></i>Perbarui SPJ
                </button>
                <a href="{{ url('/daftar-spj') }}" class="btn btn-cancel-spj">Batal</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('plugins')
<script src="{{ asset('libs/dropzone/dropzone-min.js') }}"></script>
<script src="{{ asset('libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
<script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('js/halaman/spj.js') }}"></script>
<script>
  flatpickr("#waktu_berangkat", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });

  flatpickr("#jam_kembali", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });
</script>
@endsection