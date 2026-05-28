<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $chartData = $this->dashboardService->getMonthlyBorrowingChart();
        $recentBorrowings = $this->dashboardService->getRecentBorrowings();
        $topBorrowedAssets = $this->dashboardService->getTopBorrowedAssets();
        $recentActivities = $this->dashboardService->getRecentActivities();

        return view('admin.dashboard.index', compact(
            'stats',
            'chartData',
            'recentBorrowings',
            'topBorrowedAssets',
            'recentActivities'
        ));
    }
}
