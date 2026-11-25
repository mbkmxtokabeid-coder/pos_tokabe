@extends('layout.template')
@section('has_inline_theme_toggle', '1')

@section('head')
<style>
/* ==========================================================================
   TOKABE DASHBOARD — EMERALD / FOREST MINT DESIGN SYSTEM (DARK & LIGHT)
   ========================================================================== */

/* Top Page Header */
.tkb-dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 20px;
}
.tkb-dash-title {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
    letter-spacing: -0.02em;
    margin: 0 0 2px 0;
}
.tkb-dash-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12.5px;
    color: var(--text-tertiary);
    margin: 0;
}
.tkb-dash-breadcrumb .crumb-active {
    color: var(--text-primary);
    font-weight: 600;
}

/* Base Dashboard Card */
#card-aktivitas-pembayaran,
#card-grafik-penjualan-item,
#card-grafik-pembelian-invoice {
    background-color: var(--bg-surface) !important;
    border: 1px solid var(--border-default) !important;
    border-radius: var(--radius-card) !important;
    color: var(--text-primary) !important;
    margin-bottom: 22px;
    box-shadow: var(--shadow-card) !important;
}

#card-aktivitas-pembayaran .card-header,
#card-grafik-penjualan-item .card-header,
#card-grafik-pembelian-invoice .card-header {
    background: transparent !important;
    padding: 20px 24px 16px 24px !important;
    border-bottom: 1px solid var(--border-subtle) !important;
}

#card-aktivitas-pembayaran .card-body,
#card-grafik-penjualan-item .card-body,
#card-grafik-pembelian-invoice .card-body {
    padding: 20px 24px 22px 24px !important;
}

/* Section Icon Badges */
.tkb-sec-icon {
    width: 36px;
    height: 36px;
    min-width: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.tkb-sec-icon--emerald {
    background: var(--brand-subtle);
    color: var(--brand);
}
.tkb-sec-icon--cyan {
    background: var(--accent-cyan-subtle);
    color: var(--accent-cyan);
}
.tkb-sec-icon--amber {
    background: var(--warning-subtle);
    color: var(--warning-text);
}

/* Typography Overrides (Theme-aware) */
#card-aktivitas-pembayaran h4,
#card-aktivitas-pembayaran h5,
#card-grafik-penjualan-item h4,
#card-grafik-penjualan-item h5,
#card-grafik-pembelian-invoice h4,
#card-grafik-pembelian-invoice h5,
.card-title,
.section-title {
    color: var(--text-primary) !important;
    font-weight: 700 !important;
    letter-spacing: -0.01em;
}
.tkb-card-subtitle {
    color: var(--text-tertiary) !important;
    font-size: 12.5px !important;
}

/* Emerald Gradient Hero Revenue Card */
.tkb-hero-revenue {
    position: relative;
    overflow: hidden;
    background: var(--hero-bg);
    border: 1px solid var(--hero-border);
    border-radius: 14px;
    padding: 20px 22px;
    box-shadow: var(--hero-shadow);
    color: #FFFFFF;
    min-height: 146px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.tkb-hero-revenue__label {
    font-size: 12px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.84);
    margin-bottom: 6px;
}
.tkb-hero-revenue__value {
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -0.025em;
    color: #FFFFFF !important;
    line-height: 1.15;
    margin-bottom: 12px;
    position: relative;
    z-index: 2;
}
.tkb-hero-revenue__pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(6px);
    color: #FFFFFF !important;
    font-size: 11.5px;
    font-weight: 600;
    font-style: italic;
    width: fit-content;
    position: relative;
    z-index: 2;
}
.tkb-hero-revenue__spark {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 68%;
    height: 58%;
    pointer-events: none;
    z-index: 1;
    opacity: 0.7;
}

/* Section Sub-panels */
.sub-panel {
    background: var(--bg-surface-2) !important;
    border: 1px solid var(--border-subtle) !important;
    border-radius: var(--radius-inner) !important;
}

/* Tabular Numerals */
.tabular-nums, .price, .num, td.numeric, th.numeric, .stat-value,
#hero-pendapatan-akumulasi, #terbayar, #belumTerbayar, #batal,
#stat-item-qty, #stat-item-nominal, #stat-item-top, #stat-item-dominant-tax,
#tfoot-total-transaksi, #tfoot-total-qty, #tfoot-total-nominal {
    font-variant-numeric: tabular-nums !important;
    font-feature-settings: "tnum" 1, "cv11" 1 !important;
}

/* ==========================================================================
   SEGMENTED CONTROL (Emerald Pill Style)
   ========================================================================== */
.segmented-control {
    display: inline-flex;
    align-items: center;
    background: var(--bg-surface-2);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-control);
    padding: 3px;
    gap: 2px;
}
.segmented-item {
    background: transparent;
    border: 1px solid transparent;
    color: var(--text-secondary);
    padding: 5px 12px;
    border-radius: var(--radius-sm);
    font-size: 12.5px;
    font-weight: 500;
    line-height: 1.4;
    transition: all var(--dur-fast) ease;
    cursor: pointer;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.segmented-item:hover {
    color: var(--text-primary);
    background: var(--bg-hover);
}
.segmented-item:active {
    transform: scale(0.98);
}
.segmented-item.active {
    background: var(--brand-subtle);
    color: var(--brand);
    font-weight: 600;
    border: 1px solid var(--brand-border);
}
[data-theme="light"] .segmented-item.active {
    background: var(--brand);
    color: #FFFFFF !important;
    border-color: var(--brand);
}

/* Custom Filter Button */
.btn-custom-filter {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--bg-surface-2);
    color: var(--text-secondary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-control);
    padding: 6px 12px;
    font-size: 12.5px;
    font-weight: 500;
    line-height: 1.4;
    transition: all var(--dur-fast) ease;
    cursor: pointer;
}
.btn-custom-filter:hover {
    color: var(--text-primary);
    border-color: var(--border-strong);
    background: var(--bg-hover);
}
.btn-custom-filter.active,
.btn-custom-filter.open {
    background: var(--brand-subtle);
    border-color: var(--brand-border);
    color: var(--brand);
    font-weight: 600;
}
.chevron-rotate {
    transition: transform 200ms var(--ease-out-expo);
}
.btn-custom-filter.open .chevron-rotate {
    transform: rotate(180deg);
}

/* Collapsible Filter Panel */
.custom-filter-panel {
    background: var(--bg-surface-2) !important;
    border: 1px solid var(--border-default) !important;
    border-radius: var(--radius-inner) !important;
    animation: filterSlideDown 220ms var(--ease-out-expo);
}
@keyframes filterSlideDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Custom Form Controls */
.dash-input, .dash-select {
    background: var(--bg-surface-2) !important;
    color: var(--text-primary) !important;
    border: 1px solid var(--border-default) !important;
    border-radius: var(--radius-control) !important;
    font-size: 12.5px !important;
    padding: 6px 12px !important;
    transition: border-color var(--dur-fast) ease, box-shadow var(--dur-fast) ease;
}
.dash-input:focus, .dash-select:focus {
    border-color: var(--brand) !important;
    outline: none !important;
    box-shadow: var(--focus-ring) !important;
}
.dash-select option {
    background: var(--bg-surface);
    color: var(--text-primary);
}

/* Primary Button */
.btn-dash-primary {
    background: var(--brand) !important;
    color: #ffffff !important;
    border: 1px solid var(--brand) !important;
    border-radius: var(--radius-control) !important;
    font-size: 12.5px !important;
    font-weight: 600 !important;
    padding: 6px 14px !important;
    transition: background var(--dur-fast) ease, transform 80ms ease !important;
}
.btn-dash-primary:hover {
    background: var(--brand-hover) !important;
}

/* ==========================================================================
   CARD 2: REFINED FINANCE KPI STATS
   ========================================================================== */
.stat-box {
    background: var(--bg-surface-2) !important;
    border: 1px solid var(--border-subtle) !important;
    border-radius: var(--radius-inner) !important;
    padding: 18px 20px !important;
    transition: border-color var(--dur-fast) ease, transform var(--dur-fast) var(--ease-out-expo);
}
.stat-box:hover {
    border-color: var(--border-strong) !important;
    transform: translateY(-2px);
}
.stat-overline {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0;
}
.stat-value-big {
    font-size: 23px;
    font-weight: 800;
    letter-spacing: -0.02em;
    line-height: 1.25;
    color: var(--text-primary);
    margin: 8px 0 4px 0;
}
.stat-caption {
    font-size: 11.5px;
    color: var(--text-tertiary);
}

/* ==========================================================================
   TABLE DESIGN (Clean Emerald Header & Rows)
   ========================================================================== */
.dash-table-wrapper {
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-inner);
    overflow: hidden;
}
.dash-table {
    color: var(--text-primary);
    margin-bottom: 0;
    width: 100%;
    border-collapse: collapse;
}
.dash-table thead th {
    background: var(--bg-surface-2) !important;
    color: var(--text-tertiary) !important;
    border-bottom: 1px solid var(--border-subtle) !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    letter-spacing: 0.02em !important;
    padding: 11px 14px !important;
}
.dash-table tbody tr {
    height: 48px;
    background: transparent !important;
    border-bottom: 1px solid var(--border-subtle);
    transition: background-color var(--dur-fast) ease;
}
.dash-table tbody tr:last-child {
    border-bottom: none;
}
.dash-table tbody tr:hover {
    background-color: var(--bg-hover) !important;
}
.dash-table tbody td {
    color: var(--text-primary) !important;
    padding: 11px 14px;
    font-size: 13px;
    vertical-align: middle;
}
.dash-table tfoot td {
    color: var(--text-primary) !important;
    background: var(--bg-surface-2) !important;
    border-top: 1px solid var(--border-default) !important;
    padding: 12px 14px;
    font-size: 13px;
    font-weight: 700;
}

/* ==========================================================================
   CARD 3: VENDOR BILL LIST (Matches Reference Screenshots 5 & 10)
   ========================================================================== */
