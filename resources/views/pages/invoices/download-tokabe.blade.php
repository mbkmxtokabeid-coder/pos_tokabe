<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{$namaInv}} - {{$penjualan->customer}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta content="Percetakan Terbaik di Kota Medan" name="description" /> --}}
    <meta content="Total Karya Berkah" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">

      <title>Total Karya Berkah</title>

      {{-- HEAD CSS  --}}

  <!-- Layout config Js -->
   <script src="{{asset('js/layout.js')}}"></script>
   <!-- Bootstrap Css -->
   <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <script>
        window.onload = function () {
            window.print()
        }
    </script>
</head>
{{-- <div class="main-content"> --}}

            {{-- <div class="page-content"> --}}
                <div class="container-fluid">

                    <!-- start page title -->

                    <!-- end page title -->

                    <div class="row justify-content-center">
                        <div class="col-xxl-12">
                            <div class="card" id="demo">
                                   <div class="card-body">
                                    <div class="row ">
                                        <div class="col-lg-12">
                                            <p class="fw-bold fs-17 mb-3">Invoice: Total Karya Berkah</p>
                                            <div class="row g-3">
                                                <div class="col-lg-4 col-4">
                                                    <p class="text-dark mb-1 text-uppercase fs-16">Invoice No</p>
                                                    <p class="fw-bold fs-16 mb-0"><span id="invoice-no">{{$penjualan->nomor_invoice}}</span></p>
                                                </div>
                                                <div class="col-lg-6 col-6"></div>
                                                <!--end col-->
                                                <div class="col-lg-2 col-2">
                                                    <img src="{{asset('images/LogoTKB.png')}}" class="card-logo card-logo-dark" alt="logo dark" height="35">

                                                </div>
                                                <!--end col-->
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        {{-- sini --}}
                                                        <p class="text-dark mb-1 text-uppercase fs-16">Tanggal</p>
                                                        <p class="fw-bold fs-16 mb-0"><span id="invoice-date">{{$formatTanggal}}</span></p>
                                                    </div>
                                                    <p class="text-dark text-uppercase fs-16 mb-1">Penerima</p>
                                                    <p class="fw-bold fs-16 mb-2" id="billing-name">{{$penjualan->customer}}</p>
                                                    <p class="text-dark fs-16 mb-1"><span>Perusahaan: </span><span id="billing-address-line-1">{{$penjualan->perusahaan}}</span></p>
                                                    <p class="text-dark fs-16 mb-1"><span>Telepon: </span><span id="billing-phone-no">{{$penjualan->no_telepon}}</span></p>

                                                </div>
                                                <div class="col-lg-5 col-5" style="margin-left: 100px"></div>
                                                <div class="col-lg-2 col-2">
                                                    <p class="text-muted mb-1 text-uppercase fw-medium fs-16">Payment Status</p>
                                                    @if ($penjualan->status == 'Lunas')
                                                        <span class="badge badge-soft-primary fs-13 mb-3" id="payment-status">{{$penjualan->status}}</span>
                                                    @else
                                                        <span class="badge badge-soft-danger fs-13 mb-3" id="payment-status">{{$penjualan->status}}</span>
                                                    @endif
                                                    <p class="text-dark text-uppercase fs-16 mb-1">Alamat</p>
                                                <p class="text-dark fs-16 mb-1" id="address-details">JL Setia Budi Komplek Setia Budi Point NO D-10 KEL TANJUNG SARI, MEDAN</p>
                                                <p class="mb-0 fs-16"><a href="https://tokabe.id/" class="link-primary text-decoration-none" target="_blank" id="website">www.tokabe.id</a></p>

                                                {{-- <h6 class="mb-0"><span class="text-muted fw-normal">Admin 1 : </span><span id="contact-no" class="text-muted"> 08112272727</span></h6>
                                                <h6 class="mb-0"><span class="text-muted fw-normal">Admin 2: </span><span id="contact-no" class="text-muted"> 08170769999</span></h6> --}}
                                                </div>

                                                <!--end col-->
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mt-lg-0 ">


                                            </div>
                                        </div>
                                    </div><!--end col-->

                                    <div class="row p-1 border-top border-top-dashed">
                                        <div class="col-lg-9">
                                            <div class="row g-3">

                                                <!--end col-->

                                                <!--end col-->
                                            </div>
                                            <!--end row-->
                                        </div><!--end col-->

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card-body p-3">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                        <thead>
                                                         <tr class="table-active">
                                                          <th scope="col" style="width: 50px;">#</th>
                                                                <th scope="col">Deskripsi Barang</th>
                                                                <th scope="col">Harga</th>
                                                                <th scope="col">Qty</th>
                                                                <th scope="col" class="text-end">Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="products-list">
                                                            @foreach ($penjualan_barang as $index => $jual)
                                                                @foreach ($barang[$index] as $item)
                                                                   @php
                                                                    $nomor = $index + 1;
                                                                   @endphp
                                                                   <tr>
                                                                    <th scope="row">{{$nomor}}</th>
                                                                    <td class="text-start">
                                                                        <span class="fw-medium">{{$item->jenis_barang}}</span>
                                                                        <p class="text-muted mb-0">{{$jual->deskripsi_item}}</p>
                                                                    </td>
                                                                    <td>Rp.{{$hargaMod[$index]}}</td>
                                                                    <td>{{$jual->qty}}</td>
                                                                    <td class="text-end">{{$jumlahHarga[$index]}}</td>
                                                                </tr>
                                                                @endforeach
                                                            @endforeach

                                                        </tbody>
                                                    </table><!--end table-->
                                                </div>

                                                <div class="border-top border-top-dashed mt-0">
                                                    @if (strpos($penjualan->jenis_pembayaran, 'Cash') === false && $penjualan->no_rek === 'BNI')
                                                     <div class="col-lg-6 mt-1">
                                                         <p style="color:rgb(227, 25, 25);" class="fs-16">Mohon Transfer Ke :<br>
                                                             BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999</p>
                                                     </div>
                                                     @elseif (strpos($penjualan->jenis_pembayaran, 'Cash') === false && $penjualan->no_rek === 'TKB')
                                                     <div class="col-lg-6 mt-1">
                                                         <p style="color:rgb(227, 25, 25);" class="fs-16">Mohon Transfer Ke :<br>
                                                            BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999</p>
                                                     </div>
                                                    @endif

                                                    <table class="table table-borderless table-nowrap align-middle fs-16 mb-0 ms-auto" style="width:350px">
                                                        <tbody>
                                                           
                                                            <tr>
                                                            <!--@if (!is_null($penjualan->diskon))-->
                                                            <!--<td>Diskon<small class="text-muted">({{$persen}})</small></td>-->
                                                            <!--<td class="text-end">- {{$biayaLain}}</td>-->
                                                            <!--@endif-->
                                                            <!--@if (!is_null($penjualan->potongan))-->
                                                            <!--<td>Potongan</td>-->
                                                            <!--<td class="text-end">- {{$biayaLain}}</td>-->
                                                            <!--@endif-->
                                                            @if (!is_null($penjualan->ppn))
                                                            <td>PPN<small class="text-muted">({{$persen}})</small></td>
                                                            <td class="text-end"> {{$biayaLain}}</td>
                                                            @endif
                                                            @if (is_null($penjualan->diskon) && is_null($penjualan->potongan) && is_null($penjualan->ppn))
                                                            <td></td><br>
                                                            @endif
                                                            </tr>
                                                             <tr>
                                                                <td>Total</td>
                                                                <td class="text-end">{{$totHargaMod}}</td>
                                                            </tr>
                                                            <tr>
                                                                @if (!is_null($dp))
                                                                <td>DP</td>
                                                                <td class="text-end">-Rp.{{$dp}}</td>
                                                                @endif
                                                            </tr>
                                                            <tr class="border-top border-top-dashed fw-bold fs-17"
                                                            @if ($penjualan->status === 'Belum Lunas')
                                                            style="color:rgb(227, 25, 25);"
                                                            @else
                                                            style="color: rgb(67,138,122);"
                                                            @endif>
                                                                <th scope="row" class="fw-bold fs-17">Sisa Pembayaran</th>
                                                                <th class="text-end fw-bold fs-17">Rp.{{$sisaBayarMod}}</th>

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <!--end table-->
                                                </div>
                                            </div>
                                            <!--end card-body-->
                                        </div><!--end col-->
                                    </div>
                                   </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>

                </div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-3">
                                <script>document.write(new Date().getFullYear())</script> © IkhtiarBerkah.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-start d-none d-sm-block">
                                     Develop by IkhtiarBerkah
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- container-fluid -->
            {{-- </div> --}}
            <!-- End Page-content -->


        {{-- </div> --}}
        <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('js/plugins.js')}}"></script>

{{-- @yield('plugins') --}}
<!-- App js -->
<script src="{{asset('js/app.js')}}"></script>

</html>