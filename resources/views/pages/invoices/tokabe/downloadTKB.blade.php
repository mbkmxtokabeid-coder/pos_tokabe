<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>{{$penjualan->nomor_invoice}} - {{$penjualan->customer}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Total Karya Berkah" name="author" />
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">
    <title>Total Karya Berkah</title>

    <!-- Layout config Js -->
    <script src="{{asset('js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
    
    <style>
      .watermark-bg {
        position: fixed;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        font-size: 120px;
        font-weight: 900;
        color: rgba(0, 0, 0, 0.08);
        text-transform: uppercase;
        letter-spacing: 12px;
        pointer-events: none;
        z-index: 1000;
        white-space: nowrap;
        user-select: none;
      }
    </style>
</head>

<body>
    <div class="watermark-bg">
        {{ strtoupper($type ?? 'ASLI') }}
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-12">
                <div class="card" id="demo">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-lg-12">
                                <h3 class="fw-bold mb-3"></h3>
                                <div class="row g-3">
                                    <div class="col-lg-4 col-4">
                                        <p class="text-dark mb-1 text-uppercase fs-16">Invoice No</p>
                                        <p class="fw-bold fs-16 mb-0"><span id="invoice-no">{{$penjualan->nomor_invoice}}</span></p>
                                    </div>
                                    <div class="col-lg-6 col-6"></div>
                                    <div class="col-lg-2 col-2 d-flex flex-column align-items-center text-center">
                                        <img src="{{asset('images/Logo-TKB.jpg')}}" class="card-logo card-logo-dark" alt="logo dark" height="50">
                                        <a href="https://tokabe.id/" class="link-primary text-decoration-none" target="_blank" id="website">tokabe.id</a>
                                    </div>

                                    <div class="col-4">
                                        <div class="mb-3">
                                            <p class="text-dark mb-1 text-uppercase fs-16">Tanggal</p>
                                            <p class="fw-bold fs-16 mb-0"><span id="invoice-date">{{$formatTanggal}}</span></p>
                                        </div>
                                        <p class="text-dark text-uppercase fs-16 mb-1">Penerima</p>
                                        <p class="fw-bold fs-16 mb-2" id="billing-name">{{$penjualan->customer}}</p>
                                        
                                        <p class="text-dark fs-16 mb-1"><span>Telepon: </span><span id="billing-phone-no">{{$penjualan->no_telepon}}</span></p>
                                    </div>
                                    <div class="col-lg-5 col-5" style="margin-left: 100px"></div>
                                    <div class="col-lg-2 col-2">
                                        <p class="text-dark text-uppercase fs-16 mb-1">Alamat</p>
                                        <p class="text-dark fs-16 mb-1" id="address-details">JL Setia Budi Komplek Setia Budi Point NO D-10 KEL TANJUNG SARI, MEDAN</p>
                                        <p class="mb-0 fs-16"><a href="https://tokabe.id/" class="link-primary text-decoration-none" target="_blank" id="website">www.tokabe.id</a></p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mt-lg-0 "></div>
                            </div>
                        </div>

                        <div class="row p-1 border-top border-top-dashed">
                            <div class="col-lg-9">
                                <div class="row g-3"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body p-3">
                                    <div class="table-responsive">
                                        <style>
                                            .table {
                                                table-layout: fixed;
                                                width: 100%;
                                            }
                                            th:nth-child(1), td:nth-child(1) { width: 5%; }
                                            th:nth-child(2), td:nth-child(2) { width: 40%; }
                                            th:nth-child(3), td:nth-child(3) { width: 15%; }
                                            th:nth-child(4), td:nth-child(4) { width: 10%; }
                                            th:nth-child(5), td:nth-child(5) { width: 15%; }
                                            td.text-start {
                                                word-wrap: break-word;
                                                white-space: normal;
                                            }
                                            p { white-space: normal !important; }
                                        </style>
                                        <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                            <thead>
                                                <tr class="table-active">
                                                    <th scope="col">#</th>
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
                                                                <div>
                                                                    <span class="fw-medium">{{$item->jenis_barang}}</span>
                                                                    <p class="text-muted mb-0">{{$jual->deskripsi_item}}</p>
                                                                </div>
                                                            </td>
                                                            <td>Rp.{{$hargaMod[$index]}}</td>
                                                            <td>{{$jual->qty}}</td>
                                                            <td class="text-end">{{$jumlahHarga[$index]}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="border-top border-top-dashed mt-0">
                                        <div class="col-lg-6 mt-1">
                                            <p style="color:rgb(227, 25, 25);" class="fs-16">Mohon Transfer ke : {{$norek}}</p><br>
                                        </div>

                                        <div class="d-flex justify-content-end align-items-end">
                                            <div style="text-align:center; width:250px; margin-right: 50px;">
                                              <p class="fs-16 mb-0">Medan, {{$formatTanggal}}</p>
                                              <p><br><br><br><br><br></p>
                                              <p class="fs-16 mb-0">{{$admin}}</p>
                                            </div>

                                             <table class="table table-borderless table-nowrap align-middle fs-16 mb-0" style="width:350px">
                                                 <tbody>
                                                     <tr>
                                                         <td>Total Harga</td>
                                                         <td class="text-end">Rp.{{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                                                     </tr>
                                                     
                                                     {{-- LOGIKA PPN/DISKON/PPH/POTONGAN --}}
                                                     @php
                                                         $hasPpn = !empty($penjualan->ppn) && (float)$penjualan->ppn > 0;
                                                         $hasDiskon = !empty($penjualan->diskon) && (float)$penjualan->diskon > 0;
                                                         $hasPotongan = !empty($penjualan->potongan) && (float)$penjualan->potongan > 0;
                                                         $hasPph = !empty($penjualan->pph) && (float)$penjualan->pph > 0;
                                                         
                                                         $autoPpnNominal = 0;
                                                         $autoPpnPersen = 0;
                                                         if (!$hasPpn && !$hasDiskon && !$hasPotongan && !$hasPph && $penjualan->total_pembayaran > $penjualan->total_harga && $penjualan->total_harga > 0) {
                                                             $autoPpnNominal = $penjualan->total_pembayaran - $penjualan->total_harga;
                                                             $autoPpnPersen = round(($autoPpnNominal / $penjualan->total_harga) * 100);
                                                             $hasPpn = true;
                                                         }
                                                     @endphp

                                                     @if ($hasDiskon)
                                                     <tr>
                                                         <td>Diskon<small class="text-muted"> ({{$penjualan->diskon}}%)</small></td>
                                                         <td class="text-end">- Rp.{{ number_format(($penjualan->diskon / 100) * $penjualan->total_harga, 0, ',', '.') }}</td>
                                                     </tr>
                                                     @endif
                                                     @if ($hasPotongan)
                                                     <tr>
                                                         <td>Spesial Diskon</td>
                                                         <td class="text-end">- Rp.{{ number_format($penjualan->potongan, 0, ',', '.') }}</td>
                                                     </tr>
                                                     @endif
                                                     @if ($hasPpn)
                                                     @php
                                                         $ppnVal = !empty($penjualan->ppn) ? $penjualan->ppn : $autoPpnPersen;
                                                         $ppnAmount = !empty($penjualan->ppn) ? (($penjualan->ppn / 100) * $penjualan->total_harga) : $autoPpnNominal;
                                                     @endphp
                                                     <tr>
                                                         <td>PPN<small class="text-muted"> ({{$ppnVal}}%)</small></td>
                                                         <td class="text-end">+ Rp.{{ number_format($ppnAmount, 0, ',', '.') }}</td>
                                                     </tr>
                                                     @endif
                                                     @if ($hasPph)
                                                     <tr>
                                                         <td>PPH<small class="text-muted"> ({{$penjualan->pph}}%)</small></td>
                                                         <td class="text-end">- Rp.{{ number_format(($penjualan->pph / 100) * $penjualan->total_harga, 0, ',', '.') }}</td>
                                                     </tr>
                                                     @endif

                                                     <tr class="border-top border-top-dashed">
                                                         <td>Total Pembayaran</td>
                                                         <td class="text-end">Rp.{{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</td>
                                                     </tr>

                                                     @if (!empty($penjualan->dp) && (float)$penjualan->dp > 0)
                                                     <tr>
                                                         <td>DP / Panjar</td>
                                                         <td class="text-end">- Rp.{{ number_format($penjualan->dp, 0, ',', '.') }}</td>
                                                     </tr>
                                                     @endif

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
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('js/plugins.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>

</body>
</html>