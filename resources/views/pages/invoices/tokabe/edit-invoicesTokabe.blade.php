@section('head')
<!-- dropzone css -->

<link rel="stylesheet" href="{{asset('libs/dropzone/dropzone.css')}}" type="text/css" />

<!-- Sweet Alert css-->
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/invoice-tokabe-form.css')}}" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
  .error-help-block{
    color: #ca131e;
  }
</style>
@endsection
@extends('layout.template')
@section('content')
@if($inv->approval == 'Lock')
<div class="alert alert-warning d-flex align-items-center" role="alert">
    <i class="las la-lock me-2 fs-18"></i>
    <div>
        Invoice ini sedang terkunci. Silakan minta Pemilik untuk memberikan izin unlock terlebih dahulu.
        <a href="{{ route('tokabe.approval.page', $inv->id) }}" class="alert-link ms-1">Minta Unlock</a>
    </div>
</div>
@endif

<div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Edit Invoice</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Invoice</a></li>
                                        <li class="breadcrumb-item active">Edit Invoice</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row justify-content-center">
                       <div class="col-xxl-12">
                         <div class="card">
                           <form action="{{ route('update-invoiceTokabe',['id'=> $inv->id]) }}" method ="POST" enctype="multipart/form-data" id="invoice_form" data-invoice-id="{{ $inv->id }}">
                            @csrf
                            @method('put')
                             <div class="card-body border-bottom border-bottom-dashed p-4">
                               <div class="row">
                                 <div class="col-lg-6">
                                   <div class="row g-3 mb-2">
                                       <!--end col-->

                                      <div class="col-lg-8 col-sm-6">
                                         <label for="choices-invoice">Invoice</label>
                                            <div class="input-light">
                                               <input type="text" class="form-control bg-light border-0 fw-medium" value="Tokabe" readonly><input type="hidden" id="choices-invoice" name="inv" value="5">

                                            </div>
                                      </div>

                                         {{-- End coloumn --}}
                                   </div>
                                      <div class="row mb-2 col-lg-8 col-sm-6">
                                          <label for="invoicenoInput">Invoice No</label>
                                          
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control bg-light border-0" id="noInvoice" placeholder="kode Unik" name="kodeUnik" value="{{$nomor_unik}}" readonly="readonly">
                                          </div>
                                      </div>
                                        <!--end col-->
                                      <div class="row mb-2 col-lg-9">

                                          <label for="date-field">Tanggal Penjualan</label>
                                          <div class="col-lg-4">
                                            <input type="text" class="form-control bg-light border-0 flatpickr-input" id="date-field" name="tgl_jual" data-provider="flatpickr" value="{{$inv->tgl_penjualan}}" readonly>
                                          </div>
                                          <div class="col-lg-6">
                                            <input type="text" class="form-control bg-light border-0" placeholder="H:i" name="jam" value="{{$jam}}" readonly="readonly">
                                          </div>

                                        </div>
                                        <!--end col-->


                                        <!--end col-->
                                    {{-- </div> --}}

                                 </div>
                                  <!--end col-->
                                 <div class="col-lg-4 ms-auto">
                                  <div class="profile-user mx-auto  mb-3">
                                   <input id="profile-img-file-input" type="file" class="profile-img-file-input"/>
                                   <label for="profile-img-file-input" class="d-block" tabindex="0">
                                    <span class="overflow-hidden border border-dashed d-flex align-items-center justify-content-center rounded" style="width: 256px;">
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
                                        <input type="text" class="form-control form-control-md @error('pelanggan') is-invalid @enderror" name="pelanggan" id="customer" placeholder="Nama Customer"value="{{ $inv->customer }}">
                                        @error('pelanggan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                       </div>
                                      </div>

                                         {{-- Start No.Telepon --}}
                                      <div class="row mb-3" style="margin-top: -10px;">
                                       <div class="col-lg-5">
                                        <label for="contactNumber" class="form-label col-form-label col-form-label-md">Nomor Telepon</label>
                                       </div>
                                       <div class="col-lg-7">
                                        <input type="text" class="form-control @error('tlp') is-invalid @enderror" id="phone-number" name="tlp" placeholder="08xxxxxxxx" value="{{ $inv->no_telepon }}">
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
                                        <select class="form-control bg-light border-0 @error('adm') is-invalid @enderror" name="adm" data-choices data-choices-search-false id="choices-admin" required>
                                          <option value="{{ $inv->admin }}" selected>{{$nama_adm}}</option>
                                          @foreach ($admin as $adm)
                                          @if ($adm->id != $inv->admin)
                                             <option value="{{$adm->id}}">{{$adm->nama}}</option>
                                          @endif
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
                                        <select class="form-select bg-light border-0 @error('order') is-invalid @enderror" id="choices-orderBy" name="order" required>
                                        <option value="{{ $inv->order_by }}" selected>{{ $inv->order_by }}</option>
                                        @foreach ($order as $ord)

                                        @if ($inv->order_by != $ord->order_by)
                                        <option value="{{ $ord->order_by }}">{{ $ord->order_by }}</option>
                                        @endif
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
                                                <input type="text" class="form-control form-control-md mt-2 @error('sales') is-invalid @enderror" id="colFormLabelNamaSales" placeholder="Nama Sales" name="sales" value="{{ $sales }}">
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
                                        <input type="text" class="form-control flatpickr-input @error ('tgl_selesai') is-invalid @enderror" data-provider="flatpickr" data-date-format="Y-m-d" name="tgl_selesai" id="dateInput" placeholder="YYYY-MM-DD" value="{{ $inv->tgl_selesai }}" autocomplete="off">
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

                                 <!-- TEMPLATE TERSEMBUNYI UNTUK OPTION MATERIAL DAN BARANG -->
                                 
                                 <div id="template-options-barang" style="display: none;">
                                    <option selected disabled>Pilih Item</option>
                                    @foreach ($jenisBarang as $jns)
                                        <option value="{{$jns->id}}" data-kategori-id="{{$jns->kategori_id}}">{{$jns->jenis_barang}}</option>
                                    @endforeach
                                 </div>
                                 <div id="template-options-item" style="display: none;">
                                    <option selected disabled>Pilih Item</option>
                                    @foreach ($items as $catalogItem)
                                        <option value="{{$catalogItem->id}}" data-jenis-pajak="{{$catalogItem->jenis_pajak}}" data-harga="{{$catalogItem->harga_jual ?? 0}}">{{$catalogItem->nama_item}}</option>
                                    @endforeach
                                 </div>
                                 <!-- END TEMPLATE -->

                               <table class="invoice-table  dt-responsive table table-borderless table-nowrap mb-0">
                              <div class="table-responsive">
                                <thead class="align-middle">
                                 <tr class="table-active">
                                  <th scope="col" style="width: 50px;">#</th>
                                  <th scope="col" style="min-width: 500px;">Item</th>
                                   <th scope="col" style="min-width: 110px; width: 110px;">Satuan</th>
                                  <th scope="col" style="width: 120px;">
                                   <div class="d-flex currency-select input-light align-items-center">Harga
                                    <select class="form-selectborder-0 bg-light" data-choices data-choices-search-false id="choices-payment-currency" onchange="otherPayment()">
                                    <option value="Rp">(Rp)</option>
                                    </select>
                                   </div></th>
                                  <th scope="col" style="width: 120px;">Quantity</th>
                                  <th scope="col" class="text-center" style="width: 150px;">Jumlah Harga</th>
                                  <th scope="col" class="text-end" style="width: 105px;"></th>
                                 </tr>
                                </thead>
                                <tbody id="newlink">
                                  @foreach ($penjualan_barang as $index => $jual)
                                  @foreach ($barang[$index] as $item)
                                 <tr id="{{$index + 1}}" class="product">
                                  <th scope="row" class="product-id">{{$index + 1}}</th>
                                   <td class="text-start">
                                    <div class="mb-2">
                                      <label class="visually-hidden" for="productName">Item</label>

                                      {{-- PENTING: NAME MENGGUNAKAN EXPLICIT INDEX AGAR ARRAY TIDAK TERTUKAR SAAT DISUBMIT --}}
                                      @if ($jual->item_id)
                                      <select class="form-select @error('item_id.'.$index) is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-{{$index+1}}" name="item_id[{{$index}}]" >
                                          @foreach ($items as $catalogItem)
                                            <option value="{{$catalogItem->id}}" data-jenis-pajak="{{$catalogItem->jenis_pajak}}" data-harga="{{$catalogItem->harga_jual ?? 0}}" {{ $catalogItem->id == $jual->item_id ? 'selected' : '' }}>{{$catalogItem->nama_item}}</option>
                                          @endforeach
                                      </select>
                                      @else
                                      <select class="form-select @error('barang_id.'.$index) is-invalid @enderror" data-choices data-choices-sorting="true" id="productName-{{$index+1}}" name="barang_id[{{$index}}]" >
                                          <option value="{{ $item->id }}" data-kategori-id="{{$item->kategori_id}}">{{ $item->jenis_barang }}</option>
                                          @foreach ($jenisBarang as $jns)
                                            @if ($item->id != $jns->id)
                                            <option value="{{$jns->id}}" data-kategori-id="{{$jns->kategori_id}}">{{$jns->jenis_barang}}</option>
                                            @endif
                                          @endforeach
                                      </select>
                                      @endif
                                      @error('barang_id.'.$index)
                                      <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                  </div>
                                     <textarea class="form-control bg-light border-0 @error('deskripsi_item.'.$index) is-invalid @enderror" id="productDetails-{{$index+1}}" name="deskripsi_item[{{$index}}]" rows="2" placeholder="Deskripsi Item" >{{$jual->deskripsi_item}}</textarea>
                                     @error('deskripsi_item.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror

                                     

                                   </td>
                                   <td>
                                    <select class="form-select bg-light border-0 @error ('satuan.'.$index) is-invalid @enderror" style="min-width: 95px;" id="satuan-{{$index+1}}" name="satuan[{{$index}}]" required>
                                    
                                    <option value="{{$jual->satuan}}"selected>{{$jual->satuan}}</option>
                                    @foreach(['Pcs', 'Set', 'Und', 'Blok', 'Rim', 'Lbr', 'Ktk', 'Unit', 'Kg', 'Lainnya', 'Pax'] as $option)
                                        @if($option !== $jual->satuan)
                                            <option value="{{$option}}">{{$option}}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                    @error('satuan.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                   </td>
                                   <td>
                                    <input type="text" class="form-control product-price bg-light border-0 @error('hrg.'.$index) is-invalid @enderror" id="productRate-{{$index+1}}" name="hrg[{{$index}}]" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' placeholder="Rp. 0.000" onchange="autoCalc(this)" value="{{$jual->hargaBarang}}" required/>
                                    @error('hrg.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror

                                    </td>
                                    <td>
                                     <div class="input-step">
                                      <input type="number" class="text-center @error('qty.'.$index) is-invalid @enderror" id="product-qty-{{$index+1}}" value="{{$jual->qty}}" name="qty[{{$index}}]" onchange="autoCalc(this)">
                                      @error('qty.'.$index)
                                     <div class="invalid-feedback">{{ $message }}</div>
                                     @enderror
                                     </div>
                                    </td>
                                    <td class="text-end">
                                     <div>
                                      <input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-{{$index+1}}" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' name="jlh_hrg[{{$index}}]" placeholder="Rp.0.000" value="{{$jual->jumlah_harga}}" onchange="autoCalc(this)"readonly/>
                                     </div>
                                    </td>
                                    <td class="product-removal"><a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="ri-delete-bin-5-line me-1"></i>Hapus</a>
                                    </td>
                                   </tr>
                                   @endforeach
                                   @endforeach
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
                                  <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="jenisPembayaran" class="form-label col-form-label col-form-label-md">Jenis Pembayaran</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <select class="form-select bg-light border-0 @error('jns_pem') is-invalid @enderror" id="choices-payment-type" name="jns_pem">
                                          <option selected value="{{ $inv->jenis_pembayaran }}">{{ $inv->jenis_pembayaran }}</option>
                                          @foreach (['Cash Lunas', 'Cash Belum Lunas', 'Transfer DP', 'Transfer Lunas', 'PO'] as $option)
                                            @if ($option !== $inv->jenis_pembayaran)
                                              <option value="{{ $option }}">{{ $option }}</option>
                                            @endif
                                          @endforeach
                                        </select>

                                        @error('jns_pem')
                                          <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                    </div>
                                    <div class="row mb-2 col-lg-12">
                                      <div class="col lg-12" style="{{ ($inv->no_rek !== null) ? 'display: block' : 'display: none' }};">
                                        <!--<div class="form-check mb-2 col-lg-12" id="alamatPembayaran" >-->
                                        <!--  <input class="form-check-input " type="radio" name="norek" id="flexRadioDefault1" value="BNI" {{ ($inv->no_rek ==='BNI') ? 'checked' : '' }}>-->
                                        <!--  <label class="form-check-label" for="flexRadioDefault1">-->
                                        <!--    BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999-->
                                        <!--  </label>-->
                                        <!--  </div>-->
                                          @if(isset($rekenings) && $rekenings->count() > 0)
                                           @foreach($rekenings as $rek)
                                           <div class="form-check mb-2 col-lg-12">
                                             <input class="form-check-input" type="radio" name="norek" id="flexRadioRek{{ $rek->id }}" value="{{ $rek->id }}" {{ ($inv->no_rek == $rek->id || ($inv->no_rek === 'TKB' && $loop->first)) ? 'checked' : '' }}>
                                             <label class="form-check-label" for="flexRadioRek{{ $rek->id }}">
                                               {{ $rek->formatted }}
                                             </label>
                                           </div>
                                           @endforeach
                                         @else
                                           <div class="form-check mb-2 col-lg-12" id="alamatPembayaran2" >
                                           <input class="form-check-input " type="radio" name="norek" id="flexRadioDefault2" value="TKB" {{ ($inv->no_rek ==='TKB') ? 'checked' : '' }}>
                                           <label class="form-check-label" for="flexRadioDefault2">
                                             BSI | A/N : PT. Total Karya Berkah | No. Rek :  3557999999
                                           </label>
                                           </div>
                                         @endif
                                          
                                      </div>
                                    </div>
                                  <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="total-harga" class="form-label col-form-label col-form-label-md">Jumlah Harga</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control bg-light border-0" rows="1" naplaceholder="" id="total-harga" name ="tot_harga" value="{{ $inv->total_harga }}"readonly >
                                      </div>
                                    </div>
                                    {{-- Input untuk Dp --}}
                                    <div class="row mb-1"  >
                                      <div class="col-lg-4" id="div-label" style="display: block" >
                                        <label for="dp" class="form-label col-form-label col-form-label-md" id="label-dp">Dp</label>
                                      </div>
                                      <div class="col-lg-6" id="dp" style="display: block;">
                                        @if (!empty($inv->dp))
                                          <input id="input-dp" name="dp" type="text" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }'  class="form-control bg-light border-0" rows="1" placeholder="Rp. 0,000" value="{{ $inv->dp }}">
                                        @else
                                          <input id="input-dp" data-cleave='{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }' name="dp" type="text" class="form-control bg-light border-0" rows="1" placeholder="Rp. 0,000">
                                        @endif
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
                                        <input type="number" step="any" min="0" max="100" class="form-control bg-light border-0" id="input-ppn" name="ppn" placeholder="0" value="{{ old('ppn', $inv->ppn) }}">
                                        <span class="input-group-text bg-light border-0 text-muted">%</span>
                                      </div>
                                      <small class="text-success fw-medium mt-1" id="preview-nominal-ppn" style="display: {{ ($inv->ppn && $inv->ppn > 0) ? 'block' : 'none' }};">
                                        @if ($inv->ppn && $inv->ppn > 0)
                                          + Rp. {{ number_format(($inv->ppn / 100) * $inv->total_harga, 0, ',', '.') }}
                                        @endif
                                      </small>
                                      <small class="text-danger fw-medium mt-1" id="hint-locked-ppn" style="display: none;"><i class="ri-lock-line me-1"></i>Terkunci: Item tidak dikenakan PPN</small>
                                    </div>
                                  </div>

                                  {{-- Kode Transaksi PPN --}}
                                  <div class="row mb-2" id="row-kode-ppn" style="display: {{ ($inv->ppn && $inv->ppn > 0) ? 'flex' : 'none' }};">
                                    <div class="col-lg-4">
                                      <label for="select-kode-ppn" class="form-label col-form-label col-form-label-md">Kode Transaksi PPN</label>
                                    </div>
                                    <div class="col-lg-6">
                                      <select class="form-select bg-light border-0" name="kode_ppn" id="select-kode-ppn">
                                        <option value="01" {{ ($inv->kode_ppn ?? '01') == '01' ? 'selected' : '' }}>01 - Non Pemungut PPN (Swasta)</option>
                                        <option value="02" {{ ($inv->kode_ppn ?? '') == '02' ? 'selected' : '' }}>02 - Pemungut Instansi Pemerintah</option>
                                        <option value="03" {{ ($inv->kode_ppn ?? '') == '03' ? 'selected' : '' }}>03 - Pemungut BUMN / Pemungut Lainnya</option>
                                        <option value="04" {{ ($inv->kode_ppn ?? '') == '04' ? 'selected' : '' }}>04 - DPP Nilai Lain</option>
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
                                        <input type="number" step="any" min="0" max="100" class="form-control bg-light border-0" id="input-pph" name="pph" placeholder="0" value="{{ old('pph', $inv->pph) }}">
                                        <span class="input-group-text bg-light border-0 text-muted">%</span>
                                      </div>
                                      <small class="text-danger fw-medium mt-1" id="preview-nominal-pph" style="display: {{ ($inv->pph && $inv->pph > 0) ? 'block' : 'none' }};">
                                        @if ($inv->pph && $inv->pph > 0)
                                          - Rp. {{ number_format(($inv->pph / 100) * $inv->total_harga, 0, ',', '.') }}
                                        @endif
                                      </small>
                                      <small class="text-danger fw-medium mt-1" id="hint-locked-pph" style="display: none;"><i class="ri-lock-line me-1"></i>Terkunci: Item tidak dikenakan PPh</small>
                                    </div>
                                  </div>
                                  {{-- End Input PPh --}}
                                  <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="totalPembayaran" class="form-label col-form-label col-form-label-md">Total Pembayaran</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Total Pembayaran" id="total-pembelian" name="tot_pem" value="{{ old('tot_pem', number_format($inv->total_pembayaran)) }}" readonly>
                                      </div>
                                    </div>
                                    <div class="row mb-1">
                                    <div class="col-lg-4">
                                    <label for="sisaPembayaran" class="form-label col-form-label col-form-label-md">Sisa Pembayaran</label>
                                    </div>
                                      <div class="col-lg-6">
                                        <input type="text" class="form-control bg-light border-0" rows="1" placeholder="Sisa Pembayaran" id="sisa-pembayaran" name="sisa_pem" value="{{$inv->sisa_pembayaran}}" readonly>
                                      </div>
                                    </div>
                                </div>
                               </div>
                               <!--end row-->
                               <div class="hstack gap-2 justify-content-start d-print-none mt-4">
                                 <button type="submit" id="update" class="btn btn-info"><i class="ri-printer-line align-bottom me-1"></i>Update</button>
                                 <a href="{{ $inv->is_custom == 1 ? route('list_invoice_tokabe_custom') : route('list_invoice_tokabe') }}" class="btn btn-secondary btn-cancel-tokabe"><i class="ri-close-line align-bottom me-1"></i>Batal</a>
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

              {{-- @extends('layout.vendor-script') --}}
<script>
  // var jenisBarang = @json($jenisBarang);
</script>

{!! JsValidator::formRequest('App\Http\Requests\InvoiceRequest') !!}
@endsection

@section('plugins')
<!-- dropzone min -->
<script src="{{asset('libs/dropzone/dropzone-min.js')}}"></script>

<!-- cleave.js -->
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>


<!-- Sweet Alerts js -->
<script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>

{{-- choices.js & flatpickr --}}
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('js/halaman/update-invoice.js')}}"></script>
<script src="{{asset('js/halaman/tabel-item-tokabe.js')}}"></script>
<script src="{{asset('js/halaman/invoice.js')}}"></script>
<script src="{{asset('js/halaman/draft-invoice-tokabe.js')}}"></script>

<script>


    document.addEventListener("DOMContentLoaded", function () {
        let dateField = document.getElementById("date-field");
        let dateInput = document.getElementById("dateInput");

        if (dateField && !dateField._flatpickr) {
            flatpickr(dateField, {
                dateFormat: "Y-m-d",
                @if(!empty($inv->tgl_penjualan))
                defaultDate: "{{ $inv->tgl_penjualan }}",
                @endif
            });
        }

        if (dateInput && !dateInput._flatpickr) {
            flatpickr(dateInput, {
                dateFormat: "Y-m-d",
                allowInput: true,
                @if(!empty($inv->tgl_selesai))
                defaultDate: "{{ $inv->tgl_selesai }}",
                @endif
            });
        }
    });

    $(document).ready(function() {
        $('.material-select').each(function() {
            if ($(this).val() !== '') {
                $(this).trigger('change', [true]); 
            }
        });
    });

    function calcTokabeEditMaterialQty(row) {
        var panjang = parseFloat(row.find('.material-panjang').val());
        var lebar = parseFloat(row.find('.material-lebar').val());
        var productRow = row.closest('tr.product');
        if (!productRow.length) {
            productRow = row.closest('tr');
        }
        var itemQty = parseFloat(productRow.find('input[name="qty[]"], [id^="product-qty-"]').val()) || 1;

        if (!isNaN(panjang) && !isNaN(lebar)) {
            var hasil = (panjang * lebar) * itemQty;
            row.find('.material-qty').val(parseFloat(hasil.toFixed(4))).trigger('input');
        } else if (!isNaN(panjang)) {
            var hasil = panjang * itemQty;
            row.find('.material-qty').val(parseFloat(hasil.toFixed(4))).trigger('input');
        }
    }

    // Auto Hitung "Hasil" berdasarkan input Panjang, Lebar, dan Quantity Tokabe Edit
    $(document).on('input', '.material-panjang, .material-lebar', function() {
        calcTokabeEditMaterialQty($(this).closest('.material-row'));
    });

    $(document).on('input change', 'input[name="qty[]"], [id^="product-qty-"]', function() {
        var productRow = $(this).closest('tr.product');
        if (!productRow.length) {
            productRow = $(this).closest('tr');
        }
        productRow.find('.material-row').each(function() {
            calcTokabeEditMaterialQty($(this));
        });
    });

    // Validasi Qty/Hasil Input tidak boleh melebihi stok & Update Angka Sisa Secara Realtime
    $(document).on('input', '.material-qty', function() {
        var max = parseFloat($(this).attr('max'));
        var val = parseFloat($(this).val()) || 0; // Jika kosong anggap 0
        var row = $(this).closest('.material-row');
        
        if (!isNaN(max)) {
            if (val > max) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Kurang',
                    text: 'Jumlah material (Hasil) melebihi stok (Maksimal: ' + parseFloat(max) + ')',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $(this).val(parseFloat(max));
                val = max; // Set val ke max agar sisa tidak minus
            }
            
            // Hitung dan update tampilan sisa. ParseFloat membunuh angka nol di belakang koma (misal: 10.00 -> 10)
            var sisa = max - val;
            row.find('.sisa-stok-angka').text(parseFloat(sisa.toFixed(4))); 
        }
    });

    // 1. BUAT MAPPING MATERIAL REQUIRED DI JAVASCRIPT
    const isMaterialRequiredMap = {};
    @if(isset($jenisBarang))
        @foreach ($jenisBarang as $jns)
            isMaterialRequiredMap["{{$jns->id}}"] = "{{$jns->is_material_required ?? 0}}";
        @endforeach
    @endif

    function updateMaterialVisibility($select) {
        var selectedBarangId = $select.val();
        var td = $select.closest('td');
        var materialContainers = td.find('.materials-container, [class*="material-container-"], .material-row');
        var tambahBtn = td.find('.add-material, .btn-tambah-material');
        var isNonMaterial = (isMaterialRequiredMap[selectedBarangId] == "1"); // ON = Non-Material

        if (isNonMaterial) {
            materialContainers.hide();
            tambahBtn.hide();
            materialContainers.find('select.material-select').val('');
            materialContainers.find('input.material-panjang, input.material-lebar, input.material-qty').val('');
        } else {
            materialContainers.show();
            tambahBtn.show();
        }
    }

    $(document).on('change', 'select[name^="barang_id"]', function() {
        updateMaterialVisibility($(this));
    });

    setTimeout(function() {
        $('select[name^="barang_id"]').each(function() {
            updateMaterialVisibility($(this));
        });
    }, 200);

    


    // OVERRIDE FUNGSI new_link DARI update-invoice.js / tabel-item.js
    var count = document.querySelectorAll('#newlink tr.product').length;
    
    window.new_link = function() {
        var tr = document.createElement("tr");
        tr.id = count + 1;
        tr.className = "product";
        
        // 1. Siapkan Opsi Material (dari PHP ke JS)
        

        // 2. Siapkan Opsi Item Barang (dari PHP ke JS)
        var itemOptionsDOM = document.getElementById('template-options-item');
        var itemOptions = itemOptionsDOM ? itemOptionsDOM.innerHTML : '<option selected disabled>Pilih Item</option>';

        // 3. Template HTML Baris Baru
        var template = `
            <th scope="row" class="product-id">${count + 1}</th>
            <td class="text-start">
                <div class="mb-2">
                    <select class="form-select" name="item_id[${count}]" id="productName-${count + 1}">
                        ${itemOptions}
                    </select>
                </div>
                <textarea class="form-control bg-light border-0" id="productDetails-${count + 1}" name="deskripsi_item[${count}]" rows="2" placeholder="Deskripsi Item"></textarea>
                
                
            </td>
            <td>
                <select class="form-select bg-light border-0" style="min-width: 95px;" name="satuan[${count}]" id="satuan-${count + 1}" required>
                    <option value="">Pilih</option>
                    <option value="Pcs">Pcs</option>
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

        if (typeof Cleave !== 'undefined') {
             new Cleave(`#productRate-${count + 1}`, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        }
        
        count++; // Increment AFTER rendering so indices are correct (0, 1, 2, ...)
    }
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

    $('#invoice_form').on('submit', function() {
      $('#input-ppn, #input-pph').prop('disabled', false);
    });

</script>
@endsection