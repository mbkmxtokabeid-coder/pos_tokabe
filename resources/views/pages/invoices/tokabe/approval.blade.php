@extends('layout.template')
@section('has_inline_theme_toggle', '1')

@section('head')
<style>
    /* ==========================================================================
       KONFIRMASI APPROVAL INVOICE — EMERALD / FOREST MINT (DARK & LIGHT)
       ========================================================================== */
    .tkb-appr-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
    }
    .tkb-appr-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.02em;
        margin: 0 0 2px 0;
    }
    .tkb-appr-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: var(--text-tertiary);
    }
    .tkb-appr-breadcrumb a {
        color: var(--text-tertiary);
        text-decoration: none;
    }
    .tkb-appr-breadcrumb a:hover {
        color: var(--brand);
    }
    .tkb-appr-breadcrumb .crumb-active {
        color: var(--text-primary);
        font-weight: 600;
    }

    .tkb-appr-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }
    .tkb-appr-card__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border-subtle);
    }
    .tkb-appr-card__title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 2px 0;
    }
    .tkb-appr-card__sub {
        font-size: 12px;
        color: var(--text-tertiary);
        margin: 0;
    }
    .tkb-appr-card__body {
        padding: 20px 24px;
    }

    .tkb-sec-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }
    .tkb-sec-icon--emerald { background: var(--brand-subtle); color: var(--brand); }
    .tkb-sec-icon--cyan { background: var(--accent-cyan-subtle); color: var(--accent-cyan); }
    .tkb-sec-icon--amber { background: var(--warning-subtle); color: var(--warning-text); }

    .tkb-meta-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-tertiary);
        margin-bottom: 4px;
    }
    .tkb-meta-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        word-break: break-word;
    }

    .tkb-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .tkb-pill--amber {
        background: var(--warning-subtle);
        color: var(--warning-text);
        border: 1px solid var(--warning-border);
    }
    .tkb-pill--emerald {
        background: var(--success-subtle);
        color: var(--success-text);
        border: 1px solid var(--success-border);
    }
    .tkb-pill--cyan {
        background: var(--accent-cyan-subtle);
        color: var(--accent-cyan);
        border: 1px solid var(--accent-cyan-border);
    }

    .tkb-callout {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        margin-top: 16px;
    }
    .tkb-callout--amber {
        background: var(--warning-subtle);
        border: 1px solid var(--warning-border);
        color: var(--warning-text);
    }
    .tkb-callout--emerald {
        background: var(--success-subtle);
        border: 1px solid var(--success-border);
        color: var(--success-text);
    }

    /* Rincian Barang Table */
    .tkb-items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    .tkb-items-table thead th {
        background: var(--bg-surface-2);
        color: var(--text-tertiary);
        font-size: 12px;
        font-weight: 600;
        padding: 11px 16px;
        border-bottom: 1px solid var(--border-subtle);
        white-space: nowrap;
    }
    .tkb-items-table tbody tr {
        border-bottom: 1px solid var(--border-subtle);
    }
    .tkb-items-table tbody tr:last-child {
        border-bottom: none;
    }
    .tkb-items-table tbody td {
        padding: 15px 16px;
        vertical-align: top;
        font-size: 13.5px;
        color: var(--text-primary);
    }
    .tkb-item-no {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 7px;
        background: var(--bg-surface-2);
        border: 1px solid var(--border-subtle);
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 600;
    }
    .tkb-item-desc {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.4;
        margin-bottom: 2px;
    }
    .tkb-item-cat {
        font-size: 12px;
        color: var(--text-tertiary);
    }

    /* Right Sticky Sidebar */
    .tkb-appr-sticky {
        position: sticky;
        top: 20px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* Emerald Gradient Hero Card */
    .tkb-appr-hero {
        background: var(--hero-bg);
        border: 1px solid var(--hero-border);
        border-radius: var(--radius-card);
        padding: 22px 24px;
        box-shadow: var(--hero-shadow);
        color: #FFFFFF;
    }
    .tkb-appr-hero__label {
        font-size: 12.5px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 6px;
    }
    .tkb-appr-hero__value {
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -0.025em;
        color: #FFFFFF !important;
        line-height: 1.15;
        margin-bottom: 14px;
        font-variant-numeric: tabular-nums;
    }
    .tkb-appr-hero__pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.16);
        color: #FFFFFF !important;
        font-size: 12px;
        font-weight: 600;
        font-style: italic;
    }

    /* Summary Rows */
    .tkb-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 0;
        font-size: 13.5px;
    }
    .tkb-summary-row__label {
        color: var(--text-secondary);
    }
    .tkb-summary-row__val {
        font-weight: 600;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
    }
    .tkb-summary-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        margin-top: 6px;
        border-top: 1px solid var(--border-subtle);
        font-size: 15px;
        font-weight: 700;
    }

    /* Action Buttons */
    .tkb-btn-approve {
        width: 100%;
        height: 44px;
        border-radius: 10px;
        border: 1px solid var(--brand);
        background: var(--brand);
        color: #FFFFFF !important;
        font-size: 13.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: var(--brand-glow);
    }
    .tkb-btn-approve:hover {
        background: var(--brand-hover);
        border-color: var(--brand-hover);
        color: #FFFFFF !important;
    }
    .tkb-btn-back {
        width: 100%;
        height: 42px;
        border-radius: 10px;
        border: 1px solid var(--border-default);
        background: var(--bg-surface-2);
        color: var(--text-primary) !important;
        font-size: 13.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
    }
    .tkb-btn-back:hover {
        background: var(--bg-surface-3);
        border-color: var(--border-strong);
        color: var(--text-primary) !important;
    }

    @media (max-width: 991.98px) {
        .tkb-appr-sticky {
            position: static;
        }
    }
    @media (max-width: 767.98px) {
        .tkb-appr-header {
            margin-bottom: 14px;
        }
        .tkb-appr-header .tkb-global-header-controls {
            display: none !important;
        }
        .tkb-appr-title {
            font-size: 19px;
        }
        .tkb-appr-card__header {
            padding: 14px 16px;
            flex-wrap: wrap;
        }
        .tkb-appr-card__body {
            padding: 16px;
        }
        .tkb-appr-hero {
            padding: 18px 18px;
        }
        .tkb-appr-hero__value {
            font-size: 24px;
        }
        .tkb-items-table {
            min-width: 560px;
        }
        .tkb-items-table thead th,
        .tkb-items-table tbody td {
            padding: 11px 10px;
            font-size: 12.5px;
        }
        .tkb-items-table tbody td.tabular-nums {
            white-space: nowrap;
        }
    }