.tkb-vendor-list {
    display: flex;
    flex-direction: column;
}
.tkb-vendor-item {
    display: grid;
    grid-template-columns: minmax(120px, 1.3fr) minmax(95px, 1fr) minmax(95px, 1fr);
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-subtle);
    cursor: pointer;
    transition: background-color var(--dur-fast) ease;
}
.tkb-vendor-item:last-child {
    border-bottom: none;
}
.tkb-vendor-item:hover {
    background-color: var(--bg-hover);
}
.tkb-vendor-item.active-vendor-row {
    background-color: var(--brand-subtle);
    box-shadow: inset 3px 0 0 var(--brand);
}
.tkb-vendor-name {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.tkb-vendor-bar-track {
    width: 100%;
    height: 6px;
    background: var(--bg-surface-3);
    border-radius: 9999px;
    overflow: hidden;
}
.tkb-vendor-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--brand-pressed), var(--brand));
    border-radius: 9999px;
    transition: width 350ms var(--ease-out-expo);
}
.tkb-vendor-meta-label {
    font-size: 11px;
    color: var(--text-tertiary);
    margin-bottom: 2px;
}
.tkb-vendor-meta-val {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-secondary);
}
.tkb-vendor-unpaid-val {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--text-primary);
}

/* Subtle Status Pills & Tax Badges */
.badge-tint {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    font-size: 11.5px;
    font-weight: 600;
    border-radius: 9999px;
    line-height: 1.35;
    white-space: nowrap;
    flex-shrink: 0;
}
.badge-tint-success {
    background: var(--success-subtle);
    color: var(--success-text);
    border: 1px solid var(--success-border);
}
.badge-tint-warning {
    background: var(--warning-subtle);
    color: var(--warning-text);
    border: 1px solid var(--warning-border);
}
.badge-tint-neutral {
    background: var(--bg-surface-3);
    color: var(--text-secondary);
    border: 1px solid var(--border-subtle);
}
.badge-tint-brand {
    background: var(--accent-cyan-subtle);
    color: var(--accent-cyan);
    border: 1px solid var(--accent-cyan-border);
}
.badge-tint-emerald {
    background: var(--brand-subtle);
    color: var(--brand);
    border: 1px solid var(--brand-border);
}

/* ApexCharts Theme Refinement */
.apexcharts-legend-text,
.apexcharts-text {
    fill: var(--text-tertiary) !important;
    color: var(--text-tertiary) !important;
    font-family: inherit !important;
}
.apexcharts-tooltip {
    background: var(--bg-surface) !important;
    border: 1px solid var(--border-default) !important;
    border-radius: var(--radius-control) !important;
    box-shadow: var(--shadow-dropdown) !important;
    color: var(--text-primary) !important;
}
.apexcharts-tooltip .apexcharts-tooltip-title {
    background: var(--bg-surface-2) !important;
    border-bottom: 1px solid var(--border-subtle) !important;
    font-family: inherit !important;
    font-weight: 600 !important;
    color: var(--text-primary) !important;
    font-size: 12px !important;
}
.apexcharts-grid line {
    stroke: var(--border-subtle) !important;
}

/* Motion Orchestration */
@keyframes motionEnter {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.motion-card-1 { animation: motionEnter var(--dur-enter) var(--ease-out-expo) 0ms both; }
.motion-card-2 { animation: motionEnter var(--dur-enter) var(--ease-out-expo) 70ms both; }
.motion-card-3 { animation: motionEnter var(--dur-enter) var(--ease-out-expo) 140ms both; }

@media (prefers-reduced-motion: reduce) {
    *, ::before, ::after {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}

/* ==========================================================================
   MOBILE RESPONSIVE FIT (HP: <= 767.98px & <= 575.98px)
   ========================================================================== */
@media (max-width: 767.98px) {
    .tkb-dash-header {
        margin-bottom: 14px;
    }
    .tkb-dash-header .tkb-global-header-controls {
        display: none !important;
    }
    .tkb-dash-title {
        font-size: 19px;
    }
    #card-aktivitas-pembayaran,
    #card-grafik-penjualan-item,
    #card-grafik-pembelian-invoice {
        overflow: hidden;
    }
    #card-aktivitas-pembayaran .card-header,
    #card-grafik-penjualan-item .card-header,
    #card-grafik-pembelian-invoice .card-header {
        padding: 14px 14px 12px 14px !important;
    }
    #card-aktivitas-pembayaran .card-body,
    #card-grafik-penjualan-item .card-body,
    #card-grafik-pembelian-invoice .card-body {
        padding: 14px !important;
    }
    /* Prevent flex children from pushing outside card bounds */
    #card-aktivitas-pembayaran .card-header > div,
    #card-aktivitas-pembayaran .card-header .d-flex,
    #card-grafik-penjualan-item .card-header .d-flex,
    #card-grafik-pembelian-invoice .card-header .d-flex {
        min-width: 0 !important;
        max-width: 100% !important;
    }
    .segmented-control {
        display: flex !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .segmented-control::-webkit-scrollbar {
        display: none;
    }
    .segmented-item {
        padding: 6px 10px;
        font-size: 12px;
        flex-shrink: 0;
    }
    .btn-custom-filter {
        white-space: nowrap;
        flex-shrink: 0;
    }
    .tkb-hero-revenue {
        padding: 16px;
        min-height: 124px;
    }
    .tkb-hero-revenue__value {
        font-size: 23px;
    }
    .stat-box {
        padding: 14px 16px !important;
    }
    .stat-value-big {
        font-size: 20px;
    }
    #card-grafik-penjualan-item .dash-select,
    #card-grafik-pembelian-invoice .dash-select {
        width: 100%;
        min-width: 0 !important;
    }
    #card-grafik-pembelian-invoice .card-header .ms-auto,
    #card-grafik-penjualan-item .card-header .ms-auto {
        width: 100%;
        margin-left: 0 !important;
    }
    #card-grafik-penjualan-item .card-header .ms-auto > div:first-child {
        width: 100%;
    }
    #card-grafik-penjualan-item .card-header .ms-auto > div:first-child .dash-select {
        flex: 1 1 auto;
    }
    /* Vendor list header wraps cleanly on narrow screens */
    .tkb-vendor-header {
        flex-wrap: wrap !important;
        gap: 10px !important;
    }
    .tkb-vendor-header #btn-semua-vendor {
        margin-left: auto;
    }
    #table-rincian-item {
        min-width: 720px;
    }
    #table-rincian-item th,
    #table-rincian-item td {
        white-space: nowrap;
    }
}

@media (max-width: 575.98px) {
    .tkb-vendor-item {
        grid-template-columns: 1fr 1fr;
        gap: 8px 12px;
        padding: 12px 14px;
    }
    .tkb-vendor-item > div:first-child {
        grid-column: 1 / -1;
    }
    .tkb-hero-revenue__value {
        font-size: 21px;
    }
    .custom-filter-panel [id^="wrapper-"] {
        width: 100%;
        flex-wrap: wrap;
        gap: 6px !important;
    }
    .custom-filter-panel .dash-input,
    .custom-filter-panel .dash-select,
    .custom-filter-panel .btn-dash-primary {
        width: 100%;
        min-width: 0 !important;
        flex: 1 1 120px;
    }
    #harga > div > div {
        gap: 6px;
        flex-wrap: wrap;
    }
}
</style>
@endsection

