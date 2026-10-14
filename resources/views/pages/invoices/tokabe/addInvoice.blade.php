@section('head')
<!-- dropzone css -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- <link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" /> --}}

<!-- Sweet Alert css-->
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/invoice-tokabe-form.css')}}" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function () {
    $('#loading-overlay').hide();
    $("#loading-spinner").hide();

    $("#invoice_form").submit(function (e) {
    e.preventDefault();

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

        // Tangkap pesan error dari server JSON response (400 Bad Request)
        var msg = "Terjadi kesalahan! Data belum lengkap.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
            msg = xhr.responseJSON.message;
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
@section('content')
@include('layout.loading-overlay')
<div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Tambah Invoice Tokabe</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">Tambah Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                       <div class="col-xxl-12">
                         <div class="card">
                           <form method="POST" action="{{ route('storeInvoice.Tokabe') }}" validate id="invoice_form" enctype="multipart/form-data">
                            @csrf
                             <div class="card-body border-bottom border-bottom-dashed p-4">
                               <div class="row">
                                 <div class="col-lg-6">
                                   <div class="row g-3 mb-2">
                                        <div class="col-lg-8 col-sm-6">
                                         <label for="choices-invoice">Invoice</label>
                                            <div class="input-light">
                                               <input type="text" class="form-control bg-light border-0 fw-medium @error('inv') is-invalid @enderror" id="choices-invoice" name="inv" value="Tokabe" readonly>

                                                @error('inv')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                      </div>

                                         {{-- End coloumn --}}
                                   </div>
                                        <div class="row mb-2 col-lg-8 col-sm-6">
                                      <label for="invoicenoInput">Invoice No</label>
                                      <div class="col-lg-4">
                                          <input type="text" class="form-control bg-light border-0" id="invoicenoInput" placeholder="Kode" name="kode" value="{{ explode('/', $no_invoice)[1] }}" readonly>
                                      </div>
                                      <div class="col-lg-7">
                                          <input type="text" class="form-control bg-light border-0" id="noInvoice" placeholder="Kode Unik" name="kodeUnik" value="{{ $no_invoice }}" readonly>
                                      </div>
                                  </div>

                                  <div class="row mb-2 col-lg-9">
                                      <label for="date-field">Tanggal Penjualan</label>
                                      <div class="col-lg-4">
                                          <input type="text" class="form-control bg-light border-0 flatpickr-input" id="date-field" name="tgl_jual" data-provider="flatpickr" value="{{ $hariIni }}" readonly="readonly">
                                      </div>
                                      <div class="col-lg-6">
                                          <input type="text" class="form-control bg-light border-0" placeholder="H:i" name="jam" value="{{ $formatJam }}" readonly="readonly">
                                      </div>
                                  </div>
                                        {{-- </div> --}}

                                 </div>
                                  <div class="col-lg-4 ms-auto">
                                  <div class="profile-user mx-auto  mb-3">
                                   <input id="profile-img-file-input" type="file" class="profile-img-file-input"/>
                                   <label for="profile-img-file-input" class="d-block" tabindex="0">
                                    <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style=" width: 256px;">
                                      <img src="{{asset('images/LogoTKB.png')}}" class="card-logo card-logo-dark user-profile-image img-fluid" alt="logo dark">
                                      <img src="{{asset('images/LogoTKB.png')}}" class="card-logo card-logo-light user-profile-image img-fluid" alt="logo light">
                                    </span>
                                   </label>
                                  </div>
                                 </div>
                                {{-- Customer Form --}}
                                 <div class="row mt-2">
                                  <div class="col-xxl-5">
                                  </div>
                                  <div class="col-xxl-7">
                                   {{-- <div class="card no-border border-0"> --}}
                                    <div class="row mb-3">
                                      <label for="customer" class="col-lg-5 col-form-label col-form-label-md">Nama Customer</label>
                                      <div class="col-lg-7">
                                          <input type="text" class="form-control form-control-md @error('pelanggan') is-invalid @enderror" name="pelanggan" id="customer" placeholder="Nama Customer" value="{{ old('pelanggan') }}" list="customerList">
                                          <datalist id="customerList">
                                              @foreach ($kontak as $data)
                                                  <option value="{{ $data->customer }}" data-company="{{ $data->perusahaan }}" data-phone="{{ $data->no_telepon }}">{{ $data->customer }}</option>
                                              @endforeach
                                          </datalist>
                                          @error('pelanggan')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                  </div>

                                  <div class="row mb-3" style="margin-top: -10px;">
                                      <div class="col-lg-5">
                                          <label for="contactNumber" class="form-label col-form-label col-form-label-md">Nomor Telepon</label>
                                      </div>
                                      <div class="col-lg-7">
                                          <input type="text" class="form-control @error('tlp') is-invalid @enderror" id="phone-number" name="tlp" placeholder="08xxxxxxxx" value="{{ old('tlp') }}">
                                          @error('tlp')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                  </div>
                                      {{-- End No.Telepon --}}
                                      {{-- Start Admin --}}
                                      <div class="row mb-3">
                                       <div class="col-lg-5">
                                        <label for="choices-admin" class="form-label col-form-label col-form-label-md">Nama Admin</label>
                                       </div>
                                       {{-- test --}}

                                       <div class="col-lg-7">
                                        <select class="form-select @error('adm') is-invalid @enderror" name="adm" id="choices-admin" aria-placeholder="Admin" required>
                                         <option value="">Pilih Admin</option>
                                         @foreach ($admin as $adm)
                                         <option value="{{$adm->id}}" {{old('adm') == $adm->id ? 'selected' : ''}}>{{$adm->nama}}</option>

                                         @endforeach
                                        </select>
                                        @error('adm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>

                                      </div>
                                      {{-- End Admin --}}
                                      {{-- Start Order By --}}
                                      <div class="row mb-3">
                                       <div class="col-lg-5">
                                          <label for="choices-orderBy" class="form-label col-form-label col-form-label-md">Order Dari</label>
                                       </div>
                                       <div class="col-lg-7">
                                        <select class="form-select @error('order') is-invalid @enderror"  id="choices-orderBy" name="order" required>
                                        <option value="">Order By</option>
                                        @foreach ($order as $ord)
                                        <option value="{{$ord->order_by}}" {{old('order') == $ord->order_by ? 'selected' : ''}}>{{$ord->order_by}}</option>
                                        @endforeach
                                        </select>
                                        @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                      {{-- End Order By--}}
                                      {{-- start Hide div --}}
                                        <div id="divSales"style="display: none; margin-bottom: -5px;">
                                          <div class="row mb-3">
                                            <div class="col-lg-5">
                                              <label for="colFormLabelNamaSales" class="col-form-label col-form-label-md">Nama Sales</label>
                                            </div>
                                            <div class="col-lg-7" style="margin-top: -10px;">
                                                <input type="text" class="form-control form-control-md mt-2 @error('sales') is-invalid @enderror" id="colFormLabelNamaSales" placeholder="Nama Sales" name="sales" value="{{ old('sales') }}">
                                                @error('sales')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                          </div>
                                        </div>
                                      {{-- end Hide div --}}
                                      {{-- Start Date --}}
                                      <div class="row mb-3">
                                       <div class="col-lg-5">
                                        <label for="dateInput" class="form-label col-form-label col-form-label-md">Target Penyelesaian  </label>
                                       </div>
                                       <div class="col-lg-7">
                                        <input type="text" class="form-control flatpickr-input @error ('tgl_selesai') is-invalid @enderror" data-provider="flatpickr" data-date-format="Y-m-d" name="tgl_selesai" id="dateInput" placeholder="YYYY-MM-DD" value="{{ old('tgl_selesai') }}" autocomplete="off">
                                        @error('tgl_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>
                                     {{-- End Date --}}
                                   {{-- </div> --}}
                                  </div>
                                 </div>
                                        {{-- End Customer Form --}}
                                   <!--end row-->
                             <div class="card-body p-4">

                                 
                                 <div id="template-options-barang" style="display: none;">
                                    <option selected disabled>Pilih Item</option>
                                     @foreach ($jenisBarang as $jns)
                                         <option value="{{$jns->id}}" data-jenis-pajak="{{$jns->jenis_pajak}}" data-harga="{{$jns->harga_jual ?? 0}}">{{$jns->nama_item}}</option>
                                     @endforeach
                                 </div>
                                 <!-- END TEMPLATE -->

                                 <table class="invoice-table  dt-responsive table table-borderless table-nowrap mb-0">
                                <div class="table-responsive">
                                  <thead class="align-middle">
                                   <tr class="table-active">
                                    <th scope="col" style="width: 50px;">#</th>
                                    <!-- Menambahkan min-width: 500px pada TH agar kolom tidak dipress pada layar laptop -->
                                    <th scope="col" style="min-width: 500px;">Item</th>
                                     <th scope="col" style="min-width: 110px; width: 110px;">Satuan</th>
                                    <th scope="col" style="width: 120px;">
                                     <div class="d-flex currency-select input-light align-items-center">Harga
                                      <select class="form-select border-0 bg-light" data-choices data-choices-search-false id="choices-payment-currency" onchange="otherPayment()">
                                      <option value="Rp">(Rp)</option>
                                      </select>
                                     </div></th>
                                    <th scope="col" style="width: 120px;">Quantity</th>
                                    <th scope="col" class="text-center" style="width: 150px;">Jumlah Harga</th>
                                    <th scope="col" class="text-end" style="width: 105px;"></th>
                                   </tr>
                                  </thead>
                                  <tbody id="newlink">
                                   <tr id="1" class="product">
                                    <th scope="row" class="product-id">1</th>
                                     <td class="text-start">
                                      <div class="mb-2">
                                        <label class="visually-hidden" for="productName">Item</label>

                                        <!-- INDEX [0] DITAMBAHKAN SECARA EKSPLISIT -->
                                        <select class="form-select @error('item_id.0') is-invalid @enderror" id="productName-1" name="item_id[0]" required>
                                            <option selected disabled>Pilih Item</option>
                                             @foreach ($jenisBarang as $jns)
                                             <option value="{{$jns->id}}" data-jenis-pajak="{{$jns->jenis_pajak}}" data-harga="{{$jns->harga_jual ?? 0}}" {{old('item_id.0') == $jns->id ? 'selected' : ''}}>{{$jns->nama_item}}</option>
                                             @endforeach
                                        </select>
                                        <small class="form-text text-muted item-tax-type" id="item-tax-type-1"></small>
                                        @error('item_id.0')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                      </div>
                                       <textarea class="form-control bg-light border-0 @error('deskripsi_item.0') is-invalid @enderror" id="productDetails-1" name="deskripsi_item[0]" rows="2" placeholder="Deskripsi Item"></textarea>
                                       @error('deskripsi_item.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror

                                       

                                     </td>
                                     <td>
                                      <!-- INDEX [0] DITAMBAHKAN SECARA EKSPLISIT -->
                                      <select class="form-select bg-light border-0 @error ('satuan.0') is-invalid @enderror" style="min-width: 95px;" id="satuan-1" name="satuan[0]" required>
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
                                      @error('satuan.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                     </td>
                                     <td>
                                      <input type="text" class="form-control product-price bg-light border-0 @error('hrg.0') is-invalid @enderror" id="productRate-1" name="hrg[0]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }'  placeholder="Rp. 0.000" onchange="autoCalc(this)" required/>

                                      @error('hrg.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                     </td>
                                     <td>
                                      <div class="input-step">
                                       <input type="number" class="text-center @error('qty.0') is-invalid @enderror" id="product-qty-1" value="1" name="qty[0]" onchange="autoCalc(this)">
                                       @error('qty.0')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                       @enderror
                                      </div>
                                     </td>
                                     <td class="text-end">
                                      <div>
                                       <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-1" name="jlh_hrg[0]" placeholder="Rp.0.000" onchange="autoCalc(this)" readonly/>
                                      </div>
                                     </td>
                                      <td class="product-removal"><a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="ri-delete-bin-5-line me-1"></i>Hapus</a>
                                      </td>
                                    </tr>
                                   </tbody>
                                   <tbody>
                                    <tr id="newForm" style="display: none;">
                                     <td class="d-none" colspan="5"><p>Add New Form</p>
                                     </td>
                                    </tr>
                                    <tr>
                                     <td colspan="2"><a href="javascript:new_link()" id="add-item" class="btn btn-soft-secondary fw-medium"><i class="ri-add-fill me-1 align-bottom"></i> Add Item</a>
                                     </td>
                                    </tr>
                                   </tbody>
                                  </table>
                                  <!--end table-->
                                  {{-- Start detail Payment --}}
                                 <div class="col-lg-4">
                                   <div class="col-lg-12"></div>
                                 </div>
                                 <div class="col-lg-8">
                                   <div class="row">
                                     <div class="col-lg-4">
                                     <label for="jenisPembayaran" class="form-label col-form-label col-form-label-md">Jenis Pembayaran</label>
                                     </div>
                                       <div class="col-lg-6">
                                         <select class="form-select bg-light @error('jns_pem') is-invalid @enderror" id="choices-payment-type" name="jns_pem" value="{{ old('jns_pem') }}">
                                           <option disabled selected value="">Payment Method</option>
                                           <option value="Cash Belum Lunas">Cash Belum Lunas</option>
                                           <option value="Transfer DP">Transfer Dp</option>
                                           <option value="Transfer Lunas">Transfer Lunas</option>
                                           <option value="PO">PO</option>
                                         </select>
                                         @error('jns_pem')
                                           <div class="invalid-feedback">{{ $message }}</div>
                                           @enderror
                                       </div>
                                   </div>
                                   <div class="row mb-1 col-lg-12">
                                     <div class="col lg-12">
                                         @if(isset($rekenings) && $rekenings->count() > 0)
                                          @foreach($rekenings as $rek)
                                          <div class="form-check mb-1 col-lg-12">
                                            <input class="form-check-input" type="radio" name="norek" id="flexRadioRek{{ $rek->id }}" value="{{ $rek->id }}" {{ $loop->first ? 'checked' : '' }}>
                                            <label class="form-check-label" for="flexRadioRek{{ $rek->id }}">
                                              {{ $rek->formatted }}
                                            </label>
                                          </div>
                                          @endforeach
                                        @else
                                          <div class="form-check mb-1 col-lg-12" id="alamatPembayaran2">
                                          <input class="form-check-input" type="radio" name="norek" id="flexRadioDefault2" value="TKB" checked>
                                          <label class="form-check-label" for="flexRadioDefault2">
                                            BSI | A/N : PT. Total Karya Berkah | No. Rek :  3557999999
                                          </label>
                                          </div>
                                        @endif
                                     </div>
                                   </div>
                                 <div class="row">
                                   <div class="col-lg-4">
                                   <label for="total-harga" class="form-label col-form-label col-form-label-md">Jumlah Harga</label>
                                   </div>
                                     <div class="col-lg-6">
                                       <input type="text" class="form-control bg-light border-0" rows="1" naplaceholder="" id="total-harga" name ="tot_harga" value="{{ old('tot_harga') }}"readonly >
                                     </div>
                                   </div>
                                   {{-- Input untuk Dp --}}
                                   <div class="row mb-1"  >
                                     <div class="col-lg-4" id="div-label" style="display: none" >
                                       <label for="dp" class="form-label col-form-label col-form-label-md" id="label-dp">Dp</label>
                                     </div>
                                       <div class="col-lg-6" id="dp" style="display: none;">
                                         <input id="input-dp" name="dp" type="text" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' class="form-control bg-light border-0" rows="1" placeholder="Rp. 0,000" value="{{ old('dp') }}">
                                       </div>
                                     </div>
                                     {{-- End Input --}}
                                                                   {{-- Start Input PPN --}}
                                  <div class="row mb-2">
                                    <div class="col-lg-4">
                                      <label for="input-ppn" class="form-label col-form-label col-form-label-md">PPN (%)</label>
                                    </div>
                                    <div class="col-lg-6">
                                      <div class="input-group">
                                        <input type="number" step="any" min="0" max="100" class="form-control bg-light border-0" id="input-ppn" name="ppn" placeholder="0" value="{{ old('ppn') }}">
                                        <span class="input-group-text bg-light border-0 text-muted">%</span>
                                      </div>
                                      <small class="text-success fw-medium mt-1" id="preview-nominal-ppn" style="display: none;"></small>
                                       <small class="text-danger fw-medium mt-1" id="hint-locked-ppn" style="display: none;"><i class="ri-lock-line me-1"></i>Terkunci: Item tidak dikenakan PPN</small>
                                    </div>
                                  </div>

                                  {{-- Kode Transaksi PPN (Tampil otomatis jika PPN diisi) --}}
                                  <div class="row mb-2" id="row-kode-ppn" style="display: none;">
                                    <div class="col-lg-4">
                                      <label for="select-kode-ppn" class="form-label col-form-label col-form-label-md">Kode Transaksi PPN</label>
                                    </div>
                                    <div class="col-lg-6">
                                      <select class="form-select bg-light border-0" name="kode_ppn" id="select-kode-ppn">
                                        <option value="01" selected>01 - Non Pemungut PPN (Swasta)</option>
                                        <option value="02">02 - Pemungut Instansi Pemerintah</option>
                                        <option value="03">03 - Pemungut BUMN / Pemungut Lainnya</option>
                                        <option value="04">04 - DPP Nilai Lain</option>
                                      </select>
                                    </div>
                                  </div>
                                  {{-- End Input PPN --}}

                                  {{-- Start Input PPh --}}
                                  <div class="row mb-2">
                                    <div class="col-lg-4">
                                      <label for="input-pph" class="form-label col-form-label col-form-label-md">PPh (%)</label>
                                    </div>
                                    <div class="col-lg-6">
                                      <div class="input-group">
                                        <input type="number" step="any" min="0" max="100" class="form-control bg-light border-0" id="input-pph" name="pph" placeholder="0" value="{{ old('pph') }}">
                                        <span class="input-group-text bg-light border-0 text-muted">%</span>
                                      </div>
                                      <small class="text-danger fw-medium mt-1" id="preview-nominal-pph" style="display: none;"></small>
                                       <small class="text-danger fw-medium mt-1" id="hint-locked-pph" style="display: none;"><i class="ri-lock-line me-1"></i>Terkunci: Item tidak dikenakan PPh</small>
                                    </div>
                                  </div>
                                  {{-- End Input PPh --}}
                                  <div class="row mb-1">
                                   <div class="col-lg-4">
                                   <label for="totalPembayaran" class="form-label col-form-label col-form-label-md">Total Pembayaran</label>
                                   </div>
                                     <div class="col-lg-6">
                                       <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Total Pembayaran" id="total-pembelian" name="tot_pem" value="{{ old('tot_pem') }}" readonly>
                                     </div>
                                   </div>
                                   <div class="row mb-1">
                                   <div class="col-lg-4">
                                   <label for="sisaPembayaran" class="form-label col-form-label col-form-label-md">Sisa Pembayaran</label>
                                   </div>
                                     <div class="col-lg-6">
                                       <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Sisa Pembayaran" id="sisa-pembayaran" name="sisa_pem" value="{{ old('sisa_pem') }}" readonly>
                                     </div>
                                   </div>
                               </div>
                              </div>
                               <!--end row-->
                               <div class="hstack gap-2 justify-content-start d-print-none mt-4">
                                <button type="submit" id="simpan" class="btn btn-primary btn-load btn-simpan-tokabe">
                                  <span class="d-flex align-items-center">
                                      <span id="loading-spinner" class="spinner-border flex-shrink-0" role="status">
                                          <span class="visually-hidden">Loading...</span>
                                      </span>
                                      <span class="flex-grow-1 ms-2">
                                        <i class="ri-printer-line align-bottom me-1"></i>Simpan
                                      </span>
                                  </span>
                                </button>
                                <a href="{{ route('list_invoice_tokabe') }}" class="btn btn-secondary btn-cancel-tokabe"><i class="ri-close-line align-bottom me-1"></i>Batal</a>
                               </div>
                             </div>
                            </form>
                           </div>
                          </div>
                         </div>
                        </div>
                        <!--end col-->
                      </div>
                    <!--end row-->
                <!-- container-fluid -->
             <script>
               document.getElementById('customer').addEventListener('change', function() {
                   var selectedOption = this.value;
                   var dataList = document.getElementById('customerList').getElementsByTagName('option');
                   for (var i = 0; i < dataList.length; i++) {
                       var option = dataList[i];
                       if (option.value === selectedOption) {
                           document.getElementById('usaha').value = option.getAttribute('data-company');
                           document.getElementById('phone-number').value = option.getAttribute('data-phone');
                           break;
                       }
                   }
               });
           </script>
           
          <script>
    document.addEventListener("DOMContentLoaded", function () {
      let dateField = document.getElementById("date-field");
      let invoiceInput = document.getElementById("noInvoice");
      let dateInput = document.getElementById("dateInput");

      if (dateField) {
        flatpickr(dateField, {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $hariIni }}",
            onChange: function (selectedDates, dateStr) {
                fetch(`/invoice/get-invoice-number?tanggal=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        invoiceInput.value = data.no_invoice;
                    })
                    .catch(error => console.error('Error fetching invoice number:', error));
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
                    <select class="form-select" name="item_id[${count}]" id="productName-${count + 1}">
                        ${itemOptions}
                    </select>
                    <small class="form-text text-muted item-tax-type" id="item-tax-type-${count + 1}"></small>
                </div>
                <textarea class="form-control bg-light border-0" id="productDetails-${count + 1}" name="deskripsi_item[${count}]" rows="2" placeholder="Deskripsi Item"></textarea>
                
                
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
                    <input type="number" class="text-center" id="product-qty-${count + 1}" value="1" name="qty[${count}]" onchange="autoCalc(this)">
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