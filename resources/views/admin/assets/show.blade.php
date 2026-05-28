@extends('layouts.admin')

@section('title', $asset->kode_asset)

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600">Assets</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">{{ $asset->kode_asset }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $asset->nama_asset }}</h2>
            <p class="text-sm font-mono text-primary-600 dark:text-primary-400">{{ $asset->kode_asset }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.assets.edit', $asset) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <form action="{{ route('admin.assets.regenerate-qr', $asset) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Regenerate QR
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Asset Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Asset Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->category?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</dt>
                        <dd class="mt-1"><span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $asset->status_badge }}">{{ $asset->status_label }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Condition</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->kondisi_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Brand / Model</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->merk ?? '-' }} {{ $asset->model }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Serial Number</dt>
                        <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-white">{{ $asset->serial_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->lokasi ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Purchase Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->tanggal_pembelian?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->harga ? 'Rp ' . number_format($asset->harga, 0, ',', '.') : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Supplier</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $asset->supplier ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">QR Scan URL</dt>
                        <dd class="mt-1 text-sm font-mono text-primary-600 dark:text-primary-400 break-all">{{ $asset->qr_url }}</dd>
                    </div>
                </dl>
                @if($asset->deskripsi)
                <div class="mt-4 pt-4 border-t dark:border-gray-700">
                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Description</dt>
                    <dd class="text-sm text-gray-700 dark:text-gray-300">{{ $asset->deskripsi }}</dd>
                </div>
                @endif
            </div>

            <!-- Borrowing History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Borrowing History</h3>
                @if($asset->borrowings->count() > 0)
                <div class="space-y-3">
                    @foreach($asset->borrowings as $borrowing)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrowing->borrower_name }}</span>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $borrowing->borrow_date->format('d M Y') }} - {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'Belum ditentukan' }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No borrowing history.</p>
                @endif
            </div>

            <!-- Asset History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity History</h3>
                @if($asset->histories->count() > 0)
                <div class="space-y-3">
                    @foreach($asset->histories as $history)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-primary-500"></div>
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $history->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $history->created_at->format('d M Y H:i') }} - {{ $history->user?->name ?? 'System' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No history yet.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- QR Code -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">QR Code</h3>
                @if($asset->qr_code)
                <img src="{{ Storage::url($asset->qr_code) }}" alt="QR Code" class="mx-auto w-48 h-48 border rounded-lg">
                @else
                <div class="w-48 h-48 mx-auto bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No QR Code</p>
                </div>
                @endif
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $asset->kode_asset }}</p>
            </div>

            <!-- Photo -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Photo</h3>
                @if($asset->foto_asset)
                <img src="{{ Storage::url($asset->foto_asset) }}" alt="{{ $asset->nama_asset }}" class="w-full rounded-lg object-cover">
                @else
                <div class="w-full h-32 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </div>

            <!-- Current Borrower -->
            @if($asset->activeBorrowing)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Borrower</h3>
                <div class="space-y-2">
                    <p class="text-sm"><span class="font-medium text-gray-700 dark:text-gray-300">Name:</span> {{ $asset->activeBorrowing->borrower_name }}</p>
                    <p class="text-sm"><span class="font-medium text-gray-700 dark:text-gray-300">Email:</span> {{ $asset->activeBorrowing->borrower_email }}</p>
                    <p class="text-sm"><span class="font-medium text-gray-700 dark:text-gray-300">Return:</span> {{ $asset->activeBorrowing->return_date ? $asset->activeBorrowing->return_date->format('d M Y') : 'Belum ditentukan' }}</p>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $asset->activeBorrowing->status_badge }}">{{ $asset->activeBorrowing->status_label }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
