@extends('layouts.admin')

@section('title', 'Import Preview')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600 dark:hover:text-primary-400">Assets</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.import') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600 dark:hover:text-primary-400">Import</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Preview</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Import Preview</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review data sebelum import. Pastikan semua data yang akan diimport sudah benar.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($preview) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Rows</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $validCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Akan Diimport</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $skipCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Akan Di-skip</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Row</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Nama Asset</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kode Asset</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Merk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">SN</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Alasan Skip</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($preview as $item)
                    <tr class="{{ $item['status_import'] === 'skip' ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $item['row'] }}</td>
                        <td class="px-4 py-3">
                            @if($item['status_import'] === 'valid')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    OK
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    SKIP
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ Str::limit($item['nama_asset'], 30) }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-700 dark:text-gray-300">{{ $item['kode_asset'] }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                            @if($item['category_found'])
                                <span class="text-green-600 dark:text-green-400">{{ $item['category_found'] }}</span>
                            @else
                                <span class="text-red-500">{{ $item['kategori'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $item['merk'] }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($item['serial_number'], 15) }}</td>
                        <td class="px-4 py-3 text-xs text-red-600 dark:text-red-400">{{ $item['reason'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            <span class="text-green-600 dark:text-green-400 font-bold">{{ $validCount }}</span> asset akan diimport,
            <span class="text-red-600 dark:text-red-400 font-bold">{{ $skipCount }}</span> akan di-skip
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.assets.import') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Cancel & Re-upload
            </a>
            @if($validCount > 0)
            <form action="{{ route('admin.import.assets.confirm') }}" method="POST">
                @csrf
                <input type="hidden" name="temp_path" value="{{ $tempPath }}">
                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Confirm Import ({{ $validCount }} assets)
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
