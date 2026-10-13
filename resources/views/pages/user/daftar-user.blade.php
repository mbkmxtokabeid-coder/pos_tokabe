@extends('layout.template')

@section('head')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" />
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

    .btn-create-user {
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
    .btn-create-user:hover {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        color: #FFFFFF !important;
    }

    /* Table Custom Styles */
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
        padding: 13px 16px !important;
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

    /* Cell Typography & Badges */
    .cell-karyawan {
        color: #FFFFFF !important;
        font-weight: 600 !important;
    }
    .cell-email {
        color: #CBD5E1 !important;
    }
    .cell-phone {
        color: #E2E8F0 !important;
        font-family: monospace;
        font-size: 13px;
        letter-spacing: 0.3px;
    }

    /* Role Badges */
    .badge-role-pemilik {
        display: inline-flex;
        align-items: center;
        background-color: rgba(99, 102, 241, 0.12) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-role-admin {
        display: inline-flex;
        align-items: center;
        background-color: rgba(14, 165, 233, 0.12) !important;
        color: #38BDF8 !important;
        border: 1px solid rgba(14, 165, 233, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-role-other {
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

    /* Status Badges */
    .badge-status-aktif {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-status-nonaktif {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #F87171 !important;
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
        font-weight: 600 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
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
        color: #FFFFFF !important;
        border-color: #475569 !important;
    }
    .dropdown-menu-custom {
        background-color: #181E26 !important;
        border: 1px solid #282E37 !important;
        border-radius: 10px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        padding: 6px !important;
        min-width: 170px !important;
    }
    .dropdown-menu-custom .dropdown-item {
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        display: flex !important;
        align-items: center !important;
        transition: all 150ms ease !important;
    }
    .dropdown-menu-custom .dropdown-item:hover {
        background-color: #212529 !important;
        color: #FFFFFF !important;
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
              <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Daftar Karyawan</h4>
              <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">User Management</span>
            </div>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Kelola data karyawan, hak akses, dan status akun pengguna sistem Tokabe</p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('user.add') }}" class="btn-create-user">
              <i class="ri-user-add-line fs-17"></i>Tambah Karyawan
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Table Container Card --}}
    <div class="row">
      <div class="col-12">
        <div class="table-container-card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <i class="ri-team-line text-primary fs-18"></i>
              <h5 class="card-title mb-0 fs-15 text-white fw-semibold">Data Karyawan Terdaftar</h5>
            </div>
            <span style="display:inline-flex;align-items:center;padding:2px 10px;font-size:11.5px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.25);">
              {{ $users->count() }} Karyawan
            </span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-custom align-middle mb-0" id="alternative-pagination" style="width: 100%;">
                <thead>
                  <tr>
                    <th scope="col" style="width: 50px;" class="text-center">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-center">Role</th>
                    <th scope="col">Nomor Telepon</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-center" style="width: 80px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>
                    <td><span class="cell-karyawan">{{ $user->nama }}</span></td>
                    <td><span class="cell-email">{{ $user->email }}</span></td>
                    <td class="text-center">
                      @if ($user->role == "Pemilik")
                        <span class="badge-role-pemilik">{{ $user->role }}</span>
                      @elseif($user->role == "Admin")
                        <span class="badge-role-admin">{{ $user->role }}</span>
                      @else
                        <span class="badge-role-other">{{ $user->role }}</span>
                      @endif
                    </td>
                    <td><span class="cell-phone">{{ $user->nomor_telepon ?: '-' }}</span></td>
                    <td class="text-center">
                      @if ($user->status == 'Aktif')
                        <span class="badge-status-aktif">
                          <i class="ri-checkbox-circle-line"></i>Aktif
                        </span>
                      @else
                        <span class="badge-status-nonaktif">
                          <i class="ri-close-circle-line"></i>Non Aktif
                        </span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="dropdown">
                        <button class="btn btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                          <i class="ri-more-fill fs-16"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                          <li>
                            <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}">
                              <i class="ri-edit-line text-warning fs-15 me-2"></i>Edit Karyawan
                            </a>
                          </li>
                          @if ($user->status == 'Aktif')
                          <li>
                            <a class="dropdown-item" href="{{ route('user.status', $user->id) }}">
                              <i class="ri-user-unfollow-line text-danger fs-15 me-2"></i>Non-aktifkan
                            </a>
                          </li>
                          @else
                          <li>
                            <a class="dropdown-item" href="{{ route('user.status', $user->id) }}">
                              <i class="ri-user-follow-line text-success fs-15 me-2"></i>Aktifkan
                            </a>
                          </li>
                          @endif
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
@endsection