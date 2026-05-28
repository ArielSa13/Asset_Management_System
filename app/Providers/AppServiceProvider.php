<?php

namespace App\Providers;

use App\Services\ActivityLogService;
use App\Services\AssetCodeGeneratorService;
use App\Services\AssetService;
use App\Services\BorrowingService;
use App\Services\DashboardService;
use App\Services\NotificationService;
use App\Services\QrCodeService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AssetCodeGeneratorService::class);
        $this->app->singleton(ActivityLogService::class);
        $this->app->singleton(NotificationService::class);
        $this->app->singleton(DashboardService::class);
        $this->app->singleton(QrCodeService::class);
    }

    public function boot(): void
    {
        //
    }
}
