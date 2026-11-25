@extends('layout.template')
@section('has_inline_theme_toggle', '1')

@section('head')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

  <style>
    /* ==========================================================================
       DAFTAR INVOICE TOKABE — EMERALD / FOREST MINT DESIGN (DARK & LIGHT)
       ========================================================================== */

    /* KPI Card Container */
    .kpi-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        margin-bottom: 0 !important;
        height: 100%;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        border-color: var(--border-strong);
    }

    .kpi-card__body {
        padding: 20px 22px !important;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 148px !important;
    }

    .kpi-card__top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .kpi-card__icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }
    .kpi-card__icon--cyan { background: var(--accent-cyan-subtle); color: var(--accent-cyan); }
    .kpi-card__icon--emerald { background: var(--brand-subtle); color: var(--brand); }
    .kpi-card__icon--amber { background: var(--warning-subtle); color: var(--warning-text); }
    .kpi-card__icon--rose { background: var(--danger-subtle); color: var(--danger-text); }

    .kpi-card__overline {
        font-size: 12.5px !important;
        font-weight: 500;
        color: var(--text-secondary);
        line-height: 18px;
        margin: 0;
    }

    .kpi-card__value {
        font-size: 26px !important;
        font-weight: 800;
        line-height: 1.2;
        color: var(--text-primary);
        margin: 0 0 14px 0;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }

    .kpi-card__value--success { color: var(--success-text) !important; }
    .kpi-card__value--warning { color: var(--warning-text) !important; }
    .kpi-card__value--danger { color: var(--danger-text) !important; }

    .kpi-card__footer {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: auto;
    }

    .kpi-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 22px;
        padding: 0 8px;
        font-size: 11.5px;
        font-weight: 700;
        border-radius: 9999px;
        background-color: var(--bg-surface-3);
        color: var(--text-primary);
        border: 1px solid var(--border-subtle);
        font-variant-numeric: tabular-nums;
        flex-shrink: 0;
    }
    .kpi-badge--emerald {
        background-color: var(--brand-subtle);
        color: var(--brand);
        border-color: var(--brand-border);
    }

    .kpi-card__label {
        font-size: 12px !important;
        font-weight: 400;
        color: var(--text-tertiary);
        margin: 0;
        line-height: 16px;
    }

    /* Section header */
    .kpi-section-header {
        margin-top: 22px;
        margin-bottom: 12px;
    }
    .kpi-section-header:first-of-type {
        margin-top: 4px;
    }
    .kpi-section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -0.01em;
        margin: 0 0 2px 0;
    }
    .kpi-section-subtitle {
        font-size: 12.5px;
        color: var(--text-tertiary);
        margin: 0;
        font-weight: 400;
    }

    /* Pajak: Rincian Kode PPN */
    .kpi-ppn-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 8px;
    }
    .kpi-ppn-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        line-height: 18px;
    }
    .kpi-ppn-label {
        color: var(--text-secondary);
        font-weight: 500;
    }
    .kpi-ppn-val {
        color: var(--text-primary);
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    /* Quick Filter Tab Pill Group */
    .tkb-status-pills {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        padding: 4px;
        border-radius: var(--radius-control);
        background: var(--bg-surface-2);
        border: 1px solid var(--border-default);
    }
    .filter-tab-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--text-secondary);
        background: transparent;
        border: 1px solid transparent;
        border-radius: var(--radius-sm);
        cursor: pointer;
    }
    .filter-tab-pill:hover {
        color: var(--text-primary);
        background: var(--bg-hover);
    }
    .filter-tab-pill.active {
        color: var(--brand);
        background: var(--brand-subtle);
        border-color: var(--brand-border);
        font-weight: 600;
    }
    [data-theme="light"] .filter-tab-pill.active {
        background: var(--brand);
        border-color: var(--brand);
        color: #FFFFFF !important;
    }
    [data-theme="light"] .filter-tab-pill.active i {
        color: #FFFFFF !important;
    }

    /* DataTables Controls */
    #scroll-horizontal_wrapper,
    #scroll-horizontal_wrapper .table-responsive {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    .dataTables_controls_top {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 16px !important;
        padding: 14px 20px !important;
        margin-bottom: 0 !important;
        background: var(--bg-surface) !important;
        border-bottom: 1px solid var(--border-subtle) !important;
        width: 100% !important;
    }

    #scroll-horizontal_wrapper .dataTables_length,
    #scroll-horizontal_wrapper .dataTables_filter {
        float: none !important;
        margin-bottom: 0 !important;
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
    #scroll-horizontal_wrapper .dataTables_filter {
        margin-left: auto !important;
        text-align: right !important;
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
        height: 34px !important;
        background-color: var(--bg-surface-2) !important;
        border: 1px solid var(--border-default) !important;
        color: var(--text-primary) !important;
        border-radius: var(--radius-control) !important;
        padding: 4px 28px 4px 10px !important;
        font-size: 13px !important;
        outline: none;
        cursor: pointer;
    }
    #scroll-horizontal_wrapper .dataTables_filter input {
        height: 36px !important;
        background-color: var(--bg-surface-2) !important;
        border: 1px solid var(--border-default) !important;
        color: var(--text-primary) !important;
        border-radius: var(--radius-control) !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
        outline: none;
        margin-left: 0 !important;
        width: 240px;
    }
    #scroll-horizontal_wrapper .dataTables_filter input:focus,
    #scroll-horizontal_wrapper .dataTables_length select:focus {
        border-color: var(--brand) !important;
        box-shadow: var(--focus-ring) !important;
    }

    #scroll-horizontal {
        width: 100% !important;
        min-width: 2135px !important;
        table-layout: fixed !important;
        margin-bottom: 0 !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
    }

    /* Table Header */
    #scroll-horizontal thead th {
        height: 44px !important;
        background: var(--bg-surface-2) !important;
        color: var(--text-tertiary) !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        white-space: nowrap !important;
        border-bottom: 1px solid var(--border-default) !important;
        border-top: none !important;
        padding: 11px 14px !important;
        vertical-align: middle !important;
        position: relative !important;
        user-select: none !important;
    }
    #scroll-horizontal thead th.sorting,
    #scroll-horizontal thead th.sorting_asc,
    #scroll-horizontal thead th.sorting_desc {
        cursor: pointer !important;
        padding-right: 28px !important;
    }
    #scroll-horizontal thead th.sorting:hover,
    #scroll-horizontal thead th.sorting_asc:hover,
    #scroll-horizontal thead th.sorting_desc:hover {
        background: var(--bg-surface-3) !important;
        color: var(--text-primary) !important;
    }
    #scroll-horizontal thead th.sorting_disabled {
        cursor: default !important;
        padding-right: 14px !important;
    }
    #scroll-horizontal thead th.sorting::after {
        content: "\f124" !important;
        font-family: 'remixicon' !important;
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 13px !important;
        color: var(--text-tertiary) !important;
        opacity: 0.45 !important;
    }
    #scroll-horizontal thead th.sorting_asc,
    #scroll-horizontal thead th.sorting_desc {
        color: var(--brand) !important;
        background: var(--bg-surface-2) !important;
    }
    #scroll-horizontal thead th.sorting_asc::after {
        content: "\ea78" !important;
        font-family: 'remixicon' !important;
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 14px !important;
        color: var(--brand) !important;
    }
    #scroll-horizontal thead th.sorting_desc::after {
        content: "\ea4c" !important;
        font-family: 'remixicon' !important;
        position: absolute !important;
        right: 8px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        font-size: 14px !important;
        color: var(--brand) !important;
    }
    #scroll-horizontal thead th::before,
    #scroll-horizontal thead th.sorting_disabled::after {
        display: none !important;
        content: "" !important;
    }

    /* Body Cells */
    #scroll-horizontal tbody td {
        box-sizing: border-box;
        padding: 13px 14px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--border-subtle) !important;
        font-size: 13px !important;
        background-color: transparent;
        color: var(--text-primary);
        line-height: 1.45;
    }

    /* Column Widths */
    #scroll-horizontal th:nth-child(1), #scroll-horizontal td:nth-child(1) { width: 48px; text-align: center !important; }
    #scroll-horizontal th:nth-child(2), #scroll-horizontal td:nth-child(2) { width: 175px; text-align: left !important; }
    #scroll-horizontal th:nth-child(3), #scroll-horizontal td:nth-child(3) { width: 220px; text-align: left !important; }
    #scroll-horizontal th:nth-child(4), #scroll-horizontal td:nth-child(4) { width: 140px; text-align: left !important; }
    #scroll-horizontal th:nth-child(5), #scroll-horizontal td:nth-child(5) { width: 175px; text-align: left !important; }
    #scroll-horizontal th:nth-child(6), #scroll-horizontal td:nth-child(6) { width: 140px; min-width: 135px; text-align: center !important; }
    #scroll-horizontal th:nth-child(7), #scroll-horizontal td:nth-child(7) { width: 130px; text-align: left !important; }
    #scroll-horizontal th:nth-child(8), #scroll-horizontal td:nth-child(8) { width: 240px !important; min-width: 230px !important; max-width: 260px !important; text-align: center !important; }
    #scroll-horizontal th:nth-child(9), #scroll-horizontal td:nth-child(9) { width: 320px; min-width: 280px; max-width: 450px; text-align: left !important; box-sizing: border-box !important; }
    #scroll-horizontal th:nth-child(10), #scroll-horizontal td:nth-child(10) { width: 130px; text-align: right !important; font-variant-numeric: tabular-nums; }
    #scroll-horizontal th:nth-child(11), #scroll-horizontal td:nth-child(11) { width: 155px; text-align: right !important; font-variant-numeric: tabular-nums; font-weight: 600; }
    #scroll-horizontal th:nth-child(12), #scroll-horizontal td:nth-child(12) { width: 150px; min-width: 140px; text-align: center !important; }
    #scroll-horizontal th:nth-child(13), #scroll-horizontal td:nth-child(13) { width: 155px; min-width: 145px; text-align: center !important; }
    #scroll-horizontal th:nth-child(14), #scroll-horizontal td:nth-child(14) { width: 140px; text-align: left !important; }

    /* Sticky Action Column */
    #scroll-horizontal th:nth-child(15),
    #scroll-horizontal td:nth-child(15) {
        width: 80px !important;
        min-width: 80px !important;
        max-width: 80px !important;
        text-align: center !important;
        position: sticky !important;
        right: 0 !important;
        background: var(--bg-surface) !important;
        box-shadow: -4px 0 12px rgba(0, 0, 0, 0.14) !important;
        z-index: 10 !important;
    }
    #scroll-horizontal thead th:nth-child(15) {
        z-index: 25 !important;
        background: var(--bg-surface-2) !important;
    }
    #scroll-horizontal tbody tr:hover td:nth-child(15) {
        background: var(--bg-surface-2) !important;
    }
    #scroll-horizontal tbody tr.is-expanded td:nth-child(15) {
        background: var(--bg-surface-2) !important;
    }
    #scroll-horizontal tbody td:nth-child(15):has(.show),
    #scroll-horizontal tbody td:nth-child(15):focus-within {
        z-index: 1050 !important;
    }

    #scroll-horizontal thead th:nth-child(6),
    #scroll-horizontal thead th:nth-child(8),
    #scroll-horizontal thead th:nth-child(12),
    #scroll-horizontal thead th:nth-child(13) {
        text-align: center !important;
        padding-left: 20px !important;
        padding-right: 28px !important;
    }

    #scroll-horizontal tbody td:nth-child(2),
    #scroll-horizontal tbody td:nth-child(3),
    #scroll-horizontal tbody td:nth-child(4),
    #scroll-horizontal tbody td:nth-child(14) {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    #scroll-horizontal tbody td:nth-child(2) .d-flex { min-width: 0; }
    #scroll-horizontal tbody td:nth-child(2) p {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: var(--text-primary);
        font-weight: 700 !important;
    }
    #scroll-horizontal tbody td:nth-child(6),
    #scroll-horizontal tbody td:nth-child(7),
    #scroll-horizontal tbody td:nth-child(10),
    #scroll-horizontal tbody td:nth-child(11),
    #scroll-horizontal tbody td:nth-child(12),
    #scroll-horizontal tbody td:nth-child(13) {
        white-space: nowrap !important;
    }

    /* Semantic Badges */
    .badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        height: 24px !important;
        padding: 0 10px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
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
    .badge.badge--icon::before { display: none !important; }
    .badge.badge--icon i { font-size: 13px !important; line-height: 1 !important; }

    .badge--success {
        color: var(--success-text) !important;
        background-color: var(--success-subtle) !important;
        border-color: var(--success-border) !important;
    }
    .badge--warning {
        color: var(--warning-text) !important;
        background-color: var(--warning-subtle) !important;
        border-color: var(--warning-border) !important;
    }
    .badge--danger {
        color: var(--danger-text) !important;
        background-color: var(--danger-subtle) !important;
        border-color: var(--danger-border) !important;
    }
    .badge--info {
        color: var(--accent-cyan) !important;
        background-color: var(--accent-cyan-subtle) !important;
        border-color: var(--accent-cyan-border) !important;
    }
    .badge--brand {
        color: var(--brand) !important;
        background-color: var(--brand-subtle) !important;
        border-color: var(--brand-border) !important;
    }
    .badge--neutral {
        color: var(--text-secondary) !important;
        background-color: var(--bg-surface-3) !important;
        border-color: var(--border-subtle) !important;
    }

    .badge-payment--cash-lunas { color: var(--success-text) !important; background-color: var(--success-subtle) !important; border: 1px solid var(--success-border) !important; }
    .badge-payment--transfer-lunas { color: var(--accent-cyan) !important; background-color: var(--accent-cyan-subtle) !important; border: 1px solid var(--accent-cyan-border) !important; }
    .badge-payment--cash-belum-lunas { color: var(--warning-text) !important; background-color: var(--warning-subtle) !important; border: 1px solid var(--warning-border) !important; }
    .badge-payment--transfer-dp { color: #A855F7 !important; background-color: rgba(168, 85, 247, 0.14) !important; border: 1px solid rgba(168, 85, 247, 0.30) !important; }
    .badge-payment--po { color: var(--info-text) !important; background-color: var(--info-subtle) !important; border: 1px solid var(--info-border) !important; }

    /* Table Row Hover & Expanded */
    #scroll-horizontal tbody tr {
        cursor: pointer;
        border-bottom: 1px solid var(--border-subtle) !important;
    }
    #scroll-horizontal tbody tr:hover td {
        background-color: var(--bg-hover) !important;
    }
    #scroll-horizontal tbody tr.is-expanded td {
        background-color: var(--bg-surface-2) !important;
    }

    .table-cell-collapsible { width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; overflow: hidden; }
    .table-cell-collapsible ul { margin: 0; padding: 0; list-style: none; }
    .table-cell-collapsible li { overflow: hidden; color: var(--text-secondary); line-height: 20px; text-overflow: ellipsis; white-space: nowrap; }
    .description-extra-container { max-height: 0; opacity: 0; overflow: hidden; transform: translateY(-4px); transition: max-height 260ms ease, opacity 200ms ease, transform 240ms ease; }
    #scroll-horizontal tbody tr.is-expanded .table-cell-collapsible { overflow: visible !important; }
    #scroll-horizontal tbody tr.is-expanded .description-extra-container { max-height: 3000px; opacity: 1; transform: translateY(0); overflow: visible; display: block !important; }
    #scroll-horizontal tbody tr.is-expanded .table-cell-collapsible li { white-space: normal !important; word-break: break-word !important; overflow-wrap: break-word !important; text-overflow: clip !important; }
    .table-cell-collapsible .description-item--extra { display: none; }
    #scroll-horizontal tbody tr.is-expanded .table-cell-collapsible .description-item--extra { display: list-item; }

    .badge-more-items {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
        padding: 0;
        border: 0;
        background: transparent;
        color: var(--brand);
        font-size: 11px;
        font-weight: 600;
        line-height: 18px;
        cursor: pointer;
    }
    .badge-more-items:hover { text-decoration: underline; }
    #scroll-horizontal tbody tr.is-expanded .badge-more-items { opacity: 0; max-height: 0; margin: 0; overflow: hidden; pointer-events: none; }

    .row-expand-indicator,
    button.row-expand-indicator {
        appearance: none;
        outline: none !important;
        box-shadow: none !important;
        display: inline-flex;
        width: 24px;
        height: 24px;
        flex: 0 0 24px;
        align-items: center;
        justify-content: center;
        margin-right: 6px;
        padding: 0;
        border: 1px solid transparent !important;
        border-radius: 6px;
        background: transparent !important;
        color: var(--text-tertiary) !important;
        font-size: 16px;
        cursor: pointer;
        transition: transform 220ms ease;
    }
    #scroll-horizontal tbody tr:hover .row-expand-indicator {
        color: var(--text-primary) !important;
        background: var(--bg-surface-3) !important;
    }
    #scroll-horizontal tbody tr.is-expanded .row-expand-indicator,
    .row-expand-indicator.expanded {
        transform: rotate(90deg) !important;
        background: var(--brand-subtle) !important;
        border-color: var(--brand-border) !important;
        color: var(--brand) !important;
    }

    /* Bottom Pagination */
    .dataTables_controls_bottom {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        flex-wrap: wrap !important;
        gap: 16px !important;
        padding: 14px 20px !important;
        background: var(--bg-surface) !important;
        border-top: 1px solid var(--border-subtle) !important;
        width: 100% !important;
    }
    #scroll-horizontal_wrapper .dataTables_info { float: none !important; color: var(--text-tertiary) !important; font-size: 13px !important; padding-top: 0 !important; margin-bottom: 0 !important; }
    #scroll-horizontal_wrapper .dataTables_paginate { float: none !important; padding-top: 0 !important; margin-bottom: 0 !important; margin-left: auto !important; }
    #scroll-horizontal_wrapper .pagination .page-item .page-link {
        background: var(--bg-surface-2);
        border: 1px solid var(--border-default);
        color: var(--text-secondary);
        font-size: 13px;
        padding: 5px 12px;
        border-radius: 8px;
        margin: 0 2px;
    }
    #scroll-horizontal_wrapper .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: var(--bg-surface-3);
        color: var(--text-primary);
    }
    #scroll-horizontal_wrapper .pagination .page-item.active .page-link {
        background: var(--brand) !important;
        border-color: var(--brand) !important;
        color: #ffffff !important;
    }

    /* Segmented Filter Control */
    .segmented-control {
        display: inline-flex;
        align-items: center;
        background: var(--bg-surface-2);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-control);
        padding: 3px;
        gap: 2px;
    }
    .segmented-item,
    button.segmented-item {
        appearance: none;
        outline: none !important;
        box-shadow: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: 1px solid transparent !important;
        color: var(--text-secondary);
        padding: 5px 12px;
        border-radius: var(--radius-sm);
        font-size: 12.5px;
        font-weight: 500;
        line-height: 1.4;
        white-space: nowrap;
        cursor: pointer;
    }
    .segmented-item:hover:not(.active) {
        color: var(--text-primary);
        background-color: var(--bg-hover);
    }
    .segmented-item.active,
    button.segmented-item.active {
        background: var(--brand-subtle) !important;
        border-color: var(--brand-border) !important;
        color: var(--brand) !important;
        font-weight: 600;
    }
    [data-theme="light"] .segmented-item.active,
    [data-theme="light"] button.segmented-item.active {
        background: var(--brand) !important;
        border-color: var(--brand) !important;
        color: #ffffff !important;
    }

    .btn-custom-filter,
    button.btn-custom-filter {
        appearance: none;
        outline: none !important;
        box-shadow: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 34px;
        padding: 0 12px;
        background: var(--bg-surface-2) !important;
        color: var(--text-secondary) !important;
        border: 1px solid var(--border-default) !important;
        border-radius: var(--radius-control);
        font-size: 12.5px;
        font-weight: 500;
        white-space: nowrap;
        cursor: pointer;
    }
    .btn-custom-filter:hover {
        color: var(--text-primary) !important;
        border-color: var(--border-strong) !important;
    }
    .btn-custom-filter.active,
    .btn-custom-filter.open {
        background: var(--brand-subtle) !important;
        border-color: var(--brand-border) !important;
        color: var(--brand) !important;
        font-weight: 600;
    }
    .chevron-rotate { transition: transform 160ms ease; }
    .btn-custom-filter.open .chevron-rotate { transform: rotate(180deg); }

    .custom-filter-panel {
        background: var(--bg-surface-2) !important;
        border: 1px solid var(--border-default) !important;
        border-radius: var(--radius-inner) !important;
    }

    .dash-input, .dash-select {
        height: 34px;
        padding: 0 12px !important;
        background: var(--bg-surface-2) !important;
        color: var(--text-primary) !important;
        border: 1px solid var(--border-default) !important;
        border-radius: var(--radius-control) !important;
        font-size: 12.5px !important;
    }
    .dash-select option { background: var(--bg-surface); color: var(--text-primary); }

    /* Primary Button: Tambah Invoice (Emerald) */
    .btn-create-invoice {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 38px;
        padding: 0 16px;
        background: var(--brand) !important;
        color: #ffffff !important;
        border: 1px solid transparent;
        border-radius: var(--radius-control);
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        white-space: nowrap;
        cursor: pointer;
        box-shadow: var(--brand-glow);
    }
    .btn-create-invoice:hover {
        background: var(--brand-hover) !important;
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    /* Soft Emerald Toggle All Rows Button */
    #btn-toggle-all-rows {
        background: var(--brand-subtle) !important;
        color: var(--brand) !important;
        border: 1px solid var(--brand-border) !important;
        border-radius: var(--radius-control) !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        padding: 5px 12px !important;
        height: 34px;
    }
    #btn-toggle-all-rows:hover {
        background: var(--brand) !important;
        color: #ffffff !important;
        border-color: var(--brand) !important;
    }

    /* 3-Dot Action Button & Clean Dropdown (Screenshots 3 & 6) */
    .btn-table-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-surface-2);
        border: 1px solid var(--border-default);
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
    }
    .btn-table-action:hover,
    .btn-table-action[aria-expanded="true"] {
        background: var(--brand-subtle);
        border-color: var(--brand-border);
        color: var(--brand);
    }

    .dropdown-menu {
        background: var(--bg-surface) !important;
        border: 1px solid var(--border-default) !important;
        border-radius: 14px !important;
        box-shadow: var(--shadow-dropdown) !important;
        padding: 8px !important;
        min-width: 235px !important;
        z-index: 1060 !important;
    }
    .dropdown-item {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        min-height: 38px !important;
        padding: 8px 12px !important;
        border-radius: 9px !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        white-space: nowrap !important;
        color: var(--text-primary) !important;
        background: transparent !important;
    }
    .dropdown-item:hover,
    .dropdown-item:focus {
        background-color: var(--bg-surface-2) !important;
        color: var(--text-primary) !important;
    }
    .dropdown-item.text-danger:hover,
    .dropdown-item--danger:hover {
        background-color: var(--danger-subtle) !important;
        color: var(--danger-text) !important;
    }

    .dropdown-menu a.invoice-request {
        border-inline-start: 3px solid transparent !important;
        padding-inline-start: 10px !important;
        font-weight: 600 !important;
    }

    /* ==========================================================================
       MOBILE RESPONSIVE FIT (HP: <= 767.98px & <= 575.98px)
       ========================================================================== */
    @media (max-width: 767.98px) {
        .tkb-inv-header-controls .tkb-theme-toggle {
            display: none !important;
        }
        .card {
            overflow-x: clip;
        }
        .card-body .d-flex,
        .card-header .d-flex {
            min-width: 0 !important;
            max-width: 100% !important;
        }
        .kpi-card__body {
            padding: 15px 16px !important;
            min-height: 120px !important;
        }
        .kpi-card__value {
            font-size: 21px !important;
            margin-bottom: 10px !important;
        }
        .segmented-control {
            display: flex !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            overflow-x: auto !important;
            flex-wrap: nowrap !important;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .segmented-control::-webkit-scrollbar {
            display: none;
        }
        .segmented-item {
            flex-shrink: 0;
            padding: 6px 10px;
            font-size: 12px;
        }
        .tkb-status-pills {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            width: 100% !important;
            max-width: 100% !important;
            gap: 4px !important;
        }
        .filter-tab-pill {
            justify-content: center;
            white-space: nowrap;
            padding: 6px 8px;
            font-size: 12px;
        }
        .tkb-inv-filter-right {
            width: 100% !important;
            flex-direction: column !important;
            align-items: stretch !important;
        }
        .tkb-inv-jenis-wrap {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 4px !important;
            width: 100% !important;
        }
        .dataTables_controls_top,
        .dataTables_controls_bottom {
            padding: 12px 14px !important;
            gap: 10px !important;
        }
        #scroll-horizontal_wrapper .dataTables_filter {
            width: 100%;
            margin-left: 0 !important;
        }
        #scroll-horizontal_wrapper .dataTables_filter label {
            width: 100%;
        }
        #scroll-horizontal_wrapper .dataTables_filter input {
            width: 100% !important;
            flex: 1;
        }
        #scroll-horizontal_wrapper .dataTables_paginate {
            margin-left: 0 !important;
            width: 100%;
            overflow-x: auto;
        }
        #scroll-horizontal th:nth-child(15),
        #scroll-horizontal td:nth-child(15) {
            width: 58px !important;
            min-width: 58px !important;
            max-width: 58px !important;
            padding: 8px 6px !important;
        }
    }

    @media (max-width: 575.98px) {
        .tkb-inv-header-controls {
            width: 100%;
        }
        .btn-create-invoice {
            width: 100%;
            height: 42px;
        }
        #filter-jenis-transaksi,
        #btn-toggle-all-rows {
            width: 100%;
        }
        .kpi-card__value {
            font-size: 19px !important;
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
    .dropdown-menu a.invoice-request--settlement { color: #38bdf8 !important; border-inline-start-color: #38bdf8 !important; }
    .dropdown-menu a.invoice-request--cancel { color: #fb923c !important; border-inline-start-color: #fb923c !important; }
.dropdown-menu a.invoice-request--delete { color: #f87171 !important; border-inline-start-color: #f87171 !important; }
    .dropdown-menu a.invoice-request--owner-2::after { opacity: .35; }
    .dropdown-menu a.invoice-request--owner-1::after { opacity: 1; }

</style>

@endsection

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



      <!-- Header Halaman & Aksi Tambah Invoice -->
      <div class="row mb-3">
          <div class="col-12">
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-1">
                  <div>
                      <div class="d-flex align-items-center gap-2">
                          <h4 class="mb-0" style="font-size: 22px; font-weight: 700; color: var(--text-primary); letter-spacing: -0.02em;">Invoice Tokabe</h4>
                          <span style="display:inline-flex;align-items:center;height:22px;padding:0 10px;font-size:11.5px;font-weight:600;border-radius:9999px;background:var(--accent-cyan-subtle);color:var(--accent-cyan);border:1px solid var(--accent-cyan-border);">Tokabe POS</span>
                      </div>
                      <p class="mb-0 mt-1" style="font-size: 13px; color: var(--text-tertiary);">Daftar data transaksi dan status pembayaran invoice Tokabe</p>
                  </div>
                  <div class="d-flex align-items-center gap-3 tkb-inv-header-controls">
                      <button type="button" class="tkb-theme-toggle" onclick="window.toggleTokabeTheme(event)" aria-label="Ubah Mode Terang / Gelap" title="Ubah Mode Terang / Gelap">
                          <span class="tkb-theme-toggle__thumb" aria-hidden="true"></span>
                          <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--sun"><i class="ri-sun-line"></i></span>
                          <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--moon"><i class="ri-moon-clear-line"></i></span>
                      </button>
                      <a href="{{ route('add.inv.tkb') }}" class="btn-create-invoice">
                          <i class="ri-add-line fs-18"></i>Tambah invoice
                      </a>
                  </div>
              </div>
          </div>
      </div>

      @if (Auth::user()->role === 'Pemilik' || Auth::user()->role === 'Admin')
       <!-- Filter Bar Terhubung untuk Semua Card -->
       <div class="row mb-4">
           <div class="col-12">
               <div class="card">
                   <div class="card-body p-3">
                       <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                           <div class="d-flex align-items-center gap-2">
                               <span style="font-size: 13px; font-weight: 500; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 6px;">
                                   <i class="ri-calendar-line" style="color: var(--brand); font-size: 16px;"></i>Filter periode card:
                               </span>
                               <span class="kpi-badge kpi-badge--emerald" id="badge-active-period">
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

      <!-- SECTION 1: RINGKASAN TRANSAKSI (3 cards per row x 2 rows) -->
      <div class="kpi-section-header">
          <div class="kpi-section-title">Ringkasan transaksi</div>
          <div class="kpi-section-subtitle">Statistik nilai invoice tahunan, bulanan, dan pembatalan</div>
      </div>
      <div class="row g-3 mb-3">
          {{-- Card 1: Total Invoice Tahun Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--cyan"><i class="ri-file-text-line"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-1">Total invoice tahun ini</p>
                          </div>
                          <p class="kpi-card__value" id="tokabe-card-val-1">Rp {{ number_format($jumlahPerTahun, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-1">{{ $jlhInvoice }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-1">Invoice tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 2: Invoice Lunas Tahun Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--emerald"><i class="ri-check-line"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-2">Invoice lunas tahun ini</p>
                          </div>
                          <p class="kpi-card__value kpi-card__value--success" id="tokabe-card-val-2">Rp {{ number_format($totalLunasThn, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-2">{{ $lunas }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-2">Lunas tahun {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 3: Belum Lunas Tahun Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--amber"><i class="ri-time-line"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-3">Belum lunas tahun ini</p>
                          </div>
                          <p class="kpi-card__value kpi-card__value--warning" id="tokabe-card-val-3">Rp {{ number_format($jumlahBBPerTahun, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-3">{{ $belumLunas }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-3">Sisa tagihan {{ date('Y') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 4: Terbayar Bulan Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--emerald"><i class="ri-wallet-3-line"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-4">Terbayar bulan ini</p>
                          </div>
                          <p class="kpi-card__value" id="tokabe-card-val-4">Rp {{ number_format($totalTerbayarBulan ?? 0, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-4">{{ $terbayarBulanCount ?? 0 }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-4">Bulan {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 5: Belum Lunas Bulan Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--amber"><i class="ri-time-line"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-bulan-belum">Belum lunas bulan ini</p>
                          </div>
                          <p class="kpi-card__value" id="tokabe-card-val-bulan-belum">Rp {{ number_format($jumlahBBPerBulan, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-bulan-belum">{{ $belumLunasMonth }}</span>
                          <p class="kpi-card__label" id="tokabe-card-desc-bulan-belum">Sisa bulan ini</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 6: Invoice Dibatalkan --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--rose"><i class="ri-folder-reduce-line"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-5">Invoice dibatalkan</p>
                          </div>
                          <p class="kpi-card__value kpi-card__value--danger" id="tokabe-card-val-5">Rp {{ number_format($jumlahBatal, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-5">{{ $batal }}</span>
                          <p class="kpi-card__label">Total dibatalkan</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- SECTION 2: PAJAK (PPN & PPH) (3 cards) -->
      <div class="kpi-section-header">
          <div class="kpi-section-title">Pajak (PPN dan PPh)</div>
          <div class="kpi-section-subtitle">Total PPN dan PPh serta rincian kode PPN 01 sampai 04</div>
      </div>
      <div class="row g-3 mb-4">
          {{-- Card 7: Total PPN Tahun Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--cyan"><i class="las la-balance-scale"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-6">Total PPN {{ date('Y') }}</p>
                          </div>
                          <p class="kpi-card__value" id="tokabe-card-val-6">Rp {{ number_format($ppnTotal, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-6">{{ $ppnTotalCount }}</span>
                          <p class="kpi-card__label">Invoice dengan PPN</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 8: Total PPh Tahun Ini --}}
          <div class="col-lg-4 col-md-6 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <div class="kpi-card__top">
                              <span class="kpi-card__icon kpi-card__icon--cyan"><i class="las la-balance-scale"></i></span>
                              <p class="kpi-card__overline" id="tokabe-card-title-7">Total PPh {{ date('Y') }}</p>
                          </div>
                          <p class="kpi-card__value" id="tokabe-card-val-7">Rp {{ number_format($pphTotal, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge" id="tokabe-card-count-7">{{ $pphTotalCount }}</span>
                          <p class="kpi-card__label">Invoice dengan PPh</p>
                      </div>
                  </div>
              </div>
          </div>

          {{-- Card 9: Rincian Kode PPN --}}
          <div class="col-lg-4 col-md-12 col-12">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <p class="kpi-card__overline fw-semibold mb-1" style="color: var(--text-primary);">Rincian kode PPN</p>
                      <div class="kpi-ppn-list">
                          <div class="kpi-ppn-item">
                              <span class="kpi-ppn-label">01 · Non-Pemungut</span>
                              <span class="kpi-ppn-val" id="tokabe-card-val-8">Rp {{ number_format($ppn01, 0, ',', '.') }}</span>
                              <span id="tokabe-card-count-8" class="d-none">{{ $ppn01Count }}</span>
                          </div>
                          <div class="kpi-ppn-item">
                              <span class="kpi-ppn-label">02 · Pemerintah</span>
                              <span class="kpi-ppn-val" id="tokabe-card-val-9">Rp {{ number_format($ppn02, 0, ',', '.') }}</span>
                              <span id="tokabe-card-count-9" class="d-none">{{ $ppn02Count }}</span>
                          </div>
                          <div class="kpi-ppn-item">
                              <span class="kpi-ppn-label">03 · BUMN</span>
                              <span class="kpi-ppn-val" id="tokabe-card-val-10">Rp {{ number_format($ppn03, 0, ',', '.') }}</span>
                              <span id="tokabe-card-count-10" class="d-none">{{ $ppn03Count }}</span>
                          </div>
                          <div class="kpi-ppn-item">
                              <span class="kpi-ppn-label">04 · Nilai Lain</span>
                              <span class="kpi-ppn-val" id="tokabe-card-val-11">Rp {{ number_format($ppn04, 0, ',', '.') }}</span>
                              <span id="tokabe-card-count-11" class="d-none">{{ $ppn04Count }}</span>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      @else
      <!-- Non-Pemilik: Belum Lunas Saja -->
      <div class="row g-3 mb-3">
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <p class="kpi-card__overline">BELUM TERBAYAR TAHUN {{ date('Y') }}</p>
                          <p class="kpi-card__value kpi-card__value--warning">Rp {{ number_format($jumlahBBPerTahun, 0, ',', '.') }}</p>
                      </div>
                      <div class="kpi-card__footer">
                          <span class="kpi-badge">{{ $belumLunas }}</span>
                          <p class="kpi-card__label">Invoice Tokabe Belum Lunas</p>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-xl-6 col-md-6">
              <div class="kpi-card">
                  <div class="kpi-card__body">
                      <div>
                          <p class="kpi-card__overline">BELUM TERBAYAR BULAN {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM') }}</p>
                          <p class="kpi-card__value kpi-card__value--danger">Rp {{ number_format($jumlahBBPerBulan, 0, ',', '.') }}</p>
                      </div>
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

              <div class="card">

                  <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3 p-3">
                      <!-- Quick Status Filters -->
                      <div class="tkb-status-pills">
                          <button type="button" class="filter-tab-pill active" onclick="filterTableStatus(this, '')">
                              Semua ({{ $penjualan->count() }})
                          </button>
                          <button type="button" class="filter-tab-pill" onclick="filterTableStatus(this, 'Lunas')">
                              <span style="width:7px;height:7px;border-radius:50%;background:var(--success);display:inline-block;"></span> Lunas ({{ $penjualan->where('status', 'Lunas')->count() }})
                          </button>
                          <button type="button" class="filter-tab-pill" onclick="filterTableStatus(this, 'Belum Lunas')">
                              <span style="width:7px;height:7px;border-radius:50%;background:var(--warning);display:inline-block;"></span> Belum lunas ({{ $penjualan->where('status', 'Belum Lunas')->count() }})
                          </button>
                          <button type="button" class="filter-tab-pill" onclick="filterTableStatus(this, 'Batal')">
                              <span style="width:7px;height:7px;border-radius:50%;background:var(--danger);display:inline-block;"></span> Batal ({{ $penjualan->where('status', 'Batal')->count() }})
                          </button>
                      </div>

                      <div class="d-flex flex-wrap align-items-center gap-2 tkb-inv-filter-right">
                          <div class="d-inline-flex align-items-center gap-2 tkb-inv-jenis-wrap">
                              <label for="filter-jenis-transaksi" class="mb-0" style="font-size: 12.5px; font-weight: 500; color: var(--text-tertiary);">Jenis transaksi</label>
                              <select id="filter-jenis-transaksi" class="form-select form-select-sm dash-select" style="min-width: 155px;" onchange="filterTableJenisTransaksi(this.value)">
                                  <option value="">Semua transaksi</option>
                                  <option value="PADI UMKM">PADI UMKM ({{ $penjualan->where('is_padi_umkm', true)->count() }})</option>
                                  <option value="Non-PADI UMKM">Non-PADI UMKM ({{ $penjualan->where('is_padi_umkm', false)->count() }})</option>
                              </select>
                          </div>
                          <button type="button" id="btn-toggle-all-rows" class="btn btn-sm">
                              <span id="text-toggle-all-rows">Buka semua rincian</span>
                          </button>
                      </div>
                  </div>

                  <div class="card-body p-0" style="overflow-x: auto; width: 100%; max-width: 100%;">
                          <table id="scroll-horizontal" class="table align-middle table-hover mb-0" aria-label="Daftar invoice Tokabe">

                              <thead>

                                  <tr style="background:var(--bg-surface-2,#1E293B);">

                                      <th scope="col">No</th>

                                      <th scope="col">Invoice ID</th>

                                      <th scope="col">Nama Pelanggan</th>

                                      <th scope="col">No. Telepon</th>

                                      <th scope="col">Tanggal Penjualan</th>

                                      <th scope="col">Status</th>

                                      <th scope="col">Alasan Batal</th>

                                      <th scope="col">Approval</th>

                                       <th scope="col">Deskripsi</th>



                                      <th scope="col">Panjar</th>

                                      <th scope="col">Total</th>

                                      <th scope="col">Tipe Pembayaran</th>

                                      <th scope="col">Jenis Transaksi</th>

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

                                      <td><span class="fw-medium" style="color: var(--text-primary);">{{$inv->customer}}</span></td>

                                      <td><span style="font-family: monospace; font-size: 13px; color: var(--text-secondary);">{{$inv->no_telepon ?: "-"}}</span></td>

                                      <td data-order="{{ $inv->tgl_penjualan }}"><span style="color: var(--text-primary); font-weight: 500;">{{$inv->formatted_tgl_penjualan}}</span>

                                        @if ($inv->updated_at != $inv->created_at)

                                            <br><span style="font-size:11px;color:var(--text-tertiary,#94A3B8);">Diperbarui: {{ $inv->formatted_tgl_update }}</span>

                                        @endif</td>                                      {{-- Badge Status (DESIGN.md §14.1) --}}
                                      <td data-order="{{ $inv->status }}" data-search="{{ $inv->status }}" class="text-center">
                                          @if ($inv->status == 'Lunas')
                                              <span class="badge badge--success badge-status--lunas">Lunas</span>
                                          @elseif($inv->status == 'Belum Lunas')
                                              <span class="badge badge--warning badge-status--belum-lunas">Belum Lunas</span>
                                          @elseif($inv->status == 'Batal')
                                              <span class="badge badge--danger badge-status--batal">Batal</span>
                                          @elseif($inv->status == 'Hapus')
                                              <span class="badge badge--neutral badge-status--hapus">Hapus</span>
                                          @else
                                              <span class="badge badge--neutral">{{$inv->status}}</span>
                                          @endif
                                      </td>

                                      {{-- Kolom Alasan Batal --}}
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

                                      {{-- Badge Approval (DESIGN.md §14.1) --}}
                                      <td data-order="{{ !empty($inv->no_faktur_pajak) ? 'Lengkap' : ($inv->approval == 'Unlock' ? 'Unlock' : 'Lock') }}" data-search="{{ !empty($inv->no_faktur_pajak) ? 'Faktur Pajak Lengkap Selesai ' . $inv->no_faktur_pajak : ($inv->approval == 'Unlock' ? 'Akses Terbuka Siap Diisi' : 'Faktur Pajak Belum Diisi Status: Terkunci') }}" class="text-center">
                                          @if(!empty($inv->no_faktur_pajak))
                                              <span class="badge badge--success badge--icon" title="Nomor Faktur: {{ $inv->no_faktur_pajak }}">
                                                  <i class="ri-checkbox-circle-fill"></i> Faktur Pajak Lengkap (Selesai)
                                              </span>
                                              <small class="d-block text-muted mt-1" style="font-size: 11px; font-family: monospace;">{{ $inv->no_faktur_pajak }}</small>
                                          @elseif($inv->approval == 'Unlock')
                                              <span class="badge badge--info badge--icon" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.35);">
                                                  <i class="ri-lock-unlock-line"></i> Akses Terbuka (Siap Diisi)
                                              </span>
                                          @else
                                              <span class="badge badge--warning badge--icon">
                                                  <i class="ri-alert-line"></i> Faktur Pajak Belum Diisi
                                              </span>
                                              <small class="d-block text-warning mt-1" style="font-size: 11px; font-weight: 500; opacity: 0.9;">
                                                  <i class="ri-lock-line me-1"></i>(Status: Terkunci)
                                              </small>
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
                                      <td data-order="{{ (float)($inv->dp ?? 0) }}" style="text-align:right; font-variant-numeric:tabular-nums; color:var(--text-secondary);">Rp {{$inv->formatted_dp}}</td>

                                      <td data-order="{{ (float)($inv->total_pembayaran ?? 0) }}" style="text-align:right; font-weight:700; font-variant-numeric:tabular-nums; color:var(--text-primary);">Rp {{$inv->formatted_total_pembayaran}}</td>                                      {{-- Badge Tipe Pembayaran (DESIGN.md §14.1) --}}
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

                                      <td class="text-center" data-order="{{ $inv->is_padi_umkm ? 'PADI UMKM' : 'Non-PADI UMKM' }}" data-search="{{ $inv->is_padi_umkm ? 'PADI UMKM' : 'Non-PADI UMKM' }}">
                                          @if ($inv->is_padi_umkm)
                                              <span class="badge" style="background: rgba(16, 185, 129, 0.14); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.35); padding: 4px 10px; border-radius: 6px; font-weight: 600;">PADI UMKM</span>
                                          @else
                                              <span class="badge" style="background: rgba(148, 163, 184, 0.12); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.25); padding: 4px 10px; border-radius: 6px; font-weight: 500;">Non-PADI UMKM</span>
                                          @endif
                                      </td>

                                      <td><span style="color:var(--text-secondary);">{{ $inv->user ? $inv->user->nama : "-" }}</span></td>

                                      <td class="text-center">

                                          

                                          @php

                                              $roleTkb    = Auth::user()->role;

                                              $isOwnerTkb = $roleTkb === 'Pemilik';

                                              $isAdminTkb = $roleTkb === 'Admin';



                                              // Nomor WA Tujuan Terpusat (+62 811-6029-999)
                                              $waTargetTkb = '628116029999';

                                              // Pesan Request Edit
                                              $msgEditTkb = urlencode("Halo, saya ingin mengajukan permintaan perubahan data (Edit) untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman approval:\n" . url("/tokabe-approval/{$inv->id}"));

                                              // Pesan Request Unlock
                                              $msgUnlockTkb = urlencode("Halo, saya ingin meminta approval untuk membuka akses invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}* guna pengisian Nomor Faktur Pajak. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman approval:\n" . url("/tokabe-approval/{$inv->id}"));

                                              // Pesan Request Pelunasan
                                              $msgLunasTkb = urlencode("Halo, saya ingin Request perubahan status pelunasan untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman ubah status pelunasan:\n" . url("/tokabe-pelunasan/{$inv->id}"));

                                              // Pesan Request Batal
                                              $msgBatalTkb = urlencode("Halo, saya ingin Request pembatalan untuk invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman pembatalan invoice:\n" . url("/tokabe-batal/{$inv->id}"));

                                              // Pesan Request Hapus
                                              $msgHapusTkb = urlencode("Halo, saya ingin meminta persetujuan untuk MENGHAPUS invoice *#{$inv->nomor_invoice}* atas nama *{$inv->customer}*. Mohon untuk segera ditinjau 🙏 \n\nKlik link berikut untuk membuka halaman konfirmasi hapus invoice:\n" . url("/tokabe-delete/{$inv->id}"));

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

                                                      <a class="dropdown-item" href="https://web.whatsapp.com/send?phone={{ $no_hp_cust }}&text={{ urlencode($pesan_cust) }}" target="_blank">

                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success);flex-shrink:0;"></i>Kirim invoice (WA)

                                                      </a>

                                                  </li>

                                                  <li style="height:1px;margin:4px 0;background:var(--border-subtle,#1E293B);"></li>



                                                  {{-- === EDIT: Pemilik langsung, atau Admin jika status sudah Unlock === --}}
                                                  @if($canManageTkb)
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('edit.inv.tkb', $inv->id) }}">
                                                          <i class="ri-pencil-line" style="font-size:16px;color:var(--text-tertiary,#94A3B8);flex-shrink:0;"></i>Edit
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === OWNER: Lock langsung jika sudah Unlock === --}}
                                                  @if($isOwnerTkb && $inv->approval === 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('tokabe.lock', $inv->id) }}">
                                                          <i class="ri-lock-line" style="font-size:16px;color:var(--warning,#F59E0B);flex-shrink:0;"></i>Lock Invoice
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === OWNER: Unlock Invoice & Halaman Approval jika Lock === --}}
                                                  @if($isOwnerTkb && $inv->approval !== 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--success-text,#86EFAC);" href="{{ route('tokabe.unlock', $inv->id) }}">
                                                          <i class="ri-lock-unlock-line" style="font-size:16px;color:var(--success,#10B981);flex-shrink:0;"></i>Unlock Invoice
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item" style="display:flex;align-items:center;gap:8px;min-height:36px;padding:8px 10px;border-radius:6px;font-size:14px;color:var(--text-primary,#F1F5F9);" href="{{ route('tokabe.approval.page', $inv->id) }}">
                                                          <i class="ri-shield-check-line" style="font-size:16px;color:var(--info,#06B6D4);flex-shrink:0;"></i>Halaman Approval
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Pengajuan Edit & Unlock via WA (+62 811-6029-999) jika status Terkunci === --}}
                                                  @if($isAdminTkb && $inv->approval !== 'Unlock')
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--edit" style="color:var(--warning-text);" href="https://web.whatsapp.com/send?phone={{ $waTargetTkb }}&text={{ $msgEditTkb }}" target="_blank" rel="noopener noreferrer">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success);flex-shrink:0;"></i>Request Edit (WA)
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--unlock" style="color:var(--warning-text);" href="https://web.whatsapp.com/send?phone={{ $waTargetTkb }}&text={{ $msgUnlockTkb }}" target="_blank" rel="noopener noreferrer">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success);flex-shrink:0;"></i>Request Unlock (WA)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Pelunasan via WA (+62 811-6029-999) === --}}
                                                  @if($isAdminTkb && $inv->status !== 'Lunas' && $inv->status !== 'Batal')
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--settlement" style="color:var(--warning-text);" href="https://web.whatsapp.com/send?phone={{ $waTargetTkb }}&text={{ $msgLunasTkb }}" target="_blank" rel="noopener noreferrer">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success);flex-shrink:0;"></i>Request Pelunasan (WA)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Batal via WA (+62 811-6029-999) === --}}
                                                  @if($isAdminTkb && $inv->status !== 'Batal')
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--cancel" style="color:var(--warning-text);" href="https://web.whatsapp.com/send?phone={{ $waTargetTkb }}&text={{ $msgBatalTkb }}" target="_blank" rel="noopener noreferrer">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success);flex-shrink:0;"></i>Request Batal (WA)
                                                      </a>
                                                  </li>
                                                  @endif

                                                  {{-- === ADMIN: Request Hapus via WA (+62 811-6029-999) === --}}
                                                  @if($isAdminTkb)
                                                  <li>
                                                      <a class="dropdown-item invoice-request invoice-request--delete" style="color:var(--danger-text);" href="https://web.whatsapp.com/send?phone={{ $waTargetTkb }}&text={{ $msgHapusTkb }}" target="_blank" rel="noopener noreferrer">
                                                          <i class="ri-whatsapp-line" style="font-size:16px;color:var(--success);flex-shrink:0;"></i>Request Hapus (WA)
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



                              </tbody><!-- end tbody -->

                          </table><!-- end table -->
                      </div><!-- end card-body -->
                  </div><!-- end card -->
              </div><!-- end col-12 -->
          </div><!-- end row -->
      </div><!-- end container-fluid -->
  </div><!-- end page-content -->

@endsection



@section('plugins')

{{-- Datatable --}}



<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

{{-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> --}}





<script src="{{asset('libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>

<script src="{{asset('js/halaman/daftar-invoice.js')}}"></script>



<script>

    let invoiceTable = null;

    // Quick Status Filter Tabs
    function filterTableStatus(btnOrStatus, maybeStatus) {
        let btn, status;
        if (typeof btnOrStatus === 'string') {
            status = btnOrStatus;
            btn = (typeof event !== 'undefined' && event && event.currentTarget) ? event.currentTarget : null;
        } else {
            btn = btnOrStatus;
            status = maybeStatus;
        }
        $('.filter-tab-pill').removeClass('active');
        if (btn) $(btn).addClass('active');

        if (invoiceTable) {
            if (status) {
                invoiceTable.column(5).search('^' + status + '$', true, false).draw();
            } else {
                invoiceTable.column(5).search('').draw();
            }
        }
    }

    function filterTableJenisTransaksi(jenis) {
        if (invoiceTable) {
            if (jenis) {
                invoiceTable.column(12).search('^' + jenis + '$', true, false).draw();
            } else {
                invoiceTable.column(12).search('').draw();
            }
        }
    }

    $(document).ready(function() {

        invoiceTable = $('#scroll-horizontal').DataTable({
            autoWidth: false,
            scrollX: false,
            ordering: true,
            bSort: true,
            order: [[4, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 3, 6, 8, 14] },
                { width: '240px', targets: 7, className: 'text-center' },
                { width: '270px', targets: 8 },
                { width: '80px', targets: 14, className: 'text-center' }
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

        // Pertahankan nomor urut 1, 2, 3... tetap rapi berurutan saat tabel disortir dan dipaginasi
        invoiceTable.on('order.dt search.dt draw.dt', function () {
            const pageInfo = invoiceTable.page.info();
            const start = pageInfo ? pageInfo.start : 0;
            invoiceTable.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = start + i + 1;
            });
        });

        function adjustInvoiceTable() {
            window.requestAnimationFrame(function() {
                invoiceTable.columns.adjust();
            });
        }

        function setInvoiceRowExpanded(row, expanded) {
            const $row = $(row);
            $row.toggleClass('is-expanded', expanded);
            $row.find('.row-expand-indicator').toggleClass('expanded', expanded).attr('aria-expanded', expanded ? 'true' : 'false');
        }

        invoiceTable.columns.adjust();

        // Klik baris tabel untuk expand / collapse rincian

        $(document).on('click.invoiceRows', '#scroll-horizontal tbody tr', function(e) {

            // Abaikan jika yang diklik adalah tombol, dropdown, link, input, atau aksi

            if ($(e.target).closest('a, button, input, select, textarea, .dropdown, .dropdown-menu, .btn').length) {

                return;

            }

            setInvoiceRowExpanded(this, !$(this).hasClass('is-expanded'));

        });

        $(document).on('click.invoiceRows', '#scroll-horizontal .row-expand-indicator', function(e) {
            e.stopPropagation();
            const row = $(this).closest('tr');
            setInvoiceRowExpanded(row, !row.hasClass('is-expanded'));
        });

        $(document).on('click.invoiceRows', '#scroll-horizontal .badge-more-items', function(e) {
            e.stopPropagation();
            setInvoiceRowExpanded($(this).closest('tr'), true);
        });



        // Tombol buka / tutup semua rincian

        let allExpanded = false;

        $('#btn-toggle-all-rows').on('click', function() {
            allExpanded = !allExpanded;
            if (allExpanded) {
                $('#scroll-horizontal tbody tr').addClass('is-expanded');
                $('#scroll-horizontal .row-expand-indicator').addClass('expanded').attr('aria-expanded', 'true');
                $('#text-toggle-all-rows').text('Tutup Semua Rincian');
                $(this).find('i').removeClass('ri-expand-diagonal-line').addClass('ri-collapse-diagonal-line');
            } else {
                $('#scroll-horizontal tbody tr').removeClass('is-expanded');
                $('#scroll-horizontal .row-expand-indicator').removeClass('expanded').attr('aria-expanded', 'false');
                $('#text-toggle-all-rows').text('Buka Semua Rincian');
                $(this).find('i').removeClass('ri-collapse-diagonal-line').addClass('ri-expand-diagonal-line');
            }
            adjustInvoiceTable();
        });

        $(window).off('resize.invoiceRows').on('resize.invoiceRows', adjustInvoiceTable);

    });







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

                    // 5 Transaction Cards Statistik Invoice
                    $('#tokabe-card-val-1').text('Rp ' + res.card1.nominal);
                    $('#tokabe-card-title-1').text('TOTAL INVOICE ' + label);
                    $('#tokabe-card-count-1').text(res.card1.count);
                    $('#tokabe-card-desc-1').text('Invoice Dibuat ' + label);

                    $('#tokabe-card-val-2').text('Rp ' + res.card2.nominal);
                    $('#tokabe-card-title-2').text('TOTAL INVOICE LUNAS ' + label);
                    $('#tokabe-card-count-2').text(res.card2.count);
                    $('#tokabe-card-desc-2').text('Invoice Berstatus Lunas ' + label);

                    $('#tokabe-card-val-3').text('Rp ' + res.card3.nominal);
                    $('#tokabe-card-title-3').text('TOTAL INVOICE BELUM LUNAS ' + label);
                    $('#tokabe-card-count-3').text(res.card3.count);
                    $('#tokabe-card-desc-3').text('Sisa Tagihan Belum Lunas ' + label);

                    $('#tokabe-card-val-4').text('Rp ' + res.card4.nominal);
                    $('#tokabe-card-title-4').text('TOTAL TERBAYAR BULAN ' + res.card4MonthName);
                    $('#tokabe-card-count-4').text(res.card4.count);
                    $('#tokabe-card-desc-4').text('Pembayaran Diterima Bulan ' + res.card4MonthName);

                    if (res.cardBulanBelum) {
                        $('#tokabe-card-val-bulan-belum').text('Rp ' + res.cardBulanBelum.nominal);
                        $('#tokabe-card-title-bulan-belum').text('TOTAL BELUM TERBAYAR BULAN ' + res.card4MonthName);
                        $('#tokabe-card-count-bulan-belum').text(res.cardBulanBelum.count);
                        $('#tokabe-card-desc-bulan-belum').text('Sisa Tagihan Bulan ' + res.card4MonthName);
                    }

                    // Card 5: Dibatalkan
                    $('#tokabe-card-val-5').text('Rp ' + res.card5.nominal);
                    $('#tokabe-card-count-5').text(res.card5.count);



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
