@extends('layout.template')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  /* DESIGN.md Compliance: Neutral Charcoal Dark Theme (#161B22) */
  .card-form-budget {
    background-color: #161B22 !important;
    border: 1px solid #282E37 !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25) !important;
    overflow: hidden;
  }
  .card-form-budget .card-header {
    background-color: #161B22 !important;
    border-bottom: 1px solid #282E37 !important;
    padding: 18px 24px !important;
  }
  .card-form-budget .card-body {
    padding: 24px !important;
  }
  .card-form-budget .card-footer {
    background-color: #181E26 !important;
    border-top: 1px solid #282E37 !important;
    padding: 16px 24px !important;
  }

  /* Form Labels & Controls */
  .form-label-budget {
    font-size: 11.5px !important;
    font-weight: 600 !important;
    color: #94A3B8 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin-bottom: 7px !important;
    display: block;
  }
  .form-label-budget span.req {
    color: #EF4444 !important;
  }

  .card-form-budget .form-control,
  .card-form-budget .form-select {
    background-color: #212529 !important;
    border: 1px solid #383E45 !important;
    color: #F8F9FA !important;
    border-radius: 8px !important;
    font-size: 13.5px !important;
    padding: 9px 13px !important;
    transition: border-color 150ms ease, box-shadow 150ms ease;
  }
  .card-form-budget .form-control:focus,
  .card-form-budget .form-select:focus {
    border-color: #6366F1 !important;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
    outline: none !important;
    color: #FFFFFF !important;
  }
  .card-form-budget .form-control::placeholder {
    color: #64748B !important;
  }

  /* Input Group Addons */
  .input-group-addon-custom {
    background-color: #181E26 !important;
    border: 1px solid #383E45 !important;
    color: #94A3B8 !important;
    font-weight: 600 !important;
    font-size: 13px !important;
    padding: 9px 14px !important;
  }
  .input-group > .form-control {
    border-radius: 0 8px 8px 0 !important;
  }
  .input-group > .input-group-addon-custom {
    border-radius: 8px 0 0 8px !important;
  }

  /* Buttons */
  .btn-save-custom {
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
  .btn-save-custom:hover {
    background-color: #4F46E5 !important;
    border-color: #4F46E5 !important;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
  }

  .btn-cancel-custom {
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
  .btn-cancel-custom:hover {
    background-color: #2D333B !important;
    color: #FFFFFF !important;
    border-color: #475569 !important;
  }

  .btn-delete-custom {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background-color: rgba(239, 68, 68, 0.12) !important;
    border: 1px solid rgba(239, 68, 68, 0.3) !important;
    color: #F87171 !important;
    font-weight: 600 !important;
    font-size: 13px !important;
    padding: 9px 18px !important;
    border-radius: 8px !important;
    transition: all 150ms ease !important;
    cursor: pointer;
  }
  .btn-delete-custom:hover {
    background-color: #EF4444 !important;
    border-color: #EF4444 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
  }

  /* Validation error style */
  .error-help-block {
    color: #F87171 !important;
    font-size: 12px !important;
    margin-top: 5px !important;
    display: block;
    font-weight: 500;
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
                <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Edit Anggaran</h4>
                <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">
                  {{ $budget->nama_budget }}
                </span>
              </div>
              <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Perbarui informasi pos anggaran dan alokasi nominal</p>
            </div>
            <div class="page-title-right">
              <ol class="breadcrumb m-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="javascript:void(0);" style="color: #94A3B8;">Anggaran</a></li>
                <li class="breadcrumb-item"><a href="{{ route('budget.list') }}" style="color: #94A3B8;">Daftar Anggaran</a></li>
                <li class="breadcrumb-item active" style="color: #CBD5E1;">Edit Anggaran</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      {{-- Form Card --}}
      <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
          <div class="card card-form-budget">

            <div class="card-header d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <i class="ri-edit-2-line text-primary fs-18"></i>
                <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Ubah Detail Pos Anggaran</h5>
              </div>
              <span class="text-muted fs-12">Semua field bertanda <span class="text-danger">*</span> wajib diisi</span>
            </div>

            <form action="{{ route('budget.update', ['id' => $budget->id]) }}" class="needs-validation" novalidate method="POST">
              @csrf
              @method('put')

              <div class="card-body">
                <div class="row g-4">

                  {{-- Nama Anggaran --}}
                  <div class="col-md-6">
                    <label class="form-label-budget" for="nama_anggaran">Nama Anggaran <span class="req">*</span></label>
                    <input type="text" id="nama_anggaran" name="nama_anggaran" class="form-control" placeholder="Nama Anggaran" value="{{ old('nama_anggaran', $budget->nama_budget) }}" required>
                  </div>

                  {{-- Kategori --}}
                  <div class="col-md-6">
                    <label class="form-label-budget" for="kategori">Kategori <span class="req">*</span></label>
                    <select class="form-select" id="kategori" name="kategori" required>
                      @foreach ($kategori as $id => $nama_kategori)
                        <option value="{{ $id }}" {{ (old('kategori', $budget->kategori_id) == $id) ? 'selected' : '' }}>
                          {{ $nama_kategori }}
                        </option>
                      @endforeach
                    </select>
                  </div>

                  {{-- Nominal Anggaran --}}
                  <div class="col-md-6">
                    <label class="form-label-budget" for="cleave-numeral">Nominal Anggaran <span class="req">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text input-group-addon-custom">Rp</span>
                      <input id="cleave-numeral" name="anggaran" type="text" class="form-control fw-semibold" placeholder="0" value="{{ old('anggaran', number_format($budget->anggaran, 0, ',', ',')) }}" required>
                    </div>
                  </div>

                  {{-- Tanggal --}}
                  <div class="col-md-6">
                    <label class="form-label-budget" for="dateInput">Tanggal Alokasi <span class="req">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text input-group-addon-custom"><i class="ri-calendar-line"></i></span>
                      <input type="text" name="tanggal" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" id="dateInput" placeholder="YYYY-MM-DD" value="{{ old('tanggal', $budget->tanggal) }}" required>
                    </div>
                  </div>

                </div>
              </div>

              {{-- Footer Actions --}}
              <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="{{ route('budget.list') }}" class="btn-cancel-custom">
                  <i class="ri-arrow-left-line"></i> Kembali
                </a>

                <div class="d-flex align-items-center gap-2">
                  <button type="button" class="btn-delete-custom" onclick="confirmDelete()">
                    <i class="ri-delete-bin-line"></i> Hapus
                  </button>
                  <button type="submit" id="simpan" class="btn-save-custom">
                    <i class="ri-save-3-line"></i> Simpan Perubahan
                  </button>
                </div>
              </div>

            </form>

            {{-- Hidden Form Delete --}}
            <form id="form-delete-budget" action="{{ route('budget.delete', ['id' => $budget->id]) }}" method="POST" style="display: none;">
              @csrf
              @method('delete')
            </form>

          </div>
        </div>
      </div>

    </div>
  </div>
  {!! JsValidator::formRequest('App\Http\Requests\BudgetRequest') !!}
@endsection

@section('plugins')
<script src="{{ asset('libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
<script src="{{ asset('libs/cleave.js/cleave.min.js') }}"></script>
<script src="{{ asset('js/halaman/form-masks.js') }}"></script>

<script>
  function confirmDelete() {
    Swal.fire({
      title: 'Hapus Anggaran?',
      text: 'Apakah Anda yakin ingin menghapus anggaran "{{ addslashes($budget->nama_budget) }}"? Tindakan ini tidak dapat dibatalkan.',
      icon: 'warning',
      background: '#161B22',
      color: '#F1F5F9',
      showCancelButton: true,
      confirmButtonColor: '#EF4444',
      cancelButtonColor: '#212529',
      confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Ya, Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('form-delete-budget').submit();
      }
    });
  }
</script>
@endsection