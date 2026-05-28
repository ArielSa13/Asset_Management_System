@extends('layouts.admin')

@section('title', 'Edit Asset')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600">Assets</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Edit {{ $asset->kode_asset }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Asset</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Asset Code: <span class="font-mono font-semibold text-primary-600">{{ $asset->kode_asset }}</span></p>
    </div>

    <form action="{{ route('admin.assets.update', $asset) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                    <select name="category_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->prefix }})</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Asset Name *</label>
                    <input type="text" name="nama_asset" value="{{ old('nama_asset', $asset->nama_asset) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('nama_asset') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Brand</label>
                    <input type="text" name="merk" value="{{ old('merk', $asset->merk) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model</label>
                    <input type="text" name="model" value="{{ old('model', $asset->model) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $asset->lokasi) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500" placeholder="e.g. Room 101, Floor 2">
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status & Condition</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Condition *</label>
                    <select name="kondisi" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="baik" {{ old('kondisi', $asset->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('kondisi', $asset->kondisi) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="rusak_ringan" {{ old('kondisi', $asset->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi', $asset->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                    <select name="status" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ old('status', $asset->status) == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="broken" {{ old('status', $asset->status) == 'broken' ? 'selected' : '' }}>Broken</option>
                        <option value="lost" {{ old('status', $asset->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea name="deskripsi" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">{{ old('deskripsi', $asset->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.assets.show', $asset) }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">Update Asset</button>
        </div>
    </form>
</div>
@endsection
