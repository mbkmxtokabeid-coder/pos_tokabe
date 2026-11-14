@extends('layout.template')

@section('head')
<style>
    .notif-card {
        background-color: #161B22 !important;
        border: 1px solid #282E37 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }
    .notif-item-card {
        background: #111622;
        border: 1px solid #232A35;
        border-radius: 12px;
        padding: 18px 20px;
        transition: border-color 0.15s ease;
    }
    .notif-item-card:hover {
        border-color: #334155;
    }
    .wa-admin-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, 0.12);
        color: #34D399;
        border: 1px solid rgba(16, 185, 129, 0.32);
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .wa-admin-pill:hover {
        background: rgba(16, 185, 129, 0.22);
        color: #6EE7B7;
        border-color: rgba(16, 185, 129, 0.55);
    }
</style>
@endsection

@section('content')
@php
    $panelItems = $reminder['panel_notifications'] ?? [];
    $notifCount = count($panelItems);
    $waPrimaryPhone = $reminder['wa_primary_phone'] ?? '+62 811-5239-999';
    $isPemilik = (Auth::user()->role ?? null) === 'Pemilik';
@endphp

<div class="page-content">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-white d-flex align-items-center gap-2">
                    <i class="ri-notification-3-fill" style="color: #818CF8;"></i>
                    <span>Pusat Notifikasi</span>
                    @if($notifCount > 0)
                        <span class="badge fw-bold" style="background: rgba(244, 63, 94, 0.18); color: #FDA4AF; border: 1px solid rgba(244, 63, 94, 0.35); font-size: 11px; padding: 4px 10px;">
                            {{ $notifCount }} Belum Selesai
                        </span>
                    @endif
                </h4>
                <p class="mb-0 text-muted fs-13">
                    Semua pengingat dan pemberitahuan sistem tersimpan di sini hingga ditindaklanjuti.
                </p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-3 px-3 py-2 rounded-3"
                 style="background: #161B22; border: 1px solid #282E37;">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-whatsapp-fill fs-16" style="color: #34D399;"></i>
                    <div class="lh-sm">
                        <div class="text-muted" style="font-size: 10.5px;">Target WhatsApp Pengingat Otomatis</div>
                        <div class="fw-bold text-white fs-12">{{ $waPrimaryPhone }}</div>
                    </div>
                </div>
                <div style="width: 1px; height: 24px; background: #282E37;"></div>
                <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="checkbox" role="switch" id="toggleAutoWaSpt" style="cursor: pointer;">
                    <label class="form-check-label text-white fs-12 fw-medium" for="toggleAutoWaSpt" style="cursor: pointer;">
                        Auto-buka WA saat jatuh tempo (1x/hari)
                    </label>
                </div>
            </div>
        </div>

        <div class="card notif-card">
            <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 px-4 py-3 d-flex align-items-center justify-content-between">
                <span class="fw-bold text-white fs-14">Daftar Notifikasi</span>
                <span class="text-muted fs-12">{{ $notifCount }} notifikasi aktif</span>
            </div>
            <div class="card-body p-4">
                @if($notifCount > 0)
                    <div class="d-flex flex-column" style="gap: 14px;">
                        @foreach($panelItems as $nItem)
                            @php
                                $isDanger = ($nItem['level'] ?? 'warning') === 'danger';
                                $accentHex = $isDanger ? '#F43F5E' : '#F59E0B';
                                $softBg = $isDanger ? 'rgba(244, 63, 94, 0.12)' : 'rgba(245, 158, 11, 0.12)';
                                $softText = $isDanger ? '#FDA4AF' : '#FCD34D';
                            @endphp
                            <div class="notif-item-card" style="border-left: 4px solid {{ $accentHex }};">
                                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
                                    <div class="d-flex align-items-start" style="gap: 14px;">
                                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                             style="width: 44px; height: 44px; border-radius: 10px; background: {{ $softBg }}; border: 1px solid {{ $accentHex }}; color: {{ $softText }};">
                                            <i class="{{ $isDanger ? 'ri-alarm-warning-fill' : 'ri-notification-3-fill' }}" style="font-size: 20px;"></i>
                                        </div>
                                        <div>
                                            <div class="d-flex flex-wrap align-items-center mb-1" style="gap: 8px;">
                                                <span class="badge fw-bold" style="background: {{ $softBg }}; color: {{ $softText }}; border: 1px solid {{ $accentHex }}; font-size: 10px; padding: 3px 8px;">
                                                    {{ $nItem['badge'] }}
                                                </span>
                                                <span class="badge" style="background: rgba(99, 102, 241, 0.14); color: #A5B4FC; border: 1px solid rgba(99, 102, 241, 0.3); font-size: 10px; padding: 3px 8px;">
                                                    SPT Masa PPN
                                                </span>
                                                <span class="text-muted fs-12">{{ $nItem['meta'] }}</span>
                                            </div>
                                            <h6 class="fw-bold text-white mb-1 fs-14">{{ $nItem['title'] }}</h6>
                                            <p class="mb-0 fs-13" style="color: #94A3B8; line-height: 1.5;">{{ $nItem['subtitle'] }}</p>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0">
                                        <a href="{{ $nItem['action_url'] }}"
                                           class="btn btn-sm fw-semibold"
                                           style="display: inline-flex; align-items: center; gap: 6px; background: {{ $isPemilik ? 'rgba(99, 102, 241, 0.18)' : $accentHex }}; color: {{ $isPemilik ? '#C7D2FE' : '#0B0F19' }}; border: {{ $isPemilik ? '1px solid rgba(99, 102, 241, 0.4)' : 'none' }}; border-radius: 8px; padding: 8px 14px;">
                                            <i class="{{ $isPemilik ? 'ri-arrow-right-up-line' : 'ri-upload-cloud-2-line' }}"></i>
                                            <span>{{ $isPemilik ? 'Buka SPT Masa' : 'Tindak Lanjuti Sekarang' }}</span>
                                        </a>
                                    </div>
                                </div>

                                @php
                                    $itemWaUrl = $nItem['wa_url'] ?? ($reminder['wa_primary_url'] ?? '#');
                                @endphp
                                <div class="mt-3 pt-3" style="border-top: 1px dashed #232A35;">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <span class="text-muted fw-semibold text-uppercase" style="font-size: 10.5px; letter-spacing: 0.4px;">
                                            Pengingat WhatsApp
                                        </span>
                                        <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer"
                                           onclick="if (typeof markSptAutoWaSent === 'function') markSptAutoWaSent();"
                                           class="wa-admin-pill">
                                            <i class="ri-whatsapp-fill fs-14"></i>
                                            <span>Kirim Pengingat WA ke {{ $waPrimaryPhone }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width: 56px; height: 56px; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34D399;">
                            <i class="ri-checkbox-circle-fill fs-26"></i>
                        </div>
                        <h6 class="fw-bold text-white mb-1">Tidak Ada Notifikasi Aktif</h6>
                        <p class="mb-0 text-muted fs-13">
                            Semua pengingat dan tugas sudah selesai ditindaklanjuti.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const toggle = document.getElementById('toggleAutoWaSpt');
        if (!toggle) return;
        try {
            toggle.checked = localStorage.getItem('spt_auto_wa_enabled') !== '0';
        } catch (e) {
            toggle.checked = true;
        }
        toggle.addEventListener('change', function () {
            try {
                localStorage.setItem('spt_auto_wa_enabled', toggle.checked ? '1' : '0');
            } catch (e) {}
        });
    })();
</script>
@endsection
