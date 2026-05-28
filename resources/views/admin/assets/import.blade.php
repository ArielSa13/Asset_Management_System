@extends('layouts.admin')

@section('title', 'Import Assets')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<a href="{{ route('admin.assets.index') }}" class="text-gray-500 dark:text-gray-400 text-sm hover:text-primary-600 dark:hover:text-primary-400">Assets</a>
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">Import</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Import Assets</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload Excel or CSV file to import multiple assets at once.</p>
    </div>

    <!-- Instructions Card -->
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">How to Import Assets</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800 dark:text-blue-300">
                    <li>Download the template Excel file below</li>
                    <li>Fill in the asset data according to the template format</li>
                    <li><strong>Asset Code (kode_asset):</strong> Leave empty for auto-generate, or fill with existing code for migrating old data</li>
                    <li>Upload the completed file using the form below</li>
                    <li>Wait for the import process to complete</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Download Template Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Download Template</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Excel format (.xlsx)</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Download the template file to see the required format and example data.</p>
            <a href="{{ route('admin.assets.download-template') }}" class="inline-flex items-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors w-full justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download Template
            </a>
        </div>

        <!-- Import Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">File Requirements</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Important information</p>
                </div>
            </div>
            <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Formats: .xlsx, .xls, .csv</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Maximum file size: 10MB</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Required: nama_asset, kategori</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>kode_asset:</strong> Optional - leave empty for auto-generate</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Upload File</h3>
        </div>

        <form action="{{ route('admin.import.assets') }}" method="POST" enctype="multipart/form-data" id="importForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select File *</label>
                <div class="relative">
                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" required 
                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-4 file:py-3 file:px-6
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-primary-50 file:text-primary-700
                                  hover:file:bg-primary-100
                                  dark:file:bg-primary-900/30 dark:file:text-primary-400
                                  dark:hover:file:bg-primary-900/50
                                  border border-gray-300 dark:border-gray-600 rounded-lg
                                  cursor-pointer transition-colors
                                  focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                @error('file') 
                <p class="text-red-500 dark:text-red-400 text-xs mt-2">{{ $message }}</p> 
                @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Accepted formats: .xlsx, .xls, .csv (max 10MB)</p>
            </div>

            <div id="fileInfo" class="hidden mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p id="fileName" class="font-medium text-gray-900 dark:text-white"></p>
                            <p id="fileSize" class="text-xs text-gray-500 dark:text-gray-400"></p>
                        </div>
                    </div>
                    <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t dark:border-gray-700">
                <a href="{{ route('admin.assets.index') }}" 
                   class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                        class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span id="btnText">Import Assets</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('fileInfo').classList.remove('hidden');
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
    }
});

function clearFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

document.getElementById('importForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    btn.disabled = true;
    btnText.textContent = 'Importing...';
});
</script>
@endpush
@endsection
