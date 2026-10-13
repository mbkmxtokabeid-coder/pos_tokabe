@extends('layout.template')

@section('head')
<style>
    /* Surface & KPI Cards (DESIGN.md §4 & §12) */
    .metric-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
        transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        border-color: #383E45 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
    }
    .metric-icon-box {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
    }
    
    /* Table Styling (DESIGN.md §13) */
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
        padding: 12px 14px !important;
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
        padding: 12px 14px !important;
        vertical-align: middle !important;
        font-size: 13.5px !important;
        color: #CBD5E1 !important;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none !important;
    }
    .num-tabular {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum";
    }

    /* Badges (DESIGN.md §14) */
    .badge-status-lunas {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-status-belum-lunas {
        background-color: rgba(245, 158, 11, 0.12) !important;
        color: #FCD34D !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        font-weight: 600 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        font-size: 11.5px !important;
    }
    .badge-access-locked {
        background-color: rgba(148, 163, 184, 0.1) !important;
        color: #94A3B8 !important;
        border: 1px solid rgba(148, 163, 184, 0.25) !important;
        font-weight: 500 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        font-size: 11.5px !important;
    }
    .badge-access-unlocked {
        background-color: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        font-weight: 500 !important;
        padding: 3px 9px !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        font-size: 11.5px !important;
    }

    /* Action Button & Dropdown Menu (DESIGN.md §11) */
    .btn-action-more {
        background-color: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid #383E45 !important;
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        width: 32px !important;
        height: 32px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        transition: all 120ms ease !important;
    }
    .btn-action-more:hover,
    .btn-action-more[aria-expanded="true"] {
        background-color: #2A3037 !important;
        border-color: #4A525D !important;
        color: #FFFFFF !important;
    }
    .dropdown-menu {
        background-color: #161B22 !important;
        border: 1px solid #2D333B !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
        padding: 6px !important;
        min-width: 190px !important;
    }
    .dropdown-item {
        color: #CBD5E1 !important;
        border-radius: 6px !important;
        padding: 7px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .dropdown-item:hover {
        background-color: #21262E !important;
        color: #FFFFFF !important;
    }
    .dropdown-item.text-danger:hover {
        background-color: rgba(239, 68, 68, 0.12) !important;
        color: #F87171 !important;
    }
    .dropdown-divider {
        border-color: #232A35 !important;
    }

    /* Primary Add Button (DESIGN.md §9) */
    .btn-add-pembelian {
        background-color: var(--brand, #6366F1) !important;
        border: 1px solid transparent !important;
        color: #FFFFFF !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13.5px !important;
        height: 38px !important;
        padding: 0 18px !important;
        display: inline-flex !important;
        align-items: center !important;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35) !important;
        transition: all 120ms ease !important;
    }
    .btn-add-pembelian:hover {
        background-color: var(--brand-hover, #4F46E5) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45) !important;
        color: #FFFFFF !important;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 text-white fw-bold">Daftar Pembelian &mdash; {{ $vendor->nama_vendor }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}" class="text-muted">Vendor</a></li>
                    <li class="breadcrumb-item active text-white">{{ $vendor->nama_vendor }}</li>
                </ol>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #6EE7B7; border-radius: 10px;">
                <i class="ri-checkbox-circle-line fs-20 me-2 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #FCA5A5; border-radius: 10px;">
                <i class="ri-error-warning-line fs-20 me-2 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('pembelian.create', $vendor->id) }}" class="btn-add-pembelian">
                <i class="ri-add-line me-1 fs-16"></i>Tambah Pembelian
            </a>
        </div>

        {{-- Kartu Ringkasan (KPI Cards) --}}
        <div class="row g-3 mb-4">
            {{-- Total Pembelian --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold text-white fs-20 num-tabular">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Total Pembelian</p>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(99, 102, 241, 0.12); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25);">
                                <i class="ri-file-list-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Terbayar --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: #34D399;">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Total Terbayar</p>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(16, 185, 129, 0.12); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <i class="ri-checkbox-circle-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisa Belum Dibayar --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: {{ $totalSisa > 0 ? '#FB7185' : '#94A3B8' }};">Rp {{ number_format($totalSisa, 0, ',', '.') }}</h4>
                                <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Sisa Belum Dibayar</p>
                            </div>
                            <div class="metric-icon-box" style="background-color: rgba(244, 63, 94, 0.12); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.25);">
                                <i class="ri-error-warning-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisa Duit Kas --}}
            <div class="col-xl-3 col-md-6">
                <div class="card metric-card mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1 fw-bold fs-20 num-tabular" style="color: {{ ($sisaKas ?? 0) >= 0 ? '#38BDF8' : '#FB7185' }};">
                                    @if(($sisaKas ?? 0) < 0)
                                        -Rp {{ number_format(abs($sisaKas ?? 0), 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($sisaKas ?? 0, 0, ',', '.') }}
                                    @endif
                                </h4>
                                <div class="d-flex align-items-center gap-1.5">
                                    <p class="text-uppercase fw-semibold mb-0 fs-11 text-muted" style="letter-spacing: 0.5px;">Sisa Duit Kas</p>
                                    @if(($sisaKas ?? 0) < 0)
                                        <span class="badge py-0 px-1 fs-10 fw-semibold" style="background: rgba(244, 63, 94, 0.15); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.3);">Minus</span>
                                    @else
                                        <span class="badge py-0 px-1 fs-10 fw-semibold" style="background: rgba(56, 189, 248, 0.15); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.3);">Tersedia</span>
                                    @endif
                                </div>
                            </div>
                            <div class="metric-icon-box" style="{{ ($sisaKas ?? 0) >= 0 ? 'background-color: rgba(56, 189, 248, 0.12); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.25);' : 'background-color: rgba(244, 63, 94, 0.12); color: #FB7185; border: 1px solid rgba(244, 63, 94, 0.25);' }}">
                                <i class="ri-wallet-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat Pembelian --}}
        <div class="card table-container-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fs-15 fw-semibold text-white">Riwayat Pembelian &mdash; {{ $vendor->nama_vendor }}</h5>
                <span class="badge" style="background: rgba(99, 102, 241, 0.12); color: #818CF8; border: 1px solid rgba(99, 102, 241, 0.25); font-weight: 600; padding: 4px 10px; border-radius: 9999px;">
                    {{ count($pembelian) }} Transaksi
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-custom">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th>No. Invoice</th>
                                <th class="text-center">Tanggal Pembelian</th>
                                <th class="text-center">Tanggal JTO</th>
                                <th class="text-center">Status Bayar</th>
                                <th class="text-center" style="width: 120px;">Akses Edit</th>
                                <th class="text-end">Total (Rp)</th>
                                <th class="text-end">Terbayar (Rp)</th>
                                <th class="text-end">Sisa (Rp)</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($pembelian as $item)
                            @php
                                $roleUser     = Auth::user()->role ?? '';
                                $isOwner      = $roleUser === 'Pemilik';
                                $isAdmin      = $roleUser === 'Admin';
                                $isUnlocked   = $item->approval === 'Unlock';
                                $canManage    = !$isAdmin || $isUnlocked;

                                // Kontak Owner Tokabe (sesuai modul invoice)
                                $waOwner1 = '628116029999';
                                $waOwner2 = '628116179999';

                                // Pesan Request Unlock ke Owner via WhatsApp
                                $pesanUnlock = urlencode("Halo, saya ingin meminta approval untuk membuka akses riwayat pembelian *#{$item->nomor_inv}* dari vendor *{$vendor->nama_vendor}*. Mohon untuk segera ditinjau.\n\nKlik tautan berikut untuk membuka halaman persetujuan:\n" . url("/pembelian-approval/{$item->id}"));
                            @endphp
                            <tr>
                                <td class="text-center text-muted fs-13">{{ $loop->iteration }}</td>
                                <td class="fw-semibold text-white fs-13.5">{{ $item->nomor_inv }}</td>
                                <td class="text-center fs-13">{{ optional($item->tanggal)->translatedFormat('d M Y') }}</td>
                                <td class="text-center fs-13">{{ optional($item->effective_tanggal_jto)->translatedFormat('d M Y') ?: '-' }}</td>
                                <td class="text-center">
                                    @if($item->status === 'Lunas')
                                        <span class="badge-status-lunas">Lunas</span>
                                    @else
                                        <span class="badge-status-belum-lunas">Belum Lunas</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($isUnlocked)
                                        <span class="badge-access-unlocked" title="Akses edit terbuka">
                                            <i class="ri-lock-unlock-line"></i> Terbuka
                                        </span>
                                    @else
                                        <span class="badge-access-locked" title="Akses edit terkunci">
                                            <i class="ri-lock-line" style="color: #F59E0B;"></i> Terkunci
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end fw-semibold text-white num-tabular">{{ number_format($item->jumlah_harga, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold num-tabular" style="color: #34D399;">{{ number_format($item->terbayar, 0, ',', '.') }}</td>
                                <td class="text-end fw-semibold num-tabular" style="color: {{ $item->sisa > 0 ? '#FB7185' : '#94A3B8' }};">{{ number_format($item->sisa, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-action-more" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                            <i class="ri-more-fill fs-16"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">

                                            {{-- Menu Edit --}}
                                            @if($canManage)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('pembelian.edit', $item->id) }}">
                                                        <i class="ri-edit-line fs-15 text-primary"></i>Edit Pembelian
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <span class="dropdown-item text-muted disabled">
                                                        <i class="ri-lock-line fs-15 text-warning"></i>Edit Terkunci
                                                    </span>
                                                </li>
                                            @endif

                                            {{-- ADMIN: Request Unlock via WA jika status masih Terkunci --}}
                                            @if($isAdmin && !$isUnlocked)
                                                <li>
                                                    <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner1 }}?text={{ $pesanUnlock }}" target="_blank">
                                                        <i class="ri-whatsapp-line fs-15 text-success"></i>Request Unlock (Owner 1)
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-warning fw-semibold" href="https://wa.me/{{ $waOwner2 }}?text={{ $pesanUnlock }}" target="_blank">
                                                        <i class="ri-whatsapp-line fs-15 text-success"></i>Request Unlock (Owner 2)
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- OWNER: Lock & Unlock langsung --}}
                                            @if($isOwner)
                                                @if($isUnlocked)
                                                    <li>
                                                        <a class="dropdown-item text-warning" href="{{ route('pembelian.lock', $item->id) }}">
                                                            <i class="ri-lock-line fs-15"></i>Kunci Kembali (Lock)
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form action="{{ route('pembelian.unlock', $item->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="ri-lock-unlock-line fs-15"></i>Buka Kunci (Unlock)
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-info" href="{{ route('pembelian.approval.page', $item->id) }}">
                                                            <i class="ri-shield-check-line fs-15"></i>Halaman Approval
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif

                                            {{-- Lunasi (jika belum lunas) --}}
                                            @if($item->status !== 'Lunas')
                                                <li>
                                                    <a class="dropdown-item text-success" href="javascript:void(0)" onclick="confirmLunasi('{{ route('pembelian.lunas', $item->id) }}')">
                                                        <i class="ri-check-line fs-15 text-success"></i>Lunasi
                                                    </a>
                                                </li>
                                            @endif

                                            {{-- Hapus (hanya jika memiliki hak kelola) --}}
                                            @if($canManage)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST" onsubmit="event.preventDefault(); confirmHapus(this);">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="ri-delete-bin-line fs-15 text-danger"></i>Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="ri-inbox-line fs-36 text-muted mb-2" style="opacity: 0.5;"></i>
                                        <span class="fs-14">Belum ada transaksi pembelian untuk vendor ini.</span>
                                    </div>
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
@endsection

@section('plugins')
<script>
    function confirmLunasi(url) {
        Swal.fire({
            title: 'Konfirmasi Pelunasan',
            text: 'Apakah Anda yakin ingin menandai transaksi pembelian ini sebagai Lunas?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Lunas',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#212529',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function confirmHapus(form) {
        Swal.fire({
            title: 'Hapus Pembelian?',
            text: 'Data pembelian dan seluruh rincian barang ini akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#212529',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection
