<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->module) {
            $query->where('module', $request->module);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', "%{$request->search}%")
                  ->orWhere('action', 'like', "%{$request->search}%");
            });
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', compact('logs'));
    }
}
