@extends('layout.template')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: #F1F5F9; letter-spacing: -0.015em;">Laporan Pembelian</h4>
                            <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(99,102,241,0.12);color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Rekap & Ekspor</span>
                        </div>
                        <p class="mb-0 mt-1" style="font-size: 13px; color: #94A3B8;">Pilih filter dan periode untuk melihat atau mengunduh laporan pembelian vendor</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0" style="font-size: 13px;">
                            <li class="breadcrumb-item"><a href="javascript:void(0);" style="color: #94A3B8;">Laporan</a></li>
                            <li class="breadcrumb-item active" style="color: #CBD5E1;">Pembelian</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* DESIGN.md Compliance: Neutral Charcoal Theme (#161B22) */
            .purchase-report-card {
                min-height: 220px;
                background-color: #161B22 !important;
                border: 1px solid #282E37 !important;
                border-radius: 12px !important;
                transition: transform 200ms ease, box-shadow 200ms ease, border-color 200ms ease;
                display: flex;
                flex-direction: column;
                height: 100%;
            }
            .purchase-report-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 28px rgba(0, 0, 0, 0.45) !important;
                border-color: #383E45 !important;
            }
            .purchase-report-card .card-body { 
                padding: 1.5rem !important; 
                display: flex;
                flex-direction: column;
                flex: 1 1 auto;
            }
            .purchase-report-card form.report-filter {
                display: flex;
                flex-direction: column;
                flex: 1 1 auto;
            }
            .purchase-report-card .form-label { 
                font-size: 11.5px; 
                font-weight: 600; 
                color: #94A3B8; 
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.45rem; 
            }
            .purchase-report-card .form-control,
            .purchase-report-card .form-select {
                background-color: #212529 !important;
                border: 1px solid #383E45 !important;
                color: #F8F9FA !important;
                border-radius: 8px !important;
                font-size: 13.5px !important;
                padding: 9px 12px !important;
                transition: border-color 150ms ease, box-shadow 150ms ease;
            }
            .purchase-report-card .form-control:focus,
            .purchase-report-card .form-select:focus {
                border-color: #6366F1 !important;
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.25) !important;
                outline: none !important;
                color: #FFFFFF !important;
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

            /* Direct Action Buttons matching Laporan Penjualan */
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

        @php
            $vendorOptions = '<option value="semua">Semua Vendor</option>';
            foreach ($vendors as $v) {
                $vendorOptions .= '<option value="' . e($v->id) . '">' . e($v->nama_vendor) . '</option>';
            }
            $currentYear = now()->year;
            $currentMonth = now()->month;
        @endphp

        <div class="row g-4">
            {{-- Card 1: Semua Pembelian --}}
            <div class="col-xl-6">
                <div class="card purchase-report-card mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="report-icon-box" style="background-color: rgba(59, 130, 246, 0.12); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.25);">
                                <i class="ri-file-list-3-line fs-22"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 text-white" style="font-size: 16px; font-weight: 700;">Laporan Semua Pembelian</h5>
                                <small style="color: #94A3B8; font-size: 13px;">Filter berdasarkan vendor dan status pembayaran</small>
                            </div>
                        </div>

                        <form class="report-filter" data-tipe="all">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Vendor</label>
                                    <select name="vendor" class="form-select">{!! $vendorOptions !!}</select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status Pembayaran</label>
                                    <select name="status" class="form-select">
                                        <option value="semua">Semua Status</option>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Belum Lunas">Belum Lunas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="report-actions">
                                <button type="button" class="btn-view-report report-action" data-download="0">
                                    <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                                </button>
                                <button type="button" class="btn-excel-report report-action" data-download="1">
                                    <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card 2: Laporan Custom Tanggal --}}
            <div class="col-xl-6">
                <div class="card purchase-report-card mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="report-icon-box" style="background-color: rgba(168, 85, 247, 0.12); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.25);">
                                <i class="ri-calendar-2-line fs-22"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 text-white" style="font-size: 16px; font-weight: 700;">Laporan Pembelian Custom</h5>
                                <small style="color: #94A3B8; font-size: 13px;">Pilih tanggal mulai s/d tanggal selesai</small>
                            </div>
                        </div>

                        <form class="report-filter" data-tipe="custom">
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input name="tanggal_mulai" type="date" class="form-control" value="{{ date('Y-m-01') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input name="tanggal_akhir" type="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Vendor</label>
                                    <select name="vendor" class="form-select">{!! $vendorOptions !!}</select>
                                </div>
                            </div>

                            <div class="report-actions">
                                <button type="button" class="btn-view-report report-action" data-download="0">
                                    <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                                </button>
                                <button type="button" class="btn-excel-report report-action" data-download="1">
                                    <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card 3: Laporan Bulanan --}}
            <div class="col-xl-6">
                <div class="card purchase-report-card mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="report-icon-box" style="background-color: rgba(16, 185, 129, 0.12); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25);">
                                <i class="ri-calendar-event-line fs-22"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 text-white" style="font-size: 16px; font-weight: 700;">Laporan Pembelian Bulanan</h5>
                                <small style="color: #94A3B8; font-size: 13px;">Filter per bulan dan tahun berjalan</small>
                            </div>
                        </div>

                        <form class="report-filter" data-tipe="monthly">
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pilih Bulan</label>
                                    <select name="bulan" class="form-select">
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Pilih Tahun</label>
                                    <select name="tahun" class="form-select">
                                        @foreach($tahun as $y)
                                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Vendor</label>
                                    <select name="vendor" class="form-select">{!! $vendorOptions !!}</select>
                                </div>
                            </div>

                            <div class="report-actions">
                                <button type="button" class="btn-view-report report-action" data-download="0">
                                    <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                                </button>
                                <button type="button" class="btn-excel-report report-action" data-download="1">
                                    <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card 4: Laporan Tahunan --}}
            <div class="col-xl-6">
                <div class="card purchase-report-card mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="report-icon-box" style="background-color: rgba(245, 158, 11, 0.12); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.25);">
                                <i class="ri-calendar-check-line fs-22"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 text-white" style="font-size: 16px; font-weight: 700;">Laporan Pembelian Tahunan</h5>
                                <small style="color: #94A3B8; font-size: 13px;">Akumulasi transaksi sepanjang satu tahun</small>
                            </div>
                        </div>

                        <form class="report-filter" data-tipe="yearly">
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pilih Tahun</label>
                                    <select name="tahun" class="form-select">
                                        @foreach($tahun as $y)
                                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Vendor</label>
                                    <select name="vendor" class="form-select">{!! $vendorOptions !!}</select>
                                </div>
                            </div>

                            <div class="report-actions">
                                <button type="button" class="btn-view-report report-action" data-download="0">
                                    <i class="ri-eye-line fs-16"></i>Lihat Laporan (PDF)
                                </button>
                                <button type="button" class="btn-excel-report report-action" data-download="1">
                                    <i class="ri-file-excel-2-line fs-16"></i>Download Excel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugins')
<script>
    document.querySelectorAll('.report-action').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const card = this.closest('.purchase-report-card');
            const form = card.querySelector('form.report-filter');
            const isDownload = this.getAttribute('data-download');
            
            const params = new URLSearchParams(new FormData(form));
            params.append('tipe', form.getAttribute('data-tipe'));
            if (isDownload === '1') {
                params.append('download', '1');
            } else {
                params.append('lihat', '1');
            }
            
            const url = '{{ route("laporan.pembelian.filter") }}?' + params.toString();
            if (isDownload === '1') {
                window.location.href = url;
            } else {
                window.open(url, '_blank');
            }
        });
    });
</script>
@endsection