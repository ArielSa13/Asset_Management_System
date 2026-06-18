@extends('layouts.admin')

@section('title', 'Categories')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Categories</span>
@endsection

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Categories</h2>
            <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Manage asset categories and their prefixes for code generation.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add Category
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden">
        @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-700/30">
                    <tr>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Category Name</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Prefix Code</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Total Assets</th>
                        <th class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-8 py-5 text-base font-semibold text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-100 dark:bg-primary-900/30 font-mono font-bold text-base text-primary-700 dark:text-primary-400">
                                {{ $category->prefix }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center text-base font-semibold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                {{ $category->assets_count }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1.5 text-sm font-bold rounded-lg {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400' }}">
                                {{ $category->is_active ? '✓ Active' : '✕ Inactive' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="p-2.5 text-gray-500 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-all duration-150" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category? This action cannot be undone.')">
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
        <div class="px-8 py-5 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">{{ $categories->links() }}</div>
        @else
        <div class="text-center py-16 px-4">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-700 mb-5">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No categories yet</h3>
            <p class="text-base text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first asset category.</p>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-base font-bold rounded-xl transition-all duration-200 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add Category
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
