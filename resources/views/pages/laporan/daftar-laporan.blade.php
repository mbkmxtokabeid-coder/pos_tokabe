@extends('layout.template')

@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* DESIGN.md Compliance: Neutral Charcoal Theme (#161B22) */
    .report-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        transition: transform 200ms ease, box-shadow 200ms ease, border-color 200ms ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        margin-bottom: 0;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.45) !important;
        border-color: #383E45 !important;
    }
    .report-card .card-body {
        padding: 1.5rem !important;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
    }
    .report-icon-box {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .report-card-title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #F1F5F9 !important;
        letter-spacing: -0.01em;
        margin-bottom: 4px;
    }
    .report-card-desc {
        font-size: 13px !important;
        color: #94A3B8 !important;
        margin-bottom: 0;
        line-height: 1.4;
    }

    /* Form Inputs */
    .form-group-report {
        margin-bottom: 1rem;
    }
    .form-label-report {
        font-size: 11.5px !important;
        font-weight: 600 !important;
        color: #94A3B8 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px !important;
        display: block;
    }
    .report-card .form-control,
    .report-card .form-select {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #F8F9FA !important;
        border-radius: 8px !important;
        font-size: 13.5px !important;
        padding: 9px 12px !important;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .report-card .form-control:focus,
    .report-card .form-select:focus {
        border-color: #6366F1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
        outline: none !important;
        color: #FFFFFF !important;
    }

    /* Action Buttons */
    .report-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: auto;
        padding-top: 1.25rem;
        border-top: 1px solid #21262D;
    }
    .btn-view-report {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        flex: 1;
        cursor: pointer;
    }
    .btn-view-report:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
    }
    .btn-excel-report {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background-color: rgba(16, 185, 129, 0.1) !important;
        border: 1px solid rgba(16, 185, 129, 0.35) !important;
        color: #34D399 !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        flex: 1;
        cursor: pointer;
    }
    .btn-excel-report:hover {
        background-color: #10B981 !important;
        border-color: #10B981 !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
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
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Laporan Penjualan</h4>
              <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Rekap & Ekspor</span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Filter dan cetak laporan keuangan serta rekapitulasi data penjualan invoice Tokabe</p>
          </div>
          <div class="page-title-right">
            <ol class="breadcrumb m-0" style="font-size: 13px;">
              <li class="breadcrumb-item"><a href="javascript:void(0);" style="color: #94A3B8;">Laporan</a></li>
              <li class="breadcrumb-item active" style="color: #CBD5E1;">Laporan Penjualan</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    {{-- 2x2 Grid Laporan --}}
    <div class="row g-4">

      {{-- Card 1: Laporan Keuangan Tokabe --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="/invoice/laporan-export" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(59, 130, 246, 0.12); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.25);">
                  <i class="ri-file-chart-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Keuangan Tokabe</h5>
                  <p class="report-card-desc">Filter berdasarkan kategori invoice dan status pelunasan</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-7">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select name="invoice" id="invoiceTerpilih" class="form-select">
                      <option value="semua" selected>Semua Kategori Invoice</option>
                      @foreach ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group-report">
                    <label class="form-label-report">Status Pembayaran</label>
                    <select name="status" class="form-select">
                      <option value="semua">Semua Status</option>
                      <option value="Lunas">Lunas</option>
                      <option value="Belum Lunas">Belum Lunas</option>
                      <option value="Batal">Batal</option>
                      <option value="Dihapus">Dihapus</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report">
                  <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                </button>
                <button type="submit" name="export_type" value="excel" class="btn-excel-report">
                  <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      {{-- Card 2: Laporan Penjualan Custom (Rentang Tanggal) --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="/invoice/laporan-export-date" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" class="perusahaanHidden" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(168, 85, 247, 0.12); color: #C084FC; border: 1px solid rgba(168, 85, 247, 0.25);">
                  <i class="ri-calendar-2-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Penjualan Custom</h5>
                  <p class="report-card-desc">Pilih rentang tanggal kustom untuk rekap transaksi</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Rentang Tanggal</label>
                    <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" placeholder="Pilih Tanggal Mulai s/d Akhir" name="tgl_custom" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select class="form-select invoice" id="invoiceTerpilihCstm" name="invoice" required>
                      <option value="semua">Semua Invoice</option>
                      @foreach ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report">
                  <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                </button>
                <button type="submit" name="export_type" value="excel" class="btn-excel-report">
                  <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      {{-- Card 3: Laporan Penjualan Bulanan --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="/invoice/laporan-export-month" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" class="perusahaanHidden" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(16, 185, 129, 0.12); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25);">
                  <i class="ri-calendar-event-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Penjualan Bulanan ({{ date('Y') }})</h5>
                  <p class="report-card-desc">Rekap penjualan per bulan untuk tahun berjalan</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Pilih Bulan</label>
                    <select class="form-select" name="bulan" required>
                      @foreach ($namaBulan as $bulan)
                        <option value="{{ $bulan }}" {{ $bulan == \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') ? 'selected' : '' }}>
                          {{ $bulan }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select class="form-select invoice" name="invoice" required>
                      <option value="semua">Semua Invoice</option>
                      @foreach ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report">
                  <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                </button>
                <button type="submit" name="export_type" value="excel" class="btn-excel-report">
                  <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      {{-- Card 4: Laporan Penjualan Tahunan --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="/invoice/laporan-export-year" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" class="perusahaanHidden" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(245, 158, 11, 0.12); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.25);">
                  <i class="ri-calendar-check-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Penjualan Tahunan</h5>
                  <p class="report-card-desc">Akumulasi transaksi penjualan sepanjang satu tahun penuh</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Pilih Tahun</label>
                    <select class="form-select" name="tahun" required>
                      @foreach ($daftarTahun as $tahun)
                        <option value="{{ $tahun }}" {{ $tahun == date('Y') ? 'selected' : '' }}>
                          Tahun {{ $tahun }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select class="form-select invoice" name="invoice" required>
                      <option value="semua">Semua Invoice</option>
                      @foreach ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report">
                  <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                </button>
                <button type="submit" name="export_type" value="excel" class="btn-excel-report">
                  <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                </button>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>
<script src="{{ asset('js/halaman/laporan.js') }}"></script>
@endsection