@extends('layouts.admin')

@section('title', 'Add Asset')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600 dark:hover:text-primary-400">Assets</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Add New</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Asset</h2>
        <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Fill in the information below to add a new asset. Asset code will be generated automatically.</p>
        
        <!-- Asset Code Preview -->
        <div id="assetCodePreview" class="mt-6 hidden">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-700 rounded-2xl shadow-sm">
                <div class="flex-shrink-0 w-12 h-12 bg-blue-600 dark:bg-blue-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-1">Next Asset Code</p>
                    <p id="assetCodeValue" class="text-2xl font-bold text-blue-900 dark:text-blue-100 font-mono tracking-wider">-</p>
                </div>
                <div class="flex-shrink-0 px-4 py-2 bg-blue-600 dark:bg-blue-700 rounded-lg">
                    <p class="text-sm font-semibold text-white">Auto-generated</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

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
                    <select name="category_id" id="categorySelect" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->prefix }})</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Asset Name *</label>
                    <input type="text" name="nama_asset" value="{{ old('nama_asset') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="e.g. Laptop Dell Latitude 5520">
                    @error('nama_asset') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Brand</label>
                    <input type="text" name="merk" value="{{ old('merk') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="e.g. Dell">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Model</label>
                    <input type="text" name="model" value="{{ old('model') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="e.g. Latitude 5520">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="SN123456789">
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Location</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="e.g. Room 101, Floor 2">
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
                        <option value="baik" {{ old('kondisi', 'baik') == 'baik' ? 'selected' : '' }}>✓ Baik</option>
                        <option value="cukup" {{ old('kondisi') == 'cukup' ? 'selected' : '' }}>⚠ Cukup</option>
                        <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>⚡ Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>✕ Rusak Berat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Status *</label>
                    <select name="status" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>✓ Available</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>⚙ Maintenance</option>
                        <option value="broken" {{ old('status') == 'broken' ? 'selected' : '' }}>✕ Broken</option>
                        <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>⚠ Lost</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Description</label>
                <textarea name="deskripsi" rows="4" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none" placeholder="Additional notes about this asset...">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-4 pt-4 pb-4 border-t dark:border-gray-700">
            <a href="{{ route('admin.assets.index') }}" class="inline-flex items-center px-6 py-3 text-base font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Asset
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    const previewContainer = document.getElementById('assetCodePreview');
    const codeValueElement = document.getElementById('assetCodeValue');
    
    // Load preview on page load if category is already selected (for old() values)
    if (categorySelect.value) {
        loadAssetCodePreview(categorySelect.value);
    }
    
    // Update preview when category changes
    categorySelect.addEventListener('change', function() {
        if (this.value) {
            loadAssetCodePreview(this.value);
        } else {
            previewContainer.classList.add('hidden');
        }
    });
    
    function loadAssetCodePreview(categoryId) {
        // Show loading state
        previewContainer.classList.remove('hidden');
        codeValueElement.innerHTML = '<span class="text-blue-400 animate-pulse">Loading...</span>';
        
        // Fetch preview code from API
        fetch(`{{ url('admin/assets-preview-code') }}/${categoryId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                codeValueElement.textContent = data.code;
                
                // Add animation
                codeValueElement.classList.add('animate-pulse');
                setTimeout(() => {
                    codeValueElement.classList.remove('animate-pulse');
                }, 500);
            } else {
                codeValueElement.textContent = 'Error';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            codeValueElement.textContent = 'Error loading';
        });
    }
});
</script>
@endpush
@endsection
