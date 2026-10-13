@extends('layout.template')

@section('head')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<style>
    /* ==========================================================================
       DESIGN.md §12.4 — KPI STATISTIC CARDS
       Neutral dark surface #161B22, border #282E37, tonal icon box, tabular numbers
       ========================================================================== */
    .kpi-card {
        background: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        margin-bottom: 16px;
        transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        border-color: #383E45 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }
    .kpi-card__body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 125px;
    }
    .kpi-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .kpi-card__overline {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94A3B8;
        line-height: 16px;
        margin: 0;
    }
    .kpi-card__icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background-color: var(--kpi-accent-subtle, rgba(99, 102, 241, 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--kpi-accent, #818CF8);
        font-size: 22px;
        border: 1px solid var(--kpi-accent-border, rgba(99, 102, 241, 0.25));
    }
    .kpi-card__value {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
        color: #F8F9FA;
        margin: 0;
        font-variant-numeric: tabular-nums;
    }
    .kpi-card__footer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 10px;
        border-top: 1px solid #232A35;
    }
    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 22px;
        padding: 0 8px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid var(--kpi-accent-border, rgba(99, 102, 241, 0.28));
        background-color: var(--kpi-accent-subtle, rgba(99, 102, 241, 0.12));
        color: var(--kpi-accent-text, #A5B4FC);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .kpi-card__label {
        font-size: 12.5px;
        font-weight: 400;
        color: #94A3B8;
        margin: 0;
    }

    /* KPI Semantics */
    .kpi-card--emerald {
        --kpi-accent: #10B981;
        --kpi-accent-subtle: rgba(16, 185, 129, 0.12);
        --kpi-accent-border: rgba(16, 185, 129, 0.3);
        --kpi-accent-text: #6EE7B7;
    }
    .kpi-card--sky {
        --kpi-accent: #38BDF8;
        --kpi-accent-subtle: rgba(56, 189, 248, 0.12);
        --kpi-accent-border: rgba(56, 189, 248, 0.3);
        --kpi-accent-text: #7DD3FC;
    }

    /* Primary Button: Tambah SPJ */
    .btn-create-spj {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        height: 38px !important;
        padding: 0 16px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.35) !important;
        transition: all 150ms ease !important;
        text-decoration: none !important;
    }
    .btn-create-spj:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        color: #FFFFFF !important;
    }

    /* Table Container & Table Styling (DESIGN.md §13) */
    .table-container-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        overflow: hidden;
    }
    .table-container-card .card-header {
        background-color: #181E26 !important;
        border-bottom: 1px solid #232A35 !important;
        padding: 16px 20px !important;
    }
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
        padding: 12px 16px !important;
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
        padding: 13px 16px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #CBD5E1 !important;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none !important;
    }

    /* Badges & Cell Typography */
    .badge-status-terbayar {
        display: inline-flex;
        align-items: center;
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-status-belum-bayar {
        display: inline-flex;
        align-items: center;
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #FCD34D !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .cell-no-spj {
        color: #F8F9FA !important;
        font-weight: 600 !important;
        font-family: monospace;
        font-size: 13px;
    }
    .cell-kurir {
        color: #F1F5F9 !important;
        font-weight: 500 !important;
    }
    .cell-tujuan {
        color: #CBD5E1 !important;
    }
    .cell-date {
        color: #94A3B8 !important;
        font-size: 13px;
    }
    .cell-biaya {
        display: inline-block !important;
        color: #34D399 !important;
        font-weight: 600 !important;
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
        white-space: nowrap !important;
    }
    .col-biaya,
    .cell-biaya-td {
        white-space: nowrap !important;
        min-width: 140px !important;
        width: 140px !important;
    }

    /* Action button & Dropdown */
    .btn-action-more {
        background-color: #212529 !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 8px !important;
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 150ms ease !important;
    }
    .btn-action-more:hover {
        background-color: #2D333B !important;
        border-color: #475569 !important;
        color: #FFFFFF !important;
    }
    .dropdown-menu-custom {
        background-color: #181E26 !important;
        border: 1px solid #383E45 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        padding: 6px !important;
        min-width: 160px !important;
        z-index: 1050 !important;
    }
    .dropdown-menu-custom .dropdown-item {
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        display: flex !important;
        align-items: center !important;
        transition: background-color 120ms ease !important;
    }
    .dropdown-menu-custom .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #FFFFFF !important;
    }
    .dropdown-menu-custom .dropdown-divider {
        border-top: 1px solid #282E37 !important;
        margin: 4px 0 !important;
    }

    /* DataTables Dark Mode Customization */
    .dataTables_wrapper {
        padding: 14px 18px !important;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info {
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
    {{-- Header Halaman & Tombol Tambah SPJ --}}
    <div class="row mb-3">
      <div class="col-12">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
          <div>
            <div class="d-flex align-items-center gap-2">
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Daftar SPJ</h4>
              <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Surat Perintah Jalan</span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Daftar surat tugas perjalanan dinas dan status pembayaran kurir/petugas</p>
          </div>
          <div>
            <a href="{{ url('/tambah-spj') }}" class="btn-create-spj">
              <i class="ri-add-line fs-18"></i>Tambah SPJ
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- KPI Metric Cards (DESIGN.md §12.4 & §4.3) --}}
    <div class="row g-3 mb-4">
      {{-- Card 1: Jumlah SPJ Terbayar Tahun Ini --}}
      <div class="col-xl-6 col-md-6">
        <div class="kpi-card kpi-card--emerald">
          <div class="kpi-card__body">
            <div class="kpi-card__header">
              <p class="kpi-card__overline">Jumlah SPJ Terbayar Tahun {{ date('Y') }}</p>
              <div class="kpi-card__icon" aria-hidden="true">
                <i class="ri-wallet-3-line"></i>
              </div>
            </div>
            <p class="kpi-card__value">Rp {{ number_format($totalTahunIni, 0, ',', '.') }}</p>
            <div class="kpi-card__footer">
              <span class="kpi-badge">{{ $jumlahSpjTahunIni }} SPJ</span>
              <p class="kpi-card__label">Total SPJ Terbayar Tahun Ini</p>
            </div>
          </div>
        </div>
      </div>

      {{-- Card 2: Jumlah SPJ Terbayar Bulan Ini --}}
      <div class="col-xl-6 col-md-6">
        <div class="kpi-card kpi-card--sky">
          <div class="kpi-card__body">
            <div class="kpi-card__header">
              <p class="kpi-card__overline">Jumlah SPJ Terbayar Bulan Ini</p>
              <div class="kpi-card__icon" aria-hidden="true">
                <i class="ri-calendar-check-line"></i>
              </div>
            </div>
            <p class="kpi-card__value">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</p>
            <div class="kpi-card__footer">
              <span class="kpi-badge">{{ $jumlahSpjBulanIni }} SPJ</span>
              <p class="kpi-card__label">Total SPJ Terbayar Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Table Container Card (DESIGN.md §13) --}}
    <div class="row">
      <div class="col-12">
        <div class="table-container-card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <i class="ri-route-line text-primary fs-18"></i>
              <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Riwayat Surat Perintah Jalan (SPJ)</h5>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-custom align-middle mb-0" id="scroll-horizontal">
                <thead>
                  <tr>
                    <th scope="col" style="width: 50px;" class="text-center">No</th>
                    <th scope="col">No. SPJ</th>
                    <th scope="col">Nama Kurir/Petugas</th>
                    <th scope="col">Tanggal Tugas</th>
                    <th scope="col">Tujuan</th>
                    <th scope="col" class="text-end col-biaya" style="white-space: nowrap; min-width: 140px; width: 140px;">Biaya</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="text-center" style="width: 80px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($spj as $item)
                  <tr>
                    <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                    <td><span class="cell-no-spj">{{ $item->nomor_spj }}</span></td>
                    <td><span class="cell-kurir">{{ $item->nama_kurir }}</span></td>
                    <td><span class="cell-date">{{ \Carbon\Carbon::parse($item->tanggal_tugas)->format('d-m-Y') }}</span></td>
                    <td><span class="cell-tujuan">{{ $item->tujuan }}</span></td>
                    <td class="text-end cell-biaya-td" style="white-space: nowrap; min-width: 140px;"><span class="cell-biaya">Rp&nbsp;{{ number_format($item->biaya_bahan_bakar, 0, ',', '.') }}</span></td>
                    <td>
                      @if($item->status == 'Terbayar')
                        <span class="badge-status-terbayar"><i class="ri-checkbox-circle-line me-1"></i>Terbayar</span>
                      @else
                        <span class="badge-status-belum-bayar"><i class="ri-time-line me-1"></i>Belum Bayar</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="dropdown">
                        <button class="btn btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                          <i class="ri-more-fill fs-16"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                          <li>
                            <a class="dropdown-item" href="{{ url('/lihat-spj/'.$item->id) }}">
                              <i class="ri-eye-line text-info fs-15 me-2"></i>Lihat SPJ
                            </a>
                          </li>
                          <li>
                            <a class="dropdown-item" target="_blank" href="{{ url('/cetak-spj/'.$item->id) }}">
                              <i class="ri-printer-line text-primary fs-15 me-2"></i>Cetak SPJ
                            </a>
                          </li>
                          <li>
                            <a class="dropdown-item" href="{{ url('/edit-spj/'.$item->id) }}">
                              <i class="ri-edit-line text-warning fs-15 me-2"></i>Edit SPJ
                            </a>
                          </li>
                          <li><hr class="dropdown-divider"></li>
                          <li>
                            <form action="{{ url('/invoice/delete-spj/'.$item->id) }}" method="POST" id="deletespj{{ $item->id }}" class="m-0">
                              @csrf
                              @method('DELETE')
                              <button class="dropdown-item text-danger" type="button" onclick="showConfirmation('{{ $item->nomor_spj }}', '{{ $item->id }}')">
                                <i class="ri-delete-bin-line text-danger fs-15 me-2"></i>Hapus
                              </button>
                            </form>
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
      </div>
    </div>

  </div>
</div>
@endsection

@section('plugins')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('js/pages/datatables.init.js') }}"></script>

<script>
  function showConfirmation(nospj, id) {
    Swal.fire({
      title: 'Hapus Data SPJ?',
      text: 'Apakah Anda yakin ingin menghapus SPJ No. ' + nospj + '? Tindakan ini tidak dapat dibatalkan.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#EF4444',
      cancelButtonColor: '#212529',
      cancelButtonText: 'Batal',
      confirmButtonText: 'Ya, Hapus'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('deletespj' + id).submit();
      }
    });
  }
</script>
@endsection