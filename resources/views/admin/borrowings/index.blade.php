@extends('layouts.admin')

@section('title', 'Borrowings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Borrowings</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage borrowing requests and returns.</p>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search borrower or asset..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
            <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        @if($borrowings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Borrower</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Asset</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $borrowing->borrower_email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-mono text-sm text-primary-600 dark:text-primary-400">{{ $borrowing->asset?->kode_asset }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $borrowing->asset?->nama_asset }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                            <p class="text-xs">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                            <p class="text-xs">to {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'Belum ditentukan' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">View</a>
                                @if($borrowing->status === 'pending')
                                <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded hover:bg-green-200">Approve</button>
                                </form>
                                <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded hover:bg-red-200">Reject</button>
                                </form>
                                @endif
                                @if(in_array($borrowing->status, ['approved', 'borrowed', 'overdue']))
                                <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded hover:bg-blue-200">Return</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t dark:border-gray-700">{{ $borrowings->links() }}</div>
        @else
        <div class="text-center py-12">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            <p class="text-gray-500 dark:text-gray-400">No borrowing requests found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
