@section('head')

<script src="https://cdn.lordicon.com/bhenfmcm.js" defer></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

<style>
    /* ==========================================================================
       DESIGN SYSTEM TOKABE — Sesuai DESIGN.md §2 (Tokens), §3 (Warna), §13 (Tabel)
       ========================================================================== */

        /* Base card */
    .kpi-card {
        background: var(--bg-surface, #0F172A);
        border: 1px solid var(--border-subtle, #1E293B);
        border-radius: var(--radius-lg, 12px);
        box-shadow: var(--shadow-xs, 0 1px 2px rgb(0 0 0 / 0.5));
        margin-bottom: var(--space-4, 16px);
        transition:
            box-shadow var(--dur-base, 200ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            transform var(--dur-base, 200ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            border-color var(--dur-base, 200ms) var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    .kpi-card:hover {
        box-shadow: var(--shadow-md, 0 4px 12px rgb(0 0 0 / 0.5));
        transform: translateY(-2px);
        border-color: var(--border-default, #334155);
    }
    .kpi-card:active {
        transform: scale(0.985);
    }

    /* Card body — padding dashboard: space-5 = 20px */
    .kpi-card__body {
        padding: var(--space-5, 20px);
        display: flex;
        flex-direction: column;
        gap: var(--space-3, 12px);
        min-height: 130px;
    }

    /* Top row: overline label + ikon */
    .kpi-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: var(--space-3, 12px);
    }

    /* Overline: 11/600 UPPERCASE letter-spacing 0.08em — DESIGN.md §5.2 */
    .kpi-card__overline {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-tertiary, #94A3B8);
        line-height: 16px;
        margin: 0;
    }

    /* Ikon dalam kotak tonal 40×40 — DESIGN.md §12.4 */
    .kpi-card__icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-md, 8px);
        background-color: var(--kpi-accent-subtle, rgb(99 102 241 / 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--kpi-accent, #818CF8);
        font-size: 20px;
        transition: background-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    .kpi-card:hover .kpi-card__icon {
        background-color: var(--kpi-accent-subtle-hover, rgb(99 102 241 / 0.2));
    }

    /* Nilai utama: price-md 22px/700 tabular — DESIGN.md §5.2 */
    .kpi-card__value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--text-primary, #F1F5F9);
        margin: 0;
        font-variant-numeric: tabular-nums;
    }

    /* Footer row: badge count + label */
    .kpi-card__footer {
        display: flex;
        align-items: center;
        gap: var(--space-2, 8px);
        padding-top: var(--space-2, 8px);
        border-top: 1px solid var(--border-subtle, #1E293B);
    }

    /* Badge count — DESIGN.md §14: pill, 12/500, dot, border */
    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 22px;
        padding: 0 var(--space-2, 8px);
        font-size: 12px;
        font-weight: 600;
        border-radius: var(--radius-full, 9999px);
        border: 1px solid var(--kpi-accent-border, rgb(129 140 248 / 0.35));
        background-color: var(--kpi-accent-subtle, rgb(99 102 241 / 0.12));
        color: var(--kpi-accent-text, #A5B4FC);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .kpi-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--kpi-accent, #818CF8);
        flex-shrink: 0;
    }

    /* Footer label text: body-sm 13/400 */
    .kpi-card__label {
        font-size: 13px;
        font-weight: 400;
        color: var(--text-secondary, #CBD5E1);
        margin: 0;
        line-height: 18px;
    }

    /* ---- Varian aksen per semantik ---- */
    /* Varian: brand / primer (indigo) */
    .kpi-card--brand {
        --kpi-accent:          var(--brand, #6366F1);
        --kpi-accent-subtle:   var(--brand-subtle, rgb(99 102 241 / 0.12));
        --kpi-accent-subtle-hover: var(--brand-subtle-hover, rgb(99 102 241 / 0.2));
        --kpi-accent-border:   rgb(99 102 241 / 0.35);
        --kpi-accent-text:     #A5B4FC;
    }
    /* Varian: success (hijau) — Terbayar / Lunas */
    .kpi-card--success {
        --kpi-accent:          var(--success, #10B981);
        --kpi-accent-subtle:   var(--success-subtle, rgb(16 185 129 / 0.12));
        --kpi-accent-subtle-hover: rgb(16 185 129 / 0.2);
        --kpi-accent-border:   var(--success-border, rgb(16 185 129 / 0.35));
        --kpi-accent-text:     var(--success-text, #6EE7B7);
    }
    /* Varian: danger (merah) — Belum Terbayar / Batal */
    .kpi-card--danger {
        --kpi-accent:          var(--danger, #EF4444);
        --kpi-accent-subtle:   var(--danger-subtle, rgb(239 68 68 / 0.12));
        --kpi-accent-subtle-hover: rgb(239 68 68 / 0.2);
        --kpi-accent-border:   var(--danger-border, rgb(239 68 68 / 0.35));
        --kpi-accent-text:     var(--danger-text, #FCA5A5);
    }
    /* Varian: warning (kuning) — PO Belum Lunas */
    .kpi-card--warning {
        --kpi-accent:          var(--warning, #F59E0B);
        --kpi-accent-subtle:   var(--warning-subtle, rgb(245 158 11 / 0.12));
        --kpi-accent-subtle-hover: rgb(245 158 11 / 0.2);
        --kpi-accent-border:   var(--warning-border, rgb(245 158 11 / 0.35));
        --kpi-accent-text:     var(--warning-text, #FCD34D);
    }
    /* Varian: info (biru) — PO Total / PPN / PPh */
    .kpi-card--info {
        --kpi-accent:          var(--info, #3B82F6);
        --kpi-accent-subtle:   var(--info-subtle, rgb(59 130 246 / 0.12));
        --kpi-accent-subtle-hover: rgb(59 130 246 / 0.2);
        --kpi-accent-border:   var(--info-border, rgb(59 130 246 / 0.35));
        --kpi-accent-text:     var(--info-text, #93C5FD);
    }

    /* Section header (overline row sebelum grup card) */
    .kpi-section-header {
        display: flex;
        align-items: center;
        gap: var(--space-2, 8px);
        margin-bottom: var(--space-3, 12px);
        margin-top: var(--space-4, 16px);
    }
    .kpi-section-header:first-of-type {
        margin-top: var(--space-1, 4px);
    }
    .kpi-section-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 24px;
        padding: 0 var(--space-2, 8px);
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-radius: var(--radius-full, 9999px);
        border: 1px solid transparent;
    }
    .kpi-section-badge--brand {
        background: var(--brand-subtle, rgb(99 102 241 / 0.12));
        color: #A5B4FC;
        border-color: rgb(99 102 241 / 0.3);
    }
    .kpi-section-badge--warning {
        background: var(--warning-subtle, rgb(245 158 11 / 0.12));
        color: var(--warning-text, #FCD34D);
        border-color: var(--warning-border, rgb(245 158 11 / 0.35));
    }
    .kpi-section-badge--info {
        background: var(--info-subtle, rgb(59 130 246 / 0.12));
        color: var(--info-text, #93C5FD);
        border-color: var(--info-border, rgb(59 130 246 / 0.35));
    }
    .kpi-section-badge--danger {
        background: var(--danger-subtle, rgb(239 68 68 / 0.12));
        color: var(--danger-text, #FCA5A5);
        border-color: var(--danger-border, rgb(239 68 68 / 0.35));
    }
    .kpi-section-title {
        font-size: 12px;
        font-weight: 500;
        color: var(--text-tertiary, #94A3B8);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0;
    }

    /* Custom KPI Cards */
    .custom-kpi-card {
        background: var(--bg-surface, #0F172A);
        border: 1px solid var(--border-subtle, #1E293B);
        border-radius: var(--radius-lg, 12px);
        box-shadow: var(--shadow-xs, 0 1px 2px rgb(0 0 0 / 0.5));
        margin-bottom: 16px;
        transition: box-shadow 200ms cubic-bezier(.2,0,0,1), transform 200ms cubic-bezier(.2,0,0,1), border-color 200ms cubic-bezier(.2,0,0,1);
    }
    .custom-kpi-card:hover {
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.5);
        transform: translateY(-2px);
        border-color: var(--border-default, #334155);
    }
    .custom-kpi-card__body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 125px;
    }
    .custom-kpi-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }
    .custom-kpi-card__overline {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-tertiary, #94A3B8);
        line-height: 16px;
        margin: 0;
    }
    .custom-kpi-card__icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
        transition: background-color 120ms ease;
    }
    .custom-kpi-card__value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--text-primary, #F1F5F9);
        margin: 0;
        font-variant-numeric: tabular-nums;
    }
    .custom-kpi-card__footer {
        display: flex;
        align-items: center;
        gap: 8px;
        padding-top: 8px;
        border-top: 1px solid var(--border-subtle, #1E293B);
    }

    .custom-kpi--purple .custom-kpi-card__icon { background-color: rgba(139, 92, 246, 0.15); color: #C084FC; }
    .custom-kpi--success .custom-kpi-card__icon { background-color: rgba(16, 185, 129, 0.15); color: #34D399; }
    .custom-kpi--warning .custom-kpi-card__icon { background-color: rgba(245, 158, 11, 0.15); color: #FBBF24; }
    .custom-kpi--danger .custom-kpi-card__icon { background-color: rgba(239, 68, 68, 0.15); color: #F87171; }

    /* Tombol Tambah Invoice Custom */
    .btn-create-custom-invoice {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff !important;
        background: linear-gradient(135deg, #7C3AED 0%, #6366F1 100%);
        border: 1px solid rgba(196, 181, 253, 0.3);
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
        transition: all 200ms cubic-bezier(.2,0,0,1);
        text-decoration: none;
    }
    .btn-create-custom-invoice:hover {
        background: linear-gradient(135deg, #6D28D9 0%, #4F46E5 100%);
        box-shadow: 0 6px 18px rgba(124, 58, 237, 0.5);
        transform: translateY(-1.5px);
        color: #ffffff !important;
    }

    /* Segmented Controls */
    .segmented-control {
        display: inline-flex;
        background: var(--bg-surface-2, #1E293B);
        border: 1px solid var(--border-subtle, #334155);
        border-radius: 8px;
        padding: 3px;
        gap: 2px;
    }
    .segmented-item {
        border: none;
        background: transparent;
        color: var(--text-secondary, #94A3B8);
        font-size: 13px;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 120ms ease;
    }
    .segmented-item:hover {
        color: var(--text-primary, #F1F5F9);
        background: rgba(255, 255, 255, 0.04);
    }
    .segmented-item.active {
        background: linear-gradient(135deg, #7C3AED, #6366F1);
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
    }

    .btn-custom-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary, #CBD5E1);
        background: var(--bg-surface-2, #1E293B);
        border: 1px solid var(--border-subtle, #334155);
        border-radius: 8px;
        cursor: pointer;
        transition: all 120ms ease;
    }
    .btn-custom-filter:hover, .btn-custom-filter.active {
        color: #DDD6FE;
        border-color: rgba(168, 85, 247, 0.5);
        background: rgba(168, 85, 247, 0.12);
    }

    /* Quick Filter Tab Pill */
    .filter-tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary, #94A3B8);
        background: transparent;
        border: 1px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        transition: all 150ms ease;
    }
    .filter-tab-pill:hover {
        color: #F1F5F9;
        background: rgba(255, 255, 255, 0.05);
    }
    .filter-tab-pill.active {
        color: #F1F5F9;
        background: rgba(139, 92, 246, 0.2);
        border-color: rgba(139, 92, 246, 0.45);
        font-weight: 600;
    }

    /* ==========================================================================
       DATATABLES CONTROLS (Top & Bottom) — Rapi, Presisi, Tanpa Float
       ========================================================================== */
    #scroll-horizontal-custom_wrapper {
        width: 100%;
    }

    .dataTables_controls_top {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 16px !important;
        padding: 14px 20px !important;
        margin-bottom: 0 !important;
        background: var(--bg-surface, #0F172A) !important;
        border-bottom: 1px solid var(--border-subtle, #1E293B) !important;
        width: 100% !important;
    }

    #scroll-horizontal-custom_wrapper .dataTables_length {
        float: none !important;
        margin-bottom: 0 !important;
        color: var(--text-secondary, #CBD5E1);
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    #scroll-horizontal-custom_wrapper .dataTables_filter {
        float: none !important;
        margin-bottom: 0 !important;
        margin-left: auto !important;
        text-align: right !important;
        color: var(--text-secondary, #CBD5E1);
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    #scroll-horizontal-custom_wrapper .dataTables_length label,
    #scroll-horizontal-custom_wrapper .dataTables_filter label {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 0 !important;
        height: 36px !important;
    }

    #scroll-horizontal-custom_wrapper .dataTables_length select {
        height: 36px !important;
        background-color: var(--bg-surface-2, #1E293B) !important;
        border: 1px solid var(--border-default, #334155) !important;
        color: var(--text-primary, #F1F5F9) !important;
        border-radius: var(--radius-md, 8px) !important;
        padding: 4px 28px 4px 10px !important;
        font-size: 13px !important;
        outline: none;
        cursor: pointer;
        transition: border-color 120ms ease, box-shadow 120ms ease;
    }

    #scroll-horizontal-custom_wrapper .dataTables_length select:focus {
        border-color: #8B5CF6 !important;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25) !important;
    }

    #scroll-horizontal-custom_wrapper .dataTables_filter input {
        height: 36px !important;
        background-color: var(--bg-surface, #0F172A) !important;
        border: 1px solid var(--border-default, #334155) !important;
        color: var(--text-primary, #F1F5F9) !important;
        border-radius: var(--radius-md, 8px) !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        outline: none;
        margin-left: 0 !important;
        width: 220px;
        transition: border-color 120ms ease, box-shadow 120ms ease, width 200ms ease;
    }

    #scroll-horizontal-custom_wrapper .dataTables_filter input:focus {
        border-color: #8B5CF6 !important;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25) !important;
        width: 260px;
    }

    .dataTables_controls_bottom {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 16px !important;
        padding: 14px 20px !important;
        margin-top: 0 !important;
        background: var(--bg-surface, #0F172A) !important;
        border-top: 1px solid var(--border-subtle, #1E293B) !important;
        width: 100% !important;
    }

    #scroll-horizontal-custom_wrapper .dataTables_info {
        float: none !important;
        color: var(--text-tertiary, #94A3B8) !important;
        font-size: 13px !important;
        padding-top: 0 !important;
        margin-bottom: 0 !important;
    }

    #scroll-horizontal-custom_wrapper .dataTables_paginate {
        float: none !important;
        padding-top: 0 !important;
        margin-bottom: 0 !important;
    }

    /* ==========================================================================
       TABEL UTAMA — Lebar Kolom Pasti, Tidak Ada Teks Terbelah (DESIGN.md §13)
       ========================================================================== */
    #scroll-horizontal-custom {
        width: 100% !important;
        min-width: 1915px !important;
        table-layout: fixed !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    /* Header Styling */
    #scroll-horizontal-custom thead th {
        height: 44px !important;
        background: #172033 !important;
        color: var(--text-tertiary, #94A3B8) !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
        white-space: nowrap !important;
        border-bottom: 2px solid var(--border-default, #334155) !important;
        border-top: none !important;
        padding: 11px 14px !important;
        vertical-align: middle !important;
        position: relative !important;
        user-select: none !important;
        transition: background .15s ease, color .15s ease;
    }

    /* Override panah sortir ganda Bootstrap 5 default */
    #scroll-horizontal-custom thead th::before {
        display: none !important;
        content: "" !important;
    }

    #scroll-horizontal-custom thead th.sorting,
    #scroll-horizontal-custom thead th.sorting_asc,
    #scroll-horizontal-custom thead th.sorting_desc {
        cursor: pointer !important;
        padding-right: 28px !important;
    }

    #scroll-horizontal-custom thead th.sorting:hover,
    #scroll-horizontal-custom thead th.sorting_asc:hover,
    #scroll-horizontal-custom thead th.sorting_desc:hover {
        background: #1E293B !important;
        color: var(--text-primary, #F1F5F9) !important;
    }

    #scroll-horizontal-custom thead th.sorting_disabled {
        cursor: default !important;
        padding-right: 14px !important;
    }

    #scroll-horizontal-custom thead th.sorting::after {
        content: "\f124" !important;
        font-family: 'remixicon' !important;
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 13px !important;
        color: #64748B !important;
        opacity: 0.4 !important;
        transition: opacity .15s ease, color .15s ease;
    }
    #scroll-horizontal-custom thead th.sorting:hover::after {
        opacity: 1 !important;
        color: #A78BFA !important;
    }

    #scroll-horizontal-custom thead th.sorting_asc {
        color: #C084FC !important;
        background: #17243B !important;
    }
    #scroll-horizontal-custom thead th.sorting_asc::after {
        content: "\ea78" !important;
        font-family: 'remixicon' !important;
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 14px !important;
        color: #C084FC !important;
        opacity: 1 !important;
    }

    #scroll-horizontal-custom thead th.sorting_desc {
        color: #C084FC !important;
        background: #17243B !important;
    }
    #scroll-horizontal-custom thead th.sorting_desc::after {
        content: "\ea4c" !important;
        font-family: 'remixicon' !important;
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 14px !important;
        color: #C084FC !important;
        opacity: 1 !important;
    }

    #scroll-horizontal-custom thead th.sorting_disabled::after {
        display: none !important;
        content: "" !important;
    }

    /* Lebar Kolom Definitif (14 Kolom Lengkap Sesuai DESIGN.md §13) */
    #scroll-horizontal-custom th:nth-child(1), #scroll-horizontal-custom td:nth-child(1) { width: 45px; text-align: center !important; }
    #scroll-horizontal-custom th:nth-child(2), #scroll-horizontal-custom td:nth-child(2) { width: 190px; text-align: left !important; }
    #scroll-horizontal-custom th:nth-child(3), #scroll-horizontal-custom td:nth-child(3) { width: 220px; text-align: left !important; }
    #scroll-horizontal-custom th:nth-child(4), #scroll-horizontal-custom td:nth-child(4) { width: 140px; text-align: left !important; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(5), #scroll-horizontal-custom td:nth-child(5) { width: 165px; text-align: left !important; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(6), #scroll-horizontal-custom td:nth-child(6) { width: 140px; min-width: 135px; text-align: center !important; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(7), #scroll-horizontal-custom td:nth-child(7) { width: 130px; text-align: left !important; }
    #scroll-horizontal-custom th:nth-child(8), #scroll-horizontal-custom td:nth-child(8) { width: 105px !important; min-width: 105px !important; max-width: 105px !important; text-align: center !important; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(9), #scroll-horizontal-custom td:nth-child(9) { width: 260px !important; min-width: 260px !important; max-width: 260px !important; text-align: left !important; box-sizing: border-box !important; }
    #scroll-horizontal-custom th:nth-child(10), #scroll-horizontal-custom td:nth-child(10) { width: 130px; text-align: right !important; font-variant-numeric: tabular-nums; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(11), #scroll-horizontal-custom td:nth-child(11) { width: 155px; text-align: right !important; font-variant-numeric: tabular-nums; font-weight: 600; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(12), #scroll-horizontal-custom td:nth-child(12) { width: 150px; min-width: 140px; text-align: center !important; white-space: nowrap !important; }
    #scroll-horizontal-custom th:nth-child(13), #scroll-horizontal-custom td:nth-child(13) { width: 130px; text-align: left !important; }

    /* Kolom Aksi Sticky di Kanan — Akses Cepat Tanpa Perlu Scroll Horizontal */
    #scroll-horizontal-custom th:nth-child(14),
    #scroll-horizontal-custom td:nth-child(14) {
        width: 80px !important;
        min-width: 80px !important;
        max-width: 80px !important;
        text-align: center !important;
        position: sticky !important;
        right: 0 !important;
        background: #121722 !important;
        box-shadow: -4px 0 10px rgba(0, 0, 0, 0.45) !important;
        z-index: 10 !important;
    }
    #scroll-horizontal-custom thead th:nth-child(14) {
        z-index: 25 !important;
        background: var(--bg-surface-2, #1E293B) !important;
    }
    #scroll-horizontal-custom tbody tr:hover td:nth-child(14) {
        background: #1b2332 !important;
    }
    #scroll-horizontal-custom tbody tr.is-expanded td:nth-child(14) {
        background: #161c28 !important;
    }

    /* Stacking Context Dropdown agar Selalu di Atas Sticky Baris Lainnya */
    #scroll-horizontal-custom tbody td:nth-child(14):has(.show),
    #scroll-horizontal-custom tbody td:nth-child(14):focus-within {
        z-index: 1050 !important;
    }

    #scroll-horizontal-custom thead th:nth-child(6),
    #scroll-horizontal-custom thead th:nth-child(8),
    #scroll-horizontal-custom thead th:nth-child(12) {
        text-align: center !important;
        padding-left: 20px !important;
        padding-right: 28px !important;
    }

    #scroll-horizontal-custom tbody td {
        box-sizing: border-box;
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        font-size: 13px !important;
        background-color: transparent;
        line-height: 1.45;
        transition: background-color 180ms ease, color 180ms ease;
    }

    #scroll-horizontal-custom tbody tr:hover td {
        background-color: #1E293B !important;
    }

    #scroll-horizontal-custom tbody td:nth-child(2),
    #scroll-horizontal-custom tbody td:nth-child(3),
    #scroll-horizontal-custom tbody td:nth-child(4),
    #scroll-horizontal-custom tbody td:nth-child(13) {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Badge Nomor Invoice Custom */
    .custom-invoice-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        font-size: 12.5px;
        font-weight: 600;
        font-family: var(--font-mono, "JetBrains Mono", ui-monospace, Menlo, Consolas, monospace);
        border-radius: 6px;
        background: rgba(139, 92, 246, 0.12);
        color: #DDD6FE;
        border: 1px solid rgba(139, 92, 246, 0.35);
        letter-spacing: 0.02em;
        white-space: nowrap;
    }
    .custom-invoice-tag i {
        color: #A78BFA;
        font-size: 13px;
    }

    /* Row Expand Indicator */
    .row-expand-indicator {
        display: inline-flex;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        padding: 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-secondary, #CBD5E1);
        font-size: 14px;
        cursor: pointer;
        transition: all 150ms ease;
    }
    .row-expand-indicator:hover {
        background: rgba(139, 92, 246, 0.2);
        border-color: rgba(139, 92, 246, 0.4);
        color: #DDD6FE;
    }
    .row-expand-indicator.expanded i {
        transform: rotate(90deg);
    }

    /* Action Button Kebab */
    .btn-table-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-subtle, #334155);
        color: var(--text-primary, #F1F5F9);
        cursor: pointer;
        transition: all 120ms ease;
    }
    .btn-table-action:hover,
    .btn-table-action[aria-expanded="true"] {
        background: rgba(139, 92, 246, 0.25);
        border-color: rgba(139, 92, 246, 0.45);
        color: #DDD6FE;
    }

    /* Dropdown Menu Stying Sesuai DESIGN.md */
    .dropdown-menu {
        background: var(--bg-surface-2, #181D26) !important;
        border: 1px solid var(--border-default, #2D3748) !important;
        border-radius: var(--radius-lg, 12px) !important;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6) !important;
        padding: 6px !important;
        min-width: 235px !important;
        z-index: 1060 !important;
    }
    .dropdown-item {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        min-height: 36px !important;
        padding: 8px 12px !important;
        border-radius: var(--radius-sm, 6px) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        white-space: nowrap !important;
        color: var(--text-primary, #F1F5F9) !important;
        background: transparent !important;
        transition: background-color var(--dur-fast, 120ms) var(--ease-standard), color var(--dur-fast, 120ms) var(--ease-standard), transform var(--dur-instant, 80ms) var(--ease-standard) !important;
    }
    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: var(--bg-hover, rgba(255, 255, 255, 0.08)) !important;
        color: #ffffff !important;
        transform: translateX(2px);
    }
    .dropdown-item:active {
        transform: scale(0.98);
    }
    .dropdown-item.text-danger:hover,
    .dropdown-item--danger:hover {
        background-color: var(--danger-subtle, rgba(239, 68, 68, 0.15)) !important;
        color: var(--danger, #EF4444) !important;
    }

    /* ==========================================================================
       DESIGN SYSTEM BADGES — Sesuai DESIGN.md §14.1 (Badge, Tag & Status)
       Pill shape, tinggi 24px, dot + teks, white-space nowrap ketat
       ========================================================================== */
    .badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        height: 24px !important;
        padding: 0 10px !important;
        font-family: var(--font-sans, "Inter", system-ui, sans-serif) !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        line-height: 1 !important;
        border-radius: 9999px !important;
        border: 1px solid transparent !important;
        white-space: nowrap !important;
        user-select: none !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
    }

    .badge::before {
        content: "" !important;
        width: 6px !important;
        height: 6px !important;
        border-radius: 50% !important;
        background: currentColor !important;
        flex-shrink: 0 !important;
        display: inline-block !important;
    }

    .badge.badge--icon::before {
        display: none !important;
    }
    .badge.badge--icon i {
        font-size: 13px !important;
        line-height: 1 !important;
    }

    .badge--success {
        color: var(--success-text, #6EE7B7) !important;
        background-color: var(--success-subtle, rgb(16 185 129 / 0.15)) !important;
        border-color: var(--success-border, rgb(16 185 129 / 0.35)) !important;
    }

    .badge--warning {
        color: var(--warning-text, #FCD34D) !important;
        background-color: var(--warning-subtle, rgb(245 158 11 / 0.15)) !important;
        border-color: var(--warning-border, rgb(245 158 11 / 0.35)) !important;
    }

    .badge--danger {
        color: var(--danger-text, #FCA5A5) !important;
        background-color: var(--danger-subtle, rgb(239 68 68 / 0.15)) !important;
        border-color: var(--danger-border, rgb(239 68 68 / 0.35)) !important;
    }

    .badge--info {
        color: var(--info-text, #93C5FD) !important;
        background-color: var(--info-subtle, rgb(59 130 246 / 0.15)) !important;
        border-color: var(--info-border, rgb(59 130 246 / 0.35)) !important;
    }


/* Badge Jenis Pembayaran — Warna Khusus Masing-Masing Jenis (DESIGN.md §14.1) */
.badge-payment--cash-lunas {
  color: #34D399 !important;
  background-color: rgba(16, 185, 129, 0.15) !important;
  border: 1px solid rgba(16, 185, 129, 0.35) !important;
}
.badge-payment--transfer-lunas {
  color: #22D3EE !important;
  background-color: rgba(6, 182, 212, 0.15) !important;
  border: 1px solid rgba(6, 182, 212, 0.35) !important;
}
.badge-payment--cash-belum-lunas {
  color: #FB923C !important;
  background-color: rgba(249, 115, 22, 0.15) !important;
  border: 1px solid rgba(249, 115, 22, 0.35) !important;
}
.badge-payment--transfer-dp {
  color: #C084FC !important;
  background-color: rgba(168, 85, 247, 0.15) !important;
  border: 1px solid rgba(168, 85, 247, 0.35) !important;
}
.badge-payment--po {
  color: #818CF8 !important;
  background-color: rgba(99, 102, 241, 0.15) !important;
  border: 1px solid rgba(99, 102, 241, 0.35) !important;
}

    /* Deskripsi Collapsible */
    .table-cell-collapsible {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden;
    }
    .table-cell-collapsible ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .table-cell-collapsible li {
        overflow: hidden;
        color: var(--text-secondary, #CBD5E1);
        line-height: 20px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .description-extra-container {
        display: none;
    }
    tr.is-expanded .description-extra-container {
        display: block;
    }

    /* Scrollbar Halus Modern Gelap — Menggantikan Scrollbar Abu Bawaan */
    .table-responsive::-webkit-scrollbar,
    #scroll-horizontal-custom_wrapper .dataTables_scrollBody::-webkit-scrollbar {
        height: 7px;
    }
    .table-responsive::-webkit-scrollbar-track,
    #scroll-horizontal-custom_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: #0F172A;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb,
    #scroll-horizontal-custom_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: var(--border-default, #334155);
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover,
    #scroll-horizontal-custom_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }

    /* Keep request differences visible even when the compiled stylesheet is cached. */
    .dropdown-menu a.invoice-request {
        border-inline-start: 4px solid transparent !important;
        padding-inline-start: 8px !important;
        font-weight: 700 !important;
    }
    .dropdown-menu a.invoice-request::after {
        content: "";
        width: 8px;
        height: 8px;
        margin-inline-start: auto;
        border-radius: 50%;
        background: currentColor;
    }
    .dropdown-menu a.invoice-request--unlock { color: #facc15 !important; border-inline-start-color: #facc15 !important; }
    .dropdown-menu a.invoice-request--settlement { color: #10B981 !important; border-inline-start-color: #10B981 !important; }
    .dropdown-menu a.invoice-request--cancel { color: #EF4444 !important; border-inline-start-color: #EF4444 !important; }
    .dropdown-menu a.invoice-request--delete { color: #f87171 !important; border-inline-start-color: #f87171 !important; }
    .dropdown-menu a.invoice-request--owner-2::after { opacity: .35; }
    .dropdown-menu a.invoice-request--owner-1::after { opacity: 1; }
</style>

@endsection

@extends('layout.template')

@section('content')

@if (session()->has('batal'))
    <script>
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('batal') }}",
            icon: "success",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

<div class="page-content">
  <div class="container-fluid">

      <!-- Header Halaman Invoice Keluar Custom -->
      <div class="row mb-3">
          <div class="col-12">
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                  <div>
                      <div class="d-flex align-items-center gap-2">
                          <h4 class="mb-0" style="font-size: 21px; font-weight: 700; color: var(--text-primary, #F1F5F9); letter-spacing: -0.015em;">
                              Invoice Keluar Custom
                          </h4>
                          <span style="display:inline-flex;align-items:center;height:22px;padding:0 9px;font-size:11px;font-weight:600;border-radius:9999px;background:rgba(139,92,246,0.15);color:#DDD6FE;border:1px solid rgba(139,92,246,0.35);">
                              <i class="ri-file-edit-line me-1"></i>Dedicated Custom Table
                          </span>
                      </div>
                      <p class="mb-0 mt-1" style="font-size: 13px; color: var(--text-tertiary, #94A3B8);">
                          Tabel khusus transaksi invoice keluar dengan nomor invoice custom Tokabe
                      </p>
                  </div>
                  <div>
                      <a href="{{ route('add.inv.tkb.custom') }}" class="btn-create-custom-invoice">
                          <i class="ri-add-line fs-18"></i>Tambah Invoice Custom
                      </a>
                  </div>
              </div>
          </div>
      </div>

      @if (Auth::user()->role === 'Pemilik' || Auth::user()->role === 'Admin')
      <!-- Filter Bar Terhubung untuk Semua Card Custom -->
      <div class="row mb-4">
          <div class="col-12">
              <div class="card" style="background: var(--bg-surface, #0F172A); border: 1px solid var(--border-subtle, #1E293B); border-radius: var(--radius-lg, 12px); box-shadow: var(--shadow-xs, 0 1px 2px rgb(0 0 0 / 0.5));">
                  <div class="card-body p-3">
                      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                          <div class="d-flex align-items-center gap-2">
                              <span style="font-size: 13px; font-weight: 500; color: var(--text-secondary, #CBD5E1); display: inline-flex; align-items: center; gap: 6px;">
                                  <i class="ri-calendar-line" style="color: #A78BFA; font-size: 16px;"></i>Filter Periode Custom:
                              </span>
                              <span class="badge" id="custom-badge-active-period" style="background: rgba(139, 92, 246, 0.15); color: #C084FC; border: 1px solid rgba(139, 92, 246, 0.3); font-weight: 600; padding: 5px 10px; border-radius: 6px;">
                                  Tahun {{ date('Y') }}
                              </span>
                          </div>
                          
                          <!-- Button Timeframes -->
                          <div class="d-flex flex-wrap align-items-center gap-2">
                              <div class="segmented-control" role="group">
                                  <button type="button" class="segmented-item btn-custom-card-time" id="btn-custom-card-today" onclick="switchCustomCardTimeframe('today')">Hari Ini</button>
                                  <button type="button" class="segmented-item btn-custom-card-time" id="btn-custom-card-week" onclick="switchCustomCardTimeframe('week')">Minggu Ini</button>
                                  <button type="button" class="segmented-item btn-custom-card-time" id="btn-custom-card-month" onclick="switchCustomCardTimeframe('month')">Bulan Ini</button>
                                  <button type="button" class="segmented-item btn-custom-card-time active" id="btn-custom-card-year" onclick="switchCustomCardTimeframe('year')">Tahun Ini</button>
                                  <button type="button" class="segmented-item btn-custom-card-time" id="btn-custom-card-last_year" onclick="switchCustomCardTimeframe('last_year')">Tahun Lalu</button>
                              </div>
                              <button type="button" class="btn-custom-filter" id="btn-custom-card-custom-toggle" onclick="toggleCustomFilterCustomCard()">
                                  <i class="ri-filter-3-line"></i> Filter Khusus <i class="ri-arrow-down-s-line" id="icon-toggle-custom-card"></i>
                              </button>
                          </div>
                      </div>

                      <!-- Custom Filter Panel (Toggleable) -->
                      <div id="custom-card-filter-box" class="custom-filter-panel mt-3 p-3" style="display: none; background: #13172E; border: 1px solid #334155; border-radius: 8px;">
                          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                              <div class="d-flex flex-wrap align-items-center gap-2">
                                  <span class="fs-12 fw-semibold text-muted text-uppercase" style="letter-spacing: 0.06em;">
                                      <i class="ri-filter-line me-1" style="color: #A78BFA;"></i>Pilih Spesifik:
                                  </span>
                                  
                                  <div class="segmented-control" role="group">
                                      <button type="button" class="segmented-item active" id="btn-custom-mode-date" onclick="setCustomCardFilterMode('date')">
                                          <i class="ri-calendar-event-line"></i> Tanggal
                                      </button>
                                      <button type="button" class="segmented-item" id="btn-custom-mode-month" onclick="setCustomCardFilterMode('month')">
                                          <i class="ri-calendar-2-line"></i> Bulan
                                      </button>
                                      <button type="button" class="segmented-item" id="btn-custom-mode-year" onclick="setCustomCardFilterMode('year')">
                                          <i class="ri-calendar-check-line"></i> Tahun
                                      </button>
                                  </div>

                                  <div id="wrapper-custom-filter-date" class="d-inline-flex align-items-center">
                                      <input type="date" class="form-control form-control-sm" id="input-custom-filter-date" value="{{ date('Y-m-d') }}" style="background: #0F172A; border-color: #334155; color: #F1F5F9;">
                                  </div>

                                  <div id="wrapper-custom-filter-month" class="d-none align-items-center gap-1">
                                      <select class="form-select form-select-sm" id="select-custom-filter-month" style="background: #0F172A; border-color: #334155; color: #F1F5F9; min-width: 125px;">
                                          @php
                                              $bulanIndo = [
                                                  1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                  5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                  9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                              ];
                                          @endphp
                                          @foreach($bulanIndo as $num => $nama)
                                              <option value="{{ $num }}" {{ (int)date('n') === $num ? 'selected' : '' }}>{{ $nama }}</option>
                                          @endforeach
                                      </select>
                                      <select class="form-select form-select-sm" id="select-custom-filter-month-year" style="background: #0F172A; border-color: #334155; color: #F1F5F9; min-width: 90px;">
                                          @foreach($availableYears as $y)
                                              <option value="{{ $y }}" {{ (int)date('Y') === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                                          @endforeach
                                      </select>
                                  </div>

                                  <div id="wrapper-custom-filter-year" class="d-none align-items-center">
                                      <select class="form-select form-select-sm" id="select-custom-filter-year" style="background: #0F172A; border-color: #334155; color: #F1F5F9; min-width: 100px;">
                                          @foreach($availableYears as $y)
                                              <option value="{{ $y }}" {{ (int)date('Y') === (int)$y ? 'selected' : '' }}>{{ $y }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>

                              <div class="d-flex align-items-center gap-2">
                                  <button type="button" class="btn btn-sm" onclick="applyCustomCardCustomFilter()" style="background: linear-gradient(135deg, #7C3AED, #6366F1); color: #fff; font-weight: 600; border-radius: 6px; padding: 4px 12px;">
                                      <i class="ri-check-line me-1"></i>Terapkan
                                  </button>
                                  <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleCustomFilterCustomCard()">
                                      Tutup
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- SECTION 1: CARD INVOICE NON PO (4 Cards) -->
      <div class="kpi-section-header">
          <span class="kpi-section-badge kpi-section-badge--brand" aria-hidden="true">
              <i class="ri-receipt-line"></i> NON PO
          </span>
          <p class="kpi-section-title">Ringkasan Invoice Regular (Cash / Transfer)</p>
      </div>
      <div class="row g-3 mb-1">
          {{-- Card 1: Jumlah Seluruh Non PO --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--brand">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-1">Jumlah Seluruh Non PO Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-file-text-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-1">Rp {{ number_format($jumlahPerTahun, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-1">{{ $jlhInvoice }}</span>
                          <p class="kpi-card__label" id="custom-card-desc-1">Invoice Non PO Dibuat Tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 2: Jumlah Non PO Terbayar --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--success">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-2">Non PO Terbayar Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-checkbox-circle-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-2">Rp {{ number_format($totalLunasThn, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-2">{{ $lunas }}</span>
                          <p class="kpi-card__label" id="custom-card-desc-2">Invoice Non PO Lunas Tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 3: Jumlah Non PO Belum Terbayar Tahun --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-3">Non PO Belum Terbayar Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-time-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-3">Rp {{ number_format($jumlahBBPerTahun, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-3">{{ $belumLunas }}</span>
                          <p class="kpi-card__label">Invoice Non PO Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 4: Jumlah Non PO Belum Terbayar Bulan --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-4">Non PO Belum Terbayar Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-calendar-close-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-4">Rp {{ number_format($jumlahBBPerBulan, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-4">{{ $belumLunasMonth }}</span>
                          <p class="kpi-card__label">Invoice Non PO Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- SECTION 2: CARD INVOICE PURCHASE ORDER (PO) (4 Cards) -->
      <div class="kpi-section-header">
          <span class="kpi-section-badge kpi-section-badge--warning" aria-hidden="true">
              <i class="ri-file-list-3-line"></i> Purchase Order (PO)
          </span>
          <p class="kpi-section-title">Ringkasan Invoice Khusus Purchase Order</p>
      </div>
      <div class="row g-3 mb-1">
          {{-- Card PO 1: Jumlah Seluruh PO --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--info">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-po-title-1">Jumlah Seluruh PO</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-bank-card-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-po-val-1">Rp {{ number_format($jumlahPO, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-po-count-1">{{ $countAllInvPO }}</span>
                          <p class="kpi-card__label" id="custom-card-po-desc-1">Invoice PO Aktif</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card PO 2: Jumlah PO Lunas Tahun Ini --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--success">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-po-title-2">Jumlah PO Lunas Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-checkbox-circle-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-po-val-2">Rp {{ number_format($jumlahPOLunas, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-po-count-2">{{ $countAllSettledPO }}</span>
                          <p class="kpi-card__label" id="custom-card-po-desc-2">Invoice PO Lunas Tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card PO 3: Jumlah PO Belum Lunas Tahun Ini --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--warning">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-po-title-3">PO Belum Lunas Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-error-warning-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-po-val-3">Rp {{ number_format($jumlahPOBlmLunas, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-po-count-3">{{ $countAllOutstandingPO }}</span>
                          <p class="kpi-card__label">Invoice PO Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card PO 4: Jumlah PO Belum Lunas Bulan Ini --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--warning">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-po-title-4">PO Belum Lunas Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-hourglass-2-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-po-val-4">Rp {{ number_format($jumlahBBPerBulanPO, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-po-count-4">{{ $blmLunasPO }}</span>
                          <p class="kpi-card__label">Invoice PO Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- SECTION 3: RINGKASAN PEMBATALAN -->
      <div class="kpi-section-header">
          <span class="kpi-section-badge kpi-section-badge--danger" aria-hidden="true">
              <i class="ri-close-circle-line"></i> Pembatalan
          </span>
          <p class="kpi-section-title">Status Batal Invoice Tokabe</p>
      </div>
      <div class="row g-3 mb-1">
          {{-- Card 5: Invoice Dibatalkan --}}
          <div class="col-12">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-5">Invoice Dibatalkan</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-close-circle-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-5">Rp {{ number_format($jumlahBatal, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-5">{{ $batal }}</span>
                          <p class="kpi-card__label">Invoice Tokabe Dibatalkan</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- SECTION 4: RINGKASAN PAJAK (PPN / PPh) -->
      <div class="kpi-section-header">
          <span class="kpi-section-badge kpi-section-badge--info" aria-hidden="true">
              <i class="ri-pie-chart-2-line"></i> Pajak (PPN & PPh)
          </span>
          <p class="kpi-section-title">Total PPN & PPh serta Rincian Kode PPN</p>
      </div>
      <!-- ROW 1: 2 Cards (Total PPN, Total PPh) -->
      <div class="row g-3 mb-1">
          {{-- Card 6: Total PPN Tahun Ini --}}
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card kpi-card--info">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-6">Jumlah Total PPN Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-percent-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-6">Rp {{ number_format($ppnTotal, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-6">{{ $ppnTotalCount }}</span>
                          <p class="kpi-card__label">Invoice Tokabe dengan PPN</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 7: Total PPh Tahun Ini --}}
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card kpi-card--warning">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="custom-card-title-7">Jumlah Total PPh Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-file-chart-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-7">Rp {{ number_format($pphTotal, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-7">{{ $pphTotalCount }}</span>
                          <p class="kpi-card__label">Invoice Tokabe dengan PPh</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- ROW 2: 4 Cards (Rincian Kode PPN 01, 02, 03, 04) -->
      <div class="row g-3 mb-3">
          {{-- Card 8: PPN 01 - Swasta / Non-Pemungut --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--brand">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline">PPN 01 — Non-Pemungut</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-user-star-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-8">Rp {{ number_format($ppn01, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-8">{{ $ppn01Count }}</span>
                          <p class="kpi-card__label">01 — Swasta (Non-Pemungut)</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 9: PPN 02 - Instansi Pemerintah --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--info">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline">PPN 02 — Instansi Pemerintah</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-government-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-9">Rp {{ number_format($ppn02, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-9">{{ $ppn02Count }}</span>
                          <p class="kpi-card__label">02 — Pemungut Pemerintah</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 10: PPN 03 - Pemungut BUMN --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--success">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline">PPN 03 — Pemungut BUMN</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-building-4-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-10">Rp {{ number_format($ppn03, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-10">{{ $ppn03Count }}</span>
                          <p class="kpi-card__label">03 — BUMN / Pemungut Lain</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 11: PPN 04 - DPP Nilai Lain --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--warning">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline">PPN 04 — DPP Nilai Lain</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-price-tag-3-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="custom-card-val-11">Rp {{ number_format($ppn04, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="custom-card-count-11">{{ $ppn04Count }}</span>
                          <p class="kpi-card__label">04 — DPP Nilai Lain</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      @endif

      <!-- Card Table Model Khusus Invoice Keluar Custom -->
      <div class="row">
          <div class="col-12">
              <div class="card" style="background: var(--bg-surface, #0F172A); border: 1px solid var(--border-subtle, #1E293B); border-radius: var(--radius-lg, 12px); box-shadow: var(--shadow-xs, 0 1px 2px rgb(0 0 0 / 0.5));">
                  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3 p-3" style="background: transparent; border-bottom: 1px solid var(--border-subtle, #1E293B);">
                      <!-- Quick Status Filters -->
                      <div class="d-flex flex-wrap align-items-center gap-2">
                          <button type="button" class="filter-tab-pill active" onclick="filterCustomTableStatus('')">
                              <i class="ri-apps-line"></i> Semua ({{ $penjualan->count() }})
                          </button>
                          <button type="button" class="filter-tab-pill" onclick="filterCustomTableStatus('Lunas')">
                              <i class="ri-checkbox-circle-fill text-success"></i> Lunas ({{ $penjualan->where('status', 'Lunas')->count() }})
                          </button>
                          <button type="button" class="filter-tab-pill" onclick="filterCustomTableStatus('Belum Lunas')">
                              <i class="ri-time-fill text-warning"></i> Belum Lunas ({{ $penjualan->where('status', 'Belum Lunas')->count() }})
                          </button>
                          <button type="button" class="filter-tab-pill" onclick="filterCustomTableStatus('Batal')">
                              <i class="ri-close-circle-fill text-danger"></i> Batal ({{ $penjualan->where('status', 'Batal')->count() }})
                          </button>
                      </div>

                      <div class="d-flex align-items-center gap-2">
                          <button type="button" id="btn-toggle-all-custom-rows" class="btn btn-sm" style="background: rgba(139, 92, 246, 0.15); color: #DDD6FE; border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 8px;">
                              <i class="ri-expand-diagonal-line me-1"></i><span id="text-toggle-all-custom">Buka Semua Rincian</span>
                          </button>
                      </div>
                  </div>

                  <div class="card-body p-0">
                      @if($penjualan->isEmpty())
                          <div class="text-center py-5 px-3">
                              <div style="width: 64px; height: 64px; border-radius: 50%; background: rgba(139, 92, 246, 0.12); color: #A78BFA; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 16px;">
                                  <i class="ri-file-edit-line"></i>
                              </div>
                              <h5 class="text-white mb-2">Belum Ada Invoice Custom</h5>
                              <p class="text-muted mx-auto mb-4" style="max-width: 420px; font-size: 13.5px;">
                                  Invoice Custom adalah fitur khusus untuk membuat invoice dengan nomor invoice bebas dan terpisah dari nomor urut standar Tokabe.
                              </p>
                              <a href="{{ route('add.inv.tkb.custom') }}" class="btn-create-custom-invoice">
                                  <i class="ri-add-line fs-18"></i>Buat Invoice Custom Pertama
                              </a>
                          </div>
                      @else
                          <table id="scroll-horizontal-custom" class="table align-middle mb-0" aria-label="Daftar invoice Tokabe Custom">
                              <thead>
                                  <tr>
                                      <th scope="col" class="text-center">No</th>
                                      <th scope="col">No. Invoice Custom</th>
                                      <th scope="col">Pelanggan / Perusahaan</th>
                                      <th scope="col">No. Telepon</th>
                                      <th scope="col">Tanggal Penjualan</th>
                                      <th scope="col" class="text-center">Status</th>
                                      <th scope="col">Alasan Batal</th>
                                      <th scope="col" class="text-center">Approval</th>
                                      <th scope="col">Deskripsi / Item</th>
                                      <th scope="col" class="text-end">Panjar (DP)</th>
                                      <th scope="col" class="text-end">Total Pembayaran</th>
                                      <th scope="col" class="text-center">Tipe Pembayaran</th>
                                      <th scope="col">Admin</th>
                                      <th scope="col" class="text-center">Aksi</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($penjualan as $inv)
                                      <tr style="border-bottom:1px solid var(--border-subtle,#1E293B);">
                                          <td class="text-center text-muted fw-semibold">{{ $loop->iteration }}</td>

                                          <!-- No Invoice Custom -->
                                          <td>
                                              <div class="d-flex align-items-center">
                                                  <button type="button" class="row-expand-indicator" aria-label="Tampilkan rincian invoice" aria-expanded="false">
                                                      <i class="ri-arrow-right-s-line"></i>
                                                  </button>
                                                  <span class="custom-invoice-tag">
                                                      <i class="ri-hashtag"></i>{{ $inv->nomor_invoice }}
                                                  </span>
                                              </div>
                                          </td>

                                          <!-- Customer & Perusahaan -->
                                          <td>
                                              <div class="fw-semibold text-white" style="font-size: 13.5px;">{{ $inv->customer }}</div>
                                              @if(!empty($inv->perusahaan))
                                                  <div style="font-size: 11.5px; color: #94A3B8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><i class="ri-building-line me-1"></i>{{ $inv->perusahaan }}</div>
                                              @endif
                                          </td>

                                          <!-- No Telepon -->
                                          <td>
                                              @if($inv->no_telepon)
                                                  <span style="font-family: monospace; font-size: 13px; color: #CBD5E1; white-space: nowrap;">{{ $inv->no_telepon }}</span>
                                              @else
                                                  <span class="text-muted">-</span>
                                              @endif
                                          </td>

                                          <!-- Tanggal Penjualan -->
                                          <td data-order="{{ $inv->tgl_penjualan }}">
                                              <span class="text-white">{{ $inv->formatted_tgl_penjualan }}</span>
                                              @if ($inv->updated_at != $inv->created_at)
                                                  <br><span style="font-size:11px;color:var(--text-tertiary,#94A3B8);">Diperbarui: {{ $inv->formatted_tgl_update }}</span>
                                              @endif
                                          </td>

                                          <!-- Status Pembayaran: DESIGN.md §14.1 -->
                                          <td data-order="{{ $inv->status }}" data-search="{{ $inv->status }}" class="text-center">
                                              @if ($inv->status == 'Lunas')
                                                  <span class="badge badge--success badge-status--lunas">Lunas</span>
                                              @elseif($inv->status == 'Belum Lunas')
                                                  <span class="badge badge--warning badge-status--belum-lunas">Belum Lunas</span>
                                              @elseif($inv->status == 'Batal')
                                                  <span class="badge badge--danger badge-status--batal">Batal</span>
                                              @else
                                                  <span class="badge badge--neutral">{{ $inv->status }}</span>
                                              @endif
                                          </td>

                                          <!-- Alasan Batal -->
                                          <td>
                                              @if ($inv->status == 'Batal' && !empty($inv->alasan_batal))
                                                  <span class="badge badge--subtle text-danger" title="{{ $inv->alasan_batal }}" style="max-width: 180px; display: inline-flex; align-items: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; font-weight: 500; font-size: 12px; background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 6px; padding: 4px 8px;">
                                                      <i class="ri-error-warning-line me-1" style="font-size: 13px;"></i>
                                                      <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $inv->alasan_batal }}</span>
                                                  </span>
                                              @else
                                                  <span class="text-muted">-</span>
                                              @endif
                                          </td>

                                          <!-- Approval: DESIGN.md §14.1 -->
                                          <td data-order="{{ $inv->approval }}" class="text-center">
                                              @if ($inv->approval == 'Lock')
                                                  <span class="badge badge--warning badge--icon">
                                                      <i class="ri-lock-line"></i>Lock
                                                  </span>
                                              @else
                                                  <span class="badge badge--success badge--icon">
                                                      <i class="ri-lock-unlock-line"></i>Unlock
                                                  </span>
                                              @endif
                                          </td>

                                          <!-- Deskripsi Item Collapsible -->
                                          <td>
                                              @php
                                                  $invoiceItems = $inv->penjualanBarang ?? collect();
                                                  $deskripsiCount = $invoiceItems->count();
                                              @endphp

                                              <div class="table-cell-collapsible">
                                                  <ul class="list-unstyled mb-0">
                                                      @foreach($invoiceItems->take(2) as $pb)
                                                          <li title="{{ $pb->deskripsi_item }}">- {{ $pb->deskripsi_item }}</li>
                                                      @endforeach
                                                  </ul>

                                                  @if($deskripsiCount > 2)
                                                      <div class="description-extra-container">
                                                          <ul class="list-unstyled mb-0">
                                                              @foreach($invoiceItems->slice(2) as $pb)
                                                                  <li title="{{ $pb->deskripsi_item }}">- {{ $pb->deskripsi_item }}</li>
                                                              @endforeach
                                                          </ul>
                                                      </div>
                                                  @endif
                                              </div>

                                              @if($deskripsiCount > 2)
                                                  <button type="button" class="btn btn-link p-0 text-info fw-medium" style="font-size: 11px; text-decoration: none;" onclick="toggleCustomRowExtra(this)">
                                                      +{{ $deskripsiCount - 2 }} item lagi
                                                  </button>
                                              @endif
                                          </td>

                                          <!-- DP -->
                                          <td class="text-end" data-order="{{ (float)($inv->dp ?? 0) }}">
                                              <span style="font-family: monospace; font-size: 13px; color: #CBD5E1;">Rp {{ $inv->formatted_dp }}</span>
                                          </td>

                                          <!-- Total Pembayaran -->
                                          <td class="text-end" data-order="{{ (float)($inv->total_pembayaran ?? 0) }}">
                                              <span style="font-family: monospace; font-size: 13.5px; font-weight: 700; color: #34D399;">Rp {{ $inv->formatted_total_pembayaran }}</span>
                                          </td>

                                          <!-- Pembayaran Type -->
                                          <td class="text-center">
                                              @if ($inv->jenis_pembayaran == 'Cash Lunas' || $inv->jenis_pembayaran == 'Cash')
                                                  <span class="badge badge-payment--cash-lunas">{{ $inv->jenis_pembayaran }}</span>
                                              @elseif ($inv->jenis_pembayaran == 'Transfer Lunas')
                                                  <span class="badge badge-payment--transfer-lunas">{{ $inv->jenis_pembayaran }}</span>
                                              @elseif ($inv->jenis_pembayaran == 'Cash Belum Lunas')
                                                  <span class="badge badge-payment--cash-belum-lunas">{{ $inv->jenis_pembayaran }}</span>
                                              @elseif ($inv->jenis_pembayaran == 'Transfer DP')
                                                  <span class="badge badge-payment--transfer-dp">{{ $inv->jenis_pembayaran }}</span>
                                              @elseif ($inv->jenis_pembayaran == 'PO')
                                                  <span class="badge badge-payment--po">{{ $inv->jenis_pembayaran }}</span>
                                              @else
                                                  <span class="badge badge--neutral">{{ $inv->jenis_pembayaran }}</span>
                                              @endif
                                          </td>

                                          <!-- Admin -->
                                          <td><span style="color:#CBD5E1;">{{ $inv->user ? $inv->user->nama : "-" }}</span></td>

                                          <!-- Aksi Dropdown -->
                                          <td class="text-center">
                                          @php
                                              $roleTkb    = Auth::user()->role;
                                              $isOwnerTkb = $roleTkb === 'Pemilik';
                                              $isAdminTkb = $roleTkb === 'Admin';

                                              // Nomor WA Owner Tokabe
                                              $waOwner1Tkb = '628116029999';
                                              $waOwner2Tkb = '628116179999';

                                              // Pesan Request Unlock
                                              $msgUnlockTkb = urlencode("Halo, saya ingin meminta approval untuk membuka akses invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman approval:\n" . url("/tokabe-approval/{$inv->id}"));

                                              // Pesan Request Pelunasan
                                              $msgLunasTkb = urlencode("Halo, saya ingin Request perubahan status pelunasan untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman ubah status pelunasan:\n" . url("/tokabe-pelunasan/{$inv->id}"));

                                               // Pesan Request Batal
                                               $msgBatalTkb = urlencode("Halo, saya ingin Request pembatalan untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman pembatalan invoice:\n" . url("/tokabe-batal/{$inv->id}"));

                                               // Pesan Request Hapus
                                               $msgHapusTkb = urlencode("Halo, saya ingin meminta persetujuan untuk MENGHAPUS invoice custom *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman konfirmasi hapus invoice:\n" . url("/tokabe-delete/{$inv->id}"));

                                              // Format Nomor HP Customer
                                              $no_hp_cust = $inv->no_telepon ?? '';
                                              $no_hp_cust = preg_replace('/[^0-9]/', '', $no_hp_cust);
                                              if(substr($no_hp_cust, 0, 1) == '0'){
                                                  $no_hp_cust = '62' . substr($no_hp_cust, 1);
                                              }

                                              // Pesan WA Customer
                                              $link_khusus_customer = url('/view/download/invoiceTKB/' . $inv->id);
                                              $pesan_cust = "Invoice : " . $inv->nomor_invoice . "\n";
                                              $pesan_cust .= "Link Download Invoice:\n";
                                              $pesan_cust .= $link_khusus_customer . "\n\n";
                                              $pesan_cust .= "Total Rp " . number_format($inv->total_harga, 0, ',', '.') . "\n";
                                              if($inv->ppn > 0) {
                                                  $nominal_ppn = $inv->total_harga * $inv->ppn / 100;
                                                  $pesan_cust .= "PPN (" . $inv->ppn . "%) Rp " . number_format($nominal_ppn, 0, ',', '.') . "\n";
                                                  $pesan_cust .= "-------------------------------------------\n";
                                                  $pesan_cust .= "*Total Pembayaran Rp " . number_format($inv->total_pembayaran, 0, ',', '.') . "*\n\n";
                                              } else {
                                                  $pesan_cust .= "\n";
                                              }
                                              if($inv->status !== 'Lunas' && $inv->sisa_pembayaran > 0) {
                                                   $pesan_cust .= "Mohon transfer ke:\n";
                                                   $rekDetail = null;
                                                   if (is_numeric($inv->no_rek)) {
                                                       $rekDetail = isset($rekenings) ? ($rekenings[$inv->no_rek] ?? null) : \App\Models\Rekening::find($inv->no_rek);
                                                   }
                                                   if ($rekDetail) {
                                                       $pesan_cust .= $rekDetail->formatted . "\n";
                                                   } elseif($inv->no_rek == "BNI") {
                                                       $pesan_cust .= "BNI | A/N : Yusni Kurniasih | No. Rek : 8331119999\n";
                                                   } elseif($inv->no_rek == "TKBBNI") {
                                                       $pesan_cust .= "BNI | A/N : PT. Total Karya Berkah | No. Rek : 3528289999\n";
                                                   } else {
                                                       $pesan_cust .= "BSI | A/N : PT. Total Karya Berkah | No. Rek : 3557999999\n";
                                                   }
                                                   $pesan_cust .= "**\n\nApabila sudah melakukan transaksi pembayaran mohon dikirim bukti transfernya";
                                               } else {
                                                   $pesan_cust .= "Status : Lunas\n**\n\nTerima kasih,\ntokabe.id";
                                               }

                                              $canManageTkb = !$isAdminTkb || $inv->approval === 'Unlock';
                                          @endphp

                                          <div class="dropdown d-inline-block">
                                              <button type="button" class="btn-table-action" aria-label="Aksi invoice" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window" data-bs-popper-config='{"strategy":"fixed"}'>
                                                  <i class="ri-more-2-fill" style="font-size:16px;"></i>
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-end" style="background:var(--bg-surface,#0F172A);border:1px solid var(--border-subtle,#1E293B);border-radius:12px;box-shadow:var(--shadow-md,0 4px 12px rgb(0 0 0/0.5));padding:4px;min-width:210px;">
                                                  {{-- CETAK --}}
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{route('cetak.inv.tkb',['id' => $inv->id, 'type' => 'asli'])}}" target="_blank">
                                                          <i class="ri-printer-line" style="font-size:16px;color:var(--brand,#6366F1);flex-shrink:0;"></i>Cetak Invoice (Asli)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{route('cetak.inv.tkb',['id' => $inv->id, 'type' => 'copy'])}}" target="_blank">
                                                          <i class="ri-printer-line" style="font-size:16px;color:var(--danger,#EF4444);flex-shrink:0;"></i>Cetak Invoice (Copy)
                                                      </a>
                                                  </li>

                                                  {{-- DOWNLOAD --}}
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{route('download.inv.tkb',['id' => $inv->id])}}" target="_blank">
                                                          <i class="ri-download-line" style="font-size:16px;color:var(--text-tertiary,#94A3B8);flex-shrink:0;"></i>Download
                                                      </a>
                                                  </li>

                                                  {{-- KIRIM WA CUSTOMER --}}
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="https://wa.me/{{ $no_hp_cust }}?text={{ urlencode($pesan_cust) }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Kirim Invoice (WA)
                                                      </a>
                                                  </li>

                                                  <li style="height:1px;margin:4px 0;background:var(--border-subtle,#1E293B);"></li>

                                                  {{-- === OWNER: Lock langsung jika sudah Unlock === --}}
                                                  @if($isOwnerTkb && $inv->approval === 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('tokabe.lock', $inv->id) }}">
                                                          <i class="ri-lock-line" style="font-size:16px;flex-shrink:0;"></i>Lock
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Unlock via WA jika masih Lock === --}}
                                                  @if($isAdminTkb && $inv->approval !== 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--unlock invoice-request--owner-1" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--warning-text,#FCD34D);font-weight:500;" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgUnlockTkb }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Unlock (Owner 1)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--unlock invoice-request--owner-2" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--warning-text,#FCD34D);font-weight:500;" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgUnlockTkb }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Unlock (Owner 2)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Pelunasan via WA === --}}
                                                  @if($isAdminTkb && $inv->status !== 'Lunas' && $inv->status !== 'Batal')
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--settlement invoice-request--owner-1" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--success,#10B981);font-weight:500;" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgLunasTkb }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Pelunasan (Owner 1)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--settlement invoice-request--owner-2" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--success,#10B981);font-weight:500;" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgLunasTkb }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Pelunasan (Owner 2)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Batal via WA === --}}
                                                  @if($isAdminTkb && $inv->status !== 'Batal')
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--cancel invoice-request--owner-1" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--danger,#EF4444);font-weight:500;" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgBatalTkb }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Batal (Owner 1)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--cancel invoice-request--owner-2" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--danger,#EF4444);font-weight:500;" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgBatalTkb }}" target="_blank">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Batal (Owner 2)
                                                      </a>
                                                  </li>
                                                  @endif

                                                   {{-- === ADMIN: Request Hapus via WA === --}}
                                                   @if($isAdminTkb)
                                                   <li>
                                                       <a class="dropdown-item invoice-request invoice-request--delete invoice-request--owner-1" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--danger,#EF4444);font-weight:500;" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgHapusTkb }}" target="_blank">
                                                           <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Hapus (Owner 1)
                                                       </a>
                                                   </li>
                                                   <li>
                                                       <a class="dropdown-item invoice-request invoice-request--delete invoice-request--owner-2" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--danger,#EF4444);font-weight:500;" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgHapusTkb }}" target="_blank">
                                                           <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Hapus (Owner 2)
                                                       </a>
                                                   </li>
                                                   @endif

                                                  {{-- === OWNER: Pelunasan langsung === --}}
                                                  @if($isOwnerTkb && $inv->status == 'Belum Lunas')
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('tokabe.pelunasan.page', $inv->id) }}">
                                                          <i class="ri-money-dollar-circle-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Pelunasan
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === EDIT: hanya jika Pemilik, atau Admin dengan approval Unlock === --}}
                                                  @if($canManageTkb)
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('edit.inv.tkb', $inv->id) }}">
                                                          <i class="ri-pencil-line" style="font-size:16px;color:var(--text-tertiary,#94A3B8);flex-shrink:0;"></i>Edit
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === OWNER: Pembatalan langsung === --}}
                                                  @if($isOwnerTkb && $inv->status !== 'Batal')
                                                  <li>
                                                      <button type="button" class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" onclick="confirmBatalTkb('{{ $inv->id }}', '{{ $inv->nomor_invoice }}')">
                                                          <i class="ri-folder-reduce-line" style="font-size:16px;color:var(--danger,#EF4444);flex-shrink:0;"></i>Invoice Batal
                                                      </button>
                                                      <form id="form-batal-tkb-{{ $inv->id }}" action="{{ route('tokabe.status.batal', $inv->id) }}" method="POST" style="display:none;">
                                                          @csrf
                                                          <input type="hidden" name="alasan_batal" id="input-alasan-tkb-{{ $inv->id }}">
                                                      </form>
                                                  </li>
                                                  @endif

                                                   {{-- === OWNER: Hapus Invoice langsung === --}}
                                                   @if($isOwnerTkb)
                                                   <li>
                                                       <a class="dropdown-item text-danger" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--danger,#EF4444);" href="{{ route('tokabe.delete.page', $inv->id) }}">
                                                           <i class="ri-delete-bin-line" style="font-size:16px;color:var(--danger,#EF4444);flex-shrink:0;"></i>Hapus Invoice
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
                      @endif
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
<script src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    let customDataTable = null;
    let currentCustomCardMode = 'date';
    let isCustomCardLoading = false;
    let allCustomExpanded = false;

    $(document).ready(function() {
        if ($('#scroll-horizontal-custom').length) {
            customDataTable = $('#scroll-horizontal-custom').DataTable({
                autoWidth: false,
                scrollX: false,
                ordering: true,
                bSort: true,
                order: [[4, 'desc']], // Urutkan tanggal penjualan descending
                columnDefs: [
                    { orderable: false, targets: [0, 3, 6, 8, 13] },
                    { width: '260px', targets: 8 },
                    { width: '80px', targets: 13, className: 'text-center' }
                ],
                dom: "<'dataTables_controls_top'lf><'table-responsive mb-0't><'dataTables_controls_bottom'ip>",
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                language: {
                    lengthMenu: "Tampilkan _MENU_ data",
                    search: "Cari:",
                    searchPlaceholder: "Nomor invoice, pelanggan...",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ invoice custom",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 invoice custom",
                    infoFiltered: "(disaring dari _MAX_ total invoice)",
                    zeroRecords: "Tidak ada invoice custom yang sesuai pencarian",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Nomor urut 1, 2, 3... selalu rapi berurutan saat sort/search
            customDataTable.on('order.dt search.dt', function () {
                customDataTable.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            });
        }

        // Toggle Expand All Rows
        $('#btn-toggle-all-custom-rows').on('click', function() {
            allCustomExpanded = !allCustomExpanded;
            if (allCustomExpanded) {
                $('#text-toggle-all-custom').text('Tutup Semua Rincian');
                $('#scroll-horizontal-custom tbody tr').addClass('is-expanded');
                $('.row-expand-indicator').addClass('expanded').attr('aria-expanded', 'true');
            } else {
                $('#text-toggle-all-custom').text('Buka Semua Rincian');
                $('#scroll-horizontal-custom tbody tr').removeClass('is-expanded');
                $('.row-expand-indicator').removeClass('expanded').attr('aria-expanded', 'false');
            }
        });

        // Click row expand indicator
        $(document).on('click', '#scroll-horizontal-custom .row-expand-indicator', function(e) {
            e.stopPropagation();
            const row = $(this).closest('tr');
            const isExp = row.hasClass('is-expanded');
            row.toggleClass('is-expanded', !isExp);
            $(this).toggleClass('expanded', !isExp).attr('aria-expanded', !isExp ? 'true' : 'false');
        });
    });

    function toggleCustomRowExtra(btn) {
        const row = $(btn).closest('tr');
        const isExp = row.hasClass('is-expanded');
        row.toggleClass('is-expanded', !isExp);
        row.find('.row-expand-indicator').toggleClass('expanded', !isExp);
    }

    // Quick Status Filter Tabs
    function filterCustomTableStatus(status) {
        $('.filter-tab-pill').removeClass('active');
        $(event.currentTarget).addClass('active');

        if (customDataTable) {
            if (status) {
                customDataTable.column(5).search('^' + status + '$', true, false).draw();
            } else {
                customDataTable.column(5).search('').draw();
            }
        }
    }

    // Custom Cards Filter Controls
    function toggleCustomFilterCustomCard() {
        const box = $('#custom-card-filter-box');
        box.slideToggle(180);
    }

    function setCustomCardFilterMode(mode) {
        currentCustomCardMode = mode;
        $('#btn-custom-mode-date, #btn-custom-mode-month, #btn-custom-mode-year').removeClass('active');
        $('#btn-custom-mode-' + mode).addClass('active');

        $('#wrapper-custom-filter-date').addClass('d-none').removeClass('d-inline-flex');
        $('#wrapper-custom-filter-month').addClass('d-none').removeClass('d-inline-flex');
        $('#wrapper-custom-filter-year').addClass('d-none').removeClass('d-inline-flex');

        if (mode === 'date') {
            $('#wrapper-custom-filter-date').removeClass('d-none').addClass('d-inline-flex');
        } else if (mode === 'month') {
            $('#wrapper-custom-filter-month').removeClass('d-none').addClass('d-inline-flex');
        } else if (mode === 'year') {
            $('#wrapper-custom-filter-year').removeClass('d-none').addClass('d-inline-flex');
        }
    }

    function switchCustomCardTimeframe(timeframe) {
        if (isCustomCardLoading) return;
        $('.btn-custom-card-time').removeClass('active');
        $('#btn-custom-card-' + timeframe).addClass('active');
        $('#custom-card-filter-box').slideUp(150);

        fetchCustomCardsData({ timeframe: timeframe });
    }

    function applyCustomCardCustomFilter() {
        let params = {
            timeframe: 'custom',
            mode: currentCustomCardMode
        };

        if (currentCustomCardMode === 'date') {
            params.date = $('#input-custom-filter-date').val();
            if (!params.date) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tanggal terlebih dahulu' });
                return;
            }
        } else if (currentCustomCardMode === 'month') {
            params.month = $('#select-custom-filter-month').val();
            params.year = $('#select-custom-filter-month-year').val();
        } else if (currentCustomCardMode === 'year') {
            params.year = $('#select-custom-filter-year').val();
        }

        $('.btn-custom-card-time').removeClass('active');
        $('#btn-custom-card-custom-toggle').addClass('active');

        fetchCustomCardsData(params);
    }

    function fetchCustomCardsData(params) {
        isCustomCardLoading = true;
        $('.kpi-card, .custom-kpi-card').css({ opacity: '0.45', transition: 'opacity 0.2s ease' });

        $.ajax({
            url: "{{ route('invoice.tokabe.custom.cards_data') }}",
            type: 'GET',
            data: params,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let label = res.periodLabel;
                    $('#custom-badge-active-period').text(label);

                    // Section 1: Non-PO
                    if (res.card1) {
                        $('#custom-card-val-1').text('Rp ' + res.card1.nominal);
                        $('#custom-card-title-1').text('JUMLAH SELURUH NON PO ' + label);
                        $('#custom-card-count-1').text(res.card1.count);
                        $('#custom-card-desc-1').text('Invoice Non PO Dibuat ' + label);
                    }
                    if (res.card2) {
                        $('#custom-card-val-2').text('Rp ' + res.card2.nominal);
                        $('#custom-card-title-2').text('NON PO TERBAYAR ' + label);
                        $('#custom-card-count-2').text(res.card2.count);
                        $('#custom-card-desc-2').text('Invoice Non PO Lunas ' + label);
                    }
                    if (res.card3) {
                        $('#custom-card-val-3').text('Rp ' + res.card3.nominal);
                        $('#custom-card-title-3').text('NON PO BELUM TERBAYAR ' + label);
                        $('#custom-card-count-3').text(res.card3.count);
                    }
                    if (res.card4) {
                        $('#custom-card-val-4').text('Rp ' + res.card4.nominal);
                        $('#custom-card-title-4').text('NON PO BELUM TERBAYAR BULAN ' + res.card4MonthName);
                        $('#custom-card-count-4').text(res.card4.count);
                    }

                    // Section 2: Purchase Order (PO)
                    if (res.cardPo1) {
                        $('#custom-card-po-val-1').text('Rp ' + res.cardPo1.nominal);
                        $('#custom-card-po-count-1').text(res.cardPo1.count);
                    }
                    if (res.cardPo2) {
                        $('#custom-card-po-val-2').text('Rp ' + res.cardPo2.nominal);
                        $('#custom-card-po-title-2').text('JUMLAH PO LUNAS ' + label);
                        $('#custom-card-po-count-2').text(res.cardPo2.count);
                        $('#custom-card-po-desc-2').text('Invoice PO Lunas ' + label);
                    }
                    if (res.cardPo3) {
                        $('#custom-card-po-val-3').text('Rp ' + res.cardPo3.nominal);
                        $('#custom-card-po-title-3').text('PO BELUM LUNAS ' + label);
                        $('#custom-card-po-count-3').text(res.cardPo3.count);
                    }
                    if (res.cardPo4) {
                        $('#custom-card-po-val-4').text('Rp ' + res.cardPo4.nominal);
                        $('#custom-card-po-title-4').text('PO BELUM LUNAS BULAN ' + res.card4MonthName);
                        $('#custom-card-po-count-4').text(res.cardPo4.count);
                    }

                    // Section 3: Batal
                    if (res.card5) {
                        $('#custom-card-val-5').text('Rp ' + res.card5.nominal);
                        $('#custom-card-count-5').text(res.card5.count);
                    }

                    // Section 4: Pajak
                    if (res.card6) {
                        $('#custom-card-val-6').text('Rp ' + res.card6.nominal);
                        $('#custom-card-title-6').text('JUMLAH TOTAL PPN ' + label);
                        $('#custom-card-count-6').text(res.card6.count);
                    }
                    if (res.card7) {
                        $('#custom-card-val-7').text('Rp ' + res.card7.nominal);
                        $('#custom-card-title-7').text('JUMLAH TOTAL PPH ' + label);
                        $('#custom-card-count-7').text(res.card7.count);
                    }
                    if (res.card8) {
                        $('#custom-card-val-8').text('Rp ' + res.card8.nominal);
                        $('#custom-card-count-8').text(res.card8.count);
                    }
                    if (res.card9) {
                        $('#custom-card-val-9').text('Rp ' + res.card9.nominal);
                        $('#custom-card-count-9').text(res.card9.count);
                    }
                    if (res.card10) {
                        $('#custom-card-val-10').text('Rp ' + res.card10.nominal);
                        $('#custom-card-count-10').text(res.card10.count);
                    }
                    if (res.card11) {
                        $('#custom-card-val-11').text('Rp ' + res.card11.nominal);
                        $('#custom-card-count-11').text(res.card11.count);
                    }
                }
            },
            error: function(xhr) {
                console.error('Error fetching Custom cards data:', xhr);
            },
            complete: function() {
                isCustomCardLoading = false;
                $('.kpi-card, .custom-kpi-card').css({ opacity: '1' });
            }
        });
    }

    function confirmBatalTkb(id, nomorInvoice) {
        Swal.fire({
            title: 'Pembatalan Invoice',
            text: "Masukkan alasan pembatalan untuk invoice " + nomorInvoice,
            input: 'textarea',
            inputPlaceholder: 'Tulis alasan batal di sini...',
            position: 'center',
            target: 'body',
            showCancelButton: true,
            confirmButtonText: 'Proses Batal',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#d33',
            inputValidator: (value) => {
                if (!value) return 'Alasan batal harus diisi!';
            },
            customClass: { popup: 'swal2-popup' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('input-alasan-tkb-' + id).value = result.value;
                document.getElementById('form-batal-tkb-' + id).submit();
            }
        });
    }
</script>
@endsection