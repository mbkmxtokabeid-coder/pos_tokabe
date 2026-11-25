@props(['reminder' => null])

@php
    $r = $reminder ?? [];
    $shouldRemind = !empty($r['should_remind']);
    $userRole = Auth::user()->role ?? null;
    $isPemilik = $userRole === 'Pemilik';
    $isAdmin = $userRole === 'Admin';
@endphp

@if($shouldRemind && ($isAdmin || $isPemilik))
@php
    $isDanger = ($r['level'] ?? 'warning') === 'danger';
    $levelClass = $isDanger ? 'tkb-spt-banner--danger' : 'tkb-spt-banner--warning';
    $waPrimaryUrl = $r['wa_primary_url'] ?? '';
    $dismissKey = $r['dismiss_key'] ?? 'spt_reminder_dismissed';
    $autoWaKey = $r['auto_wa_key'] ?? 'spt_auto_wa_sent';
@endphp

<style>
    .tkb-spt-wrap {
        display: none;
        padding: 16px 24px 0 24px;
    }

    .tkb-spt-banner {
        border-radius: var(--radius-inner, 12px);
        padding: 14px 18px;
        border: 1px solid var(--border-default);
        box-shadow: var(--shadow-card);
        background: var(--bg-surface);
        color: var(--text-primary);
    }

    .tkb-spt-banner--warning {
        background: linear-gradient(95deg, var(--warning-subtle) 0%, var(--bg-surface) 44%, var(--bg-surface) 100%);
        border-color: var(--warning-border);
        border-left: 4px solid var(--warning);
    }

    .tkb-spt-banner--danger {
        background: linear-gradient(95deg, var(--danger-subtle) 0%, var(--bg-surface) 44%, var(--bg-surface) 100%);
        border-color: var(--danger-border);
        border-left: 4px solid var(--danger);
    }

    [data-theme="light"] .tkb-spt-banner--warning {
        background: linear-gradient(95deg, rgba(217, 119, 6, 0.11) 0%, #FFFFFF 46%, #FFFFFF 100%);
    }

    [data-theme="light"] .tkb-spt-banner--danger {
        background: linear-gradient(95deg, rgba(225, 29, 72, 0.10) 0%, #FFFFFF 46%, #FFFFFF 100%);
    }

    .tkb-spt-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
    }

    .tkb-spt-banner--warning .tkb-spt-icon {
        background: var(--warning-subtle);
        border: 1px solid var(--warning-border);
        color: var(--warning-text);
    }

    .tkb-spt-banner--danger .tkb-spt-icon {
        background: var(--danger-subtle);
        border: 1px solid var(--danger-border);
        color: var(--danger-text);
    }

    .tkb-spt-badge-level {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 4px 9px;
        border-radius: 9999px;
        line-height: 1.2;
        white-space: nowrap;
    }

    .tkb-spt-banner--warning .tkb-spt-badge-level {
        background: var(--warning);
        color: #FFFFFF;
    }

    [data-theme="dark"] .tkb-spt-banner--warning .tkb-spt-badge-level {
        color: #09120E;
    }

    .tkb-spt-banner--danger .tkb-spt-badge-level {
        background: var(--danger);
        color: #FFFFFF;
    }

    .tkb-spt-badge-role {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 9999px;
        background: var(--accent-cyan-subtle);
        color: var(--accent-cyan);
        border: 1px solid var(--accent-cyan-border);
        line-height: 1.25;
        white-space: nowrap;
    }

    .tkb-spt-headline {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.35;
    }

    .tkb-spt-message {
        margin: 0;
        font-size: 12.5px;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .tkb-spt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border-radius: 9px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.3;
        text-decoration: none !important;
        white-space: nowrap;
        cursor: pointer;
    }

    .tkb-spt-btn--wa {
        background: var(--brand);
        color: #FFFFFF !important;
        border: 1px solid var(--brand);
    }

    .tkb-spt-btn--wa:hover {
        background: var(--brand-hover);
        border-color: var(--brand-hover);
        color: #FFFFFF !important;
    }

    .tkb-spt-btn--check {
        background: var(--accent-cyan-subtle);
        color: var(--accent-cyan) !important;
        border: 1px solid var(--accent-cyan-border);
    }

    .tkb-spt-btn--check:hover {
        background: var(--accent-cyan);
        border-color: var(--accent-cyan);
        color: #FFFFFF !important;
    }

    .tkb-spt-btn--upload {
        background: var(--warning);
        color: #FFFFFF !important;
        border: 1px solid var(--warning);
    }

    [data-theme="dark"] .tkb-spt-btn--upload {
        color: #09120E !important;
    }

    .tkb-spt-banner--danger .tkb-spt-btn--upload {
        background: var(--danger);
        border-color: var(--danger);
        color: #FFFFFF !important;
    }

    .tkb-spt-btn--close {
        background: var(--bg-surface-2);
        color: var(--text-secondary) !important;
        border: 1px solid var(--border-default);
        padding: 7px 12px;
    }

    .tkb-spt-btn--close:hover {
        background: var(--bg-surface-3);
        color: var(--text-primary) !important;
        border-color: var(--border-strong);
    }

    @media (max-width: 767.98px) {
        .tkb-spt-wrap {
            padding: 12px 12px 0 12px;
        }
        .tkb-spt-banner {
            padding: 12px 14px;
        }
        .tkb-spt-actions {
            width: 100%;
        }
        .tkb-spt-actions .tkb-spt-btn {
            flex: 1 1 auto;
        }
    }
</style>

<div id="sptGlobalReminderBanner"
     class="tkb-spt-wrap"
     data-dismiss-key="{{ $dismissKey }}"
     data-wa-url="{{ $waPrimaryUrl }}"
     data-auto-wa-key="{{ $autoWaKey }}">
    <div class="tkb-spt-banner {{ $levelClass }}">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="d-flex align-items-start" style="gap: 14px; min-width: 0;">
                <div class="tkb-spt-icon">
                    <i class="{{ $isDanger ? 'ri-alarm-warning-fill' : 'ri-notification-3-fill' }}"></i>
                </div>
                <div style="min-width: 0;">
                    <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 8px;">
                        <span class="tkb-spt-badge-level">
                            {{ $r['countdown_label'] ?? 'REMINDER' }}
                        </span>
                        @if($isPemilik)
                            <span class="tkb-spt-badge-role">
                                Monitoring Admin
                            </span>
                        @endif
                        <span class="tkb-spt-headline">{{ $r['headline'] ?? '' }}</span>
                    </div>
                    <p class="tkb-spt-message">
                        {{ $r['message'] ?? '' }}
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center flex-shrink-0 ms-lg-auto tkb-spt-actions" style="gap: 8px;">
                @if(!empty($waPrimaryUrl))
                    <a href="{{ $waPrimaryUrl }}" target="_blank" rel="noopener noreferrer"
                       onclick="markSptAutoWaSent()"
                       class="tkb-spt-btn tkb-spt-btn--wa">
                        <i class="ri-whatsapp-line" style="font-size: 15px;"></i>
                        <span>Kirim WA (+62 811-5239-999)</span>
                    </a>
                @endif

                @if($isPemilik)
                    <a href="{{ route('spt-masa.index') }}"
                       class="tkb-spt-btn tkb-spt-btn--check">
                        <i class="ri-file-list-3-line" style="font-size: 14px;"></i>
                        <span>Cek SPT Masa</span>
                    </a>
                @else
                    <a href="{{ $r['action_url'] ?? route('spt-masa.index') }}"
                       class="tkb-spt-btn tkb-spt-btn--upload">
                        <i class="ri-upload-cloud-2-line" style="font-size: 15px;"></i>
                        <span>Upload PDF CoreTax Sekarang</span>
                    </a>
                @endif

                <button type="button"
                        onclick="dismissSptGlobalReminder()"
                        class="tkb-spt-btn tkb-spt-btn--close"
                        title="Sembunyikan banner hari ini (tetap tersimpan di panel Notifikasi)">
                    <i class="ri-close-line" style="font-size: 14px;"></i>
                    <span>Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function markSptAutoWaSent() {
        const banner = document.getElementById('sptGlobalReminderBanner');
        if (!banner) return;
        const autoWaKey = banner.getAttribute('data-auto-wa-key');
        if (!autoWaKey) return;
        try {
            localStorage.setItem(autoWaKey, '1');
        } catch (e) {}
    }

    (function () {
        const banner = document.getElementById('sptGlobalReminderBanner');
        if (!banner) return;
        const key = banner.getAttribute('data-dismiss-key');
        const waUrl = banner.getAttribute('data-wa-url');
        const autoWaKey = banner.getAttribute('data-auto-wa-key');
        const isPreview = window.location.search.indexOf('preview_spt_') !== -1;

        try {
            if (isPreview || !localStorage.getItem(key)) {
                banner.style.display = 'block';
            }
        } catch (e) {
            banner.style.display = 'block';
        }

        if (waUrl && autoWaKey) {
            try {
                const autoEnabled = localStorage.getItem('spt_auto_wa_enabled') !== '0';
                const alreadySentToday = !!localStorage.getItem(autoWaKey);
                if (autoEnabled && !alreadySentToday) {
                    const w = window.open(waUrl, '_blank', 'noopener,noreferrer');
                    if (w) {
                        localStorage.setItem(autoWaKey, '1');
                    }
                }
            } catch (e) {}
        }
    })();

    function dismissSptGlobalReminder() {
        const banner = document.getElementById('sptGlobalReminderBanner');
        if (!banner) return;
        const key = banner.getAttribute('data-dismiss-key');
        try {
            localStorage.setItem(key, '1');
        } catch (e) {}
        banner.style.display = 'none';
    }
</script>
@endif
