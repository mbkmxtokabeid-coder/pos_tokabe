@extends('layout.template')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* ==========================================================================
       DESIGN.md — FORM & CARD SURFACES (§10 & §12)
       Neutral dark #161B22, border #282E37, inputs #212529, accent #6366F1
       ========================================================================== */
    .card-form-spb {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .card-form-spb .card-header {
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
    .form-control::placeholder, textarea::placeholder {
        color: #94A3B8 !important;
        opacity: 0.7;
    }

    /* Choices.js Dark Styling (DESIGN.md §11) */
    .choices {
        margin-bottom: 0 !important;
    }
    .choices__inner {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        color: #F8F9FA !important;
        min-height: 40px !important;
        padding: 6px 12px !important;
        font-size: 13.5px !important;
    }
    .is-focused .choices__inner, .is-open .choices__inner {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
        background-color: #262B32 !important;
    }
    .choices__list--dropdown {
        background-color: #181E26 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        color: #F8F9FA !important;
        z-index: 1050 !important;
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
        background-color: #262B32 !important;
        color: #FFFFFF !important;
    }
    .choices__list--dropdown .choices__input {
        background-color: #212529 !important;
        border-bottom: 1px solid #383E45 !important;
        color: #F8F9FA !important;
    }

    /* Logo Box */
    .logo-preview-box {
        background-color: #1A1F26;
        border: 1px dashed #383E45;
        border-radius: 8px;
        padding: 6px 12px;
        height: 60px;
        width: 256px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Item Table (DESIGN.md §13) */
    .table-spb-items {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        margin-bottom: 0 !important;
        border: 1px solid #282E37 !important;
        border-radius: 8px !important;
        overflow: hidden;
        width: 100% !important;
    }
    .table-spb-items thead th {
        background-color: #181E26 !important;
        color: #94A3B8 !important;
        font-size: 11.5px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        padding: 12px 14px !important;
        white-space: nowrap;
    }
    .table-spb-items tbody td, .table-spb-items tbody th {
        padding: 10px 12px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #21262D !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        color: #CBD5E1 !important;
    }
    .table-spb-items tbody tr:last-child td,
    .table-spb-items tbody tr:last-child th {
        border-bottom: none !important;
    }

    /* Actions and Buttons (DESIGN.md §9) */
    .btn-hapus-item {
        background-color: rgba(239, 68, 68, 0.1) !important;
        border: 1px solid rgba(239, 68, 68, 0.25) !important;
        color: #F87171 !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        font-weight: 500 !important;
        font-size: 12.5px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        transition: all 120ms ease !important;
    }
    .btn-hapus-item:hover {
        background-color: #EF4444 !important;
        border-color: #EF4444 !important;
        color: #FFFFFF !important;
    }

    .btn-tambah-item {
        background-color: rgba(99, 102, 241, 0.1) !important;
        border: 1px dashed rgba(99, 102, 241, 0.35) !important;
        color: #818CF8 !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        text-decoration: none !important;
        transition: all 120ms ease !important;
    }
    .btn-tambah-item:hover {
        background-color: rgba(99, 102, 241, 0.2) !important;
        color: #A5B4FC !important;
        border-color: #6366F1 !important;
    }

    .btn-submit-spb {
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
    .btn-submit-spb:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        color: #FFFFFF !important;
    }

    .btn-cancel-spb {
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
    .btn-cancel-spb:hover {
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
@if(Session::has('error'))
<script>
    $(document).ready(function () {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'SPB Gagal diperbarui',
            confirmButtonColor: '#6366F1',
            background: '#161B22',
            color: '#F8F9FA'
        });
    });
</script>
@endif
@if($errors->any())
<script>
    $(document).ready(function () {
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            text: 'Terdapat input yang kosong atau tidak valid. Silakan lengkapi form.',
            confirmButtonColor: '#6366F1',
            background: '#161B22',
            color: '#F8F9FA'
        });
    });
</script>
@endif

<div class="page-content">
    <div class="container-fluid">
        {{-- Page Title --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Edit SPB</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Perbarui Data</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Perbarui data surat pengantar barang pelanggan</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ url('daftar-spb') }}">SPB</a></li>
                            <li class="breadcrumb-item active">Edit SPB</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Content --}}
        <div class="row justify-content-center">
            <div class="col-xxl-10">
                <div class="card card-form-spb shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Informasi Surat Pengantar Barang</h5>
                    </div>
                    <form method="POST" action="{{ url('update-spb/' . $spb->id) }}" class="needs-validation" id="spb_form" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card-body p-4">
                            {{-- Header Form & Logo --}}
                            <div class="row g-4 align-items-start mb-4">
                                <div class="col-lg-8">
                                    <div class="row g-3">
                                        {{-- Nama Perusahaan SPB --}}
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-lg-4 col-form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                                <div class="col-lg-8">
                                                    <input type="hidden" name="namaSpb" value="{{ old('namaSpb', $spb->nama_spb ?? 2) }}">
                                                    <input type="text" class="form-control" value="{{ optional($namaPerusahaan->firstWhere('id', $spb->nama_spb))->nama_perusahaan ?? ($namaPerusahaan->first()->nama_perusahaan ?? 'Total Karya Berkah') }}" readonly style="background-color: #181E26 !important; color: #F1F5F9 !important; border-color: #383E45 !important; cursor: not-allowed;">
                                                    @error('namaSpb')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Nama Customer --}}
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-lg-4 col-form-label">Nama Customer <span class="text-danger">*</span></label>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" name="customer" id="customer" list="pelanggan" placeholder="Nama Pelanggan" value="{{$spb->customer}}" required>
                                                    <datalist id="pelanggan">
                                                        @foreach ($customer as $cust)
                                                            <option value="{{$cust}}">
                                                        @endforeach
                                                    </datalist>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Perusahaan Pelanggan --}}
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-lg-4 col-form-label">Perusahaan Pelanggan <span class="text-danger">*</span></label>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" name="perusahaan" id="usaha" list="usaha" placeholder="Perusahaan Pelanggan" value="{{$spb->perusahaan}}" required>
                                                    <datalist id="usaha">
                                                        @foreach ($perusahaan as $usaha)
                                                            <option value="{{$usaha}}">
                                                        @endforeach
                                                    </datalist>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Nomor Telepon --}}
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label for="phone-number" class="col-lg-4 col-form-label">Nomor Telepon</label>
                                                <div class="col-lg-8">
                                                    <input type="text" name="no_telp" class="form-control" value="{{$spb->nomor_telepon}}" placeholder="xxxx xxxx xxxx" id="phone-number">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Nama Penandatangan --}}
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <label class="col-lg-4 col-form-label">Nama Penandatangan</label>
                                                <div class="col-lg-8">
                                                    <input type="text" name="nama_penandatangan" class="form-control" value="{{ old('nama_penandatangan') ?? $spb->nama_penandatangan }}" placeholder="Nama di bawah tanggal (Contoh: Oky Irawan)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Logo Preview --}}
                                <div class="col-lg-4 d-flex justify-content-lg-end justify-content-center">
                                    <div class="logo-preview-box shadow-sm">
                                        <img src="{{asset('images/LogoTKB.png')}}" class="user-profile-image img-fluid" alt="Logo Tokabe" style="max-height: 48px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>

                            <hr style="border-color: #282E37; margin: 24px 0;">

                            {{-- Rincian Item Barang --}}
                            <div class="mb-3">
                                <h6 class="fs-14 fw-semibold text-white mb-3">Rincian Barang yang Diantar</h6>
                                <div class="table-responsive">
                                    <table class="table table-spb-items align-middle" id="item-table">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width: 50px;" class="text-center">#</th>
                                                <th scope="col" style="min-width: 260px;">Item & Deskripsi</th>
                                                <th scope="col" style="width: 130px;">Satuan</th>
                                                <th scope="col" style="width: 100px;" class="text-center">Quantity</th>
                                                <th scope="col" style="min-width: 200px;">Keterangan</th>
                                                <th scope="col" style="width: 90px;" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="newlink">
                                            @foreach ($brg_spb as $index => $brg)
                                                @foreach ($barangs[$index] as $item)
                                                    <tr id="{{$index + 1}}" class="product align-middle">
                                                        <th scope="row" class="product-id text-center text-muted fw-semibold">{{$index + 1}}</th>
                                                        <td class="text-start">
                                                            <div class="mb-2">
                                                                <select class="form-select @error('barang_id[]') is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-{{$index + 1}}" name="barang_id[]">
                                                                    <option value="{{$item->id}}">{{$item->nama_kategori}}</option>
                                                                    @foreach ($kategori_brg as $items)
                                                                        @if ($item->id != $items->id)
                                                                            <option value="{{$items->id}}">{{$items->jenis_barang}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @error('barang_id[]')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                            <textarea class="form-control @error('deskripsi_item[]') is-invalid @enderror" id="productDetails-{{$index + 1}}" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item">{{$brg->deskripsi}}</textarea>
                                                            @error('deskripsi_item[]')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <select class="form-select @error ('satuan[]') is-invalid @enderror" id="satuan-{{$index + 1}}" name="satuan[]" required>
                                                                <option disabled value="">Pilih</option>
                                                                <option value="Set" {{ $brg->satuan == 'Set' ? 'selected' : '' }}>Set</option>
                                                                <option value="Pcs" {{ $brg->satuan == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                                                                <option value="Kotak" {{ $brg->satuan == 'Kotak' ? 'selected' : '' }}>Kotak</option>
                                                                <option value="Blok" {{ $brg->satuan == 'Blok' ? 'selected' : '' }}>Blok</option>
                                                                <option value="Lbr" {{ $brg->satuan == 'Lbr' ? 'selected' : '' }}>Lbr</option>
                                                            </select>
                                                            @error('satuan[]')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <div class="input-step">
                                                                <input type="number" class="form-control text-center @error('qty[]') is-invalid @enderror" id="product-qty-{{$index + 1}}" value="{{$brg->qty}}" name="qty[]">
                                                            </div>
                                                            @error('qty[]')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <textarea id="productKeterangan-{{$index + 1}}" class="form-control @error('keterangan[]') is-invalid @enderror" name="keterangan[]" cols="2" placeholder="Keterangan">{{$brg->keterangan}}</textarea>
                                                            @error('keterangan[]')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                        <td class="product-removal text-center">
                                                            <a href="javascript:void(0)" class="btn btn-hapus-item">
                                                                <i class="ri-delete-bin-line fs-15 me-1"></i>Hapus
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="pt-3">
                                                    <a href="javascript:new_link()" id="add-item" class="btn btn-tambah-item">
                                                        <i class="ri-add-line fs-16"></i>Tambah Item
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Bottom Action Buttons --}}
                            <div class="d-flex gap-2 mt-4 pt-2">
                                <button type="submit" id="simpan" class="btn btn-submit-spb">
                                    <i class="ri-save-line fs-16"></i>Simpan Perubahan
                                </button>
                                <a href="{{ url('daftar-spb') }}" class="btn btn-cancel-spb">Batal</a>
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
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! app('jsvalidator')->formRequest('App\Http\Requests\SPBRequest', '#spb_form') !!}
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/spb.js')}}"></script>
@endsection