@extends('layouts.admin')

@section('title', 'Edit Asset')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600 dark:hover:text-primary-400">Assets</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Edit {{ $asset->kode_asset }}</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Asset</h2>
        <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Asset Code: <span class="font-mono font-bold text-primary-600 dark:text-primary-400 text-lg">{{ $asset->kode_asset }}</span></p>
    </div>

    <form action="{{ route('admin.assets.update', $asset) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Basic Information</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Primary details about the asset</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Category *</label>
                    <select name="category_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->prefix }})</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Asset Name *</label>
                    <input type="text" name="nama_asset" value="{{ old('nama_asset', $asset->nama_asset) }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                    @error('nama_asset') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Brand</label>
                    <input type="text" name="merk" value="{{ old('merk', $asset->merk) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Model</label>
                    <input type="text" name="model" value="{{ old('model', $asset->model) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Location</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $asset->lokasi) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="e.g. Room 101, Floor 2">
                </div>
            </div>
        </div>

        <!-- Status & Condition -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Status & Condition</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Current state and availability</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Condition *</label>
                    <select name="kondisi" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="baik" {{ old('kondisi', $asset->kondisi) == 'baik' ? 'selected' : '' }}>✓ Baik</option>
                        <option value="cukup" {{ old('kondisi', $asset->kondisi) == 'cukup' ? 'selected' : '' }}>⚠ Cukup</option>
                        <option value="rusak_ringan" {{ old('kondisi', $asset->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>⚡ Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi', $asset->kondisi) == 'rusak_berat' ? 'selected' : '' }}>✕ Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Status *</label>
                    <select name="status" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>✓ Available</option>
                        <option value="borrowed" {{ old('status', $asset->status) == 'borrowed' ? 'selected' : '' }}>📤 Borrowed</option>
                        <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>⚙ Maintenance</option>
                        <option value="broken" {{ old('status', $asset->status) == 'broken' ? 'selected' : '' }}>✕ Broken</option>
                        <option value="lost" {{ old('status', $asset->status) == 'lost' ? 'selected' : '' }}>⚠ Lost</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Description</label>
                <textarea name="deskripsi" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none">{{ old('deskripsi', $asset->deskripsi) }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-4 pt-4 pb-4 border-t dark:border-gray-700">
            <a href="{{ route('admin.assets.show', $asset) }}" class="inline-flex items-center px-6 py-3 text-base font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Asset
            </button>
        </div>
    </form>
</div>
@endsection
