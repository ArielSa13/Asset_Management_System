@extends('layouts.admin')

@section('title', 'Borrowing Detail')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Borrowing #{{ $borrowing->id }}</h2>
        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Borrower Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Borrower Information</h3>
            <dl class="space-y-3">
                <div><dt class="text-xs text-gray-500 uppercase">Name</dt><dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</dd></div>
                <div><dt class="text-xs text-gray-500 uppercase">Email</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->borrower_email }}</dd></div>
                <div><dt class="text-xs text-gray-500 uppercase">Phone</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->borrower_phone }}</dd></div>
                <div><dt class="text-xs text-gray-500 uppercase">Purpose</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->purpose }}</dd></div>
            </dl>
        </div>

        <!-- Asset Info -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Asset Information</h3>
            <dl class="space-y-3">
                <div><dt class="text-xs text-gray-500 uppercase">Asset Code</dt><dd class="text-sm font-mono font-semibold text-primary-600">{{ $borrowing->asset?->kode_asset }}</dd></div>
                <div><dt class="text-xs text-gray-500 uppercase">Asset Name</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->asset?->nama_asset }}</dd></div>
                <div><dt class="text-xs text-gray-500 uppercase">Category</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->asset?->category?->name }}</dd></div>
                <div><dt class="text-xs text-gray-500 uppercase">Borrow Period</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->borrow_date->format('d M Y') }} - {{ $borrowing->return_date->format('d M Y') }}</dd></div>
                @if($borrowing->actual_return_date)
                <div><dt class="text-xs text-gray-500 uppercase">Returned On</dt><dd class="text-sm text-gray-700 dark:text-gray-300">{{ $borrowing->actual_return_date->format('d M Y') }}</dd></div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Actions -->
    @if($borrowing->status === 'pending')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Admin Action</h3>
        <div class="flex flex-col sm:flex-row gap-4">
            <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="flex-1">
                @csrf
                <textarea name="admin_notes" rows="2" placeholder="Admin notes (optional)..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm mb-2"></textarea>
                <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">Approve Request</button>
            </form>
            <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST" class="flex-1">
                @csrf
                <textarea name="admin_notes" rows="2" placeholder="Rejection reason..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm mb-2"></textarea>
                <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">Reject Request</button>
            </form>
        </div>
    </div>
    @endif

    @if(in_array($borrowing->status, ['approved', 'borrowed', 'overdue']))
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
        <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">Mark as Returned</button>
        </form>
    </div>
    @endif

    <a href="{{ route('admin.borrowings.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Borrowings
    </a>
</div>
@endsection
