<?php

namespace App\Http\Controllers;

use App\Services\SptReminderService;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request, SptReminderService $reminderService)
    {
        $reminder = $reminderService->getReminderData(
            null,
            $reminderService->resolvePreviewDaysRemaining($request)
        );

        return view('pages.notifikasi.index', compact('reminder'));
    }
}
