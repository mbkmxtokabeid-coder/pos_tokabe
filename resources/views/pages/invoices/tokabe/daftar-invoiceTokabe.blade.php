@section('head')

<script src="https://cdn.lordicon.com/bhenfmcm.js" defer></script>

  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

<!--datatable responsive css-->

  {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" /> --}}


  <style>
    /* ==========================================================================
       KPI CARD — mengikuti DESIGN.md §12.4 Kartu Statistik
       Struktur: surface + border-subtle + radius-lg + shadow-xs
       Setiap varian hanya mengubah --kpi-accent (warna ikon & badge dot)
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

    /* Atur SweetAlert menjadi lebih besar dan muncul di tengah */

    .swal2-popup {

        width: 500px;

        background-color: rgba(0, 0, 0, 0.7);

        color: white;

    }

    

    /* ==========================================================================
       DATATABLES & TABLE MODEL — STYLING & SMOOTH HOVER ANIMATION
       Mengikuti DESIGN.md §13 (Tabel & Data), §18 (Motion & Animasi)
       ========================================================================== */

    #scroll-horizontal_wrapper {
        width: 100%;
    }

    /* DataTables Controls: Show Entries (kiri) & Search Bar (kanan) sejajar sempurna */
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

    #scroll-horizontal_wrapper .dataTables_length {
        float: none !important;
        margin-bottom: 0 !important;
        color: var(--text-secondary, #CBD5E1);
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    #scroll-horizontal_wrapper .dataTables_filter {
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

    #scroll-horizontal_wrapper .dataTables_length label,
    #scroll-horizontal_wrapper .dataTables_filter label {
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 0 !important;
        height: 36px !important;
    }

    #scroll-horizontal_wrapper .dataTables_length select {
        height: 36px !important;
        background-color: var(--bg-surface-2, #1E293B) !important;
        border: 1px solid var(--border-default, #334155) !important;
        color: var(--text-primary, #F1F5F9) !important;
        border-radius: var(--radius-md, 8px) !important;
        padding: 4px 28px 4px 10px !important;
        font-size: 13px !important;
        outline: none;
        cursor: pointer;
        transition: border-color var(--dur-fast, 120ms) ease, box-shadow var(--dur-fast, 120ms) ease;
    }

    #scroll-horizontal_wrapper .dataTables_length select:focus {
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
    }

    #scroll-horizontal_wrapper .dataTables_filter input {
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
        transition: border-color var(--dur-fast, 120ms) ease, box-shadow var(--dur-fast, 120ms) ease, width 200ms ease;
    }

    #scroll-horizontal_wrapper .dataTables_filter input:focus {
        border-color: var(--brand, #6366F1) !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
        width: 260px;
    }

    #scroll-horizontal {
        width: 100% !important;
        min-width: 1720px;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    /* Header Styling */
    #scroll-horizontal thead th {
        height: 44px !important;
        background: #172033 !important;
        color: var(--text-tertiary, #94A3B8) !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        letter-spacing: 0.07em !important;
        text-transform: uppercase !important;
        white-space: nowrap !important;
        border-bottom: 2px solid var(--border-default, #334155) !important;
        border-top: none !important;
        padding: 11px 14px !important;
        vertical-align: middle !important;
        cursor: default !important;
        background-image: none !important;
    }

    #scroll-horizontal thead th::before,
    #scroll-horizontal thead th::after {
        display: none !important;
        content: "" !important;
    }

    /* Body Cell Dimensions & Padding */
    #scroll-horizontal tbody td {
        box-sizing: border-box;
        padding: 12px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        font-size: 13px !important;
        background-color: transparent;
        line-height: 1.45;
    }

    /* Column Widths & Alignments */
    #scroll-horizontal th:nth-child(1), #scroll-horizontal td:nth-child(1) { width: 50px; text-align: center !important; }
    #scroll-horizontal th:nth-child(2), #scroll-horizontal td:nth-child(2) { width: 170px; text-align: left !important; }
    #scroll-horizontal th:nth-child(3), #scroll-horizontal td:nth-child(3) { width: 220px; text-align: left !important; }
    #scroll-horizontal th:nth-child(4), #scroll-horizontal td:nth-child(4) { width: 140px; text-align: left !important; }
    #scroll-horizontal th:nth-child(5), #scroll-horizontal td:nth-child(5) { width: 165px; text-align: left !important; }
    #scroll-horizontal th:nth-child(6), #scroll-horizontal td:nth-child(6) { width: 130px; text-align: center !important; }
    #scroll-horizontal th:nth-child(7), #scroll-horizontal td:nth-child(7) { width: 110px; text-align: center !important; }
    #scroll-horizontal th:nth-child(8), #scroll-horizontal td:nth-child(8) { width: 270px; text-align: left !important; }
    #scroll-horizontal th:nth-child(9), #scroll-horizontal td:nth-child(9) { width: 120px; text-align: right !important; font-variant-numeric: tabular-nums; }
    #scroll-horizontal th:nth-child(10), #scroll-horizontal td:nth-child(10) { width: 145px; text-align: right !important; font-variant-numeric: tabular-nums; font-weight: 600; }
    #scroll-horizontal th:nth-child(11), #scroll-horizontal td:nth-child(11) { width: 145px; text-align: center !important; }
    #scroll-horizontal th:nth-child(12), #scroll-horizontal td:nth-child(12) { width: 140px; text-align: left !important; }
    #scroll-horizontal th:nth-child(13), #scroll-horizontal td:nth-child(13) { width: 75px; text-align: center !important; }

    #scroll-horizontal tbody td:nth-child(2),
    #scroll-horizontal tbody td:nth-child(3),
    #scroll-horizontal tbody td:nth-child(4),
    #scroll-horizontal tbody td:nth-child(12) {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #scroll-horizontal tbody td:nth-child(2) .d-flex {
        min-width: 0;
    }

    #scroll-horizontal tbody td:nth-child(2) p {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 240ms cubic-bezier(0.2, 0, 0, 1);
    }

    #scroll-horizontal tbody td:nth-child(9),
    #scroll-horizontal tbody td:nth-child(10) {
        white-space: nowrap;
    }

    /* ------------------------------------------------------------------ */
    /* Baris Tabel: Animasi Masuk & Keluar Hover yang Sangat Smooth      */
    /* Warna serasi dengan tema dark mode (--bg-hover: #1E293B)          */
    /* ------------------------------------------------------------------ */
    #scroll-horizontal tbody tr {
        cursor: pointer;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    #scroll-horizontal tbody tr td {
        background-color: transparent;
        /* Transisi dua arah (hover masuk dan hover keluar yang halus) */
        transition:
            background-color 220ms cubic-bezier(0.2, 0, 0, 1),
            color 220ms cubic-bezier(0.2, 0, 0, 1),
            border-color 220ms cubic-bezier(0.2, 0, 0, 1);
    }

    /* State Hover: Warna Slate-800 (#1E293B) natural, serasi tanpa garis ungu aneh */
    #scroll-horizontal tbody tr:hover td {
        background-color: #1E293B !important;
    }

    #scroll-horizontal tbody tr:hover td:nth-child(2) p {
        color: #ffffff !important;
    }

    /* State Expanded: Baris sedang terbuka */
    #scroll-horizontal tbody tr.is-expanded td {
        background-color: #172336 !important;
    }

    #scroll-horizontal tbody tr.is-expanded td:nth-child(2) p {
        color: #ffffff !important;
    }

    /* Collapsible items di deskripsi dengan animasi expand/collapse yang halus */
    .table-cell-collapsible {
        max-width: 100%;
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
        text-overflow: ellipsis;
        white-space: nowrap;
        transition: color 200ms ease;
    }

    #scroll-horizontal tbody tr:hover .table-cell-collapsible li {
        color: #F1F5F9;
    }

    /* Container animasi untuk item rincian tambahan (> 2 item) */
    .description-extra-container {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transform: translateY(-4px);
        transition:
            max-height 320ms cubic-bezier(0.2, 0, 0, 1),
            opacity 240ms cubic-bezier(0.2, 0, 0, 1),
            transform 320ms cubic-bezier(0.2, 0, 0, 1);
    }

    #scroll-horizontal tbody tr.is-expanded .description-extra-container {
        max-height: 3000px;
        opacity: 1;
        transform: translateY(0);
        overflow: visible;
    }

    /* Saat baris terbuka, tampilkan teks secara penuh ke bawah tanpa terpotong */
    #scroll-horizontal tbody tr.is-expanded .table-cell-collapsible li {
        white-space: normal !important;
        word-break: break-word !important;
        text-overflow: clip !important;
    }

    /* Fallback jika ada list item langsung */
    .table-cell-collapsible .description-item--extra {
        display: none;
    }

    #scroll-horizontal tbody tr.is-expanded .table-cell-collapsible .description-item--extra {
        display: list-item;
    }

    .badge-more-items {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
        padding: 0;
        border: 0;
        background: transparent;
        color: var(--info-text, #93C5FD);
        font-size: 11px;
        line-height: 18px;
        cursor: pointer;
        transition:
            opacity 200ms ease,
            max-height 200ms ease,
            margin 200ms ease,
            color 150ms ease;
    }

    .badge-more-items:hover {
        color: #BFDBFE;
        text-decoration: underline;
    }

    .badge-more-items:focus-visible {
        outline: 2px solid var(--info-text, #93C5FD);
        outline-offset: 2px;
    }

    #scroll-horizontal tbody tr.is-expanded .badge-more-items {
        opacity: 0;
        max-height: 0;
        margin-top: 0;
        margin-bottom: 0;
        overflow: hidden;
        pointer-events: none;
    }

    /* Indikator Expand Chevron dengan transisi halus dan warna tonal elegan */
    .row-expand-indicator {
        display: inline-flex;
        width: 26px;
        height: 26px;
        flex: 0 0 26px;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        padding: 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-secondary, #CBD5E1);
        font-size: 15px;
        cursor: pointer;
        transition:
            transform 280ms cubic-bezier(0.2, 0, 0, 1),
            background-color 200ms ease,
            border-color 200ms ease,
            color 200ms ease;
    }

    #scroll-horizontal tbody tr:hover .row-expand-indicator {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    .row-expand-indicator:focus-visible {
        outline: 2px solid var(--info-text, #93C5FD);
        outline-offset: 2px;
    }

    #scroll-horizontal tbody tr.is-expanded .row-expand-indicator {
        transform: rotate(90deg);
        background: rgba(99, 102, 241, 0.2);
        border-color: rgba(99, 102, 241, 0.45);
        color: #A5B4FC;
    }

    /* Tombol toggle semua baris di header kartu */
    #btn-toggle-all-rows {
        transition:
            background-color 180ms ease,
            border-color 180ms ease,
            color 180ms ease,
            transform 120ms ease;
    }

    #btn-toggle-all-rows:hover {
        background: rgba(99, 102, 241, 0.22) !important;
        border-color: rgba(99, 102, 241, 0.5) !important;
        color: #A5B4FC !important;
        transform: translateY(-1px);
    }

    #btn-toggle-all-rows:active {
        transform: scale(0.98);
    }

    /* Dropdown action button di dalam baris */
    #scroll-horizontal tbody tr .dropdown button {
        transition:
            background-color 180ms ease,
            border-color 180ms ease,
            color 180ms ease,
            transform 150ms ease;
    }

    #scroll-horizontal tbody tr .dropdown button:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
        transform: scale(1.05);
    }

    #scroll-horizontal tbody tr .dropdown button:active {
        transform: scale(0.95);
    }

    /* Custom Modern Dark Scrollbar */
    #scroll-horizontal_wrapper .dataTables_scrollBody::-webkit-scrollbar {
        height: 7px;
    }

    #scroll-horizontal_wrapper .dataTables_scrollBody::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.6);
        border-radius: 4px;
    }

    #scroll-horizontal_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
        background: var(--border-default, #334155);
        border-radius: 4px;
        transition: background 150ms ease;
    }

    #scroll-horizontal_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }

    /* Bottom Controls: Info (kiri) & Pagination (kanan) sejajar sempurna */
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

    /* Pagination & Info */
    #scroll-horizontal_wrapper .dataTables_info {
        float: none !important;
        color: var(--text-tertiary, #94A3B8) !important;
        font-size: 13px !important;
        padding-top: 0 !important;
        margin-bottom: 0 !important;
    }

    #scroll-horizontal_wrapper .dataTables_paginate {
        float: none !important;
        padding-top: 0 !important;
        margin-bottom: 0 !important;
        margin-left: auto !important;
    }

    #scroll-horizontal_wrapper .pagination .page-item .page-link {
        background: var(--bg-surface-2, #1E293B);
        border: 1px solid var(--border-subtle, #1E293B);
        color: var(--text-secondary, #CBD5E1);
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 6px;
        margin: 0 2px;
        transition: all 180ms cubic-bezier(0.2, 0, 0, 1);
    }

    #scroll-horizontal_wrapper .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: var(--bg-hover, #334155);
        color: var(--text-primary, #F1F5F9);
        border-color: var(--border-default, #334155);
        transform: translateY(-1px);
    }

    #scroll-horizontal_wrapper .pagination .page-item.active .page-link {
        background: var(--brand, #6366F1) !important;
        border-color: var(--brand, #6366F1) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(99, 102, 241, 0.35);
    }

    #scroll-horizontal_wrapper .pagination .page-item.disabled .page-link {
        opacity: 0.4;
        background: transparent;
        border-color: transparent;
    }

    @media (max-width: 767.98px) {
        #scroll-horizontal_wrapper .dataTables_scroll {
            min-height: 0;
        }
    }

    /* Memastikan dropdown z-index berada paling atas */
    .dropdown-menu {
        z-index: 1050 !important;
    }

    /* ==========================================================================
       FILTER BAR — SEGMENTED CONTROL
       Mengikuti DESIGN.md: token Slate, radius-md (8px), dur-fast (120ms),
       ease-standard, focus-ring, brand primary-600 (#4F46E5).
       ========================================================================== */

    /* Container segmented — bg surface-2 (Slate-800 dark), border default (Slate-700 dark) */
    .segmented-control {
        display: inline-flex;
        align-items: center;
        background: var(--bg-surface-2, #1E293B);
        border: 1px solid var(--border-default, #334155);
        border-radius: var(--radius-md, 8px);
        padding: 3px;
        gap: 2px;
    }

    /* Tiap item: transparan → aktif mengikuti brand-subtle + border brand */
    .segmented-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: 1px solid transparent;
        color: var(--text-secondary, #CBD5E1);
        padding: 5px 12px;
        border-radius: var(--radius-sm, 6px);
        font-size: 13px;
        font-weight: 500;
        line-height: 1.4;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        /* DESIGN.md §18: hanya animasikan transform & opacity — tambah bg/color yang ringan */
        transition:
            color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            background-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            border-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            transform var(--dur-instant, 80ms) var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    .segmented-item:hover:not(.active) {
        color: var(--text-primary, #F1F5F9);
        background-color: var(--bg-hover, #1E293B);
    }
    /* press state — DESIGN.md §9.4 Active/Pressed: scale 0.98, 80ms */
    .segmented-item:active {
        transform: scale(0.98);
    }
    /* focus-visible — DESIGN.md §9.4: pakai --focus-ring */
    .segmented-item:focus-visible {
        outline: none;
        box-shadow: var(--focus-ring, 0 0 0 3px rgb(129 140 248 / 0.45));
    }
    /* State aktif: bg brand-subtle (dark mode = rgb transparan), border brand, teks putih */
    .segmented-item.active {
        background: var(--brand, #6366F1) !important;
        border-color: var(--brand, #6366F1) !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 1px 4px rgba(99, 102, 241, 0.45);
    }

    /* ------------------------------------------------------------------ */
    /* Tombol "Filter Khusus" — standalone di sebelah kanan segmented group */
    /* Mengikuti pola .btn--secondary dari DESIGN.md §9.5 */
    /* ------------------------------------------------------------------ */
    .btn-custom-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: var(--control-sm, 32px);
        padding: 0 12px;
        background: var(--bg-surface-2, #1E293B);
        color: var(--text-secondary, #CBD5E1);
        border: 1px solid var(--border-default, #334155);
        border-radius: var(--radius-md, 8px);
        font-size: 13px;
        font-weight: 500;
        line-height: 1;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        transition:
            color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            background-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            border-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            transform var(--dur-instant, 80ms) var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    .btn-custom-filter:hover {
        color: var(--text-primary, #F1F5F9);
        border-color: var(--border-strong, #475569);
        background-color: var(--bg-hover, #1E293B);
    }
    /* press */
    .btn-custom-filter:active {
        transform: scale(0.98);
        background-color: var(--bg-active, #334155);
    }
    /* focus-visible */
    .btn-custom-filter:focus-visible {
        outline: none;
        box-shadow: var(--focus-ring, 0 0 0 3px rgb(129 140 248 / 0.45));
    }
    /* State aktif (filter sedang berlaku) dan open (panel terbuka) */
    .btn-custom-filter.active,
    .btn-custom-filter.open {
        background-color: var(--brand, #6366F1) !important;
        border-color: var(--brand, #6366F1) !important;
        color: #ffffff !important;
        box-shadow: 0 1px 4px rgba(99, 102, 241, 0.45);
    }

    /* Chevron berputar — DESIGN.md §18.3 "chevron-rotate": 180° 150ms */
    .chevron-rotate {
        transition: transform 150ms var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    .btn-custom-filter.open .chevron-rotate {
        transform: rotate(180deg);
    }

    /* ------------------------------------------------------------------ */
    /* Panel Filter Kustom (collapsible) — DESIGN.md §15.4 drawer/popover  */
    /* Elevasi: bg surface-2, border default, shadow-sm, radius-lg (12px)  */
    /* ------------------------------------------------------------------ */
    .custom-filter-panel {
        background: var(--bg-surface-2, #1E293B) !important;
        border: 1px solid var(--border-default, #334155) !important;
        border-radius: var(--radius-lg, 12px) !important;
        box-shadow: var(--shadow-sm, 0 1px 3px rgb(0 0 0 / 0.5)) !important;
        /* slide-down — DESIGN.md §18.4 */
        animation: filter-panel-slide-down var(--dur-base, 200ms) var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    @keyframes filter-panel-slide-down {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ------------------------------------------------------------------ */
    /* Form controls di dalam panel — DESIGN.md §10.1 Text Input           */
    /* Tinggi 32 (sm), bg surface, border default, radius-md, focus-ring   */
    /* ------------------------------------------------------------------ */
    .dash-input,
    .dash-select {
        height: var(--control-sm, 32px);
        padding: 0 var(--space-3, 12px) !important;
        background: var(--bg-surface, #0F172A) !important;
        color: var(--text-primary, #F1F5F9) !important;
        border: 1px solid var(--border-default, #334155) !important;
        border-radius: var(--radius-md, 8px) !important;
        font-size: 13px !important;
        font-family: var(--font-sans, "Inter", system-ui, sans-serif);
        transition:
            border-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            box-shadow var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1));
    }
    .dash-input::placeholder {
        color: var(--text-tertiary, #94A3B8);
    }
    .dash-input:hover:not(:disabled),
    .dash-select:hover:not(:disabled) {
        border-color: var(--border-strong, #475569) !important;
    }
    .dash-input:focus,
    .dash-select:focus {
        outline: none !important;
        border-color: var(--border-focus, #6366F1) !important;
        box-shadow: var(--focus-ring, 0 0 0 3px rgb(129 140 248 / 0.45)) !important;
    }
    .dash-select option {
        background: var(--bg-surface-2, #1E293B);
        color: var(--text-primary, #F1F5F9);
    }

    /* ------------------------------------------------------------------ */
    /* Tombol "Tampilkan" — DESIGN.md §9.5 .btn--primary ukuran sm         */
    /* bg brand (#4F46E5 light / #6366F1 dark), teks putih, radius-md      */
    /* ------------------------------------------------------------------ */
    .btn-dash-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--space-2, 8px);
        height: var(--control-sm, 32px);
        padding: 0 var(--space-3, 12px);
        background: var(--brand, #4F46E5) !important;
        color: var(--text-on-primary, #ffffff) !important;
        border: 1px solid transparent !important;
        border-radius: var(--radius-md, 8px) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        font-family: var(--font-sans, "Inter", system-ui, sans-serif);
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
        transition:
            background-color var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            transform var(--dur-instant, 80ms) var(--ease-standard, cubic-bezier(.2,0,0,1)),
            box-shadow var(--dur-fast, 120ms) var(--ease-standard, cubic-bezier(.2,0,0,1)) !important;
    }
    .btn-dash-primary:hover {
        background: var(--brand-hover, #4338CA) !important;
    }
    /* press — DESIGN.md §9.4 */
    .btn-dash-primary:active {
        background: var(--brand-active, #3730A3) !important;
        transform: scale(0.98);
    }
    /* focus-visible */
    .btn-dash-primary:focus-visible {
        outline: none !important;
        box-shadow: var(--focus-ring, 0 0 0 3px rgb(129 140 248 / 0.45)) !important;
    }

    /* ==========================================================================
       TOMBOL AKSI UTAMA "TAMBAH INVOICE" — DESIGN.md §9 (.btn--primary)
       Warna: Brand Indigo (#6366F1), Radius 8px, Shadow & Hover halus
       ========================================================================== */
    .btn-create-invoice {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 38px;
        padding: 0 16px;
        background: var(--brand, #6366F1) !important;
        color: var(--text-on-primary, #ffffff) !important;
        border: 1px solid transparent;
        border-radius: var(--radius-md, 8px);
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none !important;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
        transition:
            background-color var(--dur-fast, 120ms) ease,
            transform var(--dur-instant, 80ms) ease,
            box-shadow var(--dur-fast, 120ms) ease;
    }
    .btn-create-invoice:hover {
        background: var(--brand-hover, #4F46E5) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45);
    }
    .btn-create-invoice:active {
        background: var(--brand-active, #4338CA) !important;
        transform: scale(0.98);
        box-shadow: 0 1px 3px rgba(99, 102, 241, 0.3);
    }
    .btn-create-invoice:focus-visible {
        outline: none;
        box-shadow: var(--focus-ring, 0 0 0 3px rgb(129 140 248 / 0.45));
    }

    /* ==========================================================================
       BUTTON ACCESSIBILITY & VISIBILITY (antislop-ui & ui-ux-pro-max)
       ========================================================================== */
    .btn-table-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-surface-2, #1E293B);
        border: 1px solid var(--border-default, #334155);
        border-radius: var(--radius-md, 8px);
        color: var(--text-secondary, #CBD5E1);
        cursor: pointer;
        transition: all var(--dur-fast, 120ms) var(--ease-standard);
    }
    .btn-table-action:hover,
    .btn-table-action[aria-expanded="true"] {
        background: var(--brand, #6366F1);
        border-color: var(--brand, #6366F1);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
    }
    .btn-table-action:active {
        transform: scale(0.96);
    }
    .btn-table-action:focus-visible {
        outline: none;
        box-shadow: var(--focus-ring, 0 0 0 3px rgba(129, 140, 248, 0.45));
    }

    /* Dropdown menu styling for high-contrast visibility */
    .dropdown-menu {
        background: var(--bg-surface-2, #1E293B) !important;
        border: 1px solid var(--border-default, #334155) !important;
        border-radius: var(--radius-lg, 12px) !important;
        box-shadow: var(--shadow-lg, 0 10px 25px -5px rgba(0, 0, 0, 0.5)) !important;
        padding: 6px !important;
        z-index: 1050 !important;
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

    #btn-toggle-all-rows {
        transition: all var(--dur-fast, 120ms) var(--ease-standard) !important;
    }
    #btn-toggle-all-rows:hover {
        background: var(--brand, #6366F1) !important;
        color: #ffffff !important;
        border-color: var(--brand, #6366F1) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
    }
    #btn-toggle-all-rows:active {
        transform: scale(0.98);
    }
    #btn-toggle-all-rows:focus-visible {
        outline: none;
        box-shadow: var(--focus-ring, 0 0 0 3px rgba(129, 140, 248, 0.45));
    }
</style>

@endsection

@extends('layout.template')

@section('content')

@if (session()->has('batal'))

        <script>

             Swal.fire({

            icon: 'success',

            title: 'Invoice Dibatalkan',

            text: '{{ session('batal') }}',

            confirmButtonText: 'OK'

        });

        </script>

    @elseif(session()->has('error'))

        <script>

            Swal.fire({

            icon: 'error',

            title: 'Gagal!',

            text: '{{ session('error') }}',

            confirmButtonText: 'OK'

        });

        </script>

    @elseif(session()->has('lunas'))

        <script>

            Swal.fire({

            icon: 'success',

            title: 'Invoice Lunas',

            text: '{{ session('lunas') }}',

            confirmButtonText: 'OK'

        });

        </script>

    @endif

<div class="page-content">

  <div class="container-fluid">



      <!-- Header Halaman & Aksi Tambah Invoice — Mengikuti DESIGN.md §5 & §9 -->
      <div class="row mb-3">
          <div class="col-12">
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-2">
                  <div>
                      <div class="d-flex align-items-center gap-2">
                          <h4 class="mb-0" style="font-size: 20px; font-weight: 700; color: var(--text-primary, #F1F5F9); letter-spacing: -0.015em;">Invoice Tokabe</h4>
                          <span style="display:inline-flex;align-items:center;height:20px;padding:0 8px;font-size:11px;font-weight:600;border-radius:9999px;background:var(--brand-subtle,rgba(99,102,241,0.12));color:#A5B4FC;border:1px solid rgba(99,102,241,0.3);">Tokabe POS</span>
                      </div>
                      <p class="mb-0 mt-1" style="font-size: 13px; color: var(--text-tertiary, #94A3B8);">Daftar data transaksi dan status pembayaran invoice Tokabe</p>
                  </div>
                  <div>
                      <a href="{{ route('add.inv.tkb') }}" class="btn-create-invoice">
                          <i class="ri-add-line fs-18"></i>Tambah Invoice
                      </a>
                  </div>
              </div>
          </div>
      </div>

      @if (Auth::user()->role === 'Pemilik' || Auth::user()->role === 'Admin')
       <!-- Filter Bar Terhubung untuk Semua Card — Mengikuti DESIGN.md §12.1 Card Surface & §14 Badge -->
       <div class="row mb-4">
           <div class="col-12">
               <div class="card" style="background: var(--bg-surface, #0F172A); border: 1px solid var(--border-subtle, #1E293B); border-radius: var(--radius-lg, 12px); box-shadow: var(--shadow-xs, 0 1px 2px rgb(0 0 0 / 0.5));">
                   <div class="card-body p-3">
                       <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                           <div class="d-flex align-items-center gap-2">
                               <span style="font-size: 13px; font-weight: 500; color: var(--text-secondary, #CBD5E1); display: inline-flex; align-items: center; gap: 6px;">
                                   <i class="ri-calendar-line" style="color: var(--brand, #6366F1); font-size: 16px;"></i>Filter Periode Card:
                               </span>
                               <span class="kpi-badge" id="badge-active-period">
                                   Tahun {{ date('Y') }}
                               </span>
                           </div>
                           
                           <!-- Button Timeframes -->
                           <div class="d-flex flex-wrap align-items-center gap-2">
                               <div class="segmented-control" role="group">
                                   <button type="button" class="segmented-item btn-filter-card-time" id="btn-card-today" onclick="switchTokabeCardTimeframe('today')" title="Filter Hari Ini">Hari Ini</button>
                                   <button type="button" class="segmented-item btn-filter-card-time" id="btn-card-week" onclick="switchTokabeCardTimeframe('week')" title="Filter Minggu Ini">Minggu Ini</button>
                                   <button type="button" class="segmented-item btn-filter-card-time" id="btn-card-month" onclick="switchTokabeCardTimeframe('month')" title="Filter Bulan Ini">Bulan Ini</button>
                                   <button type="button" class="segmented-item btn-filter-card-time active" id="btn-card-year" onclick="switchTokabeCardTimeframe('year')" title="Filter Tahun Ini">Tahun Ini</button>
                                   <button type="button" class="segmented-item btn-filter-card-time" id="btn-card-last_year" onclick="switchTokabeCardTimeframe('last_year')" title="Filter Tahun Lalu">Tahun Lalu</button>
                               </div>
                               <button type="button" class="btn-custom-filter" id="btn-card-custom-toggle" onclick="toggleCustomFilterCard()" title="Filter Khusus Tanggal, Bulan, atau Tahun">
                                   <i class="ri-filter-3-line"></i> Filter Khusus <i class="ri-arrow-down-s-line chevron-rotate" id="icon-toggle-card-custom"></i>
                               </button>
                           </div>
                       </div>

                       <!-- Custom Filter Panel (Toggleable) -->
                       <div id="card-custom-filter-box" class="custom-filter-panel mt-3 p-3" style="display: none;">
                           <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                               <div class="d-flex flex-wrap align-items-center gap-2">
                                   <span class="fs-12 fw-semibold text-muted text-uppercase" style="letter-spacing: 0.06em;">
                                       <i class="ri-filter-line me-1" style="color: var(--brand, #4F46E5);"></i>Pilih Spesifik:
                                   </span>
                                   
                                   <!-- Mode selector segmented -->
                                   <div class="segmented-control" role="group">
                                       <button type="button" class="segmented-item active" id="btn-card-mode-date" onclick="setCardCustomFilterMode('date')">
                                           <i class="ri-calendar-event-line"></i>Tanggal
                                       </button>
                                       <button type="button" class="segmented-item" id="btn-card-mode-month" onclick="setCardCustomFilterMode('month')">
                                           <i class="ri-calendar-2-line"></i>Bulan
                                       </button>
                                       <button type="button" class="segmented-item" id="btn-card-mode-year" onclick="setCardCustomFilterMode('year')">
                                           <i class="ri-calendar-check-line"></i>Tahun
                                       </button>
                                   </div>

                                   <!-- Form Control: Tanggal -->
                                   <div id="wrapper-card-filter-date" class="d-inline-flex align-items-center">
                                       <input type="date" class="form-control form-control-sm dash-input" id="input-card-filter-date" value="{{ date('Y-m-d') }}">
                                   </div>

                                   <!-- Form Control: Bulan -->
                                   <div id="wrapper-card-filter-month" class="d-none align-items-center gap-1">
                                       <select class="form-select form-select-sm dash-select" id="select-card-filter-month" style="min-width: 125px;">
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
                                       <select class="form-select form-select-sm dash-select" id="select-card-filter-month-year" style="min-width: 95px;">
                                           @foreach($availableYears ?? [date('Y'), date('Y') - 1] as $yr)
                                               <option value="{{ $yr }}" {{ $yr == $curYear ? 'selected' : '' }}>{{ $yr }}</option>
                                           @endforeach
                                       </select>
                                   </div>

                                   <!-- Form Control: Tahun -->
                                   <div id="wrapper-card-filter-year" class="d-none align-items-center">
                                       <select class="form-select form-select-sm dash-select" id="select-card-filter-year" style="min-width: 105px;">
                                           @foreach($availableYears ?? [date('Y'), date('Y') - 1] as $yr)
                                               <option value="{{ $yr }}" {{ $yr == $curYear ? 'selected' : '' }}>{{ $yr }}</option>
                                           @endforeach
                                       </select>
                                   </div>

                                   <!-- Tampilkan button -->
                                   <button type="button" class="btn-dash-primary btn" id="btn-apply-card-custom-filter" onclick="applyCustomFilterCard()">
                                       <i class="ri-search-line me-1"></i>Tampilkan
                                   </button>
                               </div>
                               <!-- Close button -->
                               <div>
                                   <button type="button" class="btn btn-ghost-secondary btn-sm" onclick="toggleCustomFilterCard()" title="Tutup Filter Khusus">
                                       <i class="ri-close-line fs-16 align-middle"></i>
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
                          <p class="kpi-card__overline" id="tokabe-card-title-1">Jumlah Seluruh Non PO Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-file-text-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-1">Rp {{ number_format($jumlahPerTahun, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-1">{{ $jlhInvoice }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-1">Invoice Non PO Dibuat Tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 2: Jumlah Non PO Terbayar --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--success">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="tokabe-card-title-2">Non PO Terbayar Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-checkbox-circle-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-2">Rp {{ number_format($totalLunasThn, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-2">{{ $lunas }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-2">Invoice Non PO Lunas Tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 3: Jumlah Non PO Belum Terbayar Tahun --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="tokabe-card-title-3">Non PO Belum Terbayar Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-time-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-3">Rp {{ number_format($jumlahBBPerTahun, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-3">{{ $belumLunas }}</span>
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
                          <p class="kpi-card__overline" id="tokabe-card-title-4">Non PO Belum Terbayar Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-calendar-close-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-4">Rp {{ number_format($jumlahBBPerBulan, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-4">{{ $belumLunasMonth }}</span>
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
                          <p class="kpi-card__overline" id="tokabe-card-po-title-1">Jumlah Seluruh PO</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-bank-card-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-po-val-1">Rp {{ number_format($jumlahPO, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-po-count-1">{{ $countAllInvPO }}</span>
                          <p class="kpi-card__label" id="tokabe-card-po-desc-1">Invoice PO Aktif</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card PO 2: Jumlah PO Lunas Tahun Ini --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--success">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="tokabe-card-po-title-2">Jumlah PO Lunas Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-checkbox-circle-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-po-val-2">Rp {{ number_format($jumlahPOLunas, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-po-count-2">{{ $countAllSettledPO }}</span>
                          <p class="kpi-card__label" id="tokabe-card-po-desc-2">Invoice PO Lunas Tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card PO 3: Jumlah PO Belum Lunas Tahun Ini --}}
          <div class="col-xl-3 col-md-6">
              <div class="kpi-card kpi-card--warning">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="tokabe-card-po-title-3">PO Belum Lunas Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-error-warning-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-po-val-3">Rp {{ number_format($jumlahPOBlmLunas, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-po-count-3">{{ $countAllOutstandingPO }}</span>
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
                          <p class="kpi-card__overline" id="tokabe-card-po-title-4">PO Belum Lunas Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-hourglass-2-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-po-val-4">Rp {{ number_format($jumlahBBPerBulanPO, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-po-count-4">{{ $blmLunasPO }}</span>
                          <p class="kpi-card__label">Invoice PO Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- SECTION 3: RINGKASAN PEMBATALAN & PENGHAPUSAN (2 Cards) -->
      <div class="kpi-section-header">
          <span class="kpi-section-badge kpi-section-badge--danger" aria-hidden="true">
              <i class="ri-close-circle-line"></i> Pembatalan & Penghapusan
          </span>
          <p class="kpi-section-title">Status Batal & Dihapus Invoice Tokabe</p>
      </div>
      <div class="row g-3 mb-1">
          {{-- Card 5: Invoice Dibatalkan --}}
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="tokabe-card-title-5">Invoice Dibatalkan</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-close-circle-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-5">Rp {{ number_format($jumlahBatal, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-5">{{ $batal }}</span>
                          <p class="kpi-card__label">Invoice Tokabe Dibatalkan</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card Hapus: Invoice Dihapus --}}
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline" id="tokabe-card-title-hapus">Invoice Dihapus</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-delete-bin-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-hapus">Rp {{ number_format($jumlahHapus, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-hapus">{{ $hapus }}</span>
                          <p class="kpi-card__label">Invoice Tokabe Dihapus</p>
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
                          <p class="kpi-card__overline" id="tokabe-card-title-6">Jumlah Total PPN Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-percent-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-6">Rp {{ number_format($ppnTotal, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-6">{{ $ppnTotalCount }}</span>
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
                          <p class="kpi-card__overline" id="tokabe-card-title-7">Jumlah Total PPh Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-file-chart-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value" id="tokabe-card-val-7">Rp {{ number_format($pphTotal, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-7">{{ $pphTotalCount }}</span>
                          <p class="kpi-card__label">Invoice Tokabe dengan PPh</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- ROW 3: 4 Cards (Rincian Kode PPN 01, 02, 03, 04) -->
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
                      <p class="kpi-card__value" id="tokabe-card-val-8">Rp {{ number_format($ppn01, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-8">{{ $ppn01Count }}</span>
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
                      <p class="kpi-card__value" id="tokabe-card-val-9">Rp {{ number_format($ppn02, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-9">{{ $ppn02Count }}</span>
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
                      <p class="kpi-card__value" id="tokabe-card-val-10">Rp {{ number_format($ppn03, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-10">{{ $ppn03Count }}</span>
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
                      <p class="kpi-card__value" id="tokabe-card-val-11">Rp {{ number_format($ppn04, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-11">{{ $ppn04Count }}</span>
                          <p class="kpi-card__label">04 — DPP Nilai Lain</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      @else
      <!-- Non-Pemilik: Belum Lunas Saja -->
      <div class="row g-3 mb-3">
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline">Belum Terbayar Tahun {{ date('Y') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-time-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value">Rp {{ number_format($jumlahBBPerTahun, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge">{{ $belumLunas }}</span>
                          <p class="kpi-card__label">Invoice Tokabe Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card kpi-card--danger">
                  <div class="kpi-card__body">
                      <div class="kpi-card__header">
                          <p class="kpi-card__overline">Belum Terbayar Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                          <div class="kpi-card__icon" aria-hidden="true">
                              <i class="ri-calendar-close-line"></i>
                          </div>
                      </div>
                      <p class="kpi-card__value">Rp {{ number_format($jumlahBBPerBulan, 0, ',', '.') }}</p>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge">{{ $belumLunasMonth }}</span>
                          <p class="kpi-card__label">Invoice Tokabe Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      @endif



      <div class="row">

          <div class="col-12">

              <div class="card" style="background:var(--bg-surface,#0F172A);border:1px solid var(--border-subtle,#1E293B);border-radius:var(--radius-lg,12px);box-shadow:var(--shadow-xs,0 1px 2px rgb(0 0 0/0.5));">

                  <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2" style="padding:var(--space-4,16px) var(--space-5,20px);border-bottom:1px solid var(--border-subtle,#1E293B);background:var(--bg-surface-2,#1E293B);border-radius:var(--radius-lg,12px) var(--radius-lg,12px) 0 0;">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="card-title mb-0" style="font-size:15px;font-weight:600;color:var(--text-primary,#F1F5F9);">Daftar Invoice Tokabe</h5>
                        <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--info-subtle,rgb(59 130 246/0.12));color:var(--info-text,#93C5FD);border:1px solid var(--info-border,rgb(59 130 246/0.35));"><i class="ri-information-line"></i>Klik baris untuk melihat rincian lengkap</span>
                    </div>
                    <div>
                        <button type="button" id="btn-toggle-all-rows" style="display:inline-flex;align-items:center;gap:6px;height:32px;padding:0 12px;font-size:13px;font-weight:500;background:var(--brand-subtle,rgb(99 102 241/0.12));color:var(--brand,#6366F1);border:1px solid rgb(99 102 241/0.3);border-radius:8px;cursor:pointer;"><i class="ri-expand-diagonal-line"></i><span id="text-toggle-all-rows">Buka Semua Rincian</span></button>
                    </div>
                  </div>

                      <div class="card-body p-0">
                          <table id="scroll-horizontal" class="table align-middle table-hover mb-0" aria-label="Daftar invoice Tokabe">

                              <thead>

                                  <tr style="background:var(--bg-surface-2,#1E293B);">

                                      <th scope="col">No</th>

                                      <th scope="col">Invoice ID</th>

                                      <th scope="col">Nama Pelanggan</th>

                                      <th scope="col">No. Telepon</th>

                                      <th scope="col">Tanggal Penjualan</th>

                                      <th scope="col">Status</th>

                                      <th scope="col">Approval</th>

                                       <th scope="col">Deskripsi</th>



                                      <th scope="col">Panjar</th>

                                      <th scope="col">Total</th>

                                      <th scope="col">Tipe Pembayaran</th>

                                      <th scope="col">Admin</th>

                                      <th scope="col">Aksi</th>

                                  </tr>

                              </thead>



                              <tbody>

                                @foreach ($penjualan as $inv)



                                  <tr style="border-bottom:1px solid var(--border-subtle,#1E293B);">

                                    <td class="text-center text-muted fw-semibold">{{$loop->iteration}}</td>

                                      <td>
                                          <div class="d-flex align-items-center">
                                              <button type="button" class="row-expand-indicator" aria-label="Tampilkan rincian invoice" aria-expanded="false">
                                                  <i class="ri-arrow-right-s-line"></i>
                                              </button>
                                              <p class="fw-medium mb-0 ms-1">{{$inv->nomor_invoice}}</p>
                                          </div>
                                      </td>

                                      <td><span class="text-white fw-medium">{{$inv->customer}}</span></td>

                                      <td><span style="font-family: monospace; font-size: 13px; color: #CBD5E1;">{{$inv->no_telepon ?: "-"}}</span></td>

                                      <td><span class="text-white">{{$inv->formatted_tgl_penjualan}}</span>

                                        @if ($inv->updated_at != $inv->created_at)

                                            <br><span style="font-size:11px;color:var(--text-tertiary,#94A3B8);">Diperbarui: {{ $inv->formatted_tgl_update }}</span>

                                        @endif</td>

                                      {{-- Badge Status --}}
                                      <td class="text-center">
                                          @if ($inv->status == 'Lunas')
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--success-subtle,rgb(16 185 129/0.12));color:var(--success-text,#6EE7B7);border:1px solid var(--success-border,rgb(16 185 129/0.35));"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>Lunas</span>
                                          @elseif($inv->status == 'Belum Lunas')
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--danger-subtle,rgb(239 68 68/0.12));color:var(--danger-text,#FCA5A5);border:1px solid var(--danger-border,rgb(239 68 68/0.35));"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>Belum Lunas</span>
                                          @else
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--bg-surface-2,#1E293B);color:var(--text-secondary,#CBD5E1);border:1px solid var(--border-subtle,#1E293B);"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>{{$inv->status}}</span>
                                          @endif
                                      </td>

                                      {{-- Badge Approval --}}
                                      <td class="text-center">
                                          @if($inv->approval == 'Unlock')
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--success-subtle,rgb(16 185 129/0.12));color:var(--success-text,#6EE7B7);border:1px solid var(--success-border,rgb(16 185 129/0.35));"><i class="ri-lock-unlock-line" style="font-size:11px;"></i>Unlock</span>
                                          @else
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--warning-subtle,rgb(245 158 11/0.12));color:var(--warning-text,#FCD34D);border:1px solid var(--warning-border,rgb(245 158 11/0.35));"><i class="ri-lock-line" style="font-size:11px;"></i>Lock</span>
                                          @endif
                                      </td>

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
                                              <button type="button" class="badge-more-items" aria-label="Tampilkan {{ $deskripsiCount - 2 }} item deskripsi lainnya">
                                                  <i class="ri-add-line" aria-hidden="true"></i>{{ $deskripsiCount - 2 }} item lagi
                                              </button>
                                          @endif

                                      </td>


                                      {{-- Panjar & Total: tabular-nums, rata kanan --}}
                                      <td style="text-align:right; font-variant-numeric:tabular-nums; color:#CBD5E1;">Rp {{$inv->formatted_dp}}</td>

                                      <td style="text-align:right; font-weight:600; font-variant-numeric:tabular-nums; color:#FFFFFF;">Rp {{$inv->formatted_total_pembayaran}}</td>

                                      {{-- Badge Tipe Pembayaran --}}
                                      <td class="text-center">
                                          @if ($inv->jenis_pembayaran == 'Cash Lunas' || $inv->jenis_pembayaran == 'Transfer Lunas')
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--brand-subtle,rgb(99 102 241/0.12));color:#A5B4FC;border:1px solid rgb(99 102 241/0.3);"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>{{$inv->jenis_pembayaran}}</span>
                                          @else
                                              <span style="display:inline-flex;align-items:center;gap:5px;height:22px;padding:0 8px;font-size:12px;font-weight:500;border-radius:9999px;background:var(--warning-subtle,rgb(245 158 11/0.12));color:var(--warning-text,#FCD34D);border:1px solid var(--warning-border,rgb(245 158 11/0.35));"><span style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;"></span>{{$inv->jenis_pembayaran}}</span>
                                          @endif
                                      </td>

                                      <td><span style="color:#CBD5E1;">{{ $inv->user ? $inv->user->nama : "-" }}</span></td>

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

                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--warning-text,#FCD34D);font-weight:500;" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgUnlockTkb }}" target="_blank">

                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Unlock (Owner 1)

                                                      </a>

                                                  </li>

                                                  <li>

                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--warning-text,#FCD34D);font-weight:500;" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgUnlockTkb }}" target="_blank">

                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Unlock (Owner 2)

                                                      </a>

                                                  </li>

                                                  @endif



                                                  {{-- === ADMIN: Request Pelunasan via WA === --}}

                                                  @if($isAdminTkb && $inv->status !== 'Lunas')

                                                  <li>

                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--warning-text,#FCD34D);font-weight:500;" href="https://wa.me/{{ $waOwner1Tkb }}?text={{ $msgLunasTkb }}" target="_blank">

                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Pelunasan (Owner 1)

                                                      </a>

                                                  </li>

                                                  <li>

                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--warning-text,#FCD34D);font-weight:500;" href="https://wa.me/{{ $waOwner2Tkb }}?text={{ $msgLunasTkb }}" target="_blank">

                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Request Pelunasan (Owner 2)

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



                                                  {{-- === EDIT & BATAL: hanya jika Pemilik, atau Admin dengan approval Unlock === --}}

                                                  @if($canManageTkb)

                                                  <li>

                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('edit.inv.tkb', $inv->id) }}">

                                                          <i class="ri-pencil-line" style="font-size:16px;color:var(--text-tertiary,#94A3B8);flex-shrink:0;"></i>Edit

                                                      </a>

                                                  </li>

                                                  @if($inv->status !== 'Batal')

                                                  <li>

                                                      <button type="button" class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" onclick="confirmBatalTkb('{{ $inv->id }}', '{{ $inv->nomor_invoice }}')">

                                                          <i class="ri-folder-reduce-line" style="font-size:16px;color:var(--text-tertiary,#94A3B8);flex-shrink:0;"></i>Invoice Batal

                                                      </button>

                                                      <form id="form-batal-tkb-{{ $inv->id }}" action="{{ route('tokabe.status.batal', $inv->id) }}" method="POST" style="display:none;">

                                                          @csrf

                                                          <input type="hidden" name="alasan_batal" id="input-alasan-tkb-{{ $inv->id }}">

                                                      </form>

                                                  </li>

                                                  @endif

                                                  @endif



                                                  {{-- DELETE --}}

                                                  <li style="height:1px;margin:4px 0;background:var(--border-subtle,#1E293B);"></li>

                                                  <li>

                                                      <form action="{{ route('delete.invoiceTokabe',['id'=> $inv->id]) }}" method="POST" id="deleteInv{{ $inv->id }}">

                                                          @csrf

                                                          @method('delete')

                                                          <button class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" type="button" onclick="showConfirmation('{{ $inv->nomor_invoice }}', '{{ $inv->id }}')">

                                                              <i class="ri-delete-bin-line" style="font-size:16px;flex-shrink:0;"></i>Hapus

                                                          </button>

                                                      </form>

                                                  </li>

                                              </ul>

                                          </div>

                                      </td>

                                  </tr>

                                  @endforeach



                              </tbody><!-- end tbody -->

                          </table><!-- end table -->

                  </div>

              </div>

          </div>





  </div>

  <!-- container-fluid -->

</div>









  </div>

  <!-- container-fluid -->

</div>

@endsection



@section('plugins')

{{-- Datatable --}}



<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> --}}





<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>

<script src="{{asset('js/halaman/daftar-invoice.js')}}"></script>



<script>

    $(document).ready(function() {

        const invoiceTable = $('#scroll-horizontal').DataTable({
            autoWidth: false,
            scrollX: false,
            ordering: false,
            bSort: false,
            order: [],
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
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ invoice",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 invoice",
                infoFiltered: "(disaring dari _MAX_ total invoice)",
                zeroRecords: "Tidak ada invoice yang sesuai dengan pencarian",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        invoiceTable.on('draw', function() {
            if (allExpanded) {
                $('#scroll-horizontal tbody tr').addClass('is-expanded');
                $('#scroll-horizontal .row-expand-indicator').attr('aria-expanded', 'true');
            }
            adjustInvoiceTable();
        });

        function adjustInvoiceTable() {
            window.requestAnimationFrame(function() {
                invoiceTable.columns.adjust();
            });
        }

        function setInvoiceRowExpanded(row, expanded) {
            const $row = $(row);
            $row.toggleClass('is-expanded', expanded);
            $row.find('.row-expand-indicator').attr('aria-expanded', expanded);
            adjustInvoiceTable();
            setTimeout(adjustInvoiceTable, 340);
        }

        invoiceTable.columns.adjust();

        // Klik baris tabel untuk expand / collapse rincian

        $(document).on('click.invoiceRows', '#scroll-horizontal tbody tr', function(e) {

            // Abaikan jika yang diklik adalah tombol, dropdown, link, input, atau aksi

            if ($(e.target).closest('a, button, input, select, textarea, .dropdown, .dropdown-menu, .btn').length) {

                return;

            }

            setInvoiceRowExpanded(this, !$(this).hasClass('is-expanded'));
            adjustInvoiceTable();
            setTimeout(adjustInvoiceTable, 340);

        });

        $(document).on('click.invoiceRows', '#scroll-horizontal .row-expand-indicator', function(e) {
            e.stopPropagation();
            const row = $(this).closest('tr');
            setInvoiceRowExpanded(row, !row.hasClass('is-expanded'));
            adjustInvoiceTable();
        });

        $(document).on('click.invoiceRows', '#scroll-horizontal .badge-more-items', function(e) {
            e.stopPropagation();
            setInvoiceRowExpanded($(this).closest('tr'), true);
            adjustInvoiceTable();
        });



        // Tombol buka / tutup semua rincian

        let allExpanded = false;

        $('#btn-toggle-all-rows').on('click', function() {

            allExpanded = !allExpanded;

            if (allExpanded) {

                $('#scroll-horizontal tbody tr').addClass('is-expanded');
                $('#scroll-horizontal .row-expand-indicator').attr('aria-expanded', 'true');

                $('#text-toggle-all-rows').text('Tutup Semua Rincian');

                $(this).find('i').removeClass('ri-expand-diagonal-line').addClass('ri-collapse-diagonal-line');

            } else {

                $('#scroll-horizontal tbody tr').removeClass('is-expanded');
                $('#scroll-horizontal .row-expand-indicator').attr('aria-expanded', 'false');

                $('#text-toggle-all-rows').text('Buka Semua Rincian');

                $(this).find('i').removeClass('ri-collapse-diagonal-line').addClass('ri-expand-diagonal-line');

            }

            adjustInvoiceTable();

        });

        $(window).off('resize.invoiceRows').on('resize.invoiceRows', adjustInvoiceTable);

    });



    function showConfirmation(invoice, invId) {

        Swal.fire({

            html: '<lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" colors="primary:#eee966,secondary:#c71f16" state="hover-2" style="width:200px;height:200px"></lord-icon>' +

                  '<p>Apakah Anda ingin menghapus invoice ' + invoice + '?</p>',

            showCancelButton: true,

            confirmButtonColor: '#c71f16',

            cancelButtonColor: '#eee966',

            cancelButtonText: 'Batal',

            confirmButtonText: 'Hapus',

            customClass: {

                popup: 'swal2-popup' 

            }

        }).then((result) => {

            if (result.isConfirmed) {

                document.getElementById('deleteInv' + invId).submit();

            }

        });

    }



    function confirmBatalTkb(id, nomorInvoice) {

        Swal.fire({

            title: 'Pembatalan Invoice',

            text: "Masukkan alasan pembatalan untuk invoice " + nomorInvoice,

            input: 'textarea',

            inputPlaceholder: 'Tulis alasan batal di sini...',

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


    // =========================================================================
    // FILTER TERHUBUNG UNTUK 11 CARD TOKABE
    // =========================================================================
    let currentCardCustomMode = 'date';
    let isCardLoading = false;

    function toggleCustomFilterCard() {
        const filterBox = document.getElementById('card-custom-filter-box');
        const toggleBtn = document.getElementById('btn-card-custom-toggle');
        if (!filterBox) return;

        const isOpen = filterBox.style.display !== 'none' && filterBox.style.display !== '';
        if (isOpen) {
            $(filterBox).slideUp(200, function() {
                filterBox.style.display = 'none';
            });
            if (toggleBtn) toggleBtn.classList.remove('open');
        } else {
            filterBox.style.display = 'block';
            $(filterBox).hide().slideDown(200);
            if (toggleBtn) toggleBtn.classList.add('open');
        }
    }

    function setCardCustomFilterMode(mode) {
        currentCardCustomMode = mode;

        // Update segmented-item active state pada mode selector
        ['date', 'month', 'year'].forEach(function(m) {
            const btn = document.getElementById('btn-card-mode-' + m);
            const wrapper = document.getElementById('wrapper-card-filter-' + m);
            if (btn) {
                if (m === mode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            }
            if (wrapper) {
                if (m === mode) {
                    wrapper.classList.remove('d-none');
                    wrapper.classList.add('d-inline-flex');
                } else {
                    wrapper.classList.remove('d-inline-flex', 'd-flex');
                    wrapper.classList.add('d-none');
                }
            }
        });
    }

    function switchTokabeCardTimeframe(timeframe) {
        if (isCardLoading) return;

        // Reset semua segmented-item ke state tidak aktif
        document.querySelectorAll('.btn-filter-card-time').forEach(function(btn) {
            btn.classList.remove('active');
        });
        // Aktifkan yang dipilih
        const activeBtn = document.getElementById('btn-card-' + timeframe);
        if (activeBtn) activeBtn.classList.add('active');

        // Tutup panel custom filter dan reset state tombol toggle
        const filterBox = document.getElementById('card-custom-filter-box');
        const toggleBtn = document.getElementById('btn-card-custom-toggle');
        if (filterBox && (filterBox.style.display !== 'none' && filterBox.style.display !== '')) {
            $(filterBox).slideUp(200, function() { filterBox.style.display = 'none'; });
        }
        if (toggleBtn) toggleBtn.classList.remove('open');

        fetchTokabeCardsData({ timeframe: timeframe });
    }

    function applyCustomFilterCard() {
        if (isCardLoading) return;

        let params = {
            timeframe: 'custom',
            mode: currentCardCustomMode
        };

        if (currentCardCustomMode === 'date') {
            params.date = $('#input-card-filter-date').val();
            if (!params.date) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Pilih tanggal terlebih dahulu' });
                } else {
                    alert('Pilih tanggal terlebih dahulu');
                }
                return;
            }
        } else if (currentCardCustomMode === 'month') {
            params.month = $('#select-card-filter-month').val();
            params.year = $('#select-card-filter-month-year').val();
        } else if (currentCardCustomMode === 'year') {
            params.year = $('#select-card-filter-year').val();
        }

        // Reset semua segmented-item, lalu aktifkan tombol custom toggle
        document.querySelectorAll('.btn-filter-card-time').forEach(function(btn) {
            btn.classList.remove('active');
        });
        const toggleBtn = document.getElementById('btn-card-custom-toggle');
        if (toggleBtn) {
            toggleBtn.classList.add('active');
            // Jangan tambah open saat apply - panel tetap terbuka sampai user menutupnya
        }

        fetchTokabeCardsData(params);
    }

    function fetchTokabeCardsData(params) {
        isCardLoading = true;
        $('.kpi-card, .tokabe-card').css({ opacity: '0.45', transition: 'opacity 0.2s ease' });

        $.ajax({
            url: "{{ route('invoice.tokabe.cards_data') }}",
            type: 'GET',
            data: params,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    let label = res.periodLabel;
                    $('#badge-active-period').text(label);

                    // Card Non-PO
                    $('#tokabe-card-val-1').text('Rp ' + res.card1.nominal);
                    $('#tokabe-card-title-1').text('JUMLAH SELURUH NON PO ' + label);
                    $('#tokabe-card-count-1').text(res.card1.count);
                    $('#tokabe-card-desc-1').text('Invoice Non PO Dibuat ' + label);

                    $('#tokabe-card-val-2').text('Rp ' + res.card2.nominal);
                    $('#tokabe-card-title-2').text('NON PO TERBAYAR ' + label);
                    $('#tokabe-card-count-2').text(res.card2.count);
                    $('#tokabe-card-desc-2').text('Invoice Non PO Lunas ' + label);

                    $('#tokabe-card-val-3').text('Rp ' + res.card3.nominal);
                    $('#tokabe-card-title-3').text('NON PO BELUM TERBAYAR ' + label);
                    $('#tokabe-card-count-3').text(res.card3.count);

                    $('#tokabe-card-val-4').text('Rp ' + res.card4.nominal);
                    $('#tokabe-card-title-4').text('NON PO BELUM TERBAYAR BULAN ' + res.card4MonthName);
                    $('#tokabe-card-count-4').text(res.card4.count);

                    // Card PO (Purchase Order)
                    if (res.cardPo1) {
                        $('#tokabe-card-po-val-1').text('Rp ' + res.cardPo1.nominal);
                        $('#tokabe-card-po-count-1').text(res.cardPo1.count);
                    }
                    if (res.cardPo2) {
                        $('#tokabe-card-po-val-2').text('Rp ' + res.cardPo2.nominal);
                        $('#tokabe-card-po-title-2').text('JUMLAH PO LUNAS ' + label);
                        $('#tokabe-card-po-count-2').text(res.cardPo2.count);
                        $('#tokabe-card-po-desc-2').text('Invoice PO Lunas ' + label);
                    }
                    if (res.cardPo3) {
                        $('#tokabe-card-po-val-3').text('Rp ' + res.cardPo3.nominal);
                        $('#tokabe-card-po-title-3').text('PO BELUM LUNAS ' + label);
                        $('#tokabe-card-po-count-3').text(res.cardPo3.count);
                    }
                    if (res.cardPo4) {
                        $('#tokabe-card-po-val-4').text('Rp ' + res.cardPo4.nominal);
                        $('#tokabe-card-po-title-4').text('PO BELUM LUNAS BULAN ' + res.card4MonthName);
                        $('#tokabe-card-po-count-4').text(res.cardPo4.count);
                    }

                    // Card 5: Dibatalkan
                    $('#tokabe-card-val-5').text('Rp ' + res.card5.nominal);
                    $('#tokabe-card-count-5').text(res.card5.count);

                    // Card Hapus: Dihapus
                    if (res.cardHapus) {
                        $('#tokabe-card-val-hapus').text('Rp ' + res.cardHapus.nominal);
                        $('#tokabe-card-count-hapus').text(res.cardHapus.count);
                    }

                    // Card 6: PPN Total
                    $('#tokabe-card-val-6').text('Rp ' + res.card6.nominal);
                    $('#tokabe-card-title-6').text('JUMLAH TOTAL PPN ' + label);
                    $('#tokabe-card-count-6').text(res.card6.count);

                    // Card 7: PPh Total
                    $('#tokabe-card-val-7').text('Rp ' + res.card7.nominal);
                    $('#tokabe-card-title-7').text('JUMLAH TOTAL PPH ' + label);
                    $('#tokabe-card-count-7').text(res.card7.count);

                    // Cards 8 - 11: Kode PPN
                    $('#tokabe-card-val-8').text('Rp ' + res.card8.nominal);
                    $('#tokabe-card-count-8').text(res.card8.count);

                    $('#tokabe-card-val-9').text('Rp ' + res.card9.nominal);
                    $('#tokabe-card-count-9').text(res.card9.count);

                    $('#tokabe-card-val-10').text('Rp ' + res.card10.nominal);
                    $('#tokabe-card-count-10').text(res.card10.count);

                    $('#tokabe-card-val-11').text('Rp ' + res.card11.nominal);
                    $('#tokabe-card-count-11').text(res.card11.count);
                }
            },
            error: function(xhr) {
                console.error('Error fetching Tokabe cards data:', xhr);
            },
            complete: function() {
                isCardLoading = false;
                $('.kpi-card, .tokabe-card').css({ opacity: '1' });
            }
        });
    }

</script>

@endsection
