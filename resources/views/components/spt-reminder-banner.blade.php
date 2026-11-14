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
    $accentHex = $isDanger ? '#F43F5E' : '#F59E0B';
    $accentText = $isDanger ? '#FDA4AF' : '#FCD34D';
    $accentBg = $isDanger ? 'rgba(244, 63, 94, 0.12)' : 'rgba(245, 158, 11, 0.12)';
    $accentBorder = $isDanger ? 'rgba(244, 63, 94, 0.35)' : 'rgba(245, 158, 11, 0.35)';
    $waPrimaryUrl = $r['wa_primary_url'] ?? '';
    $dismissKey = $r['dismiss_key'] ?? 'spt_reminder_dismissed';
    $autoWaKey = $r['auto_wa_key'] ?? 'spt_auto_wa_sent';
@endphp

<div id="sptGlobalReminderBanner"
     data-dismiss-key="{{ $dismissKey }}"
     data-wa-url="{{ $waPrimaryUrl }}"
     data-auto-wa-key="{{ $autoWaKey }}"
     style="display: none; padding: 16px 24px 0 24px;">
    <div style="background: linear-gradient(95deg, {{ $accentBg }} 0%, #141926 55%, #161B22 100%);
                border: 1px solid {{ $accentBorder }};
                border-left: 4px solid {{ $accentHex }};
                border-radius: 12px;
                padding: 14px 18px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="d-flex align-items-start" style="gap: 14px;">
                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width: 42px; height: 42px; border-radius: 10px; background: {{ $accentBg }}; border: 1px solid {{ $accentBorder }}; color: {{ $accentText }};">
                    <i class="{{ $isDanger ? 'ri-alarm-warning-fill' : 'ri-notification-3-fill' }}" style="font-size: 20px;"></i>
                </div>
                <div>
                    <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 8px;">
                        <span class="badge fw-bold text-uppercase"
                              style="background: {{ $accentHex }}; color: #0B0F19; font-size: 10px; letter-spacing: 0.4px; padding: 4px 8px; border-radius: 6px;">
                            {{ $r['countdown_label'] ?? 'REMINDER' }}
                        </span>
                        @if($isPemilik)
                            <span class="badge"
                                  style="background: rgba(99, 102, 241, 0.16); color: #A5B4FC; border: 1px solid rgba(99, 102, 241, 0.35); font-size: 10px; padding: 4px 8px; border-radius: 6px;">
                                Monitoring Admin
                            </span>
                        @endif
                        <span class="fw-bold text-white" style="font-size: 13.5px; line-height: 1.35;">{{ $r['headline'] ?? '' }}</span>
                    </div>
                    <p class="mb-0" style="color: #CBD5E1; font-size: 12.5px; line-height: 1.5;">
                        {{ $r['message'] ?? '' }}
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center flex-shrink-0 ms-lg-auto" style="gap: 8px;">
                @if(!empty($waPrimaryUrl))
                    <a href="{{ $waPrimaryUrl }}" target="_blank" rel="noopener noreferrer"
                       onclick="markSptAutoWaSent()"
                       class="btn btn-sm fw-semibold"
                       style="display: inline-flex; align-items: center; gap: 6px; background: #10B981; color: #052E16; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px;">
                        <i class="ri-whatsapp-line" style="font-size: 15px;"></i>
                        <span>Kirim WA (+62 811-5239-999)</span>
                    </a>
                @endif

                @if($isPemilik)
                    <a href="{{ route('spt-masa.index') }}"
                       class="btn btn-sm fw-semibold"
                       style="display: inline-flex; align-items: center; gap: 6px; background: rgba(99, 102, 241, 0.18); color: #C7D2FE; border: 1px solid rgba(99, 102, 241, 0.35); border-radius: 8px; padding: 7px 14px; font-size: 12px;">
                        <i class="ri-file-list-3-line" style="font-size: 14px;"></i>
                        <span>Cek SPT Masa</span>
                    </a>
                @else
                    <a href="{{ $r['action_url'] ?? route('spt-masa.index') }}"
                       class="btn btn-sm fw-semibold"
                       style="display: inline-flex; align-items: center; gap: 6px; background: {{ $accentHex }}; color: #0B0F19; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px;">
                        <i class="ri-upload-cloud-2-line" style="font-size: 15px;"></i>
                        <span>Upload PDF CoreTax Sekarang</span>
                    </a>
                @endif

                <button type="button"
                        onclick="dismissSptGlobalReminder()"
                        class="btn btn-sm"
                        title="Sembunyikan banner hari ini (tetap tersimpan di panel Notifikasi)"
                        style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255,255,255,0.03); color: #94A3B8; border: 1px solid #2D3548; border-radius: 8px; padding: 7px 12px; font-size: 12px;">
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
