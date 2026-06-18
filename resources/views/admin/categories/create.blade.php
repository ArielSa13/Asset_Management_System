@extends('layouts.admin')

@section('title', 'Add Category')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.categories.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600 dark:hover:text-primary-400">Categories</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Add New</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Category</h2>
        <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Create a new category for organizing assets. Prefix will be used in asset code generation.</p>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Category Information -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b dark:border-gray-700">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Category Information</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Basic details about the category</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" 
                           placeholder="e.g. Komputer, Furniture, Electronics">
                    @error('name') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Prefix Code * <span class="text-gray-500 text-sm font-normal">(max 10 chars, letters only)</span></label>
                    <input type="text" name="prefix" value="{{ old('prefix') }}" required maxlength="10" 
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 uppercase transition-all duration-200 font-mono font-semibold" 
                           placeholder="e.g. KOM">
                    @error('prefix') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3 flex items-start">
                        <svg class="w-4 h-4 mr-1.5 mt-0.5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>This prefix will be used in asset code generation (e.g. KOM000001)</span>
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">Description</label>
                <textarea name="description" rows="4" 
                          class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-base py-3 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none" 
                          placeholder="Optional description for this category...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 dark:text-red-400 text-sm mt-2 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-4 pt-4 pb-4 border-t dark:border-gray-700">
            <a href="{{ route('admin.categories.index') }}" 
               class="inline-flex items-center px-6 py-3 text-base font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Category
            </button>
        </div>
    </form>
</div>
@endsection
