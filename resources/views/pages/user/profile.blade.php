@extends('layout.template')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* ==========================================================================
       PROFIL PENGGUNA STYLESHEET — DESIGN.md
       - §4 Mode Gelap & Canvas Surface (#161B22)
       - §9 Button (Brand Primary, Ghost Tonal)
       - §10 Form & Input
       - §14 Badge Status & Role
       ========================================================================== */

    /* Card Profile Styling */
    .card-profile-user {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3) !important;
        overflow: hidden;
    }

    /* Profile Header Banner */
    .profile-card-header {
        background: linear-gradient(180deg, #1A1F26 0%, #161B22 100%) !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 24px 28px !important;
    }

    .profile-avatar-box {
        width: 68px;
        height: 68px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.25) 0%, rgba(139, 92, 246, 0.2) 100%);
        border: 1px solid rgba(99, 102, 241, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .avatar-initials {
        font-size: 22px;
        font-weight: 700;
        color: #C7D2FE;
        letter-spacing: 0.5px;
    }

    .profile-user-name {
        font-size: 18px;
        font-weight: 700;
        color: #F8F9FA;
        letter-spacing: -0.015em;
    }

    .profile-user-email {
        font-size: 13px;
        color: #94A3B8;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Role & Status Badges */
    .badge-role-user {
        display: inline-flex;
        align-items: center;
        background-color: rgba(99, 102, 241, 0.12) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }

    .badge-status-user {
        display: inline-flex;
        align-items: center;
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }

    .badge-edit-mode {
        display: inline-flex;
        align-items: center;
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #FCD34D !important;
        border: 1px solid rgba(245, 158, 11, 0.35) !important;
        font-weight: 600 !important;
        padding: 3px 10px !important;
        border-radius: 9999px !important;
        font-size: 11px !important;
    }

    /* Form Body & Labels */
    .card-profile-user .card-body {
        padding: 28px !important;
    }

    .card-profile-user .card-footer {
        background-color: #181E26 !important;
        border-top: 1px solid #282E37 !important;
        padding: 16px 28px !important;
    }

    .form-label-profile {
        font-size: 11.5px !important;
        font-weight: 600 !important;
        color: #94A3B8 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px !important;
        display: block;
    }

    /* Form Controls */
    .card-profile-user .form-control {
        background-color: #181E26 !important;
        border: 1px solid #282E37 !important;
        color: #CBD5E1 !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        padding: 9px 13px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease, background-color 150ms ease;
    }

    .card-profile-user .form-control.is-editable {
        background-color: #212529 !important;
        border-color: #383E45 !important;
        color: #F8F9FA !important;
    }

    .card-profile-user .form-control.is-editable:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
        outline: none !important;
        color: #FFFFFF !important;
    }

    .card-profile-user .form-control::placeholder {
        color: #64748B !important;
    }

    /* Readonly Info Field */
    .info-readonly-box {
        background-color: #181E26;
        border: 1px solid #282E37;
        border-radius: 8px;
        padding: 9px 13px;
        color: #CBD5E1;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Password Inputgroup & Toggle */
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

    /* Action Buttons (DESIGN.md §9) */
    .btn-edit-profile {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 9px 22px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3) !important;
    }

    .btn-edit-profile:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
        color: #FFFFFF !important;
    }

    .btn-save-profile {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 9px 22px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3) !important;
    }

    .btn-save-profile:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
    }

    .btn-cancel-profile {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-weight: 500 !important;
        font-size: 13px !important;
        padding: 9px 18px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-cancel-profile:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }

    .error-help-block {
        color: #EF4444 !important;
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
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Profil Saya</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">
                                {{ Auth::user()->role ?? 'User' }}
                            </span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Informasi akun pribadi, kredensial, dan kontak pengguna Tokabe</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0" style="font-size: 13px;">
                            <li class="breadcrumb-item"><a href="{{ url('/welcomeTKB') }}" style="color: #94A3B8;">Dashboard</a></li>
                            <li class="breadcrumb-item active" style="color: #CBD5E1;">Profil</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form & Card Content --}}
        <div class="row justify-content-center">
            <div class="col-xxl-8 col-xl-9 col-lg-10">
                <div class="card card-profile-user mb-4">
                    {{-- Profile Header Banner --}}
                    <div class="profile-card-header">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-avatar-box">
                                    <div class="avatar-initials">
                                        {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 2)) }}
                                    </div>
                                </div>
                                <div class="profile-meta-info">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <h4 class="profile-user-name mb-0">{{ Auth::user()->nama }}</h4>
                                        <span class="badge-role-user">
                                            <i class="ri-shield-user-line me-1"></i>{{ Auth::user()->role ?? 'Karyawan' }}
                                        </span>
                                        <span class="badge-status-user">
                                            <i class="ri-checkbox-circle-line me-1"></i>{{ Auth::user()->status ?? 'Aktif' }}
                                        </span>
                                    </div>
                                    <p class="profile-user-email mb-0 mt-1">
                                        <i class="ri-mail-line me-1"></i>{{ Auth::user()->email }}
                                        @if(Auth::user()->nomor_telepon)
                                            <span class="mx-2" style="opacity: 0.3;">•</span>
                                            <i class="ri-phone-line me-1"></i>{{ Auth::user()->nomor_telepon }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div id="statusEditBadge" style="display: none;">
                                <span class="badge-edit-mode">
                                    <i class="ri-edit-2-line me-1"></i>Mode Edit Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Profile --}}
                    <form method="POST" action="{{ route('user.profile.update', Auth::user()->id) }}" class="needs-validation" id="profileForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="row g-4">
                                {{-- Nama Karyawan --}}
                                <div class="col-md-6">
                                    <label class="form-label-profile" for="namaKaryawan">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input 
                                        type="text"
                                        class="form-control" 
                                        id="namaKaryawan" 
                                        name="namaKaryawan" 
                                        placeholder="Nama Lengkap" 
                                        value="{{ old('namaKaryawan', Auth::user()->nama) }}" 
                                        required 
                                        readonly
                                    >
                                    @error('namaKaryawan')
                                        <span class="error-help-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Email Karyawan --}}
                                <div class="col-md-6">
                                    <label class="form-label-profile" for="email">Email Karyawan <span class="text-danger">*</span></label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="email" 
                                        name="email" 
                                        placeholder="contoh@email.com" 
                                        value="{{ old('email', Auth::user()->email) }}" 
                                        required 
                                        readonly
                                    >
                                    @error('email')
                                        <span class="error-help-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Nomor Telepon --}}
                                <div class="col-md-6">
                                    <label class="form-label-profile" for="phone-number">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="phone-number" 
                                        name="no_telp" 
                                        placeholder="08xxxxxxxxxx" 
                                        value="{{ old('no_telp', Auth::user()->nomor_telepon) }}" 
                                        required 
                                        readonly
                                    >
                                    @error('no_telp')
                                        <span class="error-help-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6">
                                    <label class="form-label-profile" for="password-input">Password <span class="text-muted" style="font-size: 11px; font-weight: normal;">(Kosongkan jika tidak diubah)</span></label>
                                    <div class="auth-pass-inputgroup">
                                        <input 
                                            type="password" 
                                            class="form-control password-input" 
                                            id="password-input" 
                                            name="password" 
                                            placeholder="Ketik password baru (opsional)" 
                                            value="" 
                                            readonly
                                        >
                                        <button class="password-addon-btn password-addon" type="button" id="password-addon" title="Lihat/Sembunyikan Password">
                                            <i class="ri-eye-line fs-17"></i>
                                        </button>
                                    </div>
                                    <small id="passwordNote" class="text-muted mt-1" style="font-size: 11.5px; display: none;">
                                        Ketik password baru untuk mengubah, atau masukkan password saat ini untuk konfirmasi data.
                                    </small>
                                    @error('password')
                                        <span class="error-help-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Role Akses (Readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label-profile">Role / Hak Akses</label>
                                    <div class="info-readonly-box">
                                        <span>{{ Auth::user()->role ?? 'Karyawan' }}</span>
                                        <span class="badge-role-user">Permanen</span>
                                    </div>
                                </div>

                                {{-- Status Akun (Readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label-profile">Status Akun</label>
                                    <div class="info-readonly-box">
                                        <span>{{ Auth::user()->status ?? 'Aktif' }}</span>
                                        <span class="badge-status-user"><i class="ri-check-line me-1"></i>Terverifikasi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer Actions --}}
                        <div class="card-footer d-flex justify-content-end align-items-center gap-2">
                            <button type="button" id="edit-button" class="btn-edit-profile">
                                <i class="ri-edit-line"></i> Edit Profil
                            </button>

                            <button type="button" id="cancel-button" class="btn-cancel-profile" style="display: none;">
                                Batal
                            </button>

                            <button type="submit" id="simpan" class="btn-save-profile" style="display: none;">
                                <i class="ri-save-3-line"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\ProfileRequest') !!}
@endsection

@section('plugins')
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
<script src="{{ asset('js/pages/password-addon.init.js') }}"></script>

<script>
    $(document).ready(function() {
        // Simpan nilai awal form untuk rollback saat batal
        var initialData = {
            nama: $('#namaKaryawan').val(),
            email: $('#email').val(),
            phone: $('#phone-number').val(),
            password: $('#password-input').val()
        };

        // Masuk ke Mode Edit
        $('#edit-button').on('click', function() {
            var editableInputs = $('#namaKaryawan, #email, #phone-number, #password-input');
            editableInputs.removeAttr('readonly').addClass('is-editable');
            
            $('#edit-button').hide();
            $('#cancel-button').fadeIn(150);
            $('#simpan').fadeIn(150);
            $('#statusEditBadge').fadeIn(150);
            $('#passwordNote').show();

            $('#namaKaryawan').focus();
        });

        // Batalkan Mode Edit (Rollback)
        $('#cancel-button').on('click', function() {
            $('#namaKaryawan').val(initialData.nama);
            $('#email').val(initialData.email);
            $('#phone-number').val(initialData.phone);
            $('#password-input').val(initialData.password);

            var editableInputs = $('#namaKaryawan, #email, #phone-number, #password-input');
            editableInputs.attr('readonly', true).removeClass('is-editable');

            $('#cancel-button').hide();
            $('#simpan').hide();
            $('#statusEditBadge').hide();
            $('#passwordNote').hide();
            $('#edit-button').fadeIn(150);
        });

        // Jika terdapat error validasi dari backend, langsung aktifkan mode edit
        @if($errors->any())
            $('#edit-button').trigger('click');
        @endif
    });
</script>
@endsection