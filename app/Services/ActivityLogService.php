<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    /**
     * Log an activity.
     */
    public function log(string $action, string $module, ?string $description = null, ?array $properties = null): ActivityLog
    {
        return ActivityLog::create([
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'properties' => $properties,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get recent activities.
     */
    public function getRecent(int $limit = 10)
    {
        return ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
