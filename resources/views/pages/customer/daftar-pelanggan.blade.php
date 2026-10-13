@extends('layout.template')

@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* DESIGN.md Compliance: Neutral Charcoal Theme (#161B22) */
    .table-container-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }
    .table-container-card .card-header {
        background-color: #161B22 !important;
        border-bottom: 1px solid #282E37 !important;
        padding: 18px 24px !important;
    }

    /* Controls & Toolbar */
    .toolbar-filter-select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 8px !important;
        font-size: 13px !important;
        padding: 7px 12px !important;
        min-width: 200px;
    }
    .toolbar-filter-select:focus {
        border-color: #6366F1 !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25);
    }
    .btn-filter-submit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        padding: 7px 14px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
    }
    .btn-filter-submit:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
    }

    .btn-export-pdf {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(239, 68, 68, 0.12) !important;
        border: 1px solid rgba(239, 68, 68, 0.35) !important;
        color: #F87171 !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        padding: 7px 14px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
    }
    .btn-export-pdf:hover {
        background-color: #EF4444 !important;
        border-color: #EF4444 !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-export-excel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: rgba(16, 185, 129, 0.12) !important;
        border: 1px solid rgba(16, 185, 129, 0.35) !important;
        color: #34D399 !important;
        font-weight: 600 !important;
        font-size: 12.5px !important;
        padding: 7px 14px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        cursor: pointer;
    }
    .btn-export-excel:hover {
        background-color: #10B981 !important;
        border-color: #10B981 !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Table Styles */
    .table-custom {
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
    }
    .table-custom thead th {
        background-color: #181E26 !important;
        color: #94A3B8 !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
        font-weight: 600 !important;
        font-size: 11.5px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 14px 18px !important;
        white-space: nowrap;
    }
    .table-custom tbody tr {
        border-bottom: 1px solid #21262D !important;
        transition: background-color 150ms ease !important;
    }
    .table-custom tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    .table-custom tbody td {
        border: none !important;
        border-bottom: 1px solid #21262D !important;
        padding: 14px 18px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #CBD5E1 !important;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Badges & Cell Typography */
    .cell-customer {
        color: #FFFFFF !important;
        font-weight: 600 !important;
    }
    .badge-invoice-category {
        display: inline-flex;
        align-items: center;
        background-color: rgba(99, 102, 241, 0.12) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
        white-space: nowrap;
    }
    .cell-perusahaan {
        color: #F1F5F9 !important;
        font-weight: 500 !important;
    }
    .cell-phone {
        color: #E2E8F0 !important;
        font-family: monospace;
        font-size: 13px;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }
    .badge-count {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 9999px;
        background-color: rgba(99, 102, 241, 0.12);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }

    /* DataTables Dark Theme Overrides */
    .dataTables_wrapper {
        padding: 16px 20px !important;
        color: #94A3B8 !important;
        font-size: 13px !important;
    }
    .dataTables_wrapper .dataTables_length select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 6px !important;
        padding: 4px 10px !important;
        font-size: 13px !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        margin-left: 8px !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6366F1 !important;
        outline: none;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
    }
    .dataTables_wrapper .pagination .page-item .page-link {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        font-size: 13px !important;
        border-radius: 6px !important;
        margin: 0 2px !important;
    }
    .dataTables_wrapper .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #2D333B !important;
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }
    .dataTables_wrapper .pagination .page-item.active .page-link {
        background-color: #6366F1 !important;
        border-color: #6366F1 !important;
        color: #FFFFFF !important;
    }
    .dataTables_wrapper .pagination .page-item.disabled .page-link {
        opacity: 0.35 !important;
        background-color: transparent !important;
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
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Laporan Daftar Pelanggan</h4>
              <span class="badge-count">{{ count($customers) }} Pelanggan</span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Daftar pelanggan, relasi perusahaan, dan kontak yang tercatat pada sistem Tokabe</p>
          </div>
          <div class="page-title-right">
            <ol class="breadcrumb m-0" style="font-size: 13px;">
              <li class="breadcrumb-item"><a href="javascript:void(0);" style="color: #94A3B8;">Laporan</a></li>
              <li class="breadcrumb-item active" style="color: #CBD5E1;">Laporan Daftar Pelanggan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    {{-- Table Container Card --}}
    <div class="row">
      <div class="col-12">
        <div class="table-container-card">

          {{-- Card Header: Filter & Export Toolbar --}}
          <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
              <i class="ri-user-star-line text-primary fs-18"></i>
              <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Data Pelanggan Terdaftar</h5>
            </div>

            <div class="d-flex align-items-center flex-wrap gap-3">
              {{-- Filter Kategori Invoice --}}
              <form action="{{ route('daftar.pelanggan') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                <select name="invoice" class="toolbar-filter-select" id="invoiceDropdown">
                  <option value="">Semua Kategori Invoice</option>
                  @foreach ($invoices as $invoice)
                    <option value="{{ $invoice->id }}" {{ $invoice->id == $selectedInvoice ? 'selected' : '' }}>
                      {{ $invoice->nama_invoice }}
                    </option>
                  @endforeach
                </select>
                <button type="submit" class="btn-filter-submit">
                  <i class="ri-filter-3-line"></i>Filter
                </button>
              </form>

              {{-- Export PDF & Excel --}}
              <form action="{{ route('export.pelanggan') }}" method="POST" target="_blank" class="d-flex align-items-center gap-2 m-0">
                @csrf
                <input type="hidden" name="invoice" id="invoiceInput" value="{{ $selectedInvoice }}">
                <button class="btn-export-pdf" type="submit" name="export_type" value="pdf">
                  <i class="ri-file-pdf-line"></i>Ekspor PDF
                </button>
                <button class="btn-export-excel" type="submit" name="export_type" value="excel">
                  <i class="ri-file-excel-line"></i>Ekspor Excel
                </button>
              </form>
            </div>
          </div>

          {{-- Card Body: Table --}}
          <div class="card-body p-0">
            <div class="table-responsive">
              <table id="alternative-pagination" class="table table-custom align-middle mb-0" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col" style="width: 50px;" class="text-center">No</th>
                    <th scope="col">Pelanggan</th>
                    <th scope="col">Kategori Invoice</th>
                    <th scope="col">Perusahaan</th>
                    <th scope="col">No. Kontak</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($customers as $cust)
                  <tr>
                    <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                    <td><span class="cell-customer">{{ $cust->customer }}</span></td>
                    <td>
                      <span class="badge-invoice-category">
                        {{ optional($invoices->where('id', $cust->invoice)->first())->nama_invoice ?? '-' }}
                      </span>
                    </td>
                    <td><span class="cell-perusahaan">{{ $cust->perusahaan ?: '-' }}</span></td>
                    <td><span class="cell-phone">{{ $cust->no_telepon ?: '-' }}</span></td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                      Tidak ada data pelanggan yang ditemukan untuk kategori invoice yang dipilih.
                    </td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('plugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>

<script>
  document.getElementById("invoiceDropdown").addEventListener("change", function() {
    var selectedInvoiceId = this.value;
    document.getElementById("invoiceInput").value = selectedInvoiceId;
  });
</script>
@endsection