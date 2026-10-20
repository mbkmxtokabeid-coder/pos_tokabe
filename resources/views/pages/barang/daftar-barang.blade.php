@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<!--datatable responsive css-->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

  <!--<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">-->
@endsection
@extends('layout.template')
@section('content')

{{-- Modal --}}
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" id="deleteModal"  aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-body text-center p-5">
            <input type="hidden" name="barang_delete_id" id="barang_id">
            <lord-icon
            src="https://cdn.lordicon.com/tdrtiskw.json"
            trigger="loop"
            colors="primary:#eee966,secondary:#c71f16"
            state="hover-2"
            style="width:150px;height:150px">
            </lord-icon>
            <div class="mt-4">
              <h4 class="mb-3">Apakah ingin menghapus data?</h4>
              <div class="hstack gap-2 justify-content-center">
                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                  <form id="deleteForm" action="/deleteBarang/" method="POST">
                    @csrf
                    @method('delete')

                    <button type="submit" class="btn btn-danger">
                      <i class="las la-trash-alt fs-18 align-middle me-2"></i>
                      Hapus
                    </button>
                    </form>

              </div>
          </div>
          </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

  <div class="page-content">
    <div class="container-fluid">
      <!-- start page title -->
      <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Barang</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                        <li class="breadcrumb-item active">Daftar Barang</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

        {{-- Start Row 2 --}}
        <div class="row pb-2 align-items-end" style="margin-top:-10px;">
          {{-- Start Button Add Barang --}}
          <div class="col-sm-3" style="margin-right: -150px;">
            <a href="addBarang" class="btn btn-md btn-primary mt-2">
              <i class="las la-plus"></i>Tambah Barang</a>
            </div>
            {{-- End Button Add Barang --}}
          </div>
        {{-- End Row 2 --}}
        
        <!--START CARD-->
        <div class="col-xl-12">
          <div class="card">
               <div class="card-header border-0 align-items-center d-flex">
                  <h4 class="card-title mb-0 flex-grow-1">Potensi Penjualan dan  Profit</h4>
                  <div>
                    <!--<button type="button" class="btn btn-soft-info tombol btn-sm" onclick="brg(0)" id="btn_0">-->
                    <!--  Semua-->
                    <!--</button>-->
                    @foreach ($kategoriBrg as $kat)
                    <button type="button" class="btn btn-soft-info tombol btn-sm" onclick="brg({{ $kat->id }})" id="btn_{{ $kat->id }}">
                      {{ $kat->nama_kategori }}
                  </button>
                    @endforeach

                </div>
              </div>

              <!-- UNTUK ISI CARD -->
              <div class="row">
              <div class="col-6 col-md-6">
                <!-- card -->
                <div class="card m-3">
                    <div class="card-body badge-gradient-primary">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                              <p class="text-uppercase fw-medium fs-14 text-white mb-0" id="potensi_penjualan">

                              </p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">Rp<span class="counter-value penjualan" data-target="0">0</span>M</h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-light rounded-circle fs-3">
                                    {{-- <i class="las la-file-alt fs-24 text-primary"></i> --}}
                                    <i class="las la-donate fs-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-success stok me-1">0</span> <span class="text-white">Barang</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-6 col-md-6">
                <div class="card m-3">
                    <div class="card-body badge-gradient-success">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                              <p class="text-uppercase fw-medium fs-14 text-white mb-0" id="potensi_profit">

                              </p>
                                <h4 class="fs-22 fw-semibold ff-secondary mb-2">Rp<span class="counter-value profit" data-target="0">0</span>M</h4>

                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-light rounded-circle fs-3">
                                    <i class="las la-funnel-dollar fs-24 text-white"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between mt-4">
                            <div>
                                <span class="badge bg-success stok me-1">0</span> <span class="text-white">Barang</span>
                            </div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
          </div>
        </div>
      </div>
      <!-- BATAS -->
          {{-- Start Row 3 --}}
        <div class="row">
          {{-- Start Coloumn --}}
          <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Stok Barang</h5>
                </div>
                <div class="card-body">
                    <table id="alternative-pagination" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Item</th>
                                <th>Kode Barang</th>
                                <th>Jenis Barang</th>
                                <th>Terakhir Update</th>
                                <th>Stok</th>
                                <th>Harga Modal</th>
                                <th>Harga Jual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($barang as $brg)

                            <tr>
                              <td><div class="text-center">{{$loop->iteration}}</div></td>
                              <td>{{$kategori[$brg->kategori_id]}}</td>
                              <td>{{$brg->kode_barang}}</td>
                              <td>{{$brg->jenis_barang}}</td>
                              <td>{{date('d F Y H:i:s', strtotime($brg->updated_at))}}</td>
                              <td>{{$brg->stok}}</td>
                              <td id="harga-modal">Rp. {{$brg->formattedModal}}</td>
                              <td id="harga-jual">Rp. {{$brg->formattedJual}}</td>

                                <td>
                                  <div class="dropdown text-center">
                                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="las la-ellipsis-h align-middle fs-18"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/edit-stok/'.$brg->id) }}"><i class="las la-pen fs-18 align-middle me-2 text-muted"></i>
                                                Perbarui Stok</a>
                                        </li>
                                        <li class="dropdown-divider"></li>
                                        <li>
                                          <button class="dropdown-item hapus-btn remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteModal" id="barang_id" data-id="{{$brg->id}}">
                                                <i class="las la-trash-alt fs-18 align-middle me-2 text-muted"></i>
                                                Hapus
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                </td>
                            </tr>
                            @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- End Coloumn --}}
        </div>
        {{-- End Row 3 --}}
    </div>
  </div>
@endsection

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="{{asset('js/pages/datatables.init.js')}}"></script>
<script src="{{asset('js/halaman/barang.js')}}"></script>
@endsection