@section('content')
        {{-- @extends('layout.pagetitle') --}}

        <div class="page-content">
         <div class="container-fluid">

            <div class="tkb-dash-header">
                <div>
                    <h4 class="tkb-dash-title">Dashboard Tokabe</h4>
                    <div class="tkb-dash-breadcrumb">
                        <span>Dashboard</span>
                        <span>›</span>
                        <span class="crumb-active">Dashboard</span>
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

                    <!-- KARTU GABUNGAN: AKTIVITAS PEMBAYARAN & STRUKTUR STATUS PEMBAYARAN -->
                    <div class="row motion-card-1">
                        <div class="col-12">
                            <div class="card" id="card-aktivitas-pembayaran">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="tkb-sec-icon tkb-sec-icon--emerald">
                                                <i class="ri-line-chart-line"></i>
                                            </div>
                                            <div>
                                                <h4 class="card-title mb-0 fs-16">Aktivitas pembayaran dan struktur status</h4>
                                                <p class="tkb-card-subtitle mb-0">
                                                    Visualisasi arus penerimaan kas masuk serta perbandingan status pelunasan invoice
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Filter Toolbar with Segmented Control & Custom Filter -->
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <div class="segmented-control" role="group">
                                                <button type="button" class="segmented-item tombol" onclick="timeFrame('day')" id="btn-day" title="Filter Hari Ini">
                                                    Hari Ini
                                                </button>
                                                <button type="button" class="segmented-item tombol" onclick="timeFrame('week')" id="btn-week" title="Filter Minggu Ini">
                                                    Minggu Ini
                                                </button>
                                                <button type="button" class="segmented-item tombol" onclick="timeFrame('month')" id="btn-month" title="Filter Bulan Ini">
                                                    Bulan Ini
                                                </button>
                                                <button type="button" class="segmented-item tombol" onclick="timeFrame('year')" id="btn-year" title="Filter Tahun Ini">
                                                    Tahun Ini
                                                </button>
                                                <button type="button" class="segmented-item tombol active" onclick="timeFrame('lastYear')" id="btn-lastYear" title="Filter Tahun Lalu">
                                                    Tahun Lalu
                                                </button>
                                            </div>

                                            <button type="button" class="btn-custom-filter" onclick="togglePaymentCustomFilter()" id="btn-payment-custom-toggle" title="Filter Khusus Tanggal, Bulan, atau Rentang">
                                                <i class="ri-filter-3-line"></i> Filter Kustom <i class="ri-arrow-down-s-line chevron-rotate" id="icon-toggle-payment-custom"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Custom Filter Panel for Payment Activity (Collapsible) -->
                                    <div id="payment-custom-filter-box" class="custom-filter-panel mt-3 p-3" style="display: none;">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <span class="fs-12 fw-semibold text-muted text-uppercase" style="letter-spacing: 0.06em;">
                                                    <i class="ri-filter-line me-1" style="color: var(--brand);"></i>Pilih Spesifik:
                                                </span>
                                                
                                                <!-- Mode selector segmented -->
                                                <div class="segmented-control" role="group">
                                                    <button type="button" class="segmented-item active" id="btn-payment-mode-date" onclick="setPaymentCustomFilterMode('date')">
                                                        <i class="ri-calendar-event-line"></i>Tanggal
                                                    </button>
                                                    <button type="button" class="segmented-item" id="btn-payment-mode-month" onclick="setPaymentCustomFilterMode('month')">
                                                        <i class="ri-calendar-2-line"></i>Bulan
                                                    </button>
                                                    <button type="button" class="segmented-item" id="btn-payment-mode-year" onclick="setPaymentCustomFilterMode('year')">
                                                        <i class="ri-calendar-check-line"></i>Tahun
                                                    </button>
                                                    <button type="button" class="segmented-item" id="btn-payment-mode-range" onclick="setPaymentCustomFilterMode('range')">
                                                        <i class="ri-calendar-schedule-line"></i>Rentang
                                                    </button>
                                                </div>

                                                <!-- Form Control: Tanggal -->
                                                <div id="wrapper-payment-filter-date" class="d-inline-flex align-items-center">
                                                    <input type="date" class="form-control form-control-sm dash-input" id="input-payment-filter-date" value="{{ date('Y-m-d') }}">
                                                </div>

                                                <!-- Form Control: Bulan -->
                                                <div id="wrapper-payment-filter-month" class="d-none align-items-center gap-1">
                                                    <select class="form-select form-select-sm dash-select" id="select-payment-filter-month" style="min-width: 125px;">
                                                        @php
                                                            $bulanIndo = [
                                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                            ];
                                                            $curMonth = (int)date('n');
                                                            $curYear = (int)date('Y');
                                                        @endphp
                                                        @foreach($bulanIndo as $num => $nama)
                                                            <option value="{{ $num }}" {{ $num == $curMonth ? 'selected' : '' }}>{{ $nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select class="form-select form-select-sm dash-select" id="select-payment-filter-month-year" style="min-width: 95px;">
                                                        @foreach($availableYears as $yr)
                                                            <option value="{{ $yr }}" {{ $yr == $curYear ? 'selected' : '' }}>{{ $yr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Form Control: Tahun -->
                                                <div id="wrapper-payment-filter-year" class="d-none align-items-center">
                                                    <select class="form-select form-select-sm dash-select" id="select-payment-filter-year" style="min-width: 105px;">
                                                        @foreach($availableYears as $yr)
                                                            <option value="{{ $yr }}" {{ $yr == $curYear ? 'selected' : '' }}>{{ $yr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div id="wrapper-payment-filter-range" class="d-none align-items-center gap-1">
                                                    <input type="date" class="form-control form-control-sm dash-input" id="input-payment-filter-start">
                                                    <span class="text-muted fs-12">s/d</span>
                                                    <input type="date" class="form-control form-control-sm dash-input" id="input-payment-filter-end">
                                                </div>

                                                <!-- Tampilkan button -->
                                                <button type="button" class="btn btn-dash-primary" id="btn-apply-payment-custom-filter" onclick="applyPaymentCustomFilter()">
                                                    <i class="ri-search-line me-1"></i>Tampilkan
                                                </button>
                                            </div>

                                            <!-- Close button -->
                                            <div>
                                                <button type="button" class="btn btn-ghost-secondary btn-sm" onclick="togglePaymentCustomFilter()" title="Tutup Filter Khusus">
                                                    <i class="ri-close-line fs-16 align-middle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body pt-3 pb-3">
                                    <div class="row g-4">
                                        <!-- Kolom Kiri: Hero & Bar Chart Tren Pendapatan (8 Kolom) -->
                                        <div class="col-xl-8 col-lg-7 border-end-xl" style="border-color: var(--border-subtle) !important;">
                                            <div class="row g-3 align-items-stretch mb-4">
                                                <div class="col-md-5">
                                                    <div class="tkb-hero-revenue h-100">
                                                        <div>
                                                            <div class="tkb-hero-revenue__label">
                                                                Pendapatan akumulasi (s/d {{ \Carbon\Carbon::now()->locale('id')->isoFormat('DD MMM YYYY') }})
                                                            </div>
                                                            <h3 class="tkb-hero-revenue__value tabular-nums mb-0" id="hero-pendapatan-akumulasi">Rp {{$jumlahPerTahun}}</h3>
                                                        </div>
                                                        <span class="tkb-hero-revenue__pill mt-3" id="hero-period-pill">Tahun lalu {{ date('Y') - 1 }}</span>
                                                        <svg class="tkb-hero-revenue__spark" viewBox="0 0 220 70" preserveAspectRatio="none" aria-hidden="true">
                                                            <defs>
                                                                <linearGradient id="tkbSparkFill" x1="0" y1="0" x2="0" y2="1">
                                                                    <stop offset="0%" stop-color="#6EE7B7" stop-opacity="0.35"/>
                                                                    <stop offset="100%" stop-color="#6EE7B7" stop-opacity="0"/>
                                                                </linearGradient>
                                                            </defs>
                                                            <path d="M0,66 L95,64 L135,58 L168,52 L192,62 L220,10 L220,70 L0,70 Z" fill="url(#tkbSparkFill)"/>
                                                            <path d="M0,66 L95,64 L135,58 L168,52 L192,62 L220,10" fill="none" stroke="rgba(167,243,208,0.85)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="p-3 h-100 d-flex flex-column justify-content-center tabular-nums sub-panel" id="harga">
                                                        Memuat rincian transaksi...
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ri-bar-chart-2-line fs-16" style="color: var(--brand);"></i>
                                                    <span class="fw-semibold fs-13" style="color: var(--text-primary);">Tren arus penerimaan kas</span>
                                                </div>
                                                <span class="fs-12" style="color: var(--text-tertiary);" id="badge-payment-period-text">Tahun Lalu</span>
                                            </div>

                                            <!-- Loading State -->
                                            <div id="payment-chart-loading" class="text-center py-5 d-none">
                                                <div class="spinner-border" style="color: var(--brand); width: 2.2rem; height: 2.2rem;" role="status">
                                                    <span class="visually-hidden">Memuat...</span>
                                                </div>
                                                <p class="text-muted mt-2 mb-0 fs-13">Mengambil data grafik...</p>
                                            </div>

                                            <div id="stacked_column_chart" class="apex-charts" data-colors='["--in-primary", "--in-light"]' dir="ltr"></div>
                                        </div>

                                        <!-- Kolom Kanan: Donut Chart Struktur Status Pembayaran (4 Kolom) -->
                                        <div class="col-xl-4 col-lg-5 ps-xl-3 d-flex flex-column justify-content-between">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                    <h5 class="fs-14 mb-0 fw-semibold d-flex align-items-center gap-2">
                                                        <i class="ri-pie-chart-line" style="color: var(--brand);"></i> Struktur status pembayaran
                                                    </h5>
                                                    <span class="badge-tint badge-tint-neutral" id="periode">
                                                        Tahun lalu
                                                    </span>
                                                </div>

                                                <div id="structure_widget" data-colors='["--in-primary", "--in-primary-rgb, 0.7", "--in-light"]' class="apex-charts" dir="ltr"></div>
                                            </div>

                                            <div class="px-1 pt-2 mt-auto">
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                    <div class="d-flex align-items-center">
                                                        <span class="d-inline-block rounded-circle me-2" style="width: 8px; height: 8px; background: var(--success);"></span>
                                                        <span class="fs-13 fw-medium" style="color: var(--text-secondary);">Terbayar</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold fs-13 tabular-nums" style="color: var(--text-primary);" id="terbayar">Rp 0</span>
                                                        <span class="badge-tint badge-tint-success" id="persenTerbayar">0,00%</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                    <div class="d-flex align-items-center">
                                                        <span class="d-inline-block rounded-circle me-2" style="width: 8px; height: 8px; background: var(--warning);"></span>
                                                        <span class="fs-13 fw-medium" style="color: var(--text-secondary);">Belum bayar</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold fs-13 tabular-nums" style="color: var(--text-primary);" id="belumTerbayar">Rp 0</span>
                                                        <span class="badge-tint badge-tint-neutral" id="persenBlmTerbayar">0,00%</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="d-inline-block rounded-circle me-2" style="width: 8px; height: 8px; background: var(--text-tertiary);"></span>
                                                        <span class="fs-13 fw-medium" style="color: var(--text-secondary);">Batal</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold fs-13 tabular-nums" style="color: var(--text-primary);" id="batal">Rp 0</span>
                                                        <span class="badge-tint badge-tint-neutral" id="persenBatal">0,00%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                    <!-- end row -->
                    <!-- end row -->

                                        



                    <!-- ============================================================== -->
                    <!-- GRAFIK PENJUALAN ITEM & PILIHAN PAJAK DARI ITEM                -->
                    <!-- POSISI: TEPAT DI BAWAH GRAFIK AKTIVITAS PEMBAYARAN             -->
                    <!-- ============================================================== -->
                    <div class="row motion-card-2">
                        <div class="col-12">
                            <div class="card" id="card-grafik-penjualan-item">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="tkb-sec-icon tkb-sec-icon--cyan">
                                                <i class="ri-pie-chart-2-line"></i>
                                            </div>
                                            <div>
                                                <h4 class="card-title mb-0 fs-16">Grafik penjualan item dan pilihan pajak</h4>
                                                <p class="tkb-card-subtitle mb-0">
                                                    Visualisasi penjualan per item produk beserta pilihan pajak (PPN, PPh, PPN dan PPh, tanpa pajak)
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Filter Toolbar -->
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 w-100 mt-2">
                                            <!-- Timeframe Segmented Control -->
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <div class="segmented-control" role="group">
                                                    <button type="button" class="segmented-item" onclick="switchItemTimeframe('today')" id="btn-item-today" title="Filter Hari Ini">
                                                        Hari Ini
                                                    </button>
                                                    <button type="button" class="segmented-item" onclick="switchItemTimeframe('week')" id="btn-item-week" title="Filter Minggu Ini">
                                                        Minggu Ini
                                                    </button>
                                                    <button type="button" class="segmented-item" onclick="switchItemTimeframe('month')" id="btn-item-month" title="Filter Bulan Ini">
                                                        Bulan Ini
                                                    </button>
                                                    <button type="button" class="segmented-item active" onclick="switchItemTimeframe('year')" id="btn-item-year" title="Filter Tahun Ini">
                                                        Tahun Ini
                                                    </button>
                                                    <button type="button" class="segmented-item" onclick="switchItemTimeframe('last_year')" id="btn-item-last_year" title="Filter Tahun Lalu">
                                                        Tahun Lalu
                                                    </button>
                                                </div>

                                                <button type="button" class="btn-custom-filter" onclick="toggleCustomFilterItem()" id="btn-item-custom-toggle" title="Filter Khusus Tanggal, Bulan, atau Rentang">
                                                    <i class="ri-filter-3-line"></i> Filter Kustom <i class="ri-arrow-down-s-line chevron-rotate" id="icon-toggle-item-custom"></i>
                                                </button>
                                            </div>

                                            <!-- Filter Pilihan Pajak & Metrik Penjualan -->
                                            <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                                                <!-- Dropdown Filter Pilihan Pajak -->
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <span class="fs-12 text-muted fw-medium"><i class="ri-shield-check-line me-1" style="color: var(--brand);"></i>Pajak:</span>
                                                    <select id="select-filter-item-pajak" class="dash-select" style="min-width: 175px;" onchange="onTaxFilterChanged(this.value)">
                                                        <option value="all">Semua Pilihan Pajak</option>
                                                        <option value="ppn">PPN</option>
                                                        <option value="pph">PPh</option>
                                                        <option value="ppn_pph">PPN & PPh</option>
                                                        <option value="tanpa_pajak">Tanpa Pajak</option>
                                                    </select>
                                                </div>

                                                <!-- Toggle Metrik Nilai vs Unit (Segmented) -->
                                                <div class="segmented-control" role="group">
                                                    <button type="button" class="segmented-item active btn-item-metric" id="btn-item-metric-nominal" onclick="switchItemMetric('nominal')">
                                                        <i class="ri-money-dollar-circle-line me-1"></i>Nilai (Rp)
                                                    </button>
                                                    <button type="button" class="segmented-item btn-item-metric" id="btn-item-metric-qty" onclick="switchItemMetric('qty')">
                                                        <i class="ri-shopping-cart-2-line me-1"></i>Jumlah Unit
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Custom Filter Panel (Expands when Filter Custom is clicked) -->
                                    <div id="item-custom-filter-box" class="custom-filter-panel mt-3 p-3" style="display: none;">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <span class="fs-12 fw-semibold text-muted text-uppercase" style="letter-spacing: 0.06em;">
                                                    <i class="ri-filter-line me-1" style="color: var(--brand);"></i>Pilih Spesifik:
                                                </span>
                                                
                                                <!-- Mode selector segmented -->
                                                <div class="segmented-control" role="group">
                                                    <button type="button" class="segmented-item active" id="btn-item-mode-date" onclick="setItemCustomMode('date')">
                                                        <i class="ri-calendar-event-line"></i>Tanggal
                                                    </button>
                                                    <button type="button" class="segmented-item" id="btn-item-mode-month" onclick="setItemCustomMode('month')">
                                                        <i class="ri-calendar-2-line"></i>Bulan
                                                    </button>
                                                    <button type="button" class="segmented-item" id="btn-item-mode-year" onclick="setItemCustomMode('year')">
                                                        <i class="ri-calendar-check-line"></i>Tahun
                                                    </button>
                                                    <button type="button" class="segmented-item" id="btn-item-mode-range" onclick="setItemCustomMode('range')">
                                                        <i class="ri-calendar-schedule-line"></i>Rentang
                                                    </button>
                                                </div>

                                                <!-- Form Control: Tanggal -->
                                                <div id="wrapper-item-filter-date" class="d-inline-flex align-items-center">
                                                    <input type="date" class="form-control form-control-sm dash-input" id="input-item-filter-date" value="{{ date('Y-m-d') }}">
                                                </div>

                                                <!-- Form Control: Bulan -->
                                                <div id="wrapper-item-filter-month" class="d-none align-items-center gap-1">
                                                    <select class="form-select form-select-sm dash-select" id="select-item-filter-month" style="min-width: 125px;">
                                                        @php
                                                            $bulanIndo = [
                                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                            ];
                                                            $curMonth = (int)date('n');
                                                            $curYear = (int)date('Y');
                                                        @endphp
                                                        @foreach($bulanIndo as $num => $nama)
                                                            <option value="{{ $num }}" {{ $num == $curMonth ? 'selected' : '' }}>{{ $nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select class="form-select form-select-sm dash-select" id="select-item-filter-month-year" style="min-width: 95px;">
                                                        @foreach($availableYears as $yr)
                                                            <option value="{{ $yr }}" {{ $yr == $curYear ? 'selected' : '' }}>{{ $yr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Form Control: Tahun -->
                                                <div id="wrapper-item-filter-year" class="d-none align-items-center">
                                                    <select class="form-select form-select-sm dash-select" id="select-item-filter-year" style="min-width: 105px;">
                                                        @foreach($availableYears as $yr)
                                                            <option value="{{ $yr }}" {{ $yr == $curYear ? 'selected' : '' }}>{{ $yr }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="wrapper-item-filter-range" class="d-none align-items-center gap-1">
                                                    <input type="date" class="form-control form-control-sm dash-input" id="input-item-filter-start">
                                                    <span class="text-muted fs-12">s/d</span>
                                                    <input type="date" class="form-control form-control-sm dash-input" id="input-item-filter-end">
                                                </div>

                                                <!-- Tampilkan button -->
                                                <button type="button" class="btn btn-dash-primary" id="btn-apply-item-filter" onclick="applyCustomFilterItem()">
                                                    <i class="ri-search-line me-1"></i>Tampilkan
                                                </button>
                                            </div>

                                            <!-- Close button -->
                                            <div>
                                                <button type="button" class="btn btn-ghost-secondary btn-sm" onclick="toggleCustomFilterItem()" title="Tutup Filter Khusus">
                                                    <i class="ri-close-line fs-16 align-middle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body pt-3 pb-3">
                                    <!-- Periode Aktif Banner -->
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <span class="text-muted fs-13">Periode Aktif:</span>
                                            <span class="badge-tint badge-tint-brand" id="badge-item-period-text">
                                                <i class="ri-calendar-line"></i> Tahun Ini ({{ date('Y') }})
                                            </span>
                                            <span class="badge-tint badge-tint-success" id="badge-item-tax-text">
                                                <i class="ri-shield-check-line"></i> Semua Pilihan Pajak
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-muted fs-12" id="badge-item-count-text">Memuat item...</span>
                                        </div>
                                    </div>

                                    <!-- 4 Clean Finance KPI Cards -->
                                    <div class="row g-3 mb-4">
                                        <!-- Stat 1: Total Unit Terjual -->
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="stat-box d-flex flex-column justify-content-between h-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="stat-overline">Total unit terjual</span>
                                                    <span class="badge-tint badge-tint-brand">Volume</span>
                                                </div>
                                                <div>
                                                    <div class="stat-value-big tabular-nums" id="stat-item-qty">0 unit</div>
                                                    <div class="stat-caption">Kuantitas produk keluar</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stat 2: Nilai Penjualan -->
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="stat-box d-flex flex-column justify-content-between h-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="stat-overline">Total nilai penjualan</span>
                                                    <span class="badge-tint badge-tint-emerald">Omzet</span>
                                                </div>
                                                <div>
                                                    <div class="stat-value-big tabular-nums" id="stat-item-nominal">Rp 0</div>
                                                    <div class="stat-caption">Akumulasi penjualan item</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stat 3: Item Terlaris -->
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="stat-box d-flex flex-column justify-content-between h-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="stat-overline">Item terlaris</span>
                                                    <span class="badge-tint badge-tint-brand">Top item</span>
                                                </div>
                                                <div>
                                                    <div class="stat-value-big text-truncate" style="font-size: 20px;" id="stat-item-top" title="-">-</div>
                                                    <div class="stat-caption">Kontribusi penjualan tertinggi</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Stat 4: Pajak Dominan -->
                                        <div class="col-xl-3 col-sm-6">
                                            <div class="stat-box d-flex flex-column justify-content-between h-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="stat-overline">Pilihan pajak dominan</span>
                                                    <span class="badge-tint badge-tint-warning">Pajak</span>
                                                </div>
                                                <div>
                                                    <div class="stat-value-big text-truncate" style="font-size: 20px;" id="stat-item-dominant-tax" title="-">-</div>
                                                    <div class="stat-caption">Skema perpajakan dominan</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Main Content: Dua Kolom (Left: Grafik Penjualan Item, Right: Donut Pilihan Pajak) -->
                                    <div class="row g-3">
                                        <!-- Left Column: Grafik Penjualan per Item (Top Items) -->
                                        <div class="col-xl-8">
                                            <div class="p-3 h-100 d-flex flex-column sub-panel">
                                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                    <h5 class="fs-15 mb-0 fw-semibold text-light d-flex align-items-center">
                                                        <i class="ri-bar-chart-fill me-2" style="color: var(--brand);"></i>
                                                        Grafik Penjualan per Item
                                                    </h5>
                                                    <span class="badge-tint badge-tint-brand" id="badge-total-item-shown">
                                                        Top Item
                                                    </span>
                                                </div>

                                                <!-- Loading Indicator -->
                                                <div id="item-chart-loading" class="text-center py-5 d-none">
                                                    <div class="spinner-border" style="color: var(--brand); width: 2.2rem; height: 2.2rem;" role="status">
                                                        <span class="visually-hidden">Memuat data...</span>
                                                    </div>
                                                    <p class="text-muted mt-2 mb-0 fs-13">Memperbarui grafik item...</p>
                                                </div>

                                                <!-- ApexCharts Container -->
                                                <div id="chart_penjualan_item" class="apex-charts" dir="ltr"></div>

                                                <!-- Empty State -->
                                                <div id="item-chart-empty" class="text-center py-5 d-none">
                                                    <i class="ri-inbox-line fs-36 text-muted"></i>
                                                    <p class="text-muted mt-2 mb-0 fs-13">Tidak ada data transaksi penjualan item pada periode dan filter pajak ini.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column: Grafik Donut Pilihan Pajak Item -->
                                        <div class="col-xl-4">
                                            <div class="p-3 h-100 d-flex flex-column justify-content-between sub-panel">
                                                <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                    <h5 class="fs-15 mb-0 fw-semibold text-light d-flex align-items-center">
                                                        <i class="ri-donut-chart-fill me-2" style="color: var(--chart-series-2);"></i>
                                                        Distribusi Pilihan Pajak
                                                    </h5>
                                                    <span class="badge-tint badge-tint-success" id="badge-total-pajak-count">
                                                        Pilihan Pajak
                                                    </span>
                                                </div>

                                                <!-- Donut Chart Container -->
                                                <div class="d-flex align-items-center justify-content-center flex-grow-1 py-3">
                                                    <div id="chart_pilihan_pajak_donut" class="apex-charts w-100" dir="ltr"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bottom: Rincian Lengkap Item Penjualan (Tabel Presisi & Rapi) -->
                                    <div class="mt-4">
                                        <div class="dash-table-wrapper">
                                            <div class="py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom" style="border-color: var(--border-subtle) !important; background: var(--bg-surface);">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex align-items-center justify-content-center rounded" style="background: var(--brand-subtle); color: var(--brand); width: 32px; height: 32px; border-radius: var(--radius-sm) !important;">
                                                        <i class="ri-file-list-3-line fs-16"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fs-14 mb-0 fw-semibold text-light">Tabel Rincian Penjualan per Item</h6>
                                                        <p class="text-muted fs-12 mb-0">Rincian performa item dari Manajemen Item beserta pilihan pajak dan kontribusinya</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge-tint badge-tint-brand" id="badge-table-total-item">
                                                        0 Item
                                                    </span>
                                                    <span class="badge-tint badge-tint-success tabular-nums" id="badge-table-grand-total">
                                                        Rp 0
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="table-responsive" id="table-rincian-item-wrapper" style="max-height: 380px; overflow-y: auto;">
                                                <table class="dash-table align-middle" id="table-rincian-item">
                                                    <thead style="position: sticky; top: 0; z-index: 2;">
                                                        <tr>
                                                            <th scope="col" style="width: 50px;" class="text-center">#</th>
                                                            <th scope="col">Nama Item (Manajemen Item)</th>
                                                            <th scope="col" class="text-center" style="width: 150px;">Pilihan Pajak</th>
                                                            <th scope="col" class="text-center" style="width: 140px;">Total Transaksi</th>
                                                            <th scope="col" class="text-center" style="width: 140px;">Jumlah Terjual</th>
                                                            <th scope="col" class="text-end" style="width: 190px;">Total Penjualan</th>
                                                            <th scope="col" class="text-end" style="width: 140px; padding-right: 18px;">Kontribusi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-item-detail-list">
                                                        <!-- Dynamic rows inserted by JS -->
                                                    </tbody>
                                                    <tfoot id="tfoot-item-detail" style="position: sticky; bottom: 0; z-index: 2;">
                                                        <tr class="fw-bold text-light fs-13">
                                                            <td colspan="3" class="text-uppercase text-muted fs-11" style="letter-spacing: 0.06em;">Total Keseluruhan</td>
                                                            <td class="text-center tabular-nums" id="tfoot-total-transaksi" style="color: var(--text-tertiary);">0 Transaksi</td>
                                                            <td class="text-center tabular-nums" id="tfoot-total-qty" style="color: var(--success-text);">0 Unit</td>
                                                            <td class="text-end tabular-nums text-white" id="tfoot-total-nominal">Rp 0</td>
                                                            <td class="text-end tabular-nums" style="color: var(--success-text); padding-right: 18px;">100%</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-4 motion-card-3">
                        <div class="col-12">
                            <div class="card" id="card-grafik-pembelian-invoice">
                                <div class="card-header border-0 pb-0">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="tkb-sec-icon tkb-sec-icon--amber">
                                                <i class="ri-store-2-line"></i>
                                            </div>
                                            <div>
                                                <h4 class="card-title mb-0 fs-16">
                                                    <span id="pembelian-chart-title">Grafik pembelian belum dibayar per vendor</span>
                                                </h4>
                                                <p class="tkb-card-subtitle mb-0" id="pembelian-chart-subtitle">Ringkasan sisa tagihan pembelian belum dibayar per nama vendor dan pengenaan pajak</p>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap justify-content-end align-items-center gap-2 ms-auto">
                                            <!-- Toggle Sumbu: Nama Vendor vs Jenis Invoice (Segmented Control) -->
                                            <div class="segmented-control" role="group" aria-label="Tampilan Sumbu">
                                                <button type="button" class="segmented-item active" id="btn-pembelian-group-vendor" onclick="setPembelianGroupBy('vendor')" title="Tampilkan per Nama Vendor">
                                                    <i class="ri-store-2-line me-1"></i>Nama Vendor
                                                </button>
                                                <button type="button" class="segmented-item" id="btn-pembelian-group-jenis" onclick="setPembelianGroupBy('jenis')" title="Tampilkan per Jenis Invoice">
                                                    <i class="ri-file-list-3-line me-1"></i>Jenis Invoice
                                                </button>
                                            </div>

                                            <select id="filter-jenis-invoice-pembelian" class="dash-select" style="min-width: 175px;" onchange="onPembelianFilterChanged()">
                                                <option value="all">Semua Jenis Invoice</option>
                                                <option value="Barang">Barang</option>
                                                <option value="Jasa">Jasa</option>
                                                <option value="Barang dan Jasa">Barang dan Jasa</option>
                                                <option value="Jasa Iklan">Jasa Iklan</option>
                                                <option value="Tanpa Pajak">Tanpa Pajak</option>
                                            </select>
                                            <select id="filter-pajak-pembelian" class="dash-select" style="min-width: 175px;" onchange="onPembelianFilterChanged()">
                                                <option value="all">Semua Pilihan Pajak</option>
                                                <option value="ppn">Menggunakan PPN</option>
                                                <option value="pph">Menggunakan PPh</option>
                                                <option value="ppn_pph">PPN dan PPh</option>
                                                <option value="tanpa_pajak">Tanpa Pajak</option>
                                            </select>
                                            <select id="filter-status-pembelian" class="dash-select" style="min-width: 145px;" onchange="onPembelianFilterChanged()" aria-label="Filter status pembayaran">
                                                <option value="belum_lunas">Belum Lunas</option>
                                                <option value="all">Semua Status</option>
                                                <option value="lunas">Lunas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 align-items-stretch">
                                        <!-- Kolom Kiri: Grafik Pembelian (Horizontal Bar Chart) -->
                                        <div class="col-xl-7 col-lg-7">
                                            <div id="vendor-single-detail-header" class="mb-2 d-none"></div>
                                            <!-- Grafik Utama (Semua Vendor atau Global Jenis Invoice) -->
                                            <div id="chart-pembelian-invoice" class="apex-charts" dir="ltr"></div>

                                            <!-- Wadah Dua Grafik Ketika Single Vendor Dipilih -->
                                            <div id="vendor-single-charts-container" class="d-none">
                                                <div class="row g-2">
                                                    <!-- Grafik 1: Status Pembayaran (3 Batang) -->
                                                    <div class="col-md-6 col-12">
                                                        <div class="p-3 rounded-3 h-100 sub-panel">
                                                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                                <div class="d-flex align-items-center gap-1.5">
                                                                    <i class="ri-bar-chart-2-line fs-14" style="color: var(--brand);"></i>
                                                                    <span class="fs-12 fw-semibold text-light">Status Pembayaran</span>
                                                                </div>
                                                                <span class="badge-tint badge-tint-brand">Ringkasan</span>
                                                            </div>
                                                            <div id="chart-pembelian-single-status" class="apex-charts" dir="ltr"></div>
                                                        </div>
                                                    </div>

                                                    <!-- Grafik 2: Berdasarkan Jenis Invoice Vendor -->
                                                    <div class="col-md-6 col-12">
                                                        <div class="p-3 rounded-3 h-100 sub-panel">
                                                            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom" style="border-color: var(--border-subtle) !important;">
                                                                <div class="d-flex align-items-center gap-1.5">
                                                                    <i class="ri-file-list-3-line fs-14" style="color: var(--chart-series-2);"></i>
                                                                    <span class="fs-12 fw-semibold text-light">Jenis Invoice Digunakan</span>
                                                                </div>
                                                                <span class="badge-tint badge-tint-brand" id="badge-vendor-jenis-count">0 Jenis</span>
                                                            </div>
                                                            <div id="chart-pembelian-single-jenis" class="apex-charts" dir="ltr"></div>
                                                            <div id="chart-pembelian-single-jenis-empty" class="text-center text-muted py-5 fs-12 d-none">
                                                                Belum ada data jenis invoice untuk vendor ini.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="chart-pembelian-invoice-empty" class="text-center text-muted py-5 d-none">
                                                <i class="ri-inbox-line fs-32 text-muted"></i>
                                                <p class="mt-2 mb-0 fs-13">Belum ada data pembelian yang belum dibayar untuk filter ini.</p>
                                            </div>
                                        </div>

                                        <!-- Kolom Kanan: Daftar Tagihan Vendor (Card List Style) -->
                                        <div class="col-xl-5 col-lg-5">
                                            <div class="dash-table-wrapper d-flex flex-column h-100">
                                                <!-- Header Daftar -->
                                                <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom tkb-vendor-header" style="border-color: var(--border-subtle) !important; background: var(--bg-surface);">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="tkb-sec-icon tkb-sec-icon--emerald" style="width: 30px; height: 30px; min-width: 30px; font-size: 15px; border-radius: 8px;">
                                                             <i class="ri-file-list-3-line"></i>
                                                        </div>
                                                        <span class="fw-bold fs-13.5 text-nowrap" style="color: var(--text-primary);">Daftar tagihan vendor</span>
                                                        <span class="badge-tint badge-tint-brand fs-11" id="badge-vendor-table-count">0 vendor</span>
                                                    </div>
                                                    <button type="button" class="btn-custom-filter py-1 px-2.5 fs-12" id="btn-semua-vendor" onclick="resetToAllVendors()" title="Tampilkan grafik semua vendor yang belum terbayar">
                                                        <i class="ri-check-line fs-12"></i>Semua vendor
                                                    </button>
                                                </div>

                                                <!-- List Scrollable -->
                                                <div class="vendor-table-scroll flex-grow-1 overflow-auto" style="max-height: 380px;">
                                                    <div class="tkb-vendor-list" id="vendor-tagihan-tbody">
                                                        <!-- Injected by renderVendorTable() -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                    <!-- end row -->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        @endsection


@section('plugins')
    <!-- apexcharts -->
    <script src="{{asset('libs/apexcharts/apexcharts.min.js')}}"></script>


    <!-- Vector map-->
    <script src="{{asset('libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!-- Dashboard init -->
    <script>
        // ==========================================================================
        // GLOBAL INDONESIAN NUMBER & CURRENCY FORMATTERS (Intl.NumberFormat id-ID)
        // ==========================================================================
        window.idNumberFormatter = window.idNumberFormatter || new Intl.NumberFormat('id-ID');
        var idNumberFormatter = window.idNumberFormatter;

        function formatRupiah(val) {
            if (val === null || val === undefined || isNaN(val)) return 'Rp 0';
            const num = Number(val);
            const abs = Math.abs(num);
            const formatted = idNumberFormatter.format(abs);
            return num < 0 ? `\u2212Rp ${formatted}` : `Rp ${formatted}`;
        }

        function formatRupiahCompact(val) {
            if (val === null || val === undefined || isNaN(val)) return 'Rp 0';
            const num = Number(val);
            const abs = Math.abs(num);
            const prefix = num < 0 ? '\u2212Rp ' : 'Rp ';
            if (abs >= 1000000000) {
                const v = (abs / 1000000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
                return `${prefix}${v} M`;
            }
            if (abs >= 1000000) {
                const v = (abs / 1000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
                return `${prefix}${v} jt`;
            }
            if (abs >= 1000) {
                const v = (abs / 1000).toLocaleString('id-ID', { maximumFractionDigits: 0 });
                return `${prefix}${v} rb`;
            }
            return `${prefix}${idNumberFormatter.format(abs)}`;
        }

        function animateCountUp(element, endVal, duration = 650, isCurrency = true) {
            if (!element) return;
            const prefersReducedMotion = (typeof window !== 'undefined' && typeof window.matchMedia === 'function')
                ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
                : false;
            if (prefersReducedMotion) {
                element.textContent = isCurrency ? formatRupiah(endVal) : idNumberFormatter.format(endVal);
                return;
            }
            const currentText = element.textContent || '0';
            const startVal = parseFloat(currentText.replace(/[^0-9.-]/g, '')) || 0;
            const startTime = performance.now();
            function frame(now) {
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                const current = Math.round(startVal + (endVal - startVal) * ease);
                element.textContent = isCurrency ? formatRupiah(current) : idNumberFormatter.format(current);
                if (progress < 1) {
                    if (typeof requestAnimationFrame === 'function') {
                        requestAnimationFrame(frame);
                    } else {
                        setTimeout(frame, 16);
                    }
                } else {
                    element.textContent = isCurrency ? formatRupiah(endVal) : idNumberFormatter.format(endVal);
                }
            }
            if (typeof requestAnimationFrame === 'function') {
                requestAnimationFrame(frame);
            } else {
                setTimeout(frame, 16);
            }
        }

        window.formatRupiah = formatRupiah;
        window.formatRupiahCompact = formatRupiahCompact;
        window.animateCountUp = animateCountUp;

        window.hourArrayJS = {!! json_encode($hourArray) !!};
        window.dateArrayJS = {!! json_encode($dateArray) !!};
        window.dateArrayLastYearJS = {!! json_encode($dateArrayLastYear) !!};
        window.weekArrayJS = {!! json_encode($weekArray) !!};
        window.monthArrayJS = {!! json_encode($monthArray) !!};
        window.monthArrayLastYearJS = {!! json_encode($monthArrayLastYear) !!};

        window.totalJamArrayJS = {!! json_encode($totalJamArray) !!};
        window.totalHarianArrayJS = {!! json_encode($totalHarianArray) !!};
        window.totalHarianLastYearArrayJS = {!! json_encode($totalHarianArrayLastYear) !!};

        window.totalMingguanArrayJS = {!! json_encode($totalMingguanArray) !!};
        window.totalBulananArrayJS = {!! json_encode($totalBulananArray) !!};
        window.totalBulananArrayLastYearJS = {!! json_encode($totalBulananArrayLastYear) !!};

        window.totalPerHari = {!! json_encode($jumlahPerHari) !!};
        window.totalPerMinggu = {!! json_encode($jumlahPerMinggu) !!};
        window.totalPerBulan = {!! json_encode($jumlahPerBulan) !!};
        window.totalPerTahun = {!! json_encode($jumlahPerTahun) !!};

        // Week
        window.datasWeekJS = {!! json_encode($datasInWeek) !!};
        window.percentInWeekJS = {!! json_encode($percentageInWeek) !!};
        window.percentNotPaidWeekJS = {!! json_encode($percentageNotPaidWeek) !!};
        window.percentCanceledWeekJS = {!! json_encode($percentageCanceledWeek) !!};

        // Month
        window.datasMonthJS = {!! json_encode($datasInMonth) !!};
        window.percentInMonthJS = {!! json_encode($percentageInMonth) !!};
        window.percentNotPaidMonthJS = {!! json_encode($percentageNotPaidMonth) !!};
        window.percentCanceledMonthJS = {!! json_encode($percentageCanceledMonth) !!};

        // Year
        window.datasYearJS = {!! json_encode($datasInYear) !!};
        window.percentInYearJS = {!! json_encode($percentageInYear) !!};
        window.percentNotPaidYearJS = {!! json_encode($percentageNotPaidYear) !!};
        window.percentCanceledYearJS = {!! json_encode($percentageCanceledYear) !!};

        // Last Year (Tahun Lalu)
        window.datasLastYearJS = {!! json_encode($datasInLastYear) !!};
        window.percentInLastYearJS = {!! json_encode($percentageInLastYear) !!};
        window.percentNotPaidLastYearJS = {!! json_encode($percentageNotPaidLastYear) !!};
        window.percentCanceledLastYearJS = {!! json_encode($percentageCanceledLastYear) !!};

        window.dataPembelianBarangJS = {!! json_encode($dataPembelianBarang) !!};
        window.dataPembelianInvoiceJS = {!! json_encode($dataPembelianInvoice) !!};
        window.daftarTagihanVendorJS = {!! json_encode($daftarTagihanVendor ?? []) !!};
        window.vendorPembelianOptionsJS = [...new Set(window.daftarTagihanVendorJS.map(row => row.nama_vendor).filter(Boolean))].sort();
        window.netIncomeDataJS = {!! json_encode($netIncomeData ?? []) !!};

        // Local aliases for backwards compatibility in this script block
        var hourArrayJS = window.hourArrayJS;
        var dateArrayJS = window.dateArrayJS;
        var dateArrayLastYearJS = window.dateArrayLastYearJS;
        var weekArrayJS = window.weekArrayJS;
        var monthArrayJS = window.monthArrayJS;
        var monthArrayLastYearJS = window.monthArrayLastYearJS;
        var totalJamArrayJS = window.totalJamArrayJS;
        var totalHarianArrayJS = window.totalHarianArrayJS;
        var totalHarianLastYearArrayJS = window.totalHarianLastYearArrayJS;
        var totalMingguanArrayJS = window.totalMingguanArrayJS;
        var totalBulananArrayJS = window.totalBulananArrayJS;
        var totalBulananArrayLastYearJS = window.totalBulananArrayLastYearJS;
        var totalPerHari = window.totalPerHari;
        var totalPerMinggu = window.totalPerMinggu;
        var totalPerBulan = window.totalPerBulan;
        var totalPerTahun = window.totalPerTahun;
        var datasWeekJS = window.datasWeekJS;
        var percentInWeekJS = window.percentInWeekJS;
        var percentNotPaidWeekJS = window.percentNotPaidWeekJS;
        var percentCanceledWeekJS = window.percentCanceledWeekJS;
        var datasMonthJS = window.datasMonthJS;
        var percentInMonthJS = window.percentInMonthJS;
        var percentNotPaidMonthJS = window.percentNotPaidMonthJS;
        var percentCanceledMonthJS = window.percentCanceledMonthJS;
        var datasYearJS = window.datasYearJS;
        var percentInYearJS = window.percentInYearJS;
        var percentNotPaidYearJS = window.percentNotPaidYearJS;
        var percentCanceledYearJS = window.percentCanceledYearJS;
        var datasLastYearJS = window.datasLastYearJS;
        var percentInLastYearJS = window.percentInLastYearJS;
        var percentNotPaidLastYearJS = window.percentNotPaidLastYearJS;
        var percentCanceledLastYearJS = window.percentCanceledLastYearJS;
        var dataPembelianBarangJS = window.dataPembelianBarangJS;
        var dataPembelianInvoiceJS = window.dataPembelianInvoiceJS;
        var daftarTagihanVendorJS = window.daftarTagihanVendorJS;
        var vendorPembelianOptionsJS = window.vendorPembelianOptionsJS;

        let pembelianInvoiceChart = null;
        let singleStatusChart = null;
        let singleJenisChart = null;
        let pembelianGroupBy = 'vendor';
        let selectedVendorFilter = 'all';

        function destroyAllPembelianCharts() {
            if (pembelianInvoiceChart) {
                pembelianInvoiceChart.destroy();
                pembelianInvoiceChart = null;
            }
            if (singleStatusChart) {
                singleStatusChart.destroy();
                singleStatusChart = null;
            }
            if (singleJenisChart) {
                singleJenisChart.destroy();
                singleJenisChart = null;
            }
        }

        function setPembelianGroupBy(mode) {
            pembelianGroupBy = mode;
            const btnVendor = document.getElementById('btn-pembelian-group-vendor');
            const btnJenis = document.getElementById('btn-pembelian-group-jenis');
            if (btnVendor && btnJenis) {
                if (mode === 'vendor') {
                    btnVendor.classList.add('active');
                    btnJenis.classList.remove('active');
                } else {
                    btnVendor.classList.remove('active');
                    btnJenis.classList.add('active');
                }
            }
            const titleEl = document.getElementById('pembelian-chart-title');
            const subEl = document.getElementById('pembelian-chart-subtitle');
            if (titleEl) {
                titleEl.textContent = mode === 'vendor' 
                    ? 'Grafik Pembelian belum Dibayar per Vendor'
                    : 'Grafik Pembelian berdasarkan Jenis Invoice';
            }
            if (subEl) {
                subEl.textContent = mode === 'vendor'
                    ? 'Ringkasan sisa tagihan pembelian belum dibayar per nama vendor dan pengenaan pajak.'
                    : 'Ringkasan transaksi pembelian belum dibayar ke vendor berdasarkan kategori invoice dan pengenaan pajak.';
            }
            renderPembelianInvoiceChart();
        }

        function onPembelianFilterChanged() {
            if (selectedVendorFilter !== 'all') {
                const currentVendors = getFilteredVendorSummary().map(v => v.nama_vendor);
                if (!currentVendors.includes(selectedVendorFilter)) {
                    selectedVendorFilter = 'all';
                }
            }
            renderPembelianInvoiceChart();
        }

        function getFilteredPembelianRows(forceUnpaid = false) {
            const filterEl = document.getElementById('filter-pajak-pembelian');
            const jenisFilterEl = document.getElementById('filter-jenis-invoice-pembelian');
            const filter = filterEl ? filterEl.value : 'all';
            const jenisFilter = jenisFilterEl ? jenisFilterEl.value : 'all';
            const statusFilterEl = document.getElementById('filter-status-pembelian');
            const statusFilter = forceUnpaid ? 'belum_lunas' : (statusFilterEl ? statusFilterEl.value : 'belum_lunas');

            return (dataPembelianInvoiceJS || []).filter(row => {
                if (jenisFilter !== 'all' && row.label !== jenisFilter) return false;
                const sisaTagihan = Number(row.total_nominal) || 0;
                if (statusFilter === 'belum_lunas' && sisaTagihan <= 0) return false;
                if (statusFilter === 'lunas' && sisaTagihan > 0) return false;
                if (filter === 'tanpa_pajak') return row.total_ppn === 0 && row.total_pph === 0;
                if (filter === 'ppn_pph') return row.total_ppn > 0 && row.total_pph > 0;
                if (filter === 'ppn') return row.total_ppn > 0 && row.total_pph === 0;
                if (filter === 'pph') return row.total_pph > 0 && row.total_ppn === 0;
                return true;
            });
        }

        function getFilteredVendorSummary() {
            const rows = getFilteredPembelianRows();
            const vendorMap = {};

            rows.forEach(item => {
                const v = item.vendor || 'Tanpa Vendor';
                if (!vendorMap[v]) {
                    vendorMap[v] = {
                        nama_vendor: v,
                        total_pembelian: 0,
                        terbayar: 0,
                        sisa_tagihan: 0,
                        total_transaksi: 0,
                        jenis_breakdown: {}
                    };
                }
                vendorMap[v].total_pembelian += (Number(item.total_pembelian) || 0);
                vendorMap[v].terbayar += (Number(item.terbayar) || 0);
                vendorMap[v].sisa_tagihan += (Number(item.total_nominal) || 0);
                vendorMap[v].total_transaksi += (Number(item.total_transaksi) || 0);

                const lbl = item.label || 'Tanpa Jenis';
                if (!vendorMap[v].jenis_breakdown[lbl]) {
                    vendorMap[v].jenis_breakdown[lbl] = {
                        label: lbl,
                        total_nominal: 0,
                        total_pembelian: 0,
                        terbayar: 0,
                        total_transaksi: 0
                    };
                }
                vendorMap[v].jenis_breakdown[lbl].total_nominal += (Number(item.total_nominal) || 0);
                vendorMap[v].jenis_breakdown[lbl].total_pembelian += (Number(item.total_pembelian) || 0);
                vendorMap[v].jenis_breakdown[lbl].terbayar += (Number(item.terbayar) || 0);
                vendorMap[v].jenis_breakdown[lbl].total_transaksi += (Number(item.total_transaksi) || 0);
            });

            const statusFilterEl = document.getElementById('filter-status-pembelian');
            const statusFilter = statusFilterEl ? statusFilterEl.value : 'belum_lunas';
            return Object.values(vendorMap)
                // Keep paid vendors visible when the table is filtered to paid/all.
                .filter(v => statusFilter !== 'belum_lunas' || v.sisa_tagihan > 0)
                .sort((a, b) => {
                    const amountA = statusFilter === 'lunas' ? a.terbayar : a.sisa_tagihan;
                    const amountB = statusFilter === 'lunas' ? b.terbayar : b.sisa_tagihan;
                    return amountB - amountA || b.total_pembelian - a.total_pembelian;
                });
        }

        function resetToAllVendors() {
            if (pembelianGroupBy !== 'vendor') {
                setPembelianGroupBy('vendor');
            }
            selectedVendorFilter = 'all';
            const titleEl = document.getElementById('pembelian-chart-title');
            const subEl = document.getElementById('pembelian-chart-subtitle');
            if (titleEl) {
                titleEl.textContent = 'Grafik Pembelian belum Dibayar per Vendor';
            }
            if (subEl) {
                subEl.textContent = 'Ringkasan sisa tagihan pembelian belum dibayar per nama vendor dan pengenaan pajak.';
            }
            renderPembelianInvoiceChart();
        }

        function renderPembelianInvoiceChart() {
            renderVendorTable();
            const filter = document.getElementById('filter-pajak-pembelian') ? document.getElementById('filter-pajak-pembelian').value : 'all';
            const jenisFilter = document.getElementById('filter-jenis-invoice-pembelian') ? document.getElementById('filter-jenis-invoice-pembelian').value : 'all';
            const vendorFilter = selectedVendorFilter;

            const singleContainer = document.getElementById('vendor-single-charts-container');
            const mainChartEl = document.getElementById('chart-pembelian-invoice');
            const emptyEl = document.getElementById('chart-pembelian-invoice-empty');
            const headerEl = document.getElementById('vendor-single-detail-header');

            // MODE 1: Single Vendor (Ketika vendor tertentu dipilih / diklik di tabel)
            if (pembelianGroupBy === 'vendor' && vendorFilter !== 'all') {
                const vendorList = getFilteredVendorSummary();
                const selectedVendor = vendorList.find(r => r.nama_vendor === vendorFilter) || (daftarTagihanVendorJS || []).find(r => r.nama_vendor === vendorFilter);
                const totalPembelian = selectedVendor ? Number(selectedVendor.total_pembelian) || 0 : 0;
                const terbayar = selectedVendor ? Number(selectedVendor.terbayar) || 0 : 0;
                const sisa = selectedVendor ? Number(selectedVendor.sisa_tagihan) || 0 : 0;

                const terbayarPct = totalPembelian > 0 ? ((terbayar / totalPembelian) * 100).toFixed(1) : '0.0';
                const sisaPct = totalPembelian > 0 ? ((sisa / totalPembelian) * 100).toFixed(1) : '0.0';

                if (headerEl) {
                    headerEl.classList.remove('d-none');
                    headerEl.innerHTML = `
                        <div class="d-flex flex-wrap align-items-center fs-13 py-2 px-3 rounded mb-2 sub-panel">
                            <span class="fw-bold text-white fs-13 me-2">${vendorFilter}</span>
                            <span class="text-muted">Total Pembelian:</span>&nbsp;<span class="fw-semibold text-white me-3 tabular-nums">${formatRupiah(totalPembelian)}</span>
                            <span class="badge-tint badge-tint-success me-2"><i class="ri-check-line me-1"></i>Terbayar: ${formatRupiah(terbayar)} (${terbayarPct}%)</span>
                            <span class="badge-tint badge-tint-warning"><i class="ri-time-line me-1"></i>Sisa Tagihan: ${formatRupiah(sisa)} (${sisaPct}%)</span>
                        </div>
                    `;
                }

                const singleValues = [totalPembelian, terbayar, sisa];
                const hasData = singleValues.some(v => v > 0);

                destroyAllPembelianCharts();

                if (emptyEl) emptyEl.classList.toggle('d-none', hasData);
                if (mainChartEl) mainChartEl.classList.add('d-none');

                if (!hasData) {
                    if (singleContainer) singleContainer.classList.add('d-none');
                    return;
                }

                if (singleContainer) singleContainer.classList.remove('d-none');

                // 1. Render Grafik 3 Batang: Status Pembayaran
                singleStatusChart = new ApexCharts(document.querySelector('#chart-pembelian-single-status'), {
                    chart: {
                        type: 'bar',
                        height: 285,
                        toolbar: { show: false },
                        animations: { enabled: true, speed: 300 }
                    },
                    plotOptions: {
                        bar: {
                            distributed: true,
                            borderRadius: 4,
                            borderRadiusApplication: 'end',
                            columnWidth: '46%',
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetY: -20,
                        formatter: function (val) {
                            return formatRupiahCompact(val);
                        },
                        style: {
                            fontSize: '11px',
                            fontWeight: '600',
                            colors: ['#E8EBF0']
                        }
                    },
                    series: [{
                        name: 'Nominal',
                        data: singleValues
                    }],
                    xaxis: {
                        categories: ['Total Pembelian', 'Sudah Dibayar', 'Sisa Tagihan'],
                        labels: {
                            style: { colors: '#9AA4B2', fontSize: '11px', fontWeight: 500 }
                        },
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return formatRupiahCompact(value);
                            },
                            style: { colors: '#6B7686', fontSize: '10px' }
                        }
                    },
                    grid: {
                        borderColor: 'rgba(255, 255, 255, 0.05)',
                        strokeDashArray: 3
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: function (value) {
                                return formatRupiah(value);
                            }
                        }
                    },
                    colors: ['#6B73F0', '#34C28B', '#E3A33B'],
                    legend: { show: false }
                });
                singleStatusChart.render();

                // 2. Render Grafik Berdasarkan Jenis Invoice Vendor yang Dipilih
                const vendorRows = getFilteredPembelianRows().filter(row => row.vendor === vendorFilter);

                const jenisMap = {};
                vendorRows.forEach(row => {
                    const lbl = row.label || 'Tanpa Jenis';
                    if (!jenisMap[lbl]) {
                        jenisMap[lbl] = {
                            label: lbl,
                            total_nominal: 0,
                            total_pembelian: 0,
                            terbayar: 0,
                            total_transaksi: 0
                        };
                    }
                    jenisMap[lbl].total_nominal += (Number(row.total_nominal) || 0);
                    jenisMap[lbl].total_pembelian += (Number(row.total_pembelian) || 0);
                    jenisMap[lbl].terbayar += (Number(row.terbayar) || 0);
                    jenisMap[lbl].total_transaksi += (Number(row.total_transaksi) || 0);
                });

                const sortedJenis = Object.values(jenisMap)
                    .filter(j => j.total_nominal > 0 || j.total_pembelian > 0)
                    .sort((a, b) => b.total_nominal - a.total_nominal);

                const badgeJenisCount = document.getElementById('badge-vendor-jenis-count');
                if (badgeJenisCount) {
                    badgeJenisCount.textContent = `${sortedJenis.length} Jenis`;
                }

                const singleJenisEl = document.getElementById('chart-pembelian-single-jenis');
                const singleJenisEmptyEl = document.getElementById('chart-pembelian-single-jenis-empty');

                if (sortedJenis.length === 0) {
                    if (singleJenisEl) singleJenisEl.classList.add('d-none');
                    if (singleJenisEmptyEl) singleJenisEmptyEl.classList.remove('d-none');
                } else {
                    if (singleJenisEl) singleJenisEl.classList.remove('d-none');
                    if (singleJenisEmptyEl) singleJenisEmptyEl.classList.add('d-none');

                    const jenisLabels = sortedJenis.map(j => j.label);
                    const jenisValues = sortedJenis.map(j => j.total_nominal);
                    const jenisColors = ['#6B73F0', '#34C28B', '#E3A33B', '#5B9DF0', '#9AA4B2', '#818CF8'];

                    singleJenisChart = new ApexCharts(document.querySelector('#chart-pembelian-single-jenis'), {
                        chart: {
                            type: 'bar',
                            height: 285,
                            toolbar: { show: false },
                            animations: { enabled: true, speed: 300 }
                        },
                        plotOptions: {
                            bar: {
                                distributed: true,
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                columnWidth: jenisLabels.length === 1 ? '35%' : (jenisLabels.length === 2 ? '44%' : '52%'),
                                dataLabels: { position: 'top' }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            offsetY: -20,
                            formatter: function (val) {
                                return formatRupiahCompact(val);
                            },
                            style: {
                                fontSize: '11px',
                                fontWeight: '600',
                                colors: ['#E8EBF0']
                            }
                        },
                        series: [{
                            name: 'Sisa Tagihan',
                            data: jenisValues
                        }],
                        xaxis: {
                            categories: jenisLabels,
                            labels: {
                                rotate: jenisLabels.length > 2 ? -20 : 0,
                                rotateAlways: false,
                                style: { colors: '#9AA4B2', fontSize: '11px', fontWeight: 500 }
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false }
                        },
                        yaxis: {
                            labels: {
                                formatter: function (value) {
                                    return formatRupiahCompact(value);
                                },
                                style: { colors: '#6B7686', fontSize: '10px' }
                            }
                        },
                        grid: {
                            borderColor: 'rgba(255, 255, 255, 0.05)',
                            strokeDashArray: 3
                        },
                        tooltip: {
                            theme: 'dark',
                            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                const item = sortedJenis[dataPointIndex];
                                if (!item) return '';
                                const sisaFmt = formatRupiah(item.total_nominal);
                                const totalFmt = formatRupiah(item.total_pembelian);
                                const terbayarFmt = formatRupiah(item.terbayar);
                                const pct = sisa > 0 ? ((item.total_nominal / sisa) * 100).toFixed(1) : '0';
                                return `
                                    <div class="p-2.5" style="background: var(--bg-surface-2); border: 1px solid var(--border-default); color: #E8EBF0; font-size: 11px; border-radius: 8px;">
                                        <div class="fw-semibold mb-1" style="color: var(--brand); font-size: 12px;">${item.label}</div>
                                        <div><span class="text-muted">Sisa Tagihan:</span> <span class="fw-semibold text-white">${sisaFmt}</span> <span class="badge-tint badge-tint-warning ms-1">${pct}%</span></div>
                                        <div><span class="text-muted">Total Pembelian:</span> <span class="text-white">${totalFmt}</span></div>
                                        <div><span class="text-muted">Sudah Terbayar:</span> <span style="color: var(--success);">${terbayarFmt}</span></div>
                                        <div><span class="text-muted">Jumlah Transaksi:</span> <span class="text-white">${item.total_transaksi} Transaksi</span></div>
                                    </div>
                                `;
                            }
                        },
                        colors: jenisColors.slice(0, Math.max(jenisLabels.length, 1)),
                        legend: { show: false }
                    });
                    singleJenisChart.render();
                }

                return;
            }

            // MODE 2: Normal (Semua Vendor atau Berdasarkan Jenis Invoice Global - Vertical Column Bar Chart)
            if (headerEl) {
                headerEl.classList.add('d-none');
                headerEl.innerHTML = '';
            }
            if (singleContainer) {
                singleContainer.classList.add('d-none');
            }

            destroyAllPembelianCharts();

            // The chart always highlights outstanding invoices; the table follows the status filter.
            const rows = getFilteredPembelianRows(true);
            let visibleLabels = [];
            let values = [];

            if (pembelianGroupBy === 'vendor') {
                const vendorList = getFilteredVendorSummary();
                visibleLabels = vendorList.map(v => v.nama_vendor);
                values = vendorList.map(v => v.sisa_tagihan);
            } else {
                const labels = ['Barang', 'Jasa', 'Barang dan Jasa', 'Jasa Iklan', 'Tanpa Pajak'];
                visibleLabels = jenisFilter === 'all' ? labels : [jenisFilter];
                values = visibleLabels.map(label => {
                    return rows.filter(item => item.label === label).reduce((total, item) => total + item.total_nominal, 0);
                });
            }

            const hasData = rows.length > 0 && values.some(v => v > 0);
            if (emptyEl) emptyEl.classList.toggle('d-none', hasData);
            if (mainChartEl) mainChartEl.classList.toggle('d-none', !hasData);

            if (!hasData) return;

            // Height optimal untuk vertical column chart
            const isMobileVendor = window.innerWidth < 576;
            const calculatedHeight = isMobileVendor ? 290 : 340;
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            const barTopColor = isLight ? '#0F9B60' : '#22C58B';
            const barBotColor = isLight ? '#7ED9B0' : '#0E6B47';
            const labelPrimary = isLight ? '#0F1F18' : '#F2F8F5';
            const labelMuted = isLight ? '#5C7469' : '#7F9A8E';
            const gridCol = isLight ? 'rgba(15, 45, 32, 0.08)' : 'rgba(255, 255, 255, 0.06)';

            pembelianInvoiceChart = new ApexCharts(document.querySelector('#chart-pembelian-invoice'), {
                chart: {
                    type: 'bar',
                    height: calculatedHeight,
                    toolbar: { show: false },
                    animations: { enabled: true, speed: 350 }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 8,
                        borderRadiusApplication: 'end',
                        columnWidth: visibleLabels.length <= 3 ? '36%' : (visibleLabels.length <= 6 ? '44%' : '56%'),
                        dataLabels: { position: 'top' }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: isLight ? 'light' : 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.25,
                        gradientToColors: [barBotColor],
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 0.85,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: isMobileVendor ? -18 : -22,
                    formatter: function (val) {
                        return formatRupiahCompact(val);
                    },
                    style: {
                        fontSize: (isMobileVendor || visibleLabels.length > 8) ? '9.5px' : '11.5px',
                        fontWeight: '700',
                        colors: [labelPrimary]
                    }
                },
                series: [{ name: 'Sisa Belum Dibayar', data: values }],
                xaxis: {
                    categories: visibleLabels,
                    labels: {
                        rotate: (isMobileVendor ? visibleLabels.length > 2 : visibleLabels.length > 5) ? -35 : 0,
                        rotateAlways: isMobileVendor ? visibleLabels.length > 2 : visibleLabels.length > 5,
                        hideOverlappingLabels: isMobileVendor,
                        trim: isMobileVendor,
                        maxHeight: isMobileVendor ? 75 : 90,
                        style: { colors: labelMuted, fontSize: isMobileVendor ? '10px' : '11.5px', fontWeight: 500 }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    tickAmount: isMobileVendor ? 4 : 5,
                    labels: {
                        formatter: function (value) {
                            return formatRupiahCompact(value);
                        },
                        style: { colors: labelMuted, fontSize: isMobileVendor ? '10px' : '11px' }
                    }
                },
                grid: {
                    borderColor: gridCol,
                    strokeDashArray: 4,
                    padding: { top: 24, bottom: 5, left: isMobileVendor ? 4 : 10, right: isMobileVendor ? 4 : 10 },
                    xaxis: { lines: { show: false } },
                    yaxis: { lines: { show: true } }
                },
                responsive: [
                    {
                        breakpoint: 576,
                        options: {
                            chart: { height: 290 },
                            dataLabels: { offsetY: -18, style: { fontSize: '9.5px' } },
                            xaxis: { labels: { rotate: -35, trim: true, hideOverlappingLabels: true, maxHeight: 75, style: { fontSize: '10px' } } },
                            yaxis: { tickAmount: 4, labels: { style: { fontSize: '10px' } } }
                        }
                    }
                ],
                tooltip: {
                    theme: isLight ? 'light' : 'dark',
                    y: {
                        formatter: function (value) {
                            return formatRupiah(value);
                        }
                    }
                },
                colors: [barTopColor]
            });
            pembelianInvoiceChart.render();
        }

        function renderVendorTable() {
            const tbody = document.getElementById('vendor-tagihan-tbody');
            if (!tbody) return;

            const vendorList = getFilteredVendorSummary();
            const badgeCount = document.getElementById('badge-vendor-table-count');
            if (badgeCount) {
                badgeCount.textContent = `${vendorList.length} vendor`;
            }

            const currentSelected = selectedVendorFilter;

            const btnSemua = document.getElementById('btn-semua-vendor');
            if (btnSemua) {
                if (currentSelected === 'all') {
                    btnSemua.classList.add('active');
                    btnSemua.innerHTML = '<i class="ri-check-line fs-12"></i>Semua vendor';
                } else {
                    btnSemua.classList.remove('active');
                    btnSemua.innerHTML = '<i class="ri-store-2-line fs-12"></i>Semua vendor';
                }
            }

            if (!vendorList || vendorList.length === 0) {
                tbody.innerHTML = `<div class="text-center py-4 fs-13" style="color: var(--text-tertiary);">Tidak ada data vendor yang sesuai dengan filter.</div>`;
                return;
            }

            const maxSisa = Math.max(...vendorList.map(r => Number(r.sisa_tagihan) || 0), 1);

            let html = '';
            vendorList.forEach((row) => {
                const isSelected = (currentSelected === row.nama_vendor);
                const sisa = Number(row.sisa_tagihan) || 0;
                const total = Number(row.total_pembelian) || 0;
                const pctMax = Math.min(100, Math.max(4, Math.round((sisa / maxSisa) * 100)));
                const escapedVendor = row.nama_vendor.replace(/\\/g, '\\\\').replace(/'/g, "\\'");

                html += `
                    <div class="tkb-vendor-item ${isSelected ? 'active-vendor-row' : ''}" onclick="toggleVendorFilter('${escapedVendor}')" title="Klik untuk fokus ke vendor ${row.nama_vendor}">
                        <div class="overflow-hidden">
                            <div class="tkb-vendor-name">${row.nama_vendor}</div>
                            <div class="tkb-vendor-bar-track">
                                <div class="tkb-vendor-bar-fill" style="width: ${pctMax}%"></div>
                            </div>
                        </div>
                        <div class="text-start">
                            <div class="tkb-vendor-meta-label">Total tagihan</div>
                            <div class="tkb-vendor-meta-val tabular-nums">${formatRupiah(total)}</div>
                        </div>
                        <div class="text-end">
                            <div class="tkb-vendor-meta-label">Belum dibayar</div>
                            <div class="tkb-vendor-unpaid-val tabular-nums">${formatRupiah(sisa)}</div>
                        </div>
                    </div>
                `;
            });

            tbody.innerHTML = html;
        }

        function toggleVendorFilter(vendorName) {
            if (pembelianGroupBy !== 'vendor') {
                setPembelianGroupBy('vendor');
            }
            if (selectedVendorFilter === vendorName) {
                selectedVendorFilter = 'all';
            } else {
                selectedVendorFilter = vendorName;
            }

            renderPembelianInvoiceChart();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderPembelianInvoiceChart();
            renderVendorTable();
        });
    </script>
    <!-- html2canvas & Exporter -->
    <script src="{{asset('libs/html2canvas/html2canvas.min.js')}}"></script>
    <script src="{{asset('js/halaman/dashboardtokabe.js')}}?v={{ file_exists(public_path('js/halaman/dashboardtokabe.js')) ? filemtime(public_path('js/halaman/dashboardtokabe.js')) : time() }}"></script>
@endsection


{{-- </body> --}}

{{-- </html> --}}
