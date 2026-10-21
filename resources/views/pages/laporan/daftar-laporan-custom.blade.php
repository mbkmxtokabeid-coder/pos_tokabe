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

    /* Summary Stat Cards */
    .custom-summary-card {
        background: #161B22;
        border: 1px solid #282E37;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 150ms ease, border-color 150ms ease;
    }
    .custom-summary-card:hover {
        border-color: #3B4252;
        transform: translateY(-1px);
    }
    .custom-summary-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .custom-summary-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94A3B8;
        margin-bottom: 2px;
    }
    .custom-summary-value {
        font-size: 17px;
        font-weight: 700;
        color: #F1F5F9;
        margin: 0;
        line-height: 1.2;
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
        border-color: #7C3AED !important;
        box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.25) !important;
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
    .btn-view-report-custom {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        background: linear-gradient(135deg, #7C3AED 0%, #6366F1 100%) !important;
        border: 1px solid rgba(196, 181, 253, 0.3) !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        flex: 1;
        cursor: pointer;
    }
    .btn-view-report-custom:hover {
        background: linear-gradient(135deg, #6D28D9 0%, #4F46E5 100%) !important;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.4);
        color: #FFFFFF !important;
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
    <div class="row mb-3">
      <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
          <div>
            <div class="d-flex align-items-center gap-2">
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Laporan Invoice Keluar Custom</h4>
              <span style="display:inline-flex;align-items:center;gap:4px;height:22px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(124,58,237,0.15);color:#C084FC;border:1px solid rgba(124,58,237,0.35);">
                <i class="ri-file-signature-line"></i> Rekap & Ekspor Custom
              </span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Filter dan cetak laporan keuangan serta rekapitulasi data invoice keluar custom Tokabe</p>
          </div>

        </div>
      </div>
    </div>

    {{-- Quick Summary Stats --}}
    <div class="row g-3 mb-4">
      <div class="col-xl-3 col-md-6">
        <div class="custom-summary-card">
          <div class="custom-summary-icon" style="background: rgba(124, 58, 237, 0.15); color: #C084FC; border: 1px solid rgba(124, 58, 237, 0.3);">
            <i class="ri-file-text-line"></i>
          </div>
          <div>
            <p class="custom-summary-label">Total Invoice Custom</p>
            <p class="custom-summary-value">{{ number_format($totalInvoiceCustom, 0, ',', '.') }} <span style="font-size: 12px; font-weight: 500; color: #94A3B8;">Invoice</span></p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="custom-summary-card">
          <div class="custom-summary-icon" style="background: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3);">
            <i class="ri-money-dollar-circle-line"></i>
          </div>
          <div>
            <p class="custom-summary-label">Total Omset Custom</p>
            <p class="custom-summary-value">Rp {{ number_format($totalNominalCustom, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="custom-summary-card">
          <div class="custom-summary-icon" style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
            <i class="ri-checkbox-circle-line"></i>
          </div>
          <div>
            <p class="custom-summary-label">Terbayar Lunas</p>
            <p class="custom-summary-value">Rp {{ number_format($totalLunasCustom, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="custom-summary-card">
          <div class="custom-summary-icon" style="background: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.3);">
            <i class="ri-time-line"></i>
          </div>
          <div>
            <p class="custom-summary-label">Belum Lunas</p>
            <p class="custom-summary-value">Rp {{ number_format($totalBelumLunasCustom, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>
    </div>

    {{-- 2x2 Grid Laporan Khusus Custom --}}
    <div class="row g-4">

      {{-- Card 1: Laporan Status Invoice Custom --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="{{ route('laporan.custom.export_status') }}" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(124, 58, 237, 0.15); color: #C084FC; border: 1px solid rgba(124, 58, 237, 0.3);">
                  <i class="ri-file-chart-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Status Invoice Custom</h5>
                  <p class="report-card-desc">Rekapitulasi invoice custom berdasarkan status pelunasan</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-7">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select name="invoice" class="form-select">
                      <option value="semua">Semua Kategori</option>
                      @forelse ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @empty
                        <option value="5">TKB Pajak</option>
                      @endforelse
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
                <button type="submit" name="export_type" value="pdf" class="btn-view-report-custom">
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

      {{-- Card 2: Laporan Invoice Custom Rentang Tanggal --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="{{ route('laporan.custom.export_date') }}" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" class="perusahaanHidden" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(59, 130, 246, 0.15); color: #60A5FA; border: 1px solid rgba(59, 130, 246, 0.3);">
                  <i class="ri-calendar-2-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Custom Rentang Tanggal</h5>
                  <p class="report-card-desc">Pilih rentang tanggal kustom untuk rekap transaksi invoice custom</p>
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
                    <select class="form-select" name="invoice" required>
                      <option value="semua">Semua Kategori</option>
                      @forelse ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @empty
                        <option value="5">TKB Pajak</option>
                      @endforelse
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report-custom">
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

      {{-- Card 3: Laporan Invoice Custom Bulanan --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="{{ route('laporan.custom.export_month') }}" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" class="perusahaanHidden" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.3);">
                  <i class="ri-calendar-event-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Invoice Custom Bulanan</h5>
                  <p class="report-card-desc">Rekap invoice custom per bulan spesifik</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-4">
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
                <div class="col-md-4">
                  <div class="form-group-report">
                    <label class="form-label-report">Pilih Tahun</label>
                    <select class="form-select" name="tahun" required>
                      @foreach ($daftarTahun as $tahun)
                        <option value="{{ $tahun }}" {{ (int)$tahun === (int)date('Y') ? 'selected' : '' }}>
                          {{ $tahun }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select class="form-select" name="invoice" required>
                      <option value="semua">Semua Kategori</option>
                      @forelse ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @empty
                        <option value="5">TKB Pajak</option>
                      @endforelse
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report-custom">
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

      {{-- Card 4: Laporan Invoice Custom Tahunan --}}
      <div class="col-xl-6">
        <div class="card report-card">
          <form action="{{ route('laporan.custom.export_year') }}" method="POST" target="_blank" class="d-flex flex-column h-100">
            @csrf
            <input type="hidden" name="perusahaan" class="perusahaanHidden" value="{{ $perusahaan->keys()->first() }}">
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="report-icon-box" style="background-color: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.3);">
                  <i class="ri-calendar-check-line fs-22"></i>
                </div>
                <div>
                  <h5 class="report-card-title">Laporan Invoice Custom Tahunan</h5>
                  <p class="report-card-desc">Akumulasi transaksi invoice custom sepanjang satu tahun penuh</p>
                </div>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Pilih Tahun</label>
                    <select class="form-select" name="tahun" required>
                      @foreach ($daftarTahun as $tahun)
                        <option value="{{ $tahun }}" {{ (int)$tahun === (int)date('Y') ? 'selected' : '' }}>
                          Tahun {{ $tahun }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group-report">
                    <label class="form-label-report">Kategori Invoice</label>
                    <select class="form-select" name="invoice" required>
                      <option value="semua">Semua Kategori</option>
                      @forelse ($katInv as $id => $namaInvoice)
                        <option value="{{ $id }}">{{ $namaInvoice }}</option>
                      @empty
                        <option value="5">TKB Pajak</option>
                      @endforelse
                    </select>
                  </div>
                </div>
              </div>

              <div class="report-actions">
                <button type="submit" name="export_type" value="pdf" class="btn-view-report-custom">
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
