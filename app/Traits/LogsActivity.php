<?php

namespace App\Traits;

use App\Services\ActivityLogService;

trait LogsActivity
{
    protected function logActivity(string $action, string $module, ?string $description = null, ?array $properties = null): void
    {
        app(ActivityLogService::class)->log($action, $module, $description, $properties);
    }
}
