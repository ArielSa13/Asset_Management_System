@extends('layouts.admin')

@section('title', 'Assets')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Assets</span>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Asset Management</h2>
            <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Manage all your assets in one centralized place.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.assets.print-labels-bulk', request()->query()) }}" target="_blank" class="inline-flex items-center px-5 py-3 bg-white border-2 border-gray-300 hover:border-primary-500 hover:bg-primary-50 text-gray-700 hover:text-primary-700 text-base font-semibold rounded-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print QR Labels
            </a>
            <a href="{{ route('admin.assets.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add New Asset
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assets..." 
                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
            </div>
            <div>
                <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>✓ Available</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>📦 Borrowed</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>⚙ Maintenance</option>
                    <option value="broken" {{ request('status') == 'broken' ? 'selected' : '' }}>✕ Broken</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>⚠ Lost</option>
                </select>
            </div>
            <div>
                <select name="category_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="kondisi" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">All Conditions</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>✓ Baik</option>
                    <option value="cukup" {{ request('kondisi') == 'cukup' ? 'selected' : '' }}>⚠ Cukup</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>⚡ Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>✕ Rusak Berat</option>
                </select>
            </div>
            <div>
                <select name="lokasi" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                    <option value="{{ $location }}" {{ request('lokasi') == $location ? 'selected' : '' }}>{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('admin.assets.index') }}" class="px-4 py-3 text-gray-500 text-base hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-semibold transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden">
        @if($assets->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-700/30">
                    <tr>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Asset Code</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Asset Details</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Category</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Condition</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Location</th>
                        <th class="px-8 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($assets as $asset)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="font-mono text-base font-bold text-primary-600 dark:text-primary-400">{{ $asset->kode_asset }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                @if($asset->foto_asset)
                                <img src="{{ Storage::url($asset->foto_asset) }}" class="w-12 h-12 rounded-xl object-cover mr-4 border-2 border-gray-200 dark:border-gray-600" alt="">
                                @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                @endif
                                <div>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $asset->nama_asset }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $asset->merk }} {{ $asset->model }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $asset->category?->name }}
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-sm font-bold rounded-lg {{ $asset->status_badge }}">{{ $asset->status_label }}</span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-base font-semibold text-gray-700 dark:text-gray-300">{{ $asset->kondisi_label }}</td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            @if($asset->lokasi)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $asset->lokasi }}</span>
                            </div>
                            @else
                            <span class="text-sm text-gray-400 dark:text-gray-500 italic">-</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.assets.show', $asset) }}" class="p-2.5 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-all duration-150" title="View">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.assets.edit', $asset) }}" class="p-2.5 text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-150" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this asset? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-150" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-8 py-5 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
            {{ $assets->links() }}
        </div>
        @else
        <div class="text-center py-16 px-4">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-700 mb-5">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No assets found</h3>
            <p class="text-base text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first asset to track.</p>
            <a href="{{ route('admin.assets.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Asset
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
