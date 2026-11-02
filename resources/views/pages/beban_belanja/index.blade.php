@extends('layout.template')

@section('head')
<link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<style>
    /* ============================================================== */
    /* DESIGN TOKENS & SYSTEM BASE (DESIGN.md §2, §3, §4, §7)         */
    /* ============================================================== */
    :root {
        --bb-font-sans: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        --bb-font-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;

        /* Spacing (DESIGN.md §6.1) */
        --bb-space-1: 4px;
        --bb-space-2: 8px;
        --bb-space-3: 12px;
        --bb-space-4: 16px;
        --bb-space-5: 20px;
        --bb-space-6: 24px;

        /* Radius (DESIGN.md §7.2) */
        --bb-radius-xs: 4px;
        --bb-radius-sm: 6px;
        --bb-radius-md: 8px;
        --bb-radius-lg: 12px;
        --bb-radius-xl: 16px;
        --bb-radius-full: 9999px;

        /* Dark Canvas & Surfaces (DESIGN.md §4.1) */
        --bb-bg-app: #0C0F14;
        --bb-bg-surface: #12161D;
        --bb-bg-surface-2: #181E27;
        --bb-bg-surface-3: #1F2733;
        --bb-bg-hover: #1E2530;
        --bb-bg-active: #283241;

        /* Typography Colors */
        --bb-text-primary: #F1F5F9;
        --bb-text-secondary: #CBD5E1;
        --bb-text-tertiary: #94A3B8;
        --bb-text-disabled: #64748B;
        --bb-text-inverse: #0F172A;

        /* Borders */
        --bb-border-subtle: #1E2633;
        --bb-border-default: #2E3847;
        --bb-border-strong: #445163;
        --bb-border-focus: #818CF8;

        /* Shadows (DESIGN.md §7.1) */
        --bb-shadow-xs: 0 1px 2px rgb(0 0 0 / 0.4);
        --bb-shadow-sm: 0 2px 4px rgb(0 0 0 / 0.45);
        --bb-shadow-md: 0 4px 12px rgb(0 0 0 / 0.5);
        --bb-shadow-xl: 0 20px 40px -8px rgb(0 0 0 / 0.7);

        /* Brand Colors (Indigo - DESIGN.md §3.1) */
        --bb-brand: #6366F1;
        --bb-brand-hover: #4F46E5;
        --bb-brand-active: #4338CA;
        --bb-brand-subtle: rgba(99, 102, 241, 0.12);
        --bb-brand-subtle-hover: rgba(99, 102, 241, 0.22);
        --bb-brand-border: rgba(99, 102, 241, 0.35);
        --bb-brand-text: #A5B4FC;
        --bb-focus-ring: 0 0 0 3px rgba(99, 102, 241, 0.35);

        /* Semantic Colors (DESIGN.md §3.2) */
        --bb-success: #10B981;
        --bb-success-hover: #059669;
        --bb-success-subtle: rgba(16, 185, 129, 0.12);
        --bb-success-border: rgba(16, 185, 129, 0.35);
        --bb-success-text: #6EE7B7;

        --bb-warning: #F59E0B;
        --bb-warning-hover: #D97706;
        --bb-warning-subtle: rgba(245, 158, 11, 0.12);
        --bb-warning-border: rgba(245, 158, 11, 0.35);
        --bb-warning-text: #FCD34D;

        --bb-danger: #EF4444;
        --bb-danger-hover: #DC2626;
        --bb-danger-subtle: rgba(239, 68, 68, 0.12);
        --bb-danger-border: rgba(239, 68, 68, 0.35);
        --bb-danger-text: #FCA5A5;

        --bb-info: #3B82F6;
        --bb-info-hover: #2563EB;
        --bb-info-subtle: rgba(59, 130, 246, 0.12);
        --bb-info-border: rgba(59, 130, 246, 0.35);
        --bb-info-text: #93C5FD;
    }

    /* ============================================================== */
    /* TYPOGRAPHY & TABULAR NUMBERS (DESIGN.md §5)                   */
    /* ============================================================== */
    .tabular-num {
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum" 1;
    }
    .font-mono-code {
        font-family: var(--bb-font-mono);
        font-size: 12.5px;
        letter-spacing: 0.02em;
    }

    /* Page Header */
    .bb-page-title {
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--bb-text-primary);
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bb-page-subtitle {
        font-size: 13px;
        color: var(--bb-text-tertiary);
        margin-bottom: 0;
        max-width: 65ch;
    }

    /* ============================================================== */
    /* BUTTONS SYSTEM (DESIGN.md §9)                                  */
    /* ============================================================== */
    .bb-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--bb-space-2);
        height: 38px;
        padding: 0 var(--bb-space-4);
        font-size: 13.5px;
        font-weight: 500;
        border-radius: var(--bb-radius-md);
        border: 1px solid transparent;
        cursor: pointer;
        user-select: none;
        transition: all 150ms var(--ease-standard, ease);
        text-decoration: none;
        white-space: nowrap;
    }
    .bb-btn:focus-visible {
        outline: none;
        box-shadow: var(--bb-focus-ring);
    }
    .bb-btn:active {
        transform: scale(0.98);
    }
    .bb-btn--primary {
        background-color: var(--bb-brand);
        color: #FFFFFF;
        box-shadow: 0 1px 3px rgba(99, 102, 241, 0.3);
    }
    .bb-btn--primary:hover {
        background-color: var(--bb-brand-hover);
        color: #FFFFFF;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    .bb-btn--secondary {
        background-color: var(--bb-bg-surface-2);
        color: var(--bb-text-secondary);
        border-color: var(--bb-border-default);
    }
    .bb-btn--secondary:hover {
        background-color: var(--bb-bg-hover);
        color: var(--bb-text-primary);
        border-color: var(--bb-border-strong);
    }
    .bb-btn--outline-success {
        background-color: transparent;
        color: var(--bb-success-text);
        border-color: var(--bb-success-border);
    }
    .bb-btn--outline-success:hover {
        background-color: var(--bb-success-subtle);
        color: #FFFFFF;
        border-color: var(--bb-success);
        transform: translateY(-1px);
    }
    .bb-btn--outline-danger {
        background-color: transparent;
        color: var(--bb-danger-text);
        border-color: var(--bb-danger-border);
    }
    .bb-btn--outline-danger:hover {
        background-color: var(--bb-danger-subtle);
        color: #FFFFFF;
        border-color: var(--bb-danger);
        transform: translateY(-1px);
    }
    .bb-btn--ghost {
        background-color: transparent;
        color: var(--bb-text-secondary);
        border-color: transparent;
    }
    .bb-btn--ghost:hover {
        background-color: var(--bb-bg-hover);
        color: var(--bb-text-primary);
    }
    .bb-btn--icon {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: var(--bb-radius-md);
    }

    /* ============================================================== */
    /* KPI CARDS (DESIGN.md §12.4 & daftar-invoiceTokabe standard)    */
    /* ============================================================== */
    .kpi-card {
        background: var(--bb-bg-surface);
        border: 1px solid var(--bb-border-subtle);
        border-radius: var(--bb-radius-lg);
        box-shadow: var(--bb-shadow-xs);
        transition: box-shadow 200ms ease, transform 200ms ease, border-color 200ms ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .kpi-card:hover {
        box-shadow: var(--bb-shadow-md);
        transform: translateY(-2px);
        border-color: var(--bb-border-default);
    }
    .kpi-card:active {
        transform: scale(0.985);
    }
    .kpi-card__body {
        padding: var(--bb-space-5);
        display: flex;
        flex-direction: column;
        gap: var(--bb-space-3);
        min-height: 130px;
    }
    .kpi-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: var(--bb-space-3);
    }
    .kpi-card__overline {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--bb-text-tertiary);
        line-height: 16px;
        margin: 0;
    }
    .kpi-card__icon {
        width: 40px;
        height: 40px;
        border-radius: var(--bb-radius-md);
        background-color: var(--kpi-accent-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--kpi-accent);
        font-size: 20px;
        transition: background-color 150ms ease;
    }
    .kpi-card:hover .kpi-card__icon {
        background-color: var(--kpi-accent-subtle-hover);
    }
    .kpi-card__value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
        color: var(--bb-text-primary);
        margin: 0;
        font-variant-numeric: tabular-nums;
    }
    .kpi-card__unit {
        font-size: 13px;
        font-weight: 500;
        color: var(--bb-text-tertiary);
        margin-left: 2px;
    }
    .kpi-card__value--text {
        font-size: 16px;
        font-weight: 600;
        color: var(--bb-text-primary);
        line-height: 1.4;
    }
    .kpi-card__footer {
        display: flex;
        align-items: center;
        gap: var(--bb-space-2);
        padding-top: var(--bb-space-2);
        border-top: 1px solid var(--bb-border-subtle);
        margin-top: auto;
    }
    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 22px;
        padding: 0 var(--bb-space-2);
        font-size: 11.5px;
        font-weight: 600;
        border-radius: var(--bb-radius-full);
        border: 1px solid var(--kpi-accent-border);
        background-color: var(--kpi-accent-subtle);
        color: var(--kpi-accent-text);
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }
    .kpi-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--kpi-accent);
        flex-shrink: 0;
    }

    /* KPI Semantic Variants */
    .kpi-card--brand {
        --kpi-accent: var(--bb-brand);
        --kpi-accent-subtle: var(--bb-brand-subtle);
        --kpi-accent-subtle-hover: var(--bb-brand-subtle-hover);
        --kpi-accent-border: var(--bb-brand-border);
        --kpi-accent-text: var(--bb-brand-text);
    }
    .kpi-card--info {
        --kpi-accent: var(--bb-info);
        --kpi-accent-subtle: var(--bb-info-subtle);
        --kpi-accent-subtle-hover: rgba(59, 130, 246, 0.22);
        --kpi-accent-border: var(--bb-info-border);
        --kpi-accent-text: var(--bb-info-text);
    }
    .kpi-card--success {
        --kpi-accent: var(--bb-success);
        --kpi-accent-subtle: var(--bb-success-subtle);
        --kpi-accent-subtle-hover: rgba(16, 185, 129, 0.22);
        --kpi-accent-border: var(--bb-success-border);
        --kpi-accent-text: var(--bb-success-text);
    }
    .kpi-card--warning {
        --kpi-accent: var(--bb-warning);
        --kpi-accent-subtle: var(--bb-warning-subtle);
        --kpi-accent-subtle-hover: rgba(245, 158, 11, 0.22);
        --kpi-accent-border: var(--bb-warning-border);
        --kpi-accent-text: var(--bb-warning-text);
    }

    /* ============================================================== */
    /* FILTER TOOLBAR (DESIGN.md §10, §13.2)                          */
    /* ============================================================== */
    .bb-filter-card {
        background: var(--bb-bg-surface);
        border: 1px solid var(--bb-border-subtle);
        border-radius: var(--bb-radius-lg);
        box-shadow: var(--bb-shadow-xs);
        padding: var(--bb-space-4);
        margin-bottom: var(--bb-space-4);
    }
    .bb-field-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--bb-text-tertiary);
        margin-bottom: 6px;
    }
    .bb-form-control, .bb-form-select {
        height: 38px;
        background-color: var(--bb-bg-surface-2);
        border: 1px solid var(--bb-border-default);
        border-radius: var(--bb-radius-md);
        color: var(--bb-text-primary);
        font-size: 13.5px;
        padding: 0 12px;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .bb-form-control:focus, .bb-form-select:focus {
        background-color: var(--bb-bg-surface-2);
        border-color: var(--bb-border-focus);
        box-shadow: var(--bb-focus-ring);
        color: var(--bb-text-primary);
        outline: none;
    }
    .bb-form-control::placeholder {
        color: var(--bb-text-disabled);
    }
    .bb-input-search-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    .bb-input-search-group i.search-icon {
        position: absolute;
        left: 12px;
        font-size: 15px;
        color: var(--bb-text-tertiary);
        pointer-events: none;
    }
    .bb-input-search-group .bb-form-control {
        padding-left: 36px;
    }

    /* ============================================================== */
    /* TABLE & LIST DATA (DESIGN.md §13)                              */
    /* ============================================================== */
    .bb-table-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
        overflow: hidden !important;
    }
    .bb-table-card__header {
        padding: 16px 20px !important;
        border-bottom: 1px solid #232A35 !important;
        background-color: #181E26 !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .bb-table-card__title {
        font-size: 15px;
        font-weight: 600;
        color: #F0F6FC;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bb-table-card__meta {
        font-size: 13px;
        color: #94A3B8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bb-table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .bb-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
        margin-bottom: 0;
    }
    .bb-table thead th {
        height: 42px;
        padding: 10px 14px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px !important;
        text-transform: uppercase !important;
        color: #8B949E !important;
        background-color: #181E26 !important;
        border-bottom: 1px solid #282E37 !important;
        border-top: none !important;
        white-space: nowrap !important;
        vertical-align: middle;
    }
    .bb-table tbody tr {
        border-bottom: 1px solid #21262D !important;
        transition: background-color 150ms ease;
    }
    .bb-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.025) !important;
    }
    .bb-table tbody td {
        padding: 12px 14px !important;
        border: none !important;
        border-bottom: 1px solid #21262D !important;
        color: #CBD5E1 !important;
        vertical-align: middle;
        font-size: 13px;
        background-color: transparent !important;
    }
    .bb-table tbody tr:last-child td {
        border-bottom: none !important;
    }
    .bb-table th.numeric, .bb-table td.numeric {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }
    .bb-table th.text-center, .bb-table td.text-center {
        text-align: center;
    }

    /* Action Buttons in Table */
    .btn-table-action {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        font-size: 13px;
        cursor: pointer;
    }
    .btn-table-action--edit {
        background-color: rgba(56, 189, 248, 0.1);
        border-color: rgba(56, 189, 248, 0.25);
        color: #38BDF8;
    }
    .btn-table-action--edit:hover {
        background-color: #0284C7;
        color: #FFFFFF;
        border-color: #0284C7;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.35);
        transform: translateY(-1px);
    }
    .btn-table-action--delete {
        background-color: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.25);
        color: #F87171;
    }
    .btn-table-action--delete:hover {
        background-color: #DC2626;
        color: #FFFFFF;
        border-color: #DC2626;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.35);
        transform: translateY(-1px);
    }
    .bb-badge--category {
        background: rgba(99, 102, 241, 0.12) !important;
        color: #A5B4FC !important;
        border: 1px solid rgba(99, 102, 241, 0.3) !important;
        font-size: 11.5px !important;
        font-weight: 500 !important;
        border-radius: 6px !important;
        padding: 3px 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        white-space: nowrap !important;
    }
    .bb-badge--category::before,
    .bb-badge--neutral::before {
        display: none !important;
        content: none !important;
    }
    .dot-indicator {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        display: inline-block;
        flex-shrink: 0;
    }

    /* Badges (DESIGN.md §14) */
    .bb-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        height: 22px;
        padding: 0 8px;
        font-size: 11.5px;
        font-weight: 500;
        border-radius: var(--bb-radius-full);
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .bb-badge::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }
    .bb-badge--neutral {
        background: var(--bb-bg-surface-2);
        color: var(--bb-text-secondary);
        border-color: var(--bb-border-subtle);
    }
    .bb-badge--neutral::before {
        display: none;
    }
    .bb-badge--brand {
        background: var(--bb-brand-subtle);
        color: var(--bb-brand-text);
        border-color: var(--bb-brand-border);
    }
    .bb-badge--info {
        background: var(--bb-info-subtle);
        color: var(--bb-info-text);
        border-color: var(--bb-info-border);
    }
    .bb-badge--success {
        background: var(--bb-success-subtle);
        color: var(--bb-success-text);
        border-color: var(--bb-success-border);
    }
    .bb-badge--warning {
        background: var(--bb-warning-subtle);
        color: var(--bb-warning-text);
        border-color: var(--bb-warning-border);
    }
    .bb-badge--danger {
        background: var(--bb-danger-subtle);
        color: var(--bb-danger-text);
        border-color: var(--bb-danger-border);
    }
    .bb-badge--purple {
        background: rgba(168, 85, 247, 0.12);
        color: #C084FC;
        border-color: rgba(168, 85, 247, 0.35);
    }
    .bb-badge--teal {
        background: rgba(20, 184, 166, 0.12);
        color: #5EEAD4;
        border-color: rgba(20, 184, 166, 0.35);
    }

    /* Action Buttons in Table */
    .bb-action-btn {
        width: 32px;
        height: 32px;
        border-radius: var(--bb-radius-md);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--bb-border-subtle);
        background: var(--bb-bg-surface-2);
        color: var(--bb-text-secondary);
        cursor: pointer;
        transition: all 120ms ease;
        text-decoration: none;
    }
    .bb-action-btn:hover {
        background: var(--bb-bg-hover);
        color: var(--bb-text-primary);
        border-color: var(--bb-border-default);
        transform: translateY(-1px);
    }
    .bb-action-btn--edit:hover {
        background: var(--bb-info-subtle);
        color: var(--bb-info-text);
        border-color: var(--bb-info-border);
    }
    .bb-action-btn--delete:hover {
        background: var(--bb-danger-subtle);
        color: var(--bb-danger-text);
        border-color: var(--bb-danger-border);
    }

    /* Empty State (DESIGN.md §20.2) */
    .bb-empty-state {
        padding: 56px 24px;
        text-align: center;
    }
    .bb-empty-state__icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background-color: var(--bb-bg-surface-2);
        border: 1px solid var(--bb-border-subtle);
        color: var(--bb-text-tertiary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: var(--bb-space-3);
    }
    .bb-empty-state__title {
        font-size: 16px;
        font-weight: 600;
        color: var(--bb-text-primary);
        margin-bottom: 4px;
    }
    .bb-empty-state__desc {
        font-size: 13.5px;
        color: var(--bb-text-tertiary);
        max-width: 42ch;
        margin: 0 auto var(--bb-space-4);
    }

    /* ============================================================== */
    /* MODAL STYLES (DESIGN.md §15.3)                                 */
    /* ============================================================== */
    .bb-modal-content {
        background-color: var(--bb-bg-surface);
        border: 1px solid var(--bb-border-subtle);
        border-radius: var(--bb-radius-xl);
        box-shadow: var(--bb-shadow-xl);
        overflow: hidden;
    }
    .bb-modal-header {
        padding: var(--bb-space-4) var(--bb-space-6);
        background-color: var(--bb-bg-surface-2);
        border-bottom: 1px solid var(--bb-border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .bb-modal-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--bb-text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bb-modal-body {
        padding: var(--bb-space-6);
        max-height: calc(85vh - 130px);
        overflow-y: auto;
    }
    .bb-modal-footer {
        padding: var(--bb-space-3) var(--bb-space-6);
        background-color: var(--bb-bg-surface-2);
        border-top: 1px solid var(--bb-border-subtle);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: var(--bb-space-2);
    }
    .bb-category-card {
        background-color: var(--bb-bg-surface-2);
        border: 1px solid var(--bb-border-subtle);
        border-radius: var(--bb-radius-lg);
        padding: var(--bb-space-4);
    }

    /* Dropzone / Upload box */
    .bb-upload-container {
        border: 1px dashed var(--bb-border-default);
        border-radius: var(--bb-radius-md);
        background: var(--bb-bg-surface-2);
        padding: var(--bb-space-3);
        transition: border-color 150ms ease;
    }
    .bb-upload-container:hover {
        border-color: var(--bb-border-focus);
    }
    .bb-preview-img {
        max-width: 100%;
        max-height: 160px;
        object-fit: contain;
        border-radius: var(--bb-radius-md);
        border: 1px solid var(--bb-border-subtle);
        background-color: #000;
        padding: 4px;
    }

    /* Pagination (DESIGN.md §13.2) */
    .bb-pagination {
        padding: var(--bb-space-3) var(--bb-space-5);
        border-top: 1px solid var(--bb-border-subtle);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 13px;
        color: var(--bb-text-tertiary);
        background-color: var(--bb-bg-surface);
    }
    .bb-pagination-nav {
        display: flex;
        align-items: center;
    }
    .bb-pagination-list {
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .bb-pagination-item {
        display: inline-flex;
    }
    .bb-pagination-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 10px;
        font-size: 13px;
        font-weight: 500;
        border-radius: var(--bb-radius-md);
        border: 1px solid var(--bb-border-subtle);
        background-color: var(--bb-bg-surface-2);
        color: var(--bb-text-secondary);
        text-decoration: none;
        transition: all 120ms ease;
        user-select: none;
    }
    .bb-pagination-link:hover {
        background-color: var(--bb-bg-hover);
        color: var(--bb-text-primary);
        border-color: var(--bb-border-default);
        transform: translateY(-1px);
    }
    .bb-pagination-item.active .bb-pagination-link {
        background-color: var(--bb-brand);
        color: #FFFFFF;
        border-color: var(--bb-brand);
        box-shadow: 0 1px 4px rgba(99, 102, 241, 0.4);
        font-weight: 600;
    }
    .bb-pagination-item.disabled .bb-pagination-link {
        opacity: 0.35;
        cursor: not-allowed;
        background-color: transparent;
        border-color: var(--bb-border-subtle);
        color: var(--bb-text-disabled);
        pointer-events: none;
    }
    .bb-pagination-link.dots {
        border-color: transparent;
        background: transparent;
        cursor: default;
    }

    /* Fallback safety reset if default pagination elements render */
    .bb-pagination svg {
        width: 14px !important;
        height: 14px !important;
        max-width: 14px !important;
        max-height: 14px !important;
        display: inline-block;
    }
    .bb-pagination .pagination {
        margin: 0;
        gap: 4px;
    }
    .bb-pagination .page-item .page-link {
        background-color: var(--bb-bg-surface-2);
        border: 1px solid var(--bb-border-subtle);
        color: var(--bb-text-secondary);
        border-radius: var(--bb-radius-md) !important;
        font-size: 13px;
        padding: 4px 10px;
        min-width: 32px;
        text-align: center;
    }
    .bb-pagination .page-item.active .page-link {
        background-color: var(--bb-brand);
        border-color: var(--bb-brand);
        color: #FFFFFF;
    }
    .bb-pagination .page-item.disabled .page-link {
        background-color: transparent;
        border-color: var(--bb-border-subtle);
        color: var(--bb-text-disabled);
        opacity: 0.4;
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- ============================================================== -->
        <!-- PAGE TITLE & HEADER TOOLBAR (DESIGN.md §5, §9, §24)           -->
        <!-- ============================================================== -->
        <div class="row align-items-center mb-4 g-3">
            <div class="col-sm">
                <div>
                    <h1 class="bb-page-title">
                        <i class="ri-wallet-3-line text-primary"></i>
                        Beban Belanja
                    </h1>
                    <p class="bb-page-subtitle">Pencatatan pengeluaran operasional, utilitas toko, dan perlengkapan harian Tokabe.</p>
                </div>
            </div>
            <div class="col-sm-auto">
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="bb-btn bb-btn--secondary" data-bs-toggle="modal" data-bs-target="#modalKelolaKategori" title="Kelola Kategori Beban Belanja">
                        <i class="ri-price-tag-3-line"></i>
                        <span>Kategori Beban</span>
                    </button>
                    <a href="{{ route('beban-belanja.export-pdf', request()->all()) }}" target="_blank" class="bb-btn bb-btn--outline-danger" title="Buka dan cetak rekap data ke format PDF">
                        <i class="ri-file-pdf-line"></i>
                        <span>Export PDF</span>
                    </a>
                    <button type="button" class="bb-btn bb-btn--primary" data-bs-toggle="modal" data-bs-target="#modalTambahBeban">
                        <i class="ri-add-line"></i>
                        <span>Tambah Pengeluaran</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 4 KPI SUMMARY CARDS (DESIGN.md §12.4 & Tokabe Standard)        -->
        <!-- ============================================================== -->
        <div class="row g-3 mb-4">
            <!-- KPI 1: Beban Bulan Ini -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card kpi-card--brand">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <span class="kpi-card__overline">Beban Bulan Ini</span>
                            <div class="kpi-card__icon">
                                <i class="ri-wallet-3-line"></i>
                            </div>
                        </div>
                        <div class="kpi-card__value">
                            Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
                        </div>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">Periode {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI 2: Transaksi Bulan Ini -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card kpi-card--info">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <span class="kpi-card__overline">Transaksi Bulan Ini</span>
                            <div class="kpi-card__icon">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                        </div>
                        <div class="kpi-card__value">
                            {{ number_format($totalTransaksiBulanIni) }} <span class="kpi-card__unit">Transaksi</span>
                        </div>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">Pengeluaran Tercatat</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI 3: Pengeluaran Hari Ini -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card kpi-card--success">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <span class="kpi-card__overline">Pengeluaran Hari Ini</span>
                            <div class="kpi-card__icon">
                                <i class="ri-calendar-check-line"></i>
                            </div>
                        </div>
                        <div class="kpi-card__value">
                            Rp {{ number_format($totalHariIni, 0, ',', '.') }}
                        </div>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">{{ Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI 4: Kategori Terbesar -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card kpi-card--warning">
                    <div class="kpi-card__body">
                        <div class="kpi-card__header">
                            <span class="kpi-card__overline">Kategori Terbesar</span>
                            <div class="kpi-card__icon">
                                <i class="ri-pie-chart-line"></i>
                            </div>
                        </div>
                        <div class="kpi-card__value kpi-card__value--text text-truncate" title="{{ $kategoriTerbesar }}">
                            {{ $kategoriTerbesar }}
                        </div>
                        <div class="kpi-card__footer">
                            <span class="kpi-badge">Porsi Belanja Tertinggi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- FILTER TOOLBAR (DESIGN.md §10, §13.2)                          -->
        <!-- ============================================================== -->
        <div class="bb-filter-card">
            <form action="{{ route('beban-belanja.index') }}" method="GET" id="form-filter">
                <div class="row g-3 align-items-end">

                    <!-- Periode Filter -->
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <label class="bb-field-label">Periode</label>
                        <select name="filter_mode" id="filter_mode" class="form-select bb-form-select" onchange="toggleCustomDate(this.value)">
                            <option value="this_month" {{ $filterMode === 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="today" {{ $filterMode === 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="custom" {{ $filterMode === 'custom' ? 'selected' : '' }}>Rentang Tanggal</option>
                            <option value="all" {{ $filterMode === 'all' ? 'selected' : '' }}>Semua Periode</option>
                        </select>
                    </div>

                    <!-- Custom Date Range -->
                    <div class="col-lg-3 col-md-4 col-sm-6" id="div-custom-date" style="display: {{ $filterMode === 'custom' ? 'block' : 'none' }};">
                        <label class="bb-field-label">Rentang Tanggal</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control bb-form-control" value="{{ $startDate }}" placeholder="Mulai">
                            <span class="input-group-text bg-transparent border-0 text-muted px-1">-</span>
                            <input type="date" name="end_date" class="form-control bb-form-control" value="{{ $endDate }}" placeholder="Selesai">
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <label class="bb-field-label">Kategori Beban</label>
                        <select name="kategori_id" class="form-select bb-form-select" onchange="document.getElementById('form-filter').submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Sumber Anggaran -->
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <label class="bb-field-label">Sumber Anggaran</label>
                        <select name="anggaran_id" class="form-select bb-form-select" onchange="document.getElementById('form-filter').submit()">
                            <option value="">Semua Anggaran</option>
                            <option value="none" {{ request('anggaran_id') === 'none' ? 'selected' : '' }}>Non-Anggaran (Umum)</option>
                            @foreach($budgets as $b)
                                <option value="{{ $b->id }}" {{ request('anggaran_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_budget }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Box -->
                    <div class="col-lg-3 col-md-6 col-sm-12 ms-auto">
                        <label class="bb-field-label">Cari Deskripsi / Penyedia</label>
                        <div class="input-group">
                            <div class="bb-input-search-group flex-grow-1">
                                <i class="ri-search-line search-icon"></i>
                                <input type="text" name="search" class="form-control bb-form-control" placeholder="Cari kata kunci..." value="{{ request('search') }}">
                            </div>
                            <button class="bb-btn bb-btn--primary" type="submit" title="Cari data">
                                <i class="ri-search-line"></i>
                            </button>
                            @if(request()->hasAny(['search', 'kategori_id', 'anggaran_id', 'rekening_id']) || request('filter_mode') !== 'this_month')
                                <a href="{{ route('beban-belanja.index') }}" class="bb-btn bb-btn--secondary" title="Reset filter">
                                    <i class="ri-refresh-line"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <!-- ============================================================== -->
        <!-- TABLE DATA CARD (DESIGN.md §13)                                -->
        <!-- ============================================================== -->
        <div class="bb-table-card">
            <div class="bb-table-card__header">
                <div class="d-flex align-items-center gap-2">
                    <h2 class="bb-table-card__title">
                        <span>Daftar Pengeluaran Beban</span>
                    </h2>
                    <span class="bb-badge bb-badge--neutral font-mono-code">{{ $bebanList->total() }} Data</span>
                </div>
                <div class="bb-table-card__meta">
                    <span class="text-muted" style="font-size: 13px;">Total Pengeluaran Filter:</span>
                    <strong class="text-danger fw-bold tabular-num" style="font-size: 16px;">Rp {{ number_format($totalFiltered, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="bb-table-responsive">
                <table class="bb-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 45px;">No</th>
                            <th style="width: 145px;">No. Bukti</th>
                            <th style="width: 125px;">Tanggal</th>
                            <th style="width: 155px;">Kategori</th>
                            <th>Deskripsi Belanja</th>
                            <th style="width: 165px;">Penyedia</th>
                            <th class="text-end" style="width: 140px;">Nominal (Rp)</th>
                            <th style="width: 175px;">Sumber Anggaran</th>
                            <th class="text-center" style="width: 80px;">Struk</th>
                            <th style="width: 145px;">Admin PIC</th>
                            <th class="text-center" style="width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bebanList as $index => $item)
                            <tr>
                                <td class="text-center text-muted font-num" style="font-size: 12px;">{{ $bebanList->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge" style="background: rgba(56, 189, 248, 0.1); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.25); font-family: var(--font-mono, monospace); font-size: 11.5px; font-weight: 600; padding: 4px 8px; border-radius: 6px; letter-spacing: 0.3px;">
                                        {{ $item->nomor_beban }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-white fw-medium font-num text-nowrap" style="font-size: 12.5px;">
                                        {{ optional($item->tanggal_beban)->translatedFormat('d M Y') ?: $item->formatted_tanggal }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->kategori)
                                        <span class="bb-badge bb-badge--category">
                                            {{ $item->kategori->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="bb-badge bb-badge--neutral">
                                            Umum
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-white fs-13" style="line-height: 1.35;">{{ $item->nama_beban }}</div>
                                    @if(!empty($item->keterangan))
                                        <small class="text-muted d-block mt-0.5" style="font-size: 11.5px; line-height: 1.3;">"{{ Str::limit($item->keterangan, 60) }}"</small>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($item->penerima) && $item->penerima !== '-')
                                        <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded" style="background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.28);">
                                            <i class="ri-store-2-fill fs-13 flex-shrink-0" style="color: #FCD34D;"></i>
                                            <span class="text-white fw-semibold fs-12 text-nowrap" title="{{ $item->penerima }}">{{ $item->penerima }}</span>
                                        </div>
                                    @else
                                        <span class="badge" style="background-color: rgba(255, 255, 255, 0.03); color: #64748B; border: 1px dashed #334155; font-size: 11px; padding: 2px 7px; border-radius: 4px;">
                                            <i class="ri-subtract-line me-0.5"></i>Tanpa Penyedia
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-end fw-bold font-num" style="color: #FB7185; font-size: 13.5px;">
                                        {{ $item->formatted_nominal }}
                                    </div>
                                </td>
                                <td>
                                    @if($item->anggaran)
                                        <div class="d-flex align-items-center gap-1.5">
                                            <i class="ri-pie-chart-line text-info"></i>
                                            <span class="text-white fw-medium fs-12 text-nowrap">{{ $item->anggaran->nama_budget }}</span>
                                        </div>
                                        <div class="font-num mt-0.5" style="font-size: 11px; color: #8B949E;">
                                            Sisa: <span style="color: {{ $item->anggaran->anggaran < 0 ? '#FB7185' : '#34D399' }};">Rp {{ number_format($item->anggaran->anggaran, 0, ',', '.') }}</span>
                                        </div>
                                    @else
                                        <span class="badge" style="background: rgba(148, 163, 184, 0.1); color: #94A3B8; border: 1px solid rgba(148, 163, 184, 0.2); font-size: 11px; font-weight: 500;">
                                            Non-Anggaran
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->bukti_struk)
                                        @php
                                            $ext = strtolower(pathinfo($item->bukti_struk, PATHINFO_EXTENSION));
                                        @endphp
                                        @if($ext === 'pdf')
                                            <a href="{{ asset($item->bukti_struk) }}" target="_blank" class="btn btn-sm d-inline-flex align-items-center gap-1" style="background: rgba(239, 68, 68, 0.12); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 6px; padding: 2px 7px; font-size: 11px;" title="Buka Dokumen PDF">
                                                <i class="ri-file-pdf-line"></i> PDF
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-sm d-inline-flex align-items-center gap-1" style="background: rgba(56, 189, 248, 0.12); color: #38BDF8; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 6px; padding: 2px 7px; font-size: 11px;" onclick="previewStruk('{{ asset($item->bukti_struk) }}', '{{ $item->nomor_beban }} - {{ $item->nama_beban }}')" title="Pratinjau Foto Struk">
                                                <i class="ri-image-line"></i> Foto
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-muted fs-12">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $adminName = $item->admin ? ($item->admin->nama ?? $item->admin->name ?? 'Admin') : 'System';
                                    @endphp
                                    <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded" style="background-color: rgba(255, 255, 255, 0.04); border: 1px solid #334155;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 22px; height: 22px; background: rgba(56, 189, 248, 0.15); color: #38BDF8; font-size: 11px;">
                                            <i class="ri-user-3-fill"></i>
                                        </div>
                                        <span class="text-white fw-semibold fs-12 text-nowrap">
                                            {{ $adminName }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1.5">
                                        <button type="button" class="btn-table-action btn-table-action--edit" title="Edit Data" onclick="openEditModal({{ json_encode($item) }})">
                                            <i class="ri-edit-2-line"></i>
                                        </button>
                                        <form action="{{ route('beban-belanja.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-table-action btn-table-action--delete" title="Hapus Data" onclick="confirmDelete(this.closest('form'), '{{ $item->nomor_beban }}')">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="p-0">
                                    <div class="bb-empty-state">
                                        <div class="bb-empty-state__icon">
                                            <i class="ri-receipt-line"></i>
                                        </div>
                                        <h3 class="bb-empty-state__title">Belum Ada Pengeluaran Beban</h3>
                                        <p class="bb-empty-state__desc">Tidak ada data pengeluaran operasional yang sesuai dengan filter atau periode yang dipilih.</p>
                                        <button type="button" class="bb-btn bb-btn--primary" data-bs-toggle="modal" data-bs-target="#modalTambahBeban">
                                            <i class="ri-add-line"></i>
                                            <span>Tambah Pengeluaran</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bebanList->hasPages())
                <div class="bb-pagination">
                    <div class="text-secondary" style="font-size: 13px;">
                        Menampilkan <span class="fw-semibold text-primary">{{ $bebanList->firstItem() }}</span> sampai <span class="fw-semibold text-primary">{{ $bebanList->lastItem() }}</span> dari <span class="fw-semibold text-primary">{{ $bebanList->total() }}</span> total pengeluaran
                    </div>
                    <div>
                        {{ $bebanList->links('vendor.pagination.tokabe') }}
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL TAMBAH BEBAN BELANJA (DESIGN.md §15.3, §10)             -->
<!-- ============================================================== -->
<div class="modal fade" id="modalTambahBeban" tabindex="-1" aria-labelledby="modalTambahBebanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content bb-modal-content border-0">
            <div class="bb-modal-header">
                <div>
                    <h5 class="bb-modal-title" id="modalTambahBebanLabel">
                        <i class="ri-wallet-3-line text-primary"></i>
                        <span>Tambah Pengeluaran Beban Belanja</span>
                    </h5>
                    <small class="text-muted d-block mt-1">Catat pengeluaran operasional, perlengkapan, atau utilitas toko.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('beban-belanja.store') }}" method="POST" enctype="multipart/form-data" id="form-tambah-beban">
                @csrf
                <div class="bb-modal-body">
                    <div class="row g-3">
                        <!-- 1. Nomor Bukti Transaksi -->
                        <div class="col-12">
                            <label class="bb-field-label">No. Bukti Transaksi <span class="text-muted fw-normal">(Otomatis Sistem)</span></label>
                            <input type="text" class="form-control bb-form-control font-mono-code text-muted" value="{{ $autoNomor }}" readonly tabindex="-1">
                        </div>

                        <!-- 2. Tanggal Pengeluaran -->
                        <div class="col-12">
                            <label class="bb-field-label">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control bb-form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- 3. Kategori Beban -->
                        <div class="col-12">
                            <label class="bb-field-label">Kategori Beban <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select bb-form-select" required>
                                <option value="" selected disabled>-- Pilih Kategori Beban --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 4. Deskripsi Belanja -->
                        <div class="col-12">
                            <label class="bb-field-label">Deskripsi Belanja <span class="text-danger">*</span></label>
                            <input type="text" name="nama_beban" class="form-control bb-form-control" placeholder="Contoh: Pembayaran Listrik PLN Toko Bulan Oktober" required>
                        </div>

                        <!-- 5. Penyedia -->
                        <div class="col-12">
                            <label class="bb-field-label">Penyedia <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="text" name="penerima" class="form-control bb-form-control" placeholder="Contoh: PLN Medan / Toko Mitra Sejati / Staff">
                        </div>

                        <!-- 6. Nominal (Rp) -->
                        <div class="col-12">
                            <label class="bb-field-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted fw-bold">Rp</span>
                                <input type="text" name="nominal" class="form-control bb-form-control border-start-0 input-rupiah fw-bold text-end tabular-num" placeholder="0" required>
                            </div>
                        </div>

                        <!-- 7. Sumber Anggaran -->
                        <div class="col-12">
                            <label class="bb-field-label">Sumber Anggaran <span class="text-muted fw-normal">(Opsional)</span></label>
                            <select name="anggaran_id" class="form-select bb-form-select">
                                <option value="">-- Tanpa Anggaran (Pengeluaran Umum) --</option>
                                @foreach($budgets as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_budget }} (Sisa: Rp {{ number_format($b->anggaran, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 8. Bukti Struk / Nota -->
                        <div class="col-12">
                            <label class="bb-field-label">Bukti Struk / Nota <span class="text-muted fw-normal">(Opsional)</span></label>
                            <div class="bb-upload-container">
                                <input type="file" name="bukti_struk" class="form-control bb-form-control mb-1" accept="image/*,application/pdf" onchange="previewUploadImage(this, 'preview-tambah')">
                                <small class="text-muted d-block" style="font-size: 11px;">Format: JPG, PNG, WEBP, atau PDF (Maks. 5 MB)</small>
                                <div class="mt-2 text-center" id="container-preview-tambah" style="display: none;">
                                    <img src="" id="preview-tambah" class="bb-preview-img" alt="Pratinjau Struk">
                                </div>
                            </div>
                        </div>

                        <!-- 9. Catatan Tambahan -->
                        <div class="col-12">
                            <label class="bb-field-label">Catatan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="keterangan" class="form-control bb-form-control" rows="2" style="height: auto;" placeholder="Keterangan rincian atau catatan lain..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bb-modal-footer">
                    <button type="button" class="bb-btn bb-btn--secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="bb-btn bb-btn--primary">
                        <i class="ri-save-line"></i>
                        <span>Simpan Pengeluaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL EDIT BEBAN BELANJA (DESIGN.md §15.3, §10)               -->
<!-- ============================================================== -->
<div class="modal fade" id="modalEditBeban" tabindex="-1" aria-labelledby="modalEditBebanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content bb-modal-content border-0">
            <div class="bb-modal-header">
                <div>
                    <h5 class="bb-modal-title" id="modalEditBebanLabel">
                        <i class="ri-edit-2-line text-info"></i>
                        <span>Edit Data Beban Belanja</span>
                    </h5>
                    <small class="text-muted d-block mt-1">Perbarui rincian pengeluaran operasional toko.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" id="form-edit-beban">
                @csrf
                @method('PUT')
                <div class="bb-modal-body">
                    <div class="row g-3">
                        <!-- 1. Nomor Bukti Transaksi -->
                        <div class="col-12">
                            <label class="bb-field-label">No. Bukti Transaksi</label>
                            <input type="text" id="edit_nomor_beban" class="form-control bb-form-control font-mono-code text-muted" readonly tabindex="-1">
                        </div>

                        <!-- 2. Tanggal Pengeluaran -->
                        <div class="col-12">
                            <label class="bb-field-label">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="form-control bb-form-control" required>
                        </div>

                        <!-- 3. Kategori Beban -->
                        <div class="col-12">
                            <label class="bb-field-label">Kategori Beban <span class="text-danger">*</span></label>
                            <select name="kategori_id" id="edit_kategori_id" class="form-select bb-form-select" required>
                                <option value="" disabled>-- Pilih Kategori Beban --</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 4. Deskripsi Belanja -->
                        <div class="col-12">
                            <label class="bb-field-label">Deskripsi Belanja <span class="text-danger">*</span></label>
                            <input type="text" name="nama_beban" id="edit_nama_beban" class="form-control bb-form-control" required>
                        </div>

                        <!-- 5. Penyedia -->
                        <div class="col-12">
                            <label class="bb-field-label">Penyedia <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="text" name="penerima" id="edit_penerima" class="form-control bb-form-control" placeholder="Contoh: PLN Medan / Toko Mitra Sejati / Staff">
                        </div>

                        <!-- 6. Nominal (Rp) -->
                        <div class="col-12">
                            <label class="bb-field-label">Nominal (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-end-0 text-muted fw-bold">Rp</span>
                                <input type="text" name="nominal" id="edit_nominal" class="form-control bb-form-control border-start-0 input-rupiah fw-bold text-end tabular-num" required>
                            </div>
                        </div>

                        <!-- 7. Sumber Anggaran -->
                        <div class="col-12">
                            <label class="bb-field-label">Sumber Anggaran <span class="text-muted fw-normal">(Opsional)</span></label>
                            <select name="anggaran_id" id="edit_anggaran_id" class="form-select bb-form-select">
                                <option value="">-- Tanpa Anggaran (Pengeluaran Umum) --</option>
                                @foreach($budgets as $b)
                                    <option value="{{ $b->id }}">{{ $b->nama_budget }} (Sisa: Rp {{ number_format($b->anggaran, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 8. Ganti Bukti Struk / Nota -->
                        <div class="col-12">
                            <label class="bb-field-label">Ganti Bukti Struk / Nota <span class="text-muted fw-normal">(Opsional)</span></label>
                            <div class="bb-upload-container">
                                <input type="file" name="bukti_struk" class="form-control bb-form-control mb-1" accept="image/*,application/pdf" onchange="previewUploadImage(this, 'preview-edit')">
                                <small class="text-muted d-block" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengubah nota.</small>
                                <div class="mt-2 text-center" id="container-preview-edit">
                                    <img src="" id="preview-edit" class="bb-preview-img" alt="Pratinjau Struk" style="display: none;">
                                    <div id="edit_existing_file" class="mt-1" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 9. Catatan Tambahan -->
                        <div class="col-12">
                            <label class="bb-field-label">Catatan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control bb-form-control" rows="2" style="height: auto;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bb-modal-footer">
                    <button type="button" class="bb-btn bb-btn--secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="bb-btn bb-btn--primary">
                        <i class="ri-save-line"></i>
                        <span>Perbarui Pengeluaran</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL KELOLA KATEGORI BEBAN (DESIGN.md §15.3, §10, §13)        -->
<!-- ============================================================== -->
<div class="modal fade" id="modalKelolaKategori" tabindex="-1" aria-labelledby="modalKelolaKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bb-modal-content border-0">
            <div class="bb-modal-header">
                <div>
                    <h5 class="bb-modal-title" id="modalKelolaKategoriLabel">
                        <i class="ri-price-tag-3-line text-primary"></i>
                        <span>Kelola Kategori Beban Belanja</span>
                    </h5>
                    <small class="text-muted d-block mt-1">Tambah, ubah, atau hapus kategori klasifikasi beban operasional toko.</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="bb-modal-body">
                <!-- Form Tambah / Edit Kategori -->
                <div class="bb-category-card mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold text-primary" id="kategoriFormTitle" style="font-size: 13.5px;">
                            <i class="ri-add-circle-line text-primary me-1"></i>Tambah Kategori Baru
                        </span>
                        <button type="button" class="btn btn-sm text-info p-0" id="btnBatalEditKategori" style="display: none; font-size: 12px;" onclick="resetKategoriForm()">
                            <i class="ri-close-line"></i> Batal Edit (Kembali ke Tambah)
                        </button>
                    </div>
                    <form action="{{ route('kategori-beban.store') }}" method="POST" id="formKategoriBeban">
                        @csrf
                        <input type="hidden" name="_method" id="kategoriMethod" value="POST">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5 col-12">
                                <label class="bb-field-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kategori" id="input_nama_kategori" class="form-control bb-form-control" placeholder="Contoh: Pemeliharaan Mesin" required maxlength="100">
                            </div>
                            <div class="col-md-5 col-12">
                                <label class="bb-field-label">Deskripsi <span class="text-muted fw-normal">(Opsional)</span></label>
                                <input type="text" name="deskripsi" id="input_deskripsi_kategori" class="form-control bb-form-control" placeholder="Keterangan kategori..." maxlength="255">
                            </div>
                            <div class="col-md-2 col-12">
                                <button type="submit" class="bb-btn bb-btn--primary w-100" id="btnSubmitKategori">
                                    <span id="btnSubmitKategoriText">Simpan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tabel Daftar Kategori -->
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold text-primary" style="font-size: 13.5px;">Daftar Kategori Saat Ini</span>
                    <span class="bb-badge bb-badge--neutral font-mono-code">{{ count($kategoris) }} Kategori</span>
                </div>

                <div class="bb-table-responsive" style="max-height: 320px; overflow-y: auto;">
                    <table class="bb-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 45px;">No</th>
                                <th style="width: 220px;">Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th class="text-center" style="width: 120px;">Penggunaan</th>
                                <th class="text-center" style="width: 90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoris as $kIdx => $kItem)
                                <tr>
                                    <td class="text-center text-muted tabular-num">{{ $kIdx + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold text-primary" style="font-size: 13px;">{{ $kItem->nama_kategori }}</div>
                                    </td>
                                    <td>
                                        <span class="text-secondary" style="font-size: 12.5px;">{{ $kItem->deskripsi ?: '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if(($kItem->beban_belanja_count ?? 0) > 0)
                                            <span class="bb-badge bb-badge--info font-mono-code">{{ $kItem->beban_belanja_count }} Beban</span>
                                        @else
                                            <span class="bb-badge bb-badge--neutral font-mono-code">0 Beban</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button type="button" class="bb-action-btn bb-action-btn--edit" onclick="editKategori({{ json_encode($kItem) }})" title="Edit Kategori">
                                                <i class="ri-edit-2-line"></i>
                                            </button>
                                            <button type="button" class="bb-action-btn bb-action-btn--delete" onclick="deleteKategori({{ $kItem->id }}, '{{ addslashes($kItem->nama_kategori) }}', {{ $kItem->beban_belanja_count ?? 0 }})" title="Hapus Kategori">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada kategori beban belanja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Form Hidden Delete Kategori -->
                <form id="form-delete-kategori" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="bb-modal-footer">
                <button type="button" class="bb-btn bb-btn--secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL PREVIEW BUKTI STRUK (DESIGN.md §15.3, §19.6)             -->
<!-- ============================================================== -->
<div class="modal fade" id="modalPreviewStruk" tabindex="-1" aria-labelledby="modalPreviewStrukLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bb-modal-content border-0">
            <div class="bb-modal-header">
                <div>
                    <h5 class="bb-modal-title" id="previewStrukTitle">
                        <i class="ri-image-line text-info"></i>
                        <span>Pratinjau Bukti Struk</span>
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center" style="background-color: #06080B;">
                <img src="" id="previewStrukImg" class="img-fluid rounded" style="max-height: 75vh; border: 1px solid var(--bb-border-subtle);" alt="Bukti Struk">
            </div>
            <div class="bb-modal-footer justify-content-between">
                <a href="" id="previewStrukDownload" target="_blank" download class="bb-btn bb-btn--secondary">
                    <i class="ri-download-line"></i>
                    <span>Unduh File</span>
                </a>
                <button type="button" class="bb-btn bb-btn--secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('plugins')
<script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script>
    function toggleCustomDate(val) {
        var div = document.getElementById('div-custom-date');
        if (val === 'custom') {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';
            document.getElementById('form-filter').submit();
        }
    }

    // Auto format Rupiah pada input (DESIGN.md §10.2, §23)
    document.addEventListener('input', function (e) {
        if (e.target && e.target.classList.contains('input-rupiah')) {
            var value = e.target.value.replace(/[^0-9]/g, '');
            if (value !== '') {
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            } else {
                e.target.value = '';
            }
        }
    });

    // Preview File Upload (DESIGN.md §10.1)
    function previewUploadImage(input, imgId) {
        var container = document.getElementById('container-' + imgId);
        var preview = document.getElementById(imgId);
        if (input.files && input.files[0]) {
            var file = input.files[0];
            if (file.type.match('image.*')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';
                    if (container) container.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                if (container) container.style.display = 'block';
            }
        }
    }

    // Preview Struk Modal
    function previewStruk(url, title) {
        document.getElementById('previewStrukTitle').innerHTML = '<i class="ri-image-line text-info me-1"></i> ' + (title || 'Bukti Struk');
        document.getElementById('previewStrukImg').src = url;
        document.getElementById('previewStrukDownload').href = url;
        var modal = new bootstrap.Modal(document.getElementById('modalPreviewStruk'));
        modal.show();
    }

    // Open Edit Modal with Pre-filled Data
    function openEditModal(item) {
        var form = document.getElementById('form-edit-beban');
        form.action = "{{ url('beban-belanja') }}/" + item.id;

        document.getElementById('edit_nomor_beban').value = item.nomor_beban;
        document.getElementById('edit_tanggal').value = item.tanggal;
        document.getElementById('edit_kategori_id').value = item.kategori_id || '';
        document.getElementById('edit_anggaran_id').value = item.anggaran_id || '';
        document.getElementById('edit_nama_beban').value = item.nama_beban;
        document.getElementById('edit_nominal').value = new Intl.NumberFormat('id-ID').format(item.nominal);
        document.getElementById('edit_penerima').value = item.penerima || '';
        document.getElementById('edit_keterangan').value = item.keterangan || '';

        var existingContainer = document.getElementById('edit_existing_file');
        var previewImg = document.getElementById('preview-edit');
        previewImg.style.display = 'none';

        if (item.bukti_struk) {
            existingContainer.style.display = 'block';
            existingContainer.innerHTML = '<span class="text-success" style="font-size: 12px;"><i class="ri-checkbox-circle-line me-1"></i> File nota tersimpan: <a href="/' + item.bukti_struk + '" target="_blank" class="fw-semibold text-decoration-underline text-info">Lihat Bukti</a></span>';
        } else {
            existingContainer.style.display = 'none';
            existingContainer.innerHTML = '';
        }

        var modal = new bootstrap.Modal(document.getElementById('modalEditBeban'));
        modal.show();
    }

    // Confirm Delete Dialog (DESIGN.md §15.3, §24)
    function confirmDelete(form, nomorBeban) {
        Swal.fire({
            title: 'Hapus Beban Belanja?',
            text: 'Data ' + nomorBeban + ' akan dihapus dari pencatatan pengeluaran operasional.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#283241',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#12161D',
            color: '#F1F5F9',
            customClass: {
                popup: 'bb-modal-content border'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // ==============================================================
    // KELOLA KATEGORI BEBAN JS
    // ==============================================================
    function editKategori(kat) {
        document.getElementById('kategoriFormTitle').innerHTML = '<i class="ri-edit-2-line text-info me-1"></i> Edit Kategori: ' + kat.nama_kategori;
        var form = document.getElementById('formKategoriBeban');
        form.action = "{{ url('kategori-beban') }}/" + kat.id;
        document.getElementById('kategoriMethod').value = 'PUT';
        document.getElementById('input_nama_kategori').value = kat.nama_kategori;
        document.getElementById('input_deskripsi_kategori').value = kat.deskripsi || '';
        document.getElementById('btnSubmitKategoriText').innerText = 'Perbarui';
        document.getElementById('btnBatalEditKategori').style.display = 'block';
        document.getElementById('input_nama_kategori').focus();
    }

    function resetKategoriForm() {
        document.getElementById('kategoriFormTitle').innerHTML = '<i class="ri-add-circle-line text-primary me-1"></i> Tambah Kategori Baru';
        var form = document.getElementById('formKategoriBeban');
        form.action = "{{ route('kategori-beban.store') }}";
        document.getElementById('kategoriMethod').value = 'POST';
        document.getElementById('input_nama_kategori').value = '';
        document.getElementById('input_deskripsi_kategori').value = '';
        document.getElementById('btnSubmitKategoriText').innerText = 'Simpan';
        document.getElementById('btnBatalEditKategori').style.display = 'none';
    }

    function deleteKategori(id, name, count) {
        if (count > 0) {
            Swal.fire({
                title: 'Tidak Dapat Dihapus',
                text: 'Kategori "' + name + '" sedang digunakan oleh ' + count + ' data pengeluaran beban belanja. Ubah kategori transaksi terkait terlebih dahulu.',
                icon: 'error',
                confirmButtonColor: '#6366F1',
                confirmButtonText: 'Mengerti',
                background: '#12161D',
                color: '#F1F5F9',
                customClass: {
                    popup: 'bb-modal-content border'
                }
            });
            return;
        }

        Swal.fire({
            title: 'Hapus Kategori?',
            text: 'Kategori "' + name + '" akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#283241',
            confirmButtonText: 'Ya, Hapus Kategori',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#12161D',
            color: '#F1F5F9',
            customClass: {
                popup: 'bb-modal-content border'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.getElementById('form-delete-kategori');
                form.action = "{{ url('kategori-beban') }}/" + id;
                form.submit();
            }
        });
    }

    @if(session('open_modal_kategori'))
        document.addEventListener("DOMContentLoaded", function () {
            var modalKat = new bootstrap.Modal(document.getElementById('modalKelolaKategori'));
            modalKat.show();
        });
    @endif
</script>
@endsection
