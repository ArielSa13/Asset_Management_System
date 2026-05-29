@extends('layouts.admin')

@section('title', 'Borrowings')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Borrowing Requests</h2>
            <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Manage borrowing requests and returns across all assets.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by borrower name, email, or asset..." 
                   class="flex-1 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
            <select name="status" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 min-w-[180px]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏱ Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✓ Approved</option>
                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>📦 Borrowed</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>↩ Returned</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>⚠ Overdue</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>✕ Rejected</option>
            </select>
            <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 min-w-[120px]">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden">
        @if($borrowings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-700/30">
                    <tr>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Borrower</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Asset</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Period</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-8 py-5">
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $borrowing->borrower_email }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <p class="font-mono text-base font-bold text-primary-600 dark:text-primary-400">{{ $borrowing->asset?->kode_asset }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $borrowing->asset?->nama_asset }}</p>
                        </td>
                        <td class="px-8 py-5 text-gray-700 dark:text-gray-300">
                            <p class="text-sm font-medium">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">to {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'Not set' }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1.5 text-sm font-bold rounded-lg {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="px-4 py-2 text-sm font-semibold bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 transition-all duration-150">View Details</a>
                                @if($borrowing->status === 'pending')
                                <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-all duration-150">✓ Approve</button>
                                </form>
                                <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-all duration-150">✕ Reject</button>
                                </form>
                                @endif
                                @if(in_array($borrowing->status, ['approved', 'borrowed', 'overdue']))
                                <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-all duration-150">↩ Return</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-8 py-5 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">{{ $borrowings->links() }}</div>
        @else
        <div class="text-center py-16 px-4">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-700 mb-5">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No borrowing requests found</h3>
            <p class="text-base text-gray-500 dark:text-gray-400">Borrowing requests will appear here when users request to borrow assets.</p>
        </div>
        @endif
    </div>
</div>
@endsection
