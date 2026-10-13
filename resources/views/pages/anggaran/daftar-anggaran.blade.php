@extends('layout.template')

@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* DESIGN.md Compliance: Neutral Charcoal Dark Theme (#161B22) */
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

    .btn-create-anggaran {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #6366F1 !important;
        border: 1px solid #6366F1 !important;
        color: #FFFFFF !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        transition: all 150ms ease !important;
        text-decoration: none;
    }
    .btn-create-anggaran:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        color: #FFFFFF !important;
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
    .cell-budget-name {
        color: #FFFFFF !important;
        font-weight: 600 !important;
    }
    .badge-category {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: rgba(99, 102, 241, 0.12) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
        white-space: nowrap;
    }
    .cell-nominal {
        color: #34D399 !important;
        font-weight: 600 !important;
        font-variant-numeric: tabular-nums !important;
        white-space: nowrap !important;
    }
    .badge-nominal-danger {
        color: #F87171 !important;
        font-weight: 600 !important;
        font-variant-numeric: tabular-nums !important;
        white-space: nowrap !important;
    }
    .cell-date {
        color: #94A3B8 !important;
        font-size: 12.5px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .badge-count {
        display: inline-flex;
        align-items: center;
        padding: 2px 10px;
        font-size: 11.5px;
        font-weight: 600;
        border-radius: 9999px;
        background-color: rgba(99, 102, 241, 0.12);
        color: #A5B4FC;
        border: 1px solid rgba(99, 102, 241, 0.25);
    }

    /* Action Buttons */
    .btn-action-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #383E45;
        background-color: #212529;
        color: #CBD5E1;
        transition: all 150ms ease;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-action-icon:hover {
        background-color: #2D333B;
        color: #FFFFFF;
        border-color: #475569;
    }
    .btn-action-icon--edit:hover {
        border-color: #6366F1;
        color: #A5B4FC;
        background-color: rgba(99, 102, 241, 0.12);
    }
    .btn-action-icon--delete:hover {
        border-color: #EF4444;
        color: #F87171;
        background-color: rgba(239, 68, 68, 0.12);
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
        padding: 5px 10px !important;
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
                <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Daftar Anggaran</h4>
                <span class="badge-count">{{ count($budgets) }} Anggaran</span>
              </div>
              <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Kelola alokasi pos anggaran pengeluaran dan operasional Tokabe</p>
            </div>
            <div class="d-flex align-items-center gap-2">
              <a href="{{ route('budget.add') }}" class="btn-create-anggaran">
                <i class="ri-add-line fs-18"></i> Tambah Anggaran
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- Table Container Card --}}
      <div class="row">
        <div class="col-12">
          <div class="table-container-card">

            {{-- Card Header --}}
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
              <div class="d-flex align-items-center gap-2">
                <i class="ri-wallet-3-line text-primary fs-18"></i>
                <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Semua Pos Anggaran</h5>
              </div>
              <span class="badge-count">{{ count($budgets) }} Pos Anggaran</span>
            </div>

            {{-- Card Body --}}
            <div class="card-body p-0">
              <div class="table-responsive">
                <table id="alternative-pagination" class="table table-custom align-middle mb-0" style="width:100%">
                  <thead>
                    <tr>
                      <th scope="col" style="width: 50px;" class="text-center">No.</th>
                      <th scope="col">Nama Anggaran</th>
                      <th scope="col">Kategori</th>
                      <th scope="col">Nominal Anggaran</th>
                      <th scope="col">Tanggal</th>
                      <th scope="col" class="text-center" style="width: 110px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($budgets as $budget)
                      <tr>
                        <td class="text-center text-muted fw-semibold" style="width: 50px;">{{ $loop->iteration }}</td>
                        <td>
                          <span class="cell-budget-name">{{ $budget->nama_budget }}</span>
                        </td>
                        <td>
                          <span class="badge-category">
                            <i class="ri-price-tag-3-line fs-13"></i>
                            {{ optional($budget->kategori_barang)->nama_kategori ?? '-' }}
                          </span>
                        </td>
                        <td>
                          @if ($budget->anggaran < 0)
                            <span class="badge-nominal-danger">− Rp {{ number_format(abs($budget->anggaran), 0, ',', '.') }}</span>
                          @else
                            <span class="cell-nominal">Rp {{ number_format($budget->anggaran, 0, ',', '.') }}</span>
                          @endif
                        </td>
                        <td>
                          <span class="cell-date">
                            <i class="ri-calendar-line fs-14"></i>
                            {{ \Carbon\Carbon::parse($budget->tanggal)->translatedFormat('d M Y') }}
                          </span>
                        </td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('budget.edit', ['id' => $budget->id]) }}" class="btn-action-icon btn-action-icon--edit" title="Ubah Anggaran">
                              <i class="ri-edit-line fs-15"></i>
                            </a>
                            <button type="button" class="btn-action-icon btn-action-icon--delete" onclick="confirmDelete('{{ $budget->id }}', '{{ addslashes($budget->nama_budget) }}')" title="Hapus Anggaran">
                              <i class="ri-delete-bin-line fs-15"></i>
                            </button>
                            <form id="delete-form-{{ $budget->id }}" action="{{ route('budget.delete', ['id' => $budget->id]) }}" method="POST" style="display: none;">
                              @csrf
                              @method('delete')
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                          <i class="ri-wallet-3-line fs-36 d-block mb-2" style="opacity: 0.4;"></i>
                          Belum ada data anggaran. Silakan klik tombol <strong>Tambah Anggaran</strong>.
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
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
  <script src="{{ asset('js/pages/datatables.init.js') }}"></script>

  <script>
    function confirmDelete(id, name) {
      Swal.fire({
        title: 'Hapus Anggaran?',
        text: 'Apakah Anda yakin ingin menghapus anggaran "' + name + '"? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        background: '#161B22',
        color: '#F1F5F9',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#212529',
        confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Ya, Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      });
    }
  </script>
@endsection