</style>
@endsection

@section('content')
@php
    $itemRows = [];
    $jumlahHargaItem = 0;
    foreach ($penjualan_barang as $index => $jual) {
        $itemsAtIndex = $barang[$index] ?? [];
        foreach ($itemsAtIndex as $item) {
            $qty = (float) ($jual->qty ?? 0);
            $harga = (float) ($jual->harga ?? 0);
            $subtotal = $qty * $harga;
            $jumlahHargaItem += $subtotal;
            $itemRows[] = [
                'nama_item' => $item->jenis_barang ?? '-',
                'deskripsi' => $jual->deskripsi_item ?? '-',
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }
    }
    $totalTagihanNum = (float) ($invoice->total_pembayaran ?? $jumlahHargaItem);
    $pajakPenyesuaian = $totalTagihanNum - $jumlahHargaItem;
    $isUnlocked = ($invoice->approval ?? '') === 'Unlock';
    $backUrl = $invoice->is_custom == 1 ? route('list_invoice_tokabe_custom') : route('list_invoice_tokabe');
@endphp

<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="tkb-appr-header">
            <div>
                <h4 class="tkb-appr-title">Konfirmasi approval invoice</h4>
                <div class="tkb-appr-breadcrumb">
                    <a href="{{ $backUrl }}">Invoice</a>
                    <span>›</span>
                    <span>Approval</span>
                    <span>›</span>
                    <span class="crumb-active">{{ $invoice->nomor_invoice }}</span>
                </div>
            </div>
            <div class="tkb-global-header-controls">
                <button type="button" class="tkb-theme-toggle" onclick="window.toggleTokabeTheme(event)" aria-label="Ubah Mode Terang / Gelap" title="Ubah Mode Terang / Gelap">
                    <span class="tkb-theme-toggle__thumb" aria-hidden="true"></span>
                    <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--sun"><i class="ri-sun-line"></i></span>
                    <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--moon"><i class="ri-moon-clear-line"></i></span>
                </button>
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <!-- LEFT COLUMN: Detail Invoice + Rincian Barang -->
            <div class="col-lg-8 d-flex flex-column gap-4">

                <!-- Card 1: Detail Invoice -->
                <div class="tkb-appr-card">
                    <div class="tkb-appr-card__header">
                        <div class="d-flex align-items-center gap-3">
                            <span class="tkb-sec-icon tkb-sec-icon--emerald"><i class="ri-file-text-line"></i></span>
                            <div>
                                <h5 class="tkb-appr-card__title">Detail invoice</h5>
                                <p class="tkb-appr-card__sub">Periksa data berikut sebelum memberi persetujuan</p>
                            </div>
                        </div>
                        @if($isUnlocked)
                            <span class="tkb-pill tkb-pill--emerald"><i class="ri-checkbox-circle-line"></i>Sudah disetujui</span>
                        @else
                            <span class="tkb-pill tkb-pill--amber"><i class="ri-time-line"></i>Menunggu approval</span>
                        @endif
                    </div>

                    <div class="tkb-appr-card__body">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <div class="tkb-meta-label">Nomor invoice</div>
                                <p class="tkb-meta-value">{{ $invoice->nomor_invoice }}</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="tkb-meta-label">Tanggal</div>
                                <p class="tkb-meta-value tabular-nums">{{ $invoice->tgl_penjualan }}</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="tkb-meta-label">Nama pelanggan</div>
                                <p class="tkb-meta-value">{{ $invoice->customer }}</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="tkb-meta-label">NPWP customer</div>
                                <p class="tkb-meta-value">{{ $invoice->npwp ?: 'Tidak diisi / Non-NPWP' }}</p>
                            </div>
                            <div class="col-12">
                                <div class="tkb-meta-label">Status faktur pajak</div>
                                @if(!empty($invoice->no_faktur_pajak))
                                    <span class="tkb-pill tkb-pill--emerald">Lengkap: {{ $invoice->no_faktur_pajak }}</span>
                                @else
                                    <span class="tkb-pill tkb-pill--amber">Belum diisi</span>
                                @endif
                            </div>
                        </div>

                        @if(empty($invoice->no_faktur_pajak))
                            <div class="tkb-callout tkb-callout--amber">
                                <i class="ri-information-line fs-16 mt-1"></i>
                                <div>
                                    Akses akan dibuka untuk pengisian nomor faktur pajak.
                                    @if(isset($latestRequest) && $latestRequest)
                                        <div class="mt-1 fs-12" style="opacity: 0.9;">
                                            <strong>Pemohon:</strong> {{ $latestRequest->requester->nama ?? 'Admin' }} ({{ $latestRequest->created_at->format('d/m/Y H:i') }}) — {{ $latestRequest->catatan_admin ?? 'Izin penginputan Nomor Faktur Pajak' }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="tkb-callout tkb-callout--emerald">
                                <i class="ri-checkbox-circle-line fs-16 mt-1"></i>
                                <div>Nomor faktur pajak sudah terisi lengkap ({{ $invoice->no_faktur_pajak }}).</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card 2: Rincian Barang -->
                <div class="tkb-appr-card">
                    <div class="tkb-appr-card__header">
                        <div class="d-flex align-items-center gap-3">
                            <span class="tkb-sec-icon tkb-sec-icon--amber"><i class="ri-price-tag-3-line"></i></span>
                            <div>
                                <h5 class="tkb-appr-card__title">Rincian barang</h5>
                                <p class="tkb-appr-card__sub">Daftar item yang ditagihkan pada invoice ini</p>
                            </div>
                        </div>
                        <span class="tkb-pill tkb-pill--cyan">{{ count($itemRows) }} item</span>
                    </div>

                    <div class="table-responsive">
                        <table class="tkb-items-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th>Barang dan deskripsi</th>
                                    <th class="text-end" style="width: 90px;">Jumlah</th>
                                    <th class="text-end" style="width: 140px;">Harga</th>
                                    <th class="text-end" style="width: 150px;">Jumlah harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($itemRows as $idx => $row)
                                    <tr>
                                        <td><span class="tkb-item-no">{{ $idx + 1 }}</span></td>
                                        <td>
                                            <div class="tkb-item-desc">{{ $row['deskripsi'] }}</div>
                                            <div class="tkb-item-cat">{{ $row['nama_item'] }}</div>
                                        </td>
                                        <td class="text-end tabular-nums" style="color: var(--text-secondary);">{{ rtrim(rtrim(number_format($row['qty'], 2, ',', '.'), '0'), ',') }}</td>
                                        <td class="text-end tabular-nums" style="color: var(--text-secondary);">Rp {{ number_format($row['harga'], 0, ',', '.') }}</td>
                                        <td class="text-end tabular-nums fw-bold">Rp {{ number_format($row['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4" style="color: var(--text-tertiary);">Tidak ada rincian barang pada invoice ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Hero Total + Ringkasan + Action Buttons -->
            <div class="col-lg-4">
                <div class="tkb-appr-sticky">
                    <!-- Emerald Gradient Hero Card -->
                    <div class="tkb-appr-hero">
                        <div class="tkb-appr-hero__label">Total tagihan</div>
                        <div class="tkb-appr-hero__value">Rp {{ $invoice->formatted_total_pembayaran }}</div>
                        <span class="tkb-appr-hero__pill">Invoice {{ $invoice->nomor_invoice }}</span>
                    </div>

                    <!-- Ringkasan Card -->
                    <div class="tkb-appr-card">
                        <div class="tkb-appr-card__header">
                            <div class="d-flex align-items-center gap-3">
                                <span class="tkb-sec-icon tkb-sec-icon--cyan"><i class="las la-balance-scale"></i></span>
                                <h5 class="tkb-appr-card__title mb-0">Ringkasan</h5>
                            </div>
                        </div>
                        <div class="tkb-appr-card__body">
                            <div class="tkb-summary-row">
                                <span class="tkb-summary-row__label">Jumlah harga item</span>
                                <span class="tkb-summary-row__val">Rp {{ number_format($jumlahHargaItem, 0, ',', '.') }}</span>
                            </div>
                            <div class="tkb-summary-row">
                                <span class="tkb-summary-row__label">Pajak dan penyesuaian</span>
                                <span class="tkb-summary-row__val">Rp {{ number_format($pajakPenyesuaian, 0, ',', '.') }}</span>
                            </div>
                            <div class="tkb-summary-total">
                                <span style="color: var(--text-primary);">Total tagihan</span>
                                <span class="tabular-nums" style="color: var(--success-text);">Rp {{ $invoice->formatted_total_pembayaran }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Card -->
                    <div class="tkb-appr-card">
                        <div class="tkb-appr-card__body d-flex flex-column gap-2">
                            <form action="{{ route('tokabe.unlock', $invoice->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="tkb-btn-approve">
                                    <i class="ri-lock-unlock-line fs-16"></i>
                                    <span>Setujui dan buka kunci invoice</span>
                                </button>
                            </form>
                            <a href="{{ $backUrl }}" class="tkb-btn-back">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
