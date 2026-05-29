@extends('layouts.admin')

@section('title', 'Borrowing Detail')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Borrowing Request #{{ $borrowing->id }}</h2>
                <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Complete details and management options for this borrowing request.</p>
            </div>
            <span class="px-4 py-2 text-base font-bold rounded-xl {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- Borrower Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Borrower Information</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Contact and purpose details</p>
                </div>
            </div>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Full Name</dt>
                    <dd class="text-base font-semibold text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Email Address</dt>
                    <dd class="text-base text-gray-700 dark:text-gray-300">{{ $borrowing->borrower_email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Phone Number</dt>
                    <dd class="text-base text-gray-700 dark:text-gray-300">{{ $borrowing->borrower_phone }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Borrowing Purpose</dt>
                    <dd class="text-base text-gray-700 dark:text-gray-300 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">{{ $borrowing->purpose }}</dd>
                </div>
            </dl>
        </div>

        <!-- Asset Info -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Asset Information</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Details about borrowed asset</p>
                </div>
            </div>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Asset Code</dt>
                    <dd class="text-base font-mono font-bold text-primary-600 dark:text-primary-400">{{ $borrowing->asset?->kode_asset }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Asset Name</dt>
                    <dd class="text-base font-semibold text-gray-900 dark:text-white">{{ $borrowing->asset?->nama_asset }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Category</dt>
                    <dd class="text-base text-gray-700 dark:text-gray-300">{{ $borrowing->asset?->category?->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Borrow Period</dt>
                    <dd class="text-base text-gray-700 dark:text-gray-300">
                        <div class="flex items-center gap-2 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="font-semibold">{{ $borrowing->borrow_date->format('d M Y') }} - {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'Not set' }}</span>
                        </div>
                    </dd>
                </div>
                @if($borrowing->actual_return_date)
                <div>
                    <dt class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Returned On</dt>
                    <dd class="text-base font-semibold text-green-600 dark:text-green-400">{{ $borrowing->actual_return_date->format('d M Y H:i') }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Admin Actions -->
    @if($borrowing->status === 'pending')
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8 mb-8">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
            <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Admin Action Required</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Approve or reject this borrowing request</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" class="space-y-4">
                @csrf
                <textarea name="admin_notes" rows="3" placeholder="Optional admin notes (visible to borrower)..." 
                          class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none"></textarea>
                <button type="submit" class="w-full px-6 py-4 bg-green-600 hover:bg-green-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Approve Request
                </button>
            </form>
            <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST" class="space-y-4">
                @csrf
                <textarea name="admin_notes" rows="3" placeholder="Rejection reason (required)..." 
                          class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 resize-none"></textarea>
                <button type="submit" class="w-full px-6 py-4 bg-red-600 hover:bg-red-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reject Request
                </button>
            </form>
        </div>
    </div>
    @endif

    @if(in_array($borrowing->status, ['approved', 'borrowed', 'overdue']))
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8 mb-8">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Return Asset</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Mark this asset as returned</p>
            </div>
        </div>
        <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST">
            @csrf
            <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Mark as Returned
            </button>
        </form>
    </div>
    @endif

    <div class="pt-6 border-t dark:border-gray-700">
        <a href="{{ route('admin.borrowings.index') }}" class="inline-flex items-center text-base font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Borrowings List
        </a>
    </div>
</div>
@endsection
