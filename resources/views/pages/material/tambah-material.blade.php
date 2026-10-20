@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<style>
  .error-help-block{
    color: #ca131e;
  }
</style>
@endsection
@extends('layout.template')
@section('content')
    <div class="page-content">
      <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0">Tambah Material</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Material</a></li>
                          <li class="breadcrumb-item active">Tambah Material</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
      <!-- end page title -->
      <div class="row justify-content-center">
        <div class="col-xxl-12">
          <div class="card">
            {{-- Start Isi Form --}}
            <form action="{{ Route::has('material.store') ? route('material.store') : '#' }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-lg-6">
                    {{-- Start Row Utama --}}
                    <div class="row g-3">
                        

                      {{-- Start Input Kode Material --}}
                        <div class="row col-lg-12 col-sm-6">
                          <label class="col-lg-3 col-form-label">Kode Material</label>
                          <div class="col-lg-9 mb-2">
                            <input type="text" name="kode_material" class="form-control form-control-md" placeholder="Kode Material" required>
                            @error('kode_material')
                            <div class="alert alert-danger mt-2">
                                {{ $message }}
                            </div>
                            @enderror
                           </div>
                          </div>
                          {{-- End Input Kode Material --}}

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="jenis_material" class="col-lg-3 col-form-label col-form-label-md">Jenis Material</label>
                              <div class="col-lg-9">
                               <input type="text" name="jenis_material" class="form-control form-control-md" id="jenis_material" placeholder="Jenis Material">
                               @error('jenis_material')
                               <div class="alert alert-danger mt-2">
                                   {{ $message }}
                               </div>
                               @enderror
                              </div>
                          </div>

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="stok" class="col-lg-3 col-form-label col-form-label-md">Stok Material</label>
                              <div class="col-lg-9">
                               <input name="stok" type="text" class="form-control form-control-md" placeholder="Stok" id="cleave-numeral">
                              </div>
                          </div>

                          {{-- Start Input Satuan --}}
                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="satuan" class="col-lg-3 col-form-label col-form-label-md">Satuan</label>
                            <div class="col-lg-9">
                                <select class="form-select form-control-md" name="satuan" id="satuan">
                                    <option selected disabled>Pilih Satuan</option>
                                    <option value="mtr">mtr</option>
                                    <option value="pcs">pcs</option>
                                    <option value="mm">mm</option>
                                    <option value="lembar">lembar</option>
                                    <option value="cm">cm</option>
                                    <option value="cm²">cm²</option>
                                </select>
                                @error('satuan')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                          </div>
                          {{-- End Input Satuan --}}

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="harga-modal" class="col-lg-3 col-form-label col-form-label-md">Harga Modal</label>
                              <div class="col-lg-9">
                               <input name="harga_modal" type="text" class="form-control form-control-md" placeholder="Harga Modal" value="0" id="harga-modal">
                              </div>
                          </div>

                          <div class="row col-lg-12 col-sm-6 mb-2">
                            <label for="harga-jual" class="col-lg-3 col-form-label col-form-label-md">Harga Jual</label>
                              <div class="col-lg-9">
                               <input name="harga_jual" type="text" class="form-control form-control-md" placeholder="Harga Jual" value="0" id="harga-jual">
                              </div>
                          </div>

                      </div>

                    </div>

                  </div>
                  {{-- End Row Utama --}}

                {{-- End Row Input Barang --}}
                <div class="row col-lg-2" style="margin-left: 146px;">
                  <button type="submit" class="btn btn-primary fw-medium"><i class="ri-save-3-line me-1 align-bottom"></i>Tambah</button>
                </div>
              </div>
            </form>
            {{-- End Isi Form --}}
          </div>
        </div>
      </div>
      </div>
    </div>
    {!! JsValidator::formRequest('App\Http\Requests\MaterialRequest') !!}
@endsection
@section('plugins')
<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script>
<script src="{{asset('js/halaman/form-masks.js')}}"></script>
@endsection