@section('head')
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
@endsection
@extends('layout.template')
@section('content')

<div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->

                    <div class="card-body">
                      <p class="text-muted">Use <code>modal-dialog-centered</code> class to show vertically center the modal.</p>
                      <div>
                          <!-- center modal -->
                          <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target=".bs-example-modal-center">Center Modal</button>
                          <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" id="deleteModal"  aria-labelledby="mySmallModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                  <div class="modal-content">
                                      <div class="modal-body text-center p-5">
                                        <lord-icon
                                        src="https://cdn.lordicon.com/tdrtiskw.json"
                                        trigger="loop"
                                        colors="primary:#eee966,secondary:#c71f16"
                                        state="hover-2"
                                        style="width:150px;height:150px">
                                    </lord-icon>
                                          <div class="mt-4">
                                              <h4 class="mb-3">Apakah ingin menghapus data?</h4>
                                              {{-- <p class="text-muted mb-4"> The transfer was not successfully received by us. the email of the recipient wasn't correct.</p> --}}
                                              <div class="hstack gap-2 justify-content-center">
                                                  <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                  {{-- <a href="javascript:void(0);" class="btn btn-danger">Try Again</a> --}}
                                                  <form action="/delete-spb/" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-danger mt-3">
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
                      </div>
                  </div><!-- end card-body -->
                    <!--end row-->

                </div>
                <!-- container-fluid -->
              </div>

@endsection

@section('plugins')
<!-- dropzone min -->
{{-- <script src="{{asset('libs/prismjs/prism.js')}}"></script> --}}
    <!-- Sweet Alerts js -->
    <script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>
    {{-- <script src="https://cdn.lordicon.com/libs/mssddfmo/lord-icon-2.1.0.js"></script> --}}

    <!-- Modal Js -->
    <script src="{{asset('js/pages/modal.init.js')}}"></script>

    <!-- Sweet alert init js-->
    <script src="{{asset('js/halaman/spb.js')}}"></script>

<!-- cleave.js -->
{{-- <script src="{{asset('libs/cleave.js/cleave.min.js')}}"></script> --}}

<!--Invoice create init js-->
{{-- <script src="{{asset('js/pages/invoicecreate.init.js')}}"></script> --}}

<!-- Sweet Alerts js -->
@endsection

