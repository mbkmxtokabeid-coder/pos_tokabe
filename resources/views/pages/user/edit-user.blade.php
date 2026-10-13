@extends('layout.template')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  /* DESIGN.md Compliance: Neutral Charcoal Theme (#161B22) */
  .card-form-user {
      background-color: #161B22 !important;
      border: 1px solid #282E37 !important;
      border-radius: 12px !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
      overflow: hidden;
  }
  .card-form-user .card-header {
      background-color: #161B22 !important;
      border-bottom: 1px solid #282E37 !important;
      padding: 18px 24px !important;
  }
  .card-form-user .card-body {
      padding: 24px !important;
  }
  .card-form-user .card-footer {
      background-color: #181E26 !important;
      border-top: 1px solid #282E37 !important;
      padding: 16px 24px !important;
  }

  .form-label-user {
      font-size: 11.5px !important;
      font-weight: 600 !important;
      color: #94A3B8 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      margin-bottom: 6px !important;
      display: block;
  }
  .card-form-user .form-control,
  .card-form-user .form-select {
      background-color: #212529 !important;
      border: 1px solid #383E45 !important;
      color: #F8F9FA !important;
      border-radius: 8px !important;
      font-size: 13.5px !important;
      padding: 9px 13px !important;
      transition: border-color 150ms ease, box-shadow 150ms ease;
  }
  .card-form-user .form-control:focus,
  .card-form-user .form-select:focus {
      border-color: #6366F1 !important;
      box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
      outline: none !important;
      color: #FFFFFF !important;
  }
  .card-form-user .form-control::placeholder {
      color: #64748B !important;
  }

  /* Password addon button */
  .auth-pass-inputgroup {
      position: relative;
  }
  .password-addon-btn {
      position: absolute;
      right: 1px;
      top: 1px;
      bottom: 1px;
      background: transparent;
      border: none;
      color: #94A3B8;
      padding: 0 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border-radius: 0 8px 8px 0;
      transition: color 150ms ease;
  }
  .password-addon-btn:hover {
      color: #F8F9FA;
  }

  .btn-submit-user {
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
  .btn-submit-user:hover {
      background-color: #4F46E5 !important;
      border-color: #4F46E5 !important;
      box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
  }
  .btn-back-user {
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
  .btn-back-user:hover {
      background-color: #2D333B !important;
      color: #FFFFFF !important;
      border-color: #475569 !important;
  }

  .help-block.error-help-block {
      color: #EF4444 !important;
      font-size: 12px !important;
      margin-top: 4px;
      display: block;
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
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Edit Karyawan</h4>
              <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">User Management</span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Perbarui data karyawan dan peran akses sistem Tokabe</p>
          </div>
          <div class="page-title-right">
            <ol class="breadcrumb m-0" style="font-size: 13px;">
              <li class="breadcrumb-item"><a href="{{ route('user.index') }}" style="color: #94A3B8;">Karyawan</a></li>
              <li class="breadcrumb-item active" style="color: #CBD5E1;">Edit Karyawan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    {{-- Form Content --}}
    <div class="row justify-content-center">
      <div class="col-xxl-8 col-xl-9 col-lg-10">
        <div class="card card-form-user mb-4">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <i class="ri-edit-line text-primary fs-18"></i>
              <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Form Edit Karyawan</h5>
            </div>
            <a href="{{ route('user.index') }}" class="btn-back-user">
              <i class="ri-arrow-left-line"></i> Kembali ke Daftar
            </a>
          </div>

          <form method="POST" action="{{ route('user.update', $user->id) }}" class="needs-validation">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="row g-3">
                {{-- Nama Karyawan --}}
                <div class="col-md-6">
                  <label class="form-label-user" for="namaKaryawan">Nama Karyawan <span class="text-danger">*</span></label>
                  <input class="form-control" id="namaKaryawan" placeholder="Nama Karyawan" name="namaKaryawan" value="{{ old('namaKaryawan', $user->nama) }}" required>
                  @error('namaKaryawan')
                    <span class="help-block error-help-block">{{ $message }}</span>
                  @enderror
                </div>

                {{-- Email Karyawan --}}
                <div class="col-md-6">
                  <label class="form-label-user" for="email">Email Karyawan <span class="text-danger">*</span></label>
                  <input class="form-control" type="email" id="email" name="email" placeholder="contoh@email.com" value="{{ old('email', $user->email) }}" required>
                  @error('email')
                    <span class="help-block error-help-block">{{ $message }}</span>
                  @enderror
                </div>

                {{-- Password (Opsional saat Edit) --}}
                <div class="col-md-6">
                  <label class="form-label-user" for="password-input">Password Baru (Opsional)</label>
                  <div class="auth-pass-inputgroup">
                    <input class="form-control password-input" type="password" id="password-input" name="password" placeholder="Kosongkan jika tidak diubah" value="{{ old('password') }}">
                    <button class="password-addon-btn password-addon" type="button" id="password-addon">
                      <i class="ri-eye-line fs-17"></i>
                    </button>
                  </div>
                  @error('password')
                    <span class="help-block error-help-block">{{ $message }}</span>
                  @enderror
                </div>

                {{-- Ulangi Password --}}
                <div class="col-md-6">
                  <label class="form-label-user" for="re_password">Ulangi Password Baru</label>
                  <div class="auth-pass-inputgroup">
                    <input class="form-control password-input" type="password" id="re_password" name="re_password" placeholder="Ketik ulang password baru" value="{{ old('re_password') }}">
                    <button class="password-addon-btn password-addon" type="button" id="re-password-addon">
                      <i class="ri-eye-line fs-17"></i>
                    </button>
                  </div>
                  @error('re_password')
                    <span class="help-block error-help-block">{{ $message }}</span>
                  @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="col-md-6">
                  <label class="form-label-user" for="phone-number">Nomor Telepon</label>
                  <input type="text" value="{{ old('no_telp', $user->nomor_telepon) }}" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx" id="phone-number">
                  @error('no_telp')
                    <span class="help-block error-help-block">{{ $message }}</span>
                  @enderror
                </div>

                {{-- Role --}}
                <div class="col-md-6">
                  <label class="form-label-user" for="role">Role Akses <span class="text-danger">*</span></label>
                  <select name="role" id="role" class="form-select" required>
                    @foreach($roles as $role)
                      <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                        {{ $role }}
                      </option>
                    @endforeach
                  </select>
                  @error('role')
                    <span class="help-block error-help-block">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
              <a href="{{ route('user.index') }}" class="btn-back-user">Batal</a>
              <button type="submit" id="simpan" class="btn-submit-user">
                <i class="ri-save-3-line"></i> Perbarui Karyawan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\UserRequest') !!}
@endsection

@section('plugins')
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
<script src="{{ asset('js/pages/password-addon.init.js') }}"></script>
@endsection