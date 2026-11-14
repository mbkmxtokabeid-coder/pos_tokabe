<?php

namespace App\Http\Controllers;

use App\Services\SptReminderService;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request, SptReminderService $reminderService)
    {
        $previewH = method_exists($reminderService, 'resolvePreviewDaysRemaining')
            ? $reminderService->resolvePreviewDaysRemaining($request)
            : null;

        $reminder = $reminderService->getReminderData(null, $previewH);

        return view('pages.notifikasi.index', compact('reminder'));
    }
}
