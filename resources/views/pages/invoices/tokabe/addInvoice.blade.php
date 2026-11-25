@section('head')
<!-- dropzone css -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- <link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" /> --}}

<!-- Sweet Alert css-->
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/invoice-tokabe-form.css')}}?v={{ file_exists(public_path('css/invoice-tokabe-form.css')) ? filemtime(public_path('css/invoice-tokabe-form.css')) : time() }}" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function () {
    $('#loading-overlay').hide();
    $("#loading-spinner").hide();

    $("#invoice_form").submit(function (e) {
    e.preventDefault();

    var formElem = this;
    if (formElem.checkValidity && !formElem.checkValidity()) {
        formElem.reportValidity();
        return false;
    }

    // Validasi Nomor Invoice jika mode manual
    if ($('#opt_mode_manual').is(':checked') && !$.trim($('#manual_invoice_input').val())) {
        Swal.fire({
            icon: "warning",
            title: "Nomor Invoice Belum Diisi",
            text: "Silakan masukkan nomor invoice manual Anda.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#manual_invoice_input').focus(); });
        return false;
    }

    // Validasi Customer & Telepon
    if (!$.trim($('#customer').val())) {
        Swal.fire({
            icon: "warning",
            title: "Data Belum Lengkap",
            text: "Nama Customer wajib diisi.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#customer').focus(); });
        return false;
    }

    if (!$.trim($('#phone-number').val())) {
        Swal.fire({
            icon: "warning",
            title: "Data Belum Lengkap",
            text: "Nomor Telepon wajib diisi.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#phone-number').focus(); });
        return false;
    }

    if (!$('#choices-admin').val()) {
        Swal.fire({
            icon: "warning",
            title: "Data Belum Lengkap",
            text: "Nama Admin wajib dipilih.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#choices-admin').focus(); });
        return false;
    }

    if (!$('#choices-orderBy').val()) {
        Swal.fire({
            icon: "warning",
            title: "Data Belum Lengkap",
            text: "Order Dari wajib dipilih.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#choices-orderBy').focus(); });
        return false;
    }

    // Validasi Sales jika Order Dari Sales
    var orderVal = ($('#choices-orderBy').val() || '').toLowerCase();
    if (orderVal === 'sales') {
        var salesVal = $.trim($('#colFormLabelNamaSales').val());
        if (!salesVal) {
            Swal.fire({
                icon: "warning",
                title: "Data Belum Lengkap",
                text: "Nama Sales wajib diisi karena Order Dari Sales dipilih.",
                confirmButtonColor: "#6366F1",
            }).then(function() {
                $('#colFormLabelNamaSales').focus();
            });
            return false;
        }
    }

    if (!$.trim($('#dateInput').val())) {
        Swal.fire({
            icon: "warning",
            title: "Data Belum Lengkap",
            text: "Target Penyelesaian wajib dipilih.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#dateInput').focus(); });
        return false;
    }

    // Validasi baris item
    var rows = $('#newlink tr.product');
    if (rows.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Data Belum Lengkap",
            text: "Minimal harus ada 1 baris item.",
            confirmButtonColor: "#6366F1",
        });
        return false;
    }

    var itemValid = true;
    rows.each(function(index) {
        var rowNum = index + 1;
        var itemSelect = $(this).find('select[name^="item_id"]');
        var descInput = $(this).find('textarea[name^="deskripsi_item"]');
        var satuanSelect = $(this).find('select[name^="satuan"]');
        var hrgInput = $(this).find('input[name^="hrg"]');
        var qtyInput = $(this).find('input[name^="qty"]');

        if (!itemSelect.val()) {
            Swal.fire({
                icon: "warning",
                title: "Item Belum Dipilih",
                text: "Baris #" + rowNum + ": Silakan pilih item terlebih dahulu.",
                confirmButtonColor: "#6366F1",
            }).then(function() { itemSelect.focus(); });
            itemValid = false;
            return false;
        }

        if (!$.trim(descInput.val())) {
            Swal.fire({
                icon: "warning",
                title: "Deskripsi Kosong",
                text: "Baris #" + rowNum + ": Deskripsi item wajib diisi.",
                confirmButtonColor: "#6366F1",
            }).then(function() { descInput.focus(); });
            itemValid = false;
            return false;
        }

        if (!satuanSelect.val()) {
            Swal.fire({
                icon: "warning",
                title: "Satuan Kosong",
                text: "Baris #" + rowNum + ": Satuan item wajib dipilih.",
                confirmButtonColor: "#6366F1",
            }).then(function() { satuanSelect.focus(); });
            itemValid = false;
            return false;
        }

        var hrgNum = parseInt((hrgInput.val() || '').replace(/[^0-9]/g, '')) || 0;
        if (hrgNum <= 0) {
            Swal.fire({
                icon: "warning",
                title: "Harga Tidak Valid",
                text: "Baris #" + rowNum + ": Harga item wajib diisi dan lebih dari 0.",
                confirmButtonColor: "#6366F1",
            }).then(function() { hrgInput.focus(); });
            itemValid = false;
            return false;
        }

        var qtyNum = parseInt(qtyInput.val()) || 0;
        if (qtyNum < 1) {
            Swal.fire({
                icon: "warning",
                title: "Quantity Tidak Valid",
                text: "Baris #" + rowNum + ": Quantity item minimal 1.",
                confirmButtonColor: "#6366F1",
            }).then(function() { qtyInput.focus(); });
            itemValid = false;
            return false;
        }
    });

    if (!itemValid) return false;

    // Validasi Jenis Pembayaran
    var jnsPem = $('#choices-payment-type').val();
    if (!jnsPem) {
        Swal.fire({
            icon: "warning",
            title: "Jenis Pembayaran Kosong",
            text: "Jenis Pembayaran wajib dipilih.",
            confirmButtonColor: "#6366F1",
        }).then(function() { $('#choices-payment-type').focus(); });
        return false;
    }

    // Temporarily enable inputs so serialize captures values
    $('#input-ppn, #input-pph').prop('disabled', false);
    var formData = $(this).serialize();
    if (typeof syncTaxInputs === 'function') syncTaxInputs();

    $.ajax({
      type: $(this).attr("method"),
      url: $(this).attr("action"),
      data: formData,
      dataType: "json",
      beforeSend: function () {
        $("#loading-spinner").show();
        $('#loading-overlay').show();
      },
      success: function (response) {
        $("#loading-spinner").hide();
        $('#loading-overlay').hide();

        if (response.status === "success") {
          if (typeof clearInvoiceDraft === 'function') clearInvoiceDraft();
          // Data berhasil disimpan
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: response.message,
            confirmButtonColor: "#6366F1",
          }).then(function () {
            // Redirect ke /list-invoice/tokabe
            window.location.href = "{{ route('list_invoice_tokabe') }}";
          });
        }
      },
      error: function (xhr, status, error) {
        $("#loading-spinner").hide();
        $('#loading-overlay').hide();

        // Tangkap pesan error dari server JSON response
        var msg = "Terjadi kesalahan! Data belum lengkap.";
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                msg = xhr.responseJSON.errors[firstKey][0];
            } else if (xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
        }

        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: msg,
          confirmButtonColor: "#6366F1",
        });
      },
    });
  });
});

</script>
@endsection
@extends('layout.template')
@section('has_inline_theme_toggle', '1')
@section('content')
@include('layout.loading-overlay')
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="tkb-form-header">
            <div>
                <h4 class="tkb-form-title">Tambah Invoice Tokabe</h4>
                <div class="tkb-form-breadcrumb">
                    <a href="{{ route('list_invoice_tokabe') }}">Invoice</a>
                    <span>›</span>
                    <span class="crumb-active">Tambah invoice</span>
                </div>
            </div>
            <div class="tkb-global-header-controls">
                <button type="button" class="tkb-theme-toggle" onclick="window.toggleTokabeTheme(event)" aria-label="Ubah Mode Terang / Gelap" title="Ubah Mode Terang / Gelap">
                    <span class="tkb-theme-toggle__thumb" aria-hidden="true"></span>
                    <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--sun"><i class="ri-sun-line"></i></span>
                    <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--moon"><i class="ri-moon-clear-line"></i></span>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('storeInvoice.Tokabe') }}" validate id="invoice_form" enctype="multipart/form-data">
            @csrf

            <!-- TOP 2-COLUMN CARDS: Informasi Invoice & Data Pelanggan -->
            <div class="row g-4 mb-4">
                <!-- Left Card: Informasi Invoice -->
                <div class="col-lg-6">
                    <div class="tkb-form-card h-100">
                        <div class="tkb-form-card__header">
                            <div class="d-flex align-items-center gap-3">
                                <span class="tkb-sec-icon tkb-sec-icon--emerald"><i class="ri-file-text-line"></i></span>
                                <div>
                                    <h5 class="tkb-form-card__title">Informasi invoice</h5>
                                    <p class="tkb-form-card__sub">Penomoran dan tanggal penjualan</p>
                                </div>
                            </div>
                            <div class="tkb-logo-circle">
                                <img src="{{asset('images/LogoTKB.png')}}" alt="Tokabe Logo">
                            </div>
                        </div>

                        <div class="tkb-form-card__body">
                            <div class="mb-3">
                                <label for="choices-invoice" class="form-label">Invoice</label>
                                <input type="text" class="form-control fw-medium @error('inv') is-invalid @enderror" id="choices-invoice" name="inv" value="Tokabe" readonly>
                                @error('inv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Penomoran invoice</label>
                                <div class="d-flex flex-column gap-2 mb-2">
                                    <label class="tkb-radio-card" for="opt_mode_kategori">
                                        <input class="form-check-input" type="radio" name="opt_mode_penomoran" id="opt_mode_kategori" value="kategori" checked onchange="switchInvoiceNumberMode('kategori')">
                                        <span>Nomor otomatis (standar sistem / kategori)</span>
                                    </label>
                                    <label class="tkb-radio-card" for="opt_mode_manual">
                                        <input class="form-check-input" type="radio" name="opt_mode_penomoran" id="opt_mode_manual" value="manual" onchange="switchInvoiceNumberMode('manual')">
                                        <span>Input nomor manual bebas</span>
                                    </label>
                                </div>

                                <div id="panel_mode_kategori" class="mb-2">
                                    <select class="form-select" id="kategori_invoice_id" name="kategori_invoice_id">
                                        @if(isset($kategoriInvoices) && $kategoriInvoices->isNotEmpty())
                                            @foreach($kategoriInvoices as $katInv)
                                                <option value="{{ $katInv->id }}" data-prefix="{{ $katInv->prefix ?? 'TKB' }}" data-custom="{{ $katInv->use_custom_format ? '1' : '0' }}" data-format="{{ $katInv->format_nomor }}" {{ $katInv->prefix === 'TKB' || str_contains(strtolower($katInv->nama_kategori), 'standar') ? 'selected' : '' }}>
                                                    {{ $katInv->nama_kategori }} (Prefix: {{ $katInv->prefix ?? 'TKB' }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" data-prefix="TKB" selected>Invoice Standar Tokabe (Prefix: TKB)</option>
                                        @endif
                                    </select>
                                </div>

                                <div id="panel_mode_manual" style="display: none;" class="mb-2">
                                    <div class="tkb-manual-box">
                                        <span class="tkb-manual-box__label">Masukkan nomor invoice manual sesuai kebutuhan dokumen Anda:</span>
                                        <input type="text" id="manual_invoice_input" class="form-control fw-semibold" placeholder="Contoh: 001/INV-IKL/2026" oninput="applyManualInvoiceNumber()">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-5">
                                    <label class="form-label d-flex align-items-center justify-content-between">
                                        <span>Kode prefix</span>
                                        <small id="prefix_status_label" style="color: var(--brand); font-size: 11px; font-weight: 500;">(Otomatis)</small>
                                    </label>
                                    <input type="text" class="form-control fw-bold" id="invoicenoInput" placeholder="Kode" name="kode" value="{{ explode('/', $no_invoice)[1] ?? 'TKB' }}" readonly oninput="onPrefixCustomChange()">
                                </div>
                                <div class="col-sm-7">
                                    <label class="form-label">Nomor invoice aktif</label>
                                    <input type="text" class="form-control" id="noInvoice" placeholder="Nomor Invoice Aktif" name="kodeUnik" value="{{ $no_invoice }}" readonly>
                                    <input type="hidden" id="nomor_invoice_custom" name="nomor_invoice_custom" value="">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="date-field" class="form-label">Tanggal penjualan</label>
                                    <input type="text" class="form-control flatpickr-input" id="date-field" name="tgl_jual" data-provider="flatpickr" value="{{ $hariIni }}" readonly="readonly">
                                </div>
                                <div class="col-sm-6">
                                    <label for="time-field" class="form-label">Jam</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="time-field" placeholder="HH:mm" name="jam" value="{{ old('jam', $formatJam) }}" title="Jam transaksi (dapat diedit bebas)">
                                        <span class="input-group-text"><i class="ri-time-line"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Card: Data Pelanggan -->
                <div class="col-lg-6">
                    <div class="tkb-form-card h-100">
                        <div class="tkb-form-card__header">
                            <div class="d-flex align-items-center gap-3">
                                <span class="tkb-sec-icon tkb-sec-icon--cyan"><i class="ri-store-2-line"></i></span>
                                <div>
                                    <h5 class="tkb-form-card__title">Data pelanggan</h5>
                                    <p class="tkb-form-card__sub">Identitas customer dan pengaturan order</p>
                                </div>
                            </div>
                        </div>

                        <div class="tkb-form-card__body">
                            <div class="mb-3">
                                <label for="customer" class="form-label">Nama customer <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('pelanggan') is-invalid @enderror" name="pelanggan" id="customer" placeholder="Nama customer" value="{{ old('pelanggan') }}" list="customerList" required>
                                <datalist id="customerList">
                                    @foreach ($kontak as $data)
                                        <option value="{{ $data->customer }}" data-company="{{ $data->perusahaan }}" data-phone="{{ $data->no_telepon }}" data-npwp="{{ $data->npwp ?? '' }}">{{ $data->customer }}</option>
                                    @endforeach
                                </datalist>
                                @error('pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="npwp" class="form-label">NPWP</label>
                                    <input type="text" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" placeholder="Contoh: 01.234.567.8-901.000" value="{{ old('npwp') }}" maxlength="50">
                                    @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="phone-number" class="form-label">Nomor telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tlp') is-invalid @enderror" id="phone-number" name="tlp" placeholder="08xxxxxxxx" value="{{ old('tlp') }}" required>
                                    @error('tlp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="choices-admin" class="form-label">Nama admin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('adm') is-invalid @enderror" name="adm" id="choices-admin" required>
                                        <option value="">Pilih admin</option>
                                        @foreach ($admin as $adm)
                                            <option value="{{$adm->id}}" {{old('adm') == $adm->id ? 'selected' : ''}}>{{$adm->nama}}</option>
                                        @endforeach
                                    </select>
                                    @error('adm')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="choices-orderBy" class="form-label">Order dari <span class="text-danger">*</span></label>
                                    <select class="form-select @error('order') is-invalid @enderror" id="choices-orderBy" name="order" required>
                                        <option value="">Order by</option>
                                        @foreach ($order as $ord)
                                            <option value="{{$ord->order_by}}" {{old('order') == $ord->order_by ? 'selected' : ''}}>{{$ord->order_by}}</option>
                                        @endforeach
                                    </select>
                                    @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div id="divSales" style="display: none;" class="mb-3">
                                <label for="colFormLabelNamaSales" class="form-label">Nama sales <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sales') is-invalid @enderror" id="colFormLabelNamaSales" placeholder="Nama sales" name="sales" value="{{ old('sales') }}">
                                @error('sales')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="dateInput" class="form-label">Target penyelesaian <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatpickr-input @error ('tgl_selesai') is-invalid @enderror" data-provider="flatpickr" data-date-format="Y-m-d" name="tgl_selesai" id="dateInput" placeholder="YYYY-MM-DD" value="{{ old('tgl_selesai') }}" autocomplete="off" required>
                                    @error('tgl_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Transaksi PADI UMKM <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="tkb-radio-card" for="padi_umkm_tidak">
                                                <input class="form-check-input" type="radio" name="is_padi_umkm" id="padi_umkm_tidak" value="0" {{ old('is_padi_umkm', '0') == '0' ? 'checked' : '' }}>
                                                <span>Tidak</span>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label class="tkb-radio-card" for="padi_umkm_ya">
                                                <input class="form-check-input" type="radio" name="is_padi_umkm" id="padi_umkm_ya" value="1" {{ old('is_padi_umkm') == '1' ? 'checked' : '' }}>
                                                <span>Ya</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MIDDLE CARD: Item Invoice -->
            <div class="tkb-form-card mb-4">
                <div class="tkb-form-card__header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="tkb-sec-icon tkb-sec-icon--amber"><i class="ri-price-tag-3-line"></i></span>
                        <div>
                            <h5 class="tkb-form-card__title">Item invoice</h5>
                            <p class="tkb-form-card__sub">Tambahkan produk atau jasa yang dijual</p>
                        </div>
                    </div>
                    <a href="javascript:new_link()" id="add-item" class="btn btn-soft-secondary">
                        <i class="ri-add-line"></i> Add item
                    </a>
                </div>

                <div class="tkb-form-card__body">
                    <div id="template-options-barang" style="display: none;">
                        <option value="" selected disabled>Pilih item</option>
                        @foreach ($jenisBarang as $jns)
                            <option value="{{$jns->id}}" data-jenis-pajak="{{$jns->jenis_pajak}}" data-harga="{{$jns->harga_jual ?? 0}}">{{$jns->nama_item}}</option>
                        @endforeach
                    </div>

                    <div class="table-responsive">
                        <table class="invoice-table table table-borderless table-nowrap mb-0">
                            <thead class="align-middle">
                                <tr class="table-active">
                                    <th scope="col" style="width: 48px;">#</th>
                                    <th scope="col" style="min-width: 380px;">Item <span class="text-danger">*</span></th>
                                    <th scope="col" style="min-width: 110px; width: 110px;">Satuan <span class="text-danger">*</span></th>
                                    <th scope="col" style="width: 150px;">
                                        <div class="d-flex currency-select align-items-center">Harga <span class="text-danger me-1">*</span>
                                            <select class="form-select border-0" data-choices data-choices-search-false id="choices-payment-currency" onchange="otherPayment()">
                                                <option value="Rp">(Rp)</option>
                                            </select>
                                        </div>
                                    </th>
                                    <th scope="col" style="width: 110px;">Quantity <span class="text-danger">*</span></th>
                                    <th scope="col" style="width: 160px;">Jumlah harga</th>
                                    <th scope="col" class="text-end" style="width: 105px;"></th>
                                </tr>
                            </thead>
                            <tbody id="newlink">
                                <tr id="1" class="product">
                                    <th scope="row" class="product-id">1</th>
                                    <td class="text-start">
                                        <div class="mb-2">
                                            <label class="visually-hidden" for="productName-1">Item</label>
                                            <select class="form-select @error('item_id.0') is-invalid @enderror" id="productName-1" name="item_id[0]" required>
                                                <option value="" selected disabled>Pilih item</option>
                                                @foreach ($jenisBarang as $jns)
                                                    <option value="{{$jns->id}}" data-jenis-pajak="{{$jns->jenis_pajak}}" data-harga="{{$jns->harga_jual ?? 0}}" {{old('item_id.0') == $jns->id ? 'selected' : ''}}>{{$jns->nama_item}}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-text item-tax-type" id="item-tax-type-1"></small>
                                            @error('item_id.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <textarea class="form-control @error('deskripsi_item.0') is-invalid @enderror" id="productDetails-1" name="deskripsi_item[0]" rows="2" placeholder="Deskripsi item" required></textarea>
                                        @error('deskripsi_item.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                    <td>
                                        <select class="form-select @error ('satuan.0') is-invalid @enderror" style="min-width: 95px;" id="satuan-1" name="satuan[0]" required>
                                            <option value="">Pilih</option>
                                            <option value="Pcs" selected>Pcs</option>
                                            <option value="Set">Set</option>
                                            <option value="Und">Und</option>
                                            <option value="Blok">Blok</option>
                                            <option value="Rim">Rim</option>
                                            <option value="Lbr">Lbr</option>
                                            <option value="Ktk">Ktk</option>
                                            <option value="Unit">Unit</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Lainnya">Lainnya</option>
                                            <option value="Pax">Pax</option>
                                        </select>
                                        @error('satuan.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control product-price @error('hrg.0') is-invalid @enderror" id="productRate-1" name="hrg[0]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' placeholder="Rp 0.000" onchange="autoCalc(this)" required/>
                                        @error('hrg.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </td>
                                    <td>
                                        <div class="input-step">
                                            <input type="number" class="text-center @error('qty.0') is-invalid @enderror" id="product-qty-1" value="1" min="1" name="qty[0]" onchange="autoCalc(this)" required>
                                            @error('qty.0')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control product-line-price" id="productPrice-1" name="jlh_hrg[0]" placeholder="0" onchange="autoCalc(this)" readonly/>
                                    </td>
                                    <td class="product-removal text-end">
                                        <a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="ri-delete-bin-5-line"></i>Hapus</a>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody style="display: none;">
                                <tr id="newForm" style="display: none;"><td class="d-none" colspan="5"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- BOTTOM CARD: Pembayaran -->
            <div class="tkb-form-card mb-4">
                <div class="tkb-form-card__header">
                    <div class="d-flex align-items-center gap-3">
                        <span class="tkb-sec-icon tkb-sec-icon--emerald"><i class="ri-wallet-3-line"></i></span>
                        <div>
                            <h5 class="tkb-form-card__title">Pembayaran</h5>
                            <p class="tkb-form-card__sub">Rincian pajak dan total yang harus dibayar</p>
                        </div>
                    </div>
                </div>

                <div class="tkb-form-card__body">
                    <div class="col-lg-7">
                        <div class="row align-items-center mb-3">
                            <div class="col-sm-4">
                                <label for="choices-payment-type" class="col-form-label mb-0">Jenis pembayaran <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-8">
                                <select class="form-select @error('jns_pem') is-invalid @enderror" id="choices-payment-type" name="jns_pem" value="{{ old('jns_pem') }}" required>
                                    <option disabled selected value="">Payment method</option>
                                    <option value="Cash Belum Lunas">Cash Belum Lunas</option>
                                    <option value="Transfer DP">Transfer Dp</option>
                                    <option value="Transfer Lunas">Transfer Lunas</option>
                                    <option value="PO">PO</option>
                                </select>
                                @error('jns_pem')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                @if(isset($rekenings) && $rekenings->count() > 0)
                                    @foreach($rekenings as $rek)
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="norek" id="flexRadioRek{{ $rek->id }}" value="{{ $rek->id }}" {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label fw-normal" for="flexRadioRek{{ $rek->id }}">{{ $rek->formatted }}</label>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="form-check mb-1" id="alamatPembayaran2">
                                        <input class="form-check-input" type="radio" name="norek" id="flexRadioDefault2" value="TKB" checked>
                                        <label class="form-check-label fw-normal" for="flexRadioDefault2">BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999</label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-sm-4">
                                <label for="total-harga" class="col-form-label mb-0">Jumlah harga</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control tabular-nums" id="total-harga" name="tot_harga" placeholder="0" value="{{ old('tot_harga') }}" readonly>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3" id="div-dp-row">
                            <div class="col-sm-4" id="div-label" style="display: none;">
                                <label for="input-dp" class="col-form-label mb-0" id="label-dp">Dp</label>
                            </div>
                            <div class="col-sm-8" id="dp" style="display: none;">
                                <input id="input-dp" name="dp" type="text" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand", "numeralDecimalScale": 0, "numeralPositiveOnly": true }' class="form-control" placeholder="Rp 0" value="{{ old('dp') }}">
                                <small class="d-block mt-1" style="color: var(--text-tertiary);"><i class="ri-information-line me-1"></i>Default 70%, dapat diedit manual jika negosiasi</small>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-sm-4">
                                <label for="input-ppn" class="col-form-label mb-0">PPN (%)</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" step="any" min="0" max="100" class="form-control" id="input-ppn" name="ppn" placeholder="0" value="{{ old('ppn') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="fw-medium mt-1" id="preview-nominal-ppn" style="display: none;"></small>
                                <small class="fw-medium mt-1" id="hint-locked-ppn" style="display: none;"><i class="ri-lock-line me-1"></i>Terkunci: Item tidak dikenakan PPN</small>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3" id="row-kode-ppn" style="display: none;">
                            <div class="col-sm-4">
                                <label for="select-kode-ppn" class="col-form-label mb-0">Kode transaksi PPN</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="form-select" name="kode_ppn" id="select-kode-ppn">
                                    <option value="01" selected>01 - Non Pemungut PPN (Swasta)</option>
                                    <option value="02">02 - Pemungut Instansi Pemerintah</option>
                                    <option value="03">03 - Pemungut BUMN / Pemungut Lainnya</option>
                                    <option value="04">04 - DPP Nilai Lain</option>
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-sm-4">
                                <label for="input-pph" class="col-form-label mb-0">PPh (%)</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" step="any" min="0" max="100" class="form-control" id="input-pph" name="pph" placeholder="0" value="{{ old('pph') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="fw-medium mt-1" id="preview-nominal-pph" style="display: none;"></small>
                                <small class="fw-medium mt-1" id="hint-locked-pph" style="display: none;"><i class="ri-lock-line me-1"></i>Terkunci: Item tidak dikenakan PPh</small>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-sm-4">
                                <label for="total-pembelian" class="col-form-label mb-0">Total pembayaran</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control fw-bold tabular-nums" placeholder="0" id="total-pembelian" name="tot_pem" value="{{ old('tot_pem') }}" readonly>
                            </div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-sm-4">
                                <label for="sisa-pembayaran" class="col-form-label mb-0">Sisa pembayaran</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control tabular-nums" placeholder="0" id="sisa-pembayaran" name="sisa_pem" value="{{ old('sisa_pem') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="tkb-form-actions hstack gap-2 d-print-none">
                        <button type="submit" id="simpan" class="btn btn-primary btn-load btn-simpan-tokabe">
                            <span class="d-flex align-items-center">
                                <span id="loading-spinner" class="spinner-border flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="flex-grow-1 ms-1">
                                    <i class="ri-printer-line align-bottom me-1"></i>Simpan
                                </span>
                            </span>
                        </button>
                        <button type="button" id="btn-save-draft" class="btn btn-save-draft-tokabe">
                            <i class="ri-draft-line align-bottom"></i>Simpan draf
                        </button>
                        <a href="{{ route('list_invoice_tokabe') }}" class="btn btn-secondary btn-cancel-tokabe">
                            <i class="ri-close-line align-bottom"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
             <script>
               document.getElementById('customer').addEventListener('change', function() {
                   var selectedOption = this.value;
                   var dataList = document.getElementById('customerList').getElementsByTagName('option');
                   for (var i = 0; i < dataList.length; i++) {
                       var option = dataList[i];
                       if (option.value === selectedOption) {
                           document.getElementById('usaha').value = option.getAttribute('data-company');
                           document.getElementById('phone-number').value = option.getAttribute('data-phone');
                           var npwpVal = option.getAttribute('data-npwp');
                           if (npwpVal && document.getElementById('npwp')) {
                               document.getElementById('npwp').value = npwpVal;
                           }
                           break;
                       }
                   }
               });
           </script>
           
          <script>
    let isPrefixManuallyEdited = false;

    function onPrefixCustomChange() {
        isPrefixManuallyEdited = true;
        const customPrefix = ($('#invoicenoInput').val() || '').trim().toUpperCase();
        
        if ($('#opt_mode_kategori').is(':checked')) {
            let currentNo = $('#noInvoice').val() || '';
            const opt = $('#kategori_invoice_id')[0] ? $('#kategori_invoice_id')[0].selectedOptions[0] : null;
            const oldPrefix = (opt && opt.dataset.prefix) ? opt.dataset.prefix : 'TKB';
            
            if (currentNo && oldPrefix && customPrefix && oldPrefix !== customPrefix) {
                const regex = new RegExp('/' + oldPrefix + '/', 'i');
                if (regex.test(currentNo)) {
                    currentNo = currentNo.replace(regex, '/' + customPrefix + '/');
                } else if (currentNo.includes(oldPrefix)) {
                    currentNo = currentNo.replace(oldPrefix, customPrefix);
                }
                $('#noInvoice').val(currentNo);
                $('#nomor_invoice_custom').val(currentNo);
            }
        }
    }

    function switchInvoiceNumberMode(mode) {
        if (mode === 'kategori') {
            $('#panel_mode_kategori').show();
            $('#panel_mode_manual').hide();
            $('#nomor_invoice_custom').val('');
            isPrefixManuallyEdited = false;

            // Kunci prefix dan nomor invoice agar tidak bisa diedit saat mode otomatis
            $('#invoicenoInput')
                .prop('readonly', true)
                .removeClass('bg-dark text-white border-secondary')
                .addClass('bg-light border-0')
                .attr('title', 'Kode prefix terisi otomatis sesuai format pilihan');
            $('#prefix_status_label').text('(Otomatis)').removeClass('text-primary').addClass('text-muted');

            if (typeof window.refreshGeneratedInvoiceNumber === 'function') {
                window.refreshGeneratedInvoiceNumber();
            }
        } else if (mode === 'manual') {
            $('#panel_mode_kategori').hide();
            $('#panel_mode_manual').show();

            // Buka kunci prefix agar BISA dikustomisasi saat mode manual bebas
            $('#invoicenoInput')
                .prop('readonly', false)
                .removeClass('bg-light border-0')
                .addClass('bg-dark text-white border-secondary')
                .attr('title', 'Kode prefix dapat diketik/dikustomisasi bebas');
            $('#prefix_status_label').text('(Bisa Diedit)').removeClass('text-muted').addClass('text-primary');

            $('#manual_invoice_input').focus();
            applyManualInvoiceNumber();
        }
    }

    function applyManualInvoiceNumber() {
        const manualVal = ($('#manual_invoice_input').val() || '').trim();
        if (manualVal) {
            $('#noInvoice').val(manualVal);
            $('#nomor_invoice_custom').val(manualVal);

            // Hanya auto-set prefix jika user belum mengedit kolom prefix secara manual
            if (!isPrefixManuallyEdited) {
                const parts = manualVal.split('/');
                if (parts.length >= 3 && isNaN(parts[1])) {
                    $('#invoicenoInput').val(parts[1].toUpperCase());
                } else if (!isNaN(parts[1]) || !$('#invoicenoInput').val()) {
                    // Jika format tanggal seperti 19/04/2005, gunakan default TKB daripada angka bulan '04'
                    const opt = $('#kategori_invoice_id')[0] ? $('#kategori_invoice_id')[0].selectedOptions[0] : null;
                    $('#invoicenoInput').val(opt && opt.dataset.prefix ? opt.dataset.prefix : 'TKB');
                }
            }
        } else {
            $('#nomor_invoice_custom').val('');
            if (typeof window.refreshGeneratedInvoiceNumber === 'function') {
                window.refreshGeneratedInvoiceNumber();
            }
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
      let dateField = document.getElementById("date-field");
      let invoiceInput = document.getElementById("noInvoice");
      let invoiceKodeInput = document.getElementById("invoicenoInput");
      let kategoriSelect = document.getElementById("kategori_invoice_id");
      let dateInput = document.getElementById("dateInput");

      window.refreshGeneratedInvoiceNumber = function() {
          if ($('#opt_mode_manual').is(':checked')) {
              return;
          }
          const tgl = dateField ? dateField.value : '{{ $hariIni }}';
          const katId = kategoriSelect ? kategoriSelect.value : '';
          
          let url = katId ? `/kategori-invoice/generate-number?kategori_id=${katId}&date=${tgl}` : `/invoice/get-invoice-number?tanggal=${tgl}`;
          fetch(url)
              .then(response => response.json())
              .then(data => {
                  const no = data.no_invoice || data.nomor_invoice || data.invoiceNumber;
                  if (no) {
                      invoiceInput.value = no;
                      const parts = no.split('/');
                      const opt = kategoriSelect ? kategoriSelect.selectedOptions[0] : null;
                      const prefix = data.prefix || (opt && opt.dataset.prefix ? opt.dataset.prefix : (parts.length > 1 ? parts[1] : 'TKB'));
                      if (invoiceKodeInput && !isPrefixManuallyEdited) {
                          invoiceKodeInput.value = prefix;
                      }
                  }
              })
              .catch(error => console.error('Error fetching invoice number:', error));
      };

      if (kategoriSelect) {
          kategoriSelect.addEventListener("change", window.refreshGeneratedInvoiceNumber);
      }

      if (dateField) {
        flatpickr(dateField, {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $hariIni }}",
            onChange: function (selectedDates, dateStr) {
                window.refreshGeneratedInvoiceNumber();
            }
        });
      }
      if (dateInput) {
        flatpickr(dateInput, {
            dateFormat: "Y-m-d",
            allowInput: true,
            @if(old('tgl_selesai'))
            defaultDate: "{{ old('tgl_selesai') }}"
            @endif
        });
      }

      let timeField = document.getElementById("time-field");
      if (timeField && !timeField._flatpickr) {
        flatpickr(timeField, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            allowInput: true,
            defaultDate: timeField.value || "{{ $formatJam }}"
        });
      }
    });
   </script> 
           
{!! JsValidator::formRequest('App\Http\Requests\InvoiceRequest') !!}
@endsection

@section('plugins')

<!-- dropzone min -->
<script src="{{asset('libs/dropzone/dropzone-min.js')}}"></script>

<!-- cleave.js -->
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>

<!--Invoice create init js-->
{{-- <script src="{{asset('js/pages/invoicecreate.init.js')}}"></script> --}}

<!-- Sweet Alerts js -->
<script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>

{{-- choices.js & flatpickr --}}
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>

<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('js/halaman/tabel-item-tokabe.js')}}"></script>
<script src="{{asset('js/halaman/invoice.js')}}"></script>
<script src="{{asset('js/halaman/searchable-item-select.js')}}"></script>
<script src="{{asset('js/halaman/draft-invoice-tokabe.js')}}"></script>
{{-- <script src="{{asset('js/halaman/form-masks.js')}}"></script> --}}

<script>
    // OVERRIDE FUNGSI new_link DARI tabel-item.js
    // Masalah: Fungsi bawaan tabel-item.js tidak mengetahui adanya form Material yang baru ditambahkan.
    // Solusi: Kita timpa fungsi tersebut di sini agar baris baru menyertakan kolom Material.
    
    // Pastikan count sesuai dengan jumlah baris yang ada saat ini
    const invoiceTaxLabels = {
      ppn: 'PPN',
      pph: 'PPh',
      ppn_pph: 'PPN dan PPh',
      tanpa_pajak: 'Tanpa Pajak'
    };

    function setInputLockState(input, isLocked, hintElem, message) {
      if (!input) return;
      if (isLocked) {
        input.value = '0';
        input.readOnly = true;
        input.disabled = true;
        input.style.backgroundColor = '#f1f3f5';
        input.style.cursor = 'not-allowed';
        input.style.pointerEvents = 'none';
        input.tabIndex = -1;
        if (hintElem) {
          hintElem.innerHTML = '<i class="ri-lock-line me-1"></i>' + message;
          hintElem.style.display = 'block';
        }
      } else {
        input.readOnly = false;
        input.disabled = false;
        input.style.backgroundColor = '';
        input.style.cursor = 'auto';
        input.style.pointerEvents = 'auto';
        input.removeAttribute('tabindex');
        if (hintElem) {
          hintElem.style.display = 'none';
        }
      }
    }

    function syncTaxInputs() {
      const itemSelects = document.querySelectorAll('select[name^="item_id"]');
      const ppnInput = document.getElementById('input-ppn');
      const pphInput = document.getElementById('input-pph');
      const hintPpn = document.getElementById('hint-locked-ppn');
      const hintPph = document.getElementById('hint-locked-pph');

      if (!ppnInput || !pphInput) return;

      let canHavePpn = false;
      let canHavePph = false;
      let hasItemChosen = false;
      let allTanpaPajak = true;

      itemSelects.forEach(function (select) {
        const opt = select.selectedOptions && select.selectedOptions[0];
        if (opt && opt.value && !opt.disabled) {
          hasItemChosen = true;
          const taxType = opt.dataset.jenisPajak;
          if (taxType === 'ppn') {
            canHavePpn = true;
            allTanpaPajak = false;
          } else if (taxType === 'pph') {
            canHavePph = true;
            allTanpaPajak = false;
          } else if (taxType === 'ppn_pph') {
            canHavePpn = true;
            canHavePph = true;
            allTanpaPajak = false;
          } else if (taxType !== 'tanpa_pajak') {
            allTanpaPajak = false;
          }
        }
      });

      if (!hasItemChosen) {
        setInputLockState(ppnInput, false, hintPpn);
        setInputLockState(pphInput, false, hintPph);
      } else if (allTanpaPajak) {
        setInputLockState(ppnInput, true, hintPpn, 'Terkunci: Item Tanpa Pajak');
        setInputLockState(pphInput, true, hintPph, 'Terkunci: Item Tanpa Pajak');
      } else {
        if (!canHavePpn) {
          setInputLockState(ppnInput, true, hintPpn, 'Terkunci: Item tidak dikenakan PPN');
        } else {
          setInputLockState(ppnInput, false, hintPpn);
        }

        if (!canHavePph) {
          setInputLockState(pphInput, true, hintPph, 'Terkunci: Item tidak dikenakan PPh');
        } else {
          setInputLockState(pphInput, false, hintPph);
        }
      }

      if (typeof totalPembayaran === 'function') {
        totalPembayaran();
      }
    }

    function applyItemTaxDefaults(taxType) {
      const ppnInput = document.getElementById('input-ppn');
      const pphInput = document.getElementById('input-pph');
      if (!ppnInput || !pphInput) return;

      if (taxType === 'ppn') {
        if (!ppnInput.value || ppnInput.value === '0') ppnInput.value = '11';
        pphInput.value = '0';
      } else if (taxType === 'pph') {
        ppnInput.value = '0';
        if (!pphInput.value || pphInput.value === '0') pphInput.value = '2';
      } else if (taxType === 'ppn_pph') {
        if (!ppnInput.value || ppnInput.value === '0') ppnInput.value = '11';
        if (!pphInput.value || pphInput.value === '0') pphInput.value = '2';
      } else if (taxType === 'tanpa_pajak') {
        ppnInput.value = '0';
        pphInput.value = '0';
      }
    }

    function updateItemTaxType(select, isUserChange = false) {
      const container = select.closest('.mb-2') || select.closest('td') || select.parentElement;
      const label = container ? container.querySelector('.item-tax-type') : null;
      const opt = select.selectedOptions && select.selectedOptions[0];
      const taxType = opt ? opt.dataset.jenisPajak : '';
      if (label) {
        label.textContent = taxType && invoiceTaxLabels[taxType] ? `Jenis pajak item: ${invoiceTaxLabels[taxType]}` : '';
      }
      if (isUserChange && taxType) {
        applyItemTaxDefaults(taxType);
      }
      syncTaxInputs();
    }

    document.addEventListener('change', function (event) {
      if (event.target.matches('select[name^="item_id"]')) {
        updateItemTaxType(event.target, true);
        const sel = event.target;
        const opt = sel.selectedOptions && sel.selectedOptions[0];
        if (opt && opt.dataset.harga && parseInt(opt.dataset.harga) > 0) {
          const row = sel.closest('tr.product') || sel.closest('tr');
          if (row) {
            const rateInput = row.querySelector('input[id^="productRate-"]');
            if (rateInput && (!rateInput.value || rateInput.value === '0' || rateInput.value === 'Rp. 0.000' || rateInput.value === 'Rp.0.000' || rateInput.value === '')) {
              rateInput.value = parseInt(opt.dataset.harga).toLocaleString('id-ID');
              if (typeof autoCalc === 'function') {
                autoCalc(rateInput);
              }
            }
          }
        }
      }
    });

    $(document).on('click', '.product-removal a, .product-removal button', function () {
      setTimeout(syncTaxInputs, 150);
    });

    document.querySelectorAll('select[name^="item_id"]').forEach(function(el) {
      updateItemTaxType(el, false);
    });

    var count = document.querySelectorAll('#newlink tr.product').length;
    
    window.new_link = function() {
        var tr = document.createElement("tr");
        tr.id = count;
        tr.className = "product";
        
        // MENGAMBIL DATA DARI ELEMEN HIDDEN HTML (BUKAN MELOOPING PHP DI DALAM SCRIPT)
        // Ini adalah kunci yang mengembalikan kecepatan web Anda menjadi normal
        

        var itemOptionsDOM = document.getElementById('template-options-barang');
        var itemOptions = itemOptionsDOM ? itemOptionsDOM.innerHTML : '<option selected disabled>Pilih Item</option>';

        // 3. Template HTML Baris Baru
        var template = `
            <th scope="row" class="product-id">${count + 1}</th>
            <td class="text-start">
                <div class="mb-2">
                    <select class="form-select" name="item_id[${count}]" id="productName-${count + 1}" required>
                        ${itemOptions}
                    </select>
                    <small class="form-text text-muted item-tax-type" id="item-tax-type-${count + 1}"></small>
                </div>
                <textarea class="form-control bg-light border-0" id="productDetails-${count + 1}" name="deskripsi_item[${count}]" rows="2" placeholder="Deskripsi Item" required></textarea>
                
                
            </td>
            <td>
                <select class="form-select bg-light border-0" style="min-width: 95px;" name="satuan[${count}]" id="satuan-${count + 1}" required>
                    <option value="">Pilih</option>
                    <option value="Pcs" selected>Pcs</option>
                    <option value="Set">Set</option>
                    <option value="Und">Und</option>
                    <option value="Blok">Blok</option>
                    <option value="Rim">Rim</option>
                    <option value="Lbr">Lbr</option>
                    <option value="Ktk">Ktk</option>
                    <option value="Unit">Unit</option>
                    <option value="Kg">Kg</option>
                    <option value="Lainnya">Lainnya</option>
                    <option value="Pax">Pax</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control product-price bg-light border-0" id="productRate-${count + 1}" name="hrg[${count}]" placeholder="Rp. 0.000" onchange="autoCalc(this)" required />
            </td>
            <td>
                <div class="input-step">
                    <input type="number" class="text-center" id="product-qty-${count + 1}" value="1" min="1" name="qty[${count}]" onchange="autoCalc(this)" required>
                </div>
            </td>
            <td class="text-end">
                <div>
                    <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-${count + 1}" name="jlh_hrg[${count}]" placeholder="Rp.0.000" readonly />
                </div>
            </td>
            <td class="product-removal">
                <a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="ri-delete-bin-5-line me-1"></i>Hapus</a>
            </td>
        `;

        tr.innerHTML = template;
        document.getElementById("newlink").appendChild(tr);

        // Init Cleave.js untuk input harga yang baru
        if (typeof Cleave !== 'undefined') {
             new Cleave(`#productRate-${count + 1}`, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        }
        
        count++;
    }
</script>
@endsection