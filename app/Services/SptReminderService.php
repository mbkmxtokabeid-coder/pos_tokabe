<?php

namespace App\Services;

use App\Models\SptMasa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SptReminderService
{
    private const TARGET_WA_DISPLAY = '+62 811-5239-999';
    private const TARGET_WA_NUMBER  = '628115239999';

    private const BULAN_INDONESIA = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Resolve parameter query preview (preview_spt_h / preview_spt_reminder) dalam satu tempat.
     */
    public function resolvePreviewDaysRemaining(?Request $request = null): ?int
    {
        $req = $request ?? request();
        if (!$req) {
            return null;
        }

        if ($req->has('preview_spt_h')) {
            return max(0, min(5, (int) $req->query('preview_spt_h')));
        }

        if ($req->boolean('preview_spt_reminder')) {
            return 3;
        }

        return null;
    }

    /**
     * Ambil array query parameter preview aktif untuk tautan navigasi.
     */
    public function getPreviewQueryParams(?Request $request = null): array
    {
        $req = $request ?? request();
        if (!$req) {
            return [];
        }

        if ($req->has('preview_spt_h')) {
            return ['preview_spt_h' => $req->query('preview_spt_h')];
        }

        if ($req->boolean('preview_spt_reminder')) {
            return ['preview_spt_reminder' => 1];
        }

        return [];
    }

    /**
     * Hitung status reminder SPT Masa PPN (H-5 s/d Hari H akhir bulan).
     * Target masa pajak: bulan sebelumnya (sesuai jatuh tempo resmi PPN akhir bulan berikutnya).
     */
    public function getReminderData(?Carbon $now = null, ?int $previewDaysRemaining = null): array
    {
        $today = ($now ? $now->copy() : Carbon::now('Asia/Jakarta'))->startOfDay();
        $endOfMonth = $today->copy()->endOfMonth()->startOfDay();
        $daysRemaining = (int) $today->diffInDays($endOfMonth, false);

        if ($previewDaysRemaining !== null && $previewDaysRemaining >= 0 && $previewDaysRemaining <= 5) {
            $daysRemaining = $previewDaysRemaining;
        }

        $isInWindow = $daysRemaining >= 0 && $daysRemaining <= 5;

        // Target Masa Pajak = bulan sebelumnya
        $targetDate = $today->copy()->startOfMonth()->subMonth();
        $targetBulan = (int) $targetDate->month;
        $targetTahun = (int) $targetDate->year;
        $targetLabel = (self::BULAN_INDONESIA[$targetBulan] ?? (string) $targetBulan) . ' ' . $targetTahun;

        $deadlineLabel = $endOfMonth->day . ' '
            . (self::BULAN_INDONESIA[(int) $endOfMonth->month] ?? '') . ' '
            . $endOfMonth->year;

        // Cek apakah SPT Masa PPN untuk masa pajak target sudah diinput & file surat/PDF-nya sudah terupload
        $existingRecords = SptMasa::query()
            ->where('masa_pajak_bulan', $targetBulan)
            ->where('masa_pajak_tahun', $targetTahun)
            ->whereIn('jenis_pajak', ['SPT Masa PPN', 'PPN Masa'])
            ->get(['id', 'nomor_spt', 'tgl_lapor', 'bukti_bayar', 'status']);

        $hasCompletedSpt = $existingRecords->contains(function (SptMasa $spt) {
            return !empty($spt->bukti_bayar);
        });

        $hasDraftOnly = !$hasCompletedSpt && $existingRecords->isNotEmpty();

        // Cari juga SPT Masa PPN lain yang sudah tercatat namun file PDF/suratnya belum diupload
        $pendingUploadRecords = SptMasa::query()
            ->whereIn('jenis_pajak', ['SPT Masa PPN', 'PPN Masa'])
            ->where(function ($q) {
                $q->whereNull('bukti_bayar')->orWhere('bukti_bayar', '');
            })
            ->orderByDesc('masa_pajak_tahun')
            ->orderByDesc('masa_pajak_bulan')
            ->limit(5)
            ->get(['id', 'nomor_spt', 'masa_pajak_bulan', 'masa_pajak_tahun']);

        $shouldRemind = $isInWindow && !$hasCompletedSpt;

        $level = $daysRemaining <= 1 ? 'danger' : 'warning';
        $countdownLabel = $daysRemaining === 0 ? 'HARI INI (H-0)' : "H-{$daysRemaining}";

        if ($hasDraftOnly) {
            $headline = "{$countdownLabel} Batas Akhir! File PDF SPT Masa PPN {$targetLabel} belum diupload";
            $message = "Data SPT Masa PPN {$targetLabel} sudah tercatat, namun file PDF CoreTax belum diunggah. Segera upload sebelum {$deadlineLabel}.";
        } elseif ($daysRemaining === 0) {
            $headline = "DEADLINE HARI INI! SPT Masa PPN {$targetLabel} belum diinput";
            $message = "Hari ini ({$deadlineLabel}) adalah batas akhir pelaporan SPT Masa PPN {$targetLabel}. Segera lapor di CoreTax dan upload PDF-nya ke sistem.";
        } elseif ($daysRemaining === 1) {
            $headline = "BESOK DEADLINE (H-1)! SPT Masa PPN {$targetLabel} belum diinput";
            $message = "Batas akhir pelaporan SPT Masa PPN {$targetLabel} jatuh pada {$deadlineLabel}. Segera lapor di CoreTax dan upload PDF-nya ke sistem.";
        } else {
            $headline = "{$countdownLabel} Menuju Batas Akhir Pelaporan SPT Masa PPN {$targetLabel}";
            $message = "Segera lapor SPT Masa PPN {$targetLabel} di CoreTax dan upload file PDF-nya ke sistem sebelum {$deadlineLabel}.";
        }

        $primaryWaText = "⚠️ *PENGINGAT SPT MASA PPN TOKABE ({$countdownLabel})*\n\n"
            . "Halo, ini adalah pengingat otomatis bahwa *SPT Masa PPN periode {$targetLabel}* belum selesai dilaporkan / file PDF CoreTax belum diunggah ke sistem Tokabe.\n\n"
            . "📅 *Masa Pajak:* {$targetLabel}\n"
            . "⏳ *Batas Akhir:* {$deadlineLabel} ({$countdownLabel})\n\n"
            . "Mohon segera lapor SPT Masa PPN di CoreTax dan upload file surat/PDF-nya ke sistem Tokabe. Terima kasih.";

        $primaryWaUrl = ($shouldRemind || $pendingUploadRecords->isNotEmpty())
            ? $this->buildTargetWaUrl($primaryWaText)
            : null;

        // Daftar item persisten di Panel Notifikasi (tersimpan sampai surat/PDF terupload)
        $panelNotifications = [];
        if ($shouldRemind) {
            $panelNotifications[] = [
                'id'         => "reminder-{$targetTahun}-{$targetBulan}",
                'badge'      => $countdownLabel,
                'level'      => $level,
                'title'      => $headline,
                'subtitle'   => $message,
                'meta'       => "Batas akhir: {$deadlineLabel}",
                'wa_url'     => $primaryWaUrl,
                'action_url' => route('spt-masa.index', [
                    'open_modal' => 1,
                    'bulan'      => $targetBulan,
                    'tahun'      => $targetTahun,
                ]),
            ];
        }

        foreach ($pendingUploadRecords as $pending) {
            if ($shouldRemind && (int) $pending->masa_pajak_bulan === $targetBulan && (int) $pending->masa_pajak_tahun === $targetTahun) {
                continue;
            }
            $pLabel = (self::BULAN_INDONESIA[(int) $pending->masa_pajak_bulan] ?? $pending->masa_pajak_bulan) . ' ' . $pending->masa_pajak_tahun;
            $pendingWaText = "⚠️ *PENGINGAT UPLOAD PDF SPT MASA PPN TOKABE*\n\n"
                . "Halo, data *SPT Masa PPN periode {$pLabel}* ({$pending->nomor_spt}) sudah tercatat di sistem, namun file surat/PDF CoreTax belum diupload.\n\n"
                . "📄 *No. SPT:* {$pending->nomor_spt}\n"
                . "📅 *Masa Pajak:* {$pLabel}\n\n"
                . "Mohon segera upload file PDF SPT Masa tersebut ke sistem Tokabe. Terima kasih.";

            $panelNotifications[] = [
                'id'         => "pending-pdf-{$pending->id}",
                'badge'      => 'BELUM UPLOAD PDF',
                'level'      => 'warning',
                'title'      => "SPT Masa PPN {$pLabel} ({$pending->nomor_spt}) belum ada file PDF",
                'subtitle'   => 'Notifikasi ini akan tetap tersimpan hingga file surat/PDF SPT Masa diupload.',
                'meta'       => 'Status: Menunggu upload PDF',
                'wa_url'     => $this->buildTargetWaUrl($pendingWaText),
                'action_url' => route('spt-masa.index'),
            ];
        }

        return [
            'should_remind'       => $shouldRemind,
            'is_in_window'        => $isInWindow,
            'is_completed'        => $hasCompletedSpt,
            'has_draft_only'      => $hasDraftOnly,
            'days_remaining'      => $daysRemaining,
            'countdown_label'     => $countdownLabel,
            'level'               => $level,
            'target_bulan'        => $targetBulan,
            'target_tahun'        => $targetTahun,
            'target_label'        => $targetLabel,
            'deadline_date'       => $endOfMonth->toDateString(),
            'deadline_label'      => $deadlineLabel,
            'headline'            => $headline,
            'message'             => $message,
            'action_url'          => route('spt-masa.index', [
                'open_modal' => 1,
                'bulan'      => $targetBulan,
                'tahun'      => $targetTahun,
            ]),
            'dismiss_key'         => 'spt_reminder_dismissed_' . $today->toDateString() . "_{$targetTahun}_{$targetBulan}",
            'auto_wa_key'         => 'spt_auto_wa_sent_' . $today->toDateString() . "_{$targetTahun}_{$targetBulan}",
            'wa_primary_phone'    => self::TARGET_WA_DISPLAY,
            'wa_primary_url'      => $primaryWaUrl,
            'preview_query'       => $this->getPreviewQueryParams(),
            'panel_notifications' => $panelNotifications,
            'notif_count'         => count($panelNotifications),
        ];
    }

    /**
     * Bangun tautan WhatsApp wa.me menuju nomor target utama.
     */
    private function buildTargetWaUrl(string $text): string
    {
        return 'https://wa.me/' . self::TARGET_WA_NUMBER . '?text=' . rawurlencode($text);
    }
}

