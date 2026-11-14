<?php

namespace App\Providers;

use App\Services\SptReminderService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        View::composer('layout.template', function ($view) {
            static $cachedReminder = null;

            if ($cachedReminder === null) {
                $user = Auth::user();
                if ($user && in_array($user->role, ['Admin', 'Pemilik'], true)) {
                    $service = app(SptReminderService::class);
                    $previewH = method_exists($service, 'resolvePreviewDaysRemaining')
                        ? $service->resolvePreviewDaysRemaining(request())
                        : null;
                    $cachedReminder = $service->getReminderData(null, $previewH);
                } else {
                    $cachedReminder = ['should_remind' => false, 'notif_count' => 0];
                }
            }

            $view->with('sptReminder', $cachedReminder);
        });
    }
}
