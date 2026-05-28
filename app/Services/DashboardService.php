<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        return [
            'total_assets' => Asset::count(),
            'available_assets' => Asset::available()->count(),
            'borrowed_assets' => Asset::borrowed()->count(),
            'maintenance_assets' => Asset::maintenance()->count(),
            'broken_assets' => Asset::broken()->count(),
            'lost_assets' => Asset::lost()->count(),
            'overdue_borrowings' => Borrowing::overdue()->count(),
            'pending_requests' => Borrowing::pending()->count(),
        ];
    }

    public function getMonthlyBorrowingChart(): array
    {
        $data = Borrowing::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $found = $data->first(fn($item) => $item->month == $date->month && $item->year == $date->year);
            $values[] = $found ? $found->total : 0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function getRecentBorrowings(int $limit = 5)
    {
        return Borrowing::with(['asset', 'approvedBy'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getTopBorrowedAssets(int $limit = 5)
    {
        return Asset::withCount('borrowings')
            ->orderByDesc('borrowings_count')
            ->limit($limit)
            ->get();
    }

    public function getRecentActivities(int $limit = 10)
    {
        return ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
