<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Scan - {{ $exportDate }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: #fff; }
            .print-container { box-shadow: none; border: none; border-radius: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen pb-24 sm:pb-6">
    <!-- Top Action Bar -->
    <div class="no-print sticky top-0 z-50 bg-white border-b shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
            <button onclick="history.back()" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </button>
            <!-- Desktop: Print + Download -->
            <div class="hidden sm:flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    Print
                </button>
                <form method="POST" action="{{ route('admin.assets.scanner.export-pdf') }}" class="inline">
                    @csrf
                    <input type="hidden" name="scan_data" value="{{ json_encode($scanData) }}">
                    <input type="hidden" name="action" value="download">
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </button>
                </form>
            </div>
            <!-- Mobile: Only Download (top bar) -->
            <form method="POST" action="{{ route('admin.assets.scanner.export-pdf') }}" class="sm:hidden">
                @csrf
                <input type="hidden" name="scan_data" value="{{ json_encode($scanData) }}">
                <input type="hidden" name="action" value="download">
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download
                </button>
            </form>
        </div>
    </div>

    <!-- Print Content -->
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="print-container bg-white rounded-2xl shadow-sm border overflow-hidden">
            <!-- Header -->
            <div class="bg-blue-600 px-6 py-5 text-center">
                <h1 class="text-lg sm:text-xl font-bold text-white">{{ config('app.name') }}</h1>
                <p class="text-blue-200 text-xs sm:text-sm mt-1">Laporan Riwayat Scan QR Code</p>
            </div>

            <!-- Meta Info -->
            <div class="px-4 sm:px-6 py-4 bg-blue-50 border-b">
                <div class="grid grid-cols-2 gap-3 text-xs sm:text-sm">
                    <div>
                        <span class="text-gray-500">Tanggal:</span>
                        <span class="font-semibold text-gray-900 ml-1">{{ $exportDate }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Total Scan:</span>
                        <span class="font-semibold text-gray-900 ml-1">{{ $totalScanned }} asset</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Oleh:</span>
                        <span class="font-semibold text-gray-900 ml-1">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Metode:</span>
                        <span class="font-semibold text-gray-900 ml-1">QR Scanner</span>
                    </div>
                </div>
            </div>

            <!-- Table (Desktop) -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">No</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">Kode Asset</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">Nama Asset</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">Kategori</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">Kondisi</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">Lokasi</th>
                            <th class="text-left py-3 px-4 font-bold text-gray-600 text-xs uppercase">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scanData as $index => $item)
                        <tr class="border-b hover:bg-gray-50 {{ $index % 2 == 0 ? '' : 'bg-gray-50/50' }}">
                            <td class="py-3 px-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-blue-600">{{ $item['code'] ?? '-' }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-900">{{ $item['name'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $item['category'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $item['kondisi'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $item['lokasi'] ?? '-' }}</td>
                            <td class="py-3 px-4 text-gray-500 text-xs">{{ $item['time'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cards (Mobile) -->
            <div class="sm:hidden divide-y">
                @foreach($scanData as $index => $item)
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-blue-600">{{ $item['code'] ?? '-' }}</span>
                        <span class="text-[10px] text-gray-400">{{ $item['time'] ?? '-' }}</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $item['name'] ?? '-' }}</p>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="text-xs text-gray-500">{{ $item['category'] ?? '-' }}</span>
                        <span class="text-xs text-gray-500">{{ $item['kondisi'] ?? '-' }}</span>
                        <span class="text-xs text-gray-500">{{ $item['lokasi'] ?? '-' }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t text-center">
                <p class="text-xs text-gray-400">Digenerate oleh {{ config('app.name') }} pada {{ $exportDate }}</p>
            </div>
        </div>
    </div>

    <!-- Bottom Fixed Bar (Mobile Only) - Download Only -->
    <div class="no-print sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg px-4 py-3 safe-bottom">
        <form method="POST" action="{{ route('admin.assets.scanner.export-pdf') }}">
            @csrf
            <input type="hidden" name="scan_data" value="{{ json_encode($scanData) }}">
            <input type="hidden" name="action" value="download">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 py-4 bg-green-600 hover:bg-green-700 text-white text-base font-bold rounded-xl transition-all shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </button>
        </form>
    </div>
</body>
</html>
