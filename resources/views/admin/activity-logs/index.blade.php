@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Activity Log</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">View all system activities and audit trail.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activities..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
            <select name="module" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                <option value="">All Modules</option>
                <option value="auth" {{ request('module') == 'auth' ? 'selected' : '' }}>Authentication</option>
                <option value="asset" {{ request('module') == 'asset' ? 'selected' : '' }}>Assets</option>
                <option value="category" {{ request('module') == 'category' ? 'selected' : '' }}>Categories</option>
                <option value="borrowing" {{ request('module') == 'borrowing' ? 'selected' : '' }}>Borrowings</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Module</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $log->user?->name ?? 'System' }}</td>
                        <td class="px-6 py-4"><span class="px-2 py-0.5 text-xs rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-mono">{{ $log->action }}</span></td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300 capitalize">{{ $log->module }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300 max-w-xs truncate">{{ $log->description }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500 font-mono">{{ $log->ip_address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $logs->links() }}</div>
        @else
        <div class="text-center py-12"><p class="text-gray-500">No activity logs found.</p></div>
        @endif
    </div>
</div>
@endsection
