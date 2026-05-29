@extends('layouts.admin')

@section('title', 'QR Scanner')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">QR Scanner</span>
@endsection

@section('content')
<div class="w-full max-w-2xl mx-auto" x-data="qrScanner()">
    <!-- Header -->
    <div class="mb-4 px-1">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">QR Scanner</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Arahkan kamera ke QR Code asset.</p>
    </div>

    <!-- Camera Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden mb-4">
        <!-- Camera Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full animate-pulse" :class="scanning ? 'bg-green-500' : 'bg-gray-300'"></div>
                <span class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300" x-text="scanning ? 'Scanning...' : 'Camera Off'"></span>
            </div>
            <button @click="toggleCamera()" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200"
                    :class="scanning ? 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400' : 'bg-primary-100 text-primary-700 hover:bg-primary-200 dark:bg-primary-900/30 dark:text-primary-400'">
                <svg x-show="!scanning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <svg x-show="scanning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
                <span x-text="scanning ? 'Stop' : 'Start'"></span>
            </button>
        </div>

        <!-- Camera View -->
        <div class="relative bg-black aspect-[4/3] sm:aspect-video">
            <div id="qr-reader" class="w-full h-full"></div>
            
            <div x-show="!scanning" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-gray-800 flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-gray-400 text-xs sm:text-sm font-medium">Tap "Start" untuk mulai scan</p>
            </div>
        </div>
    </div>

    <!-- Scan Result Section -->
    <div x-show="lastScanned" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
        
        <!-- Loading State -->
        <div x-show="loading" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 p-6 text-center mb-4">
            <div class="animate-spin w-6 h-6 border-3 border-primary-200 border-t-primary-600 rounded-full mx-auto"></div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Memuat...</p>
        </div>

        <!-- Asset Detail Card -->
        <div x-show="!loading && assetData" class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden mb-4">
            <!-- Result Header -->
            <div class="flex items-center justify-between px-4 py-2.5 border-b dark:border-gray-700 bg-green-50 dark:bg-green-900/20">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-green-800 dark:text-green-400">Terdeteksi!</span>
                </div>
                <span class="text-xs text-green-600 dark:text-green-500" x-text="scanTime"></span>
            </div>

            <!-- Asset Info -->
            <div class="p-4 sm:p-5">
                <!-- Name & Badges -->
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-tight" x-text="assetData?.nama_asset"></h3>
                <div class="flex flex-wrap items-center gap-1.5 mt-2">
                    <span class="px-2 py-0.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded text-xs font-mono font-bold" x-text="assetData?.kode_asset"></span>
                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded text-xs font-medium" x-text="assetData?.category"></span>
                    <span class="px-2 py-0.5 text-xs font-bold rounded" 
                          :class="{
                              'bg-green-100 text-green-800': assetData?.status === 'available',
                              'bg-yellow-100 text-yellow-800': assetData?.status === 'borrowed',
                              'bg-blue-100 text-blue-800': assetData?.status === 'maintenance',
                              'bg-red-100 text-red-800': assetData?.status === 'broken' || assetData?.status === 'lost'
                          }" 
                          x-text="assetData?.status_label"></span>
                </div>

                <!-- Info Grid - 2 cols on mobile -->
                <div class="grid grid-cols-2 gap-3 mt-4 pt-3 border-t dark:border-gray-700">
                    <div>
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Brand</p>
                        <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mt-0.5" x-text="assetData?.merk || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Model</p>
                        <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mt-0.5" x-text="assetData?.model || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Kondisi</p>
                        <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mt-0.5" x-text="assetData?.kondisi_label || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 uppercase font-medium">Lokasi</p>
                        <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white mt-0.5" x-text="assetData?.lokasi || '-'"></p>
                    </div>
                </div>

                <!-- Borrower Info -->
                <div x-show="assetData?.borrower" class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                    <p class="text-xs font-bold text-amber-800 dark:text-amber-400">Dipinjam: <span class="font-normal" x-text="assetData?.borrower"></span></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 mt-4 pt-3 border-t dark:border-gray-700">
                    <a :href="'/admin/assets/' + assetData?.id" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Detail
                    </a>
                    <a :href="'/admin/assets/' + assetData?.id + '/edit'" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <a :href="'/admin/assets/' + assetData?.id + '/print-label'" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-semibold rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div x-show="!loading && scanError" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-2 border-red-200 dark:border-red-800 p-4 mb-4">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-red-800 dark:text-red-400">Asset tidak ditemukan</p>
                    <p class="text-[10px] text-red-600 dark:text-red-500" x-text="scanError"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scan History -->
    <div x-show="scanHistory.length > 0" class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-sm border dark:border-gray-700 p-4 sm:p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white">Riwayat</h3>
                <span class="px-1.5 py-0.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 text-[10px] font-bold rounded" x-text="scanHistory.length"></span>
            </div>
            <div class="flex items-center gap-1.5">
                <button @click="exportPDF()" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-[10px] sm:text-xs font-semibold rounded-lg transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    PDF
                </button>
                <button @click="scanHistory = []" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[10px] sm:text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 font-semibold rounded-lg transition-all">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </div>
        </div>

        <!-- Mobile-friendly card list (shown on mobile) -->
        <div class="sm:hidden space-y-2 max-h-64 overflow-y-auto">
            <template x-for="(item, index) in scanHistory" :key="index">
                <a :href="'/admin/assets/' + item.id" class="block p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-mono font-bold text-primary-600 dark:text-primary-400" x-text="item.code"></span>
                        <span class="text-[10px] text-gray-400" x-text="item.time"></span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1 truncate" x-text="item.name"></p>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[10px] text-gray-500" x-text="item.category"></span>
                        <span class="text-[10px] text-gray-500" x-text="item.kondisi"></span>
                        <span class="text-[10px] text-gray-500" x-text="item.lokasi"></span>
                    </div>
                </a>
            </template>
        </div>

        <!-- Desktop table (hidden on mobile) -->
        <div class="hidden sm:block overflow-x-auto max-h-64 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 bg-white dark:bg-gray-800">
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">No</th>
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Kode</th>
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Kategori</th>
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Kondisi</th>
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Lokasi</th>
                        <th class="text-left py-2 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in scanHistory" :key="index">
                        <tr class="border-b dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors cursor-pointer" @click="window.location='/admin/assets/' + item.id">
                            <td class="py-2 px-2 text-gray-500 text-xs" x-text="index + 1"></td>
                            <td class="py-2 px-2 font-mono font-bold text-primary-600 dark:text-primary-400 text-xs" x-text="item.code"></td>
                            <td class="py-2 px-2 font-semibold text-gray-900 dark:text-white text-xs" x-text="item.name"></td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-300 text-xs" x-text="item.category"></td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-300 text-xs" x-text="item.kondisi"></td>
                            <td class="py-2 px-2 text-gray-600 dark:text-gray-300 text-xs" x-text="item.lokasi"></td>
                            <td class="py-2 px-2 text-gray-400 text-xs" x-text="item.time"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function qrScanner() {
    return {
        scanning: false,
        scanner: null,
        lastScanned: null,
        loading: false,
        assetData: null,
        scanError: null,
        scanTime: null,
        scanHistory: [],
        cooldown: false,

        toggleCamera() {
            this.scanning ? this.stopCamera() : this.startCamera();
        },

        startCamera() {
            this.scanner = new Html5Qrcode("qr-reader");
            this.scanning = true;

            // Responsive QR box size
            const screenWidth = window.innerWidth;
            const qrboxSize = screenWidth < 640 ? 180 : 250;

            this.scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: qrboxSize, height: qrboxSize }, aspectRatio: 1.333 },
                (decodedText) => this.onScanSuccess(decodedText),
                () => {}
            ).catch((err) => {
                this.scanning = false;
                alert("Tidak bisa akses kamera. Pastikan izin kamera diberikan dan menggunakan HTTPS.");
            });
        },

        stopCamera() {
            if (this.scanner) {
                this.scanner.stop().then(() => {
                    this.scanning = false;
                    this.scanner = null;
                });
            }
        },

        onScanSuccess(decodedText) {
            if (this.cooldown || this.lastScanned === decodedText) return;

            this.cooldown = true;
            setTimeout(() => { this.cooldown = false; }, 2000);

            this.lastScanned = decodedText;
            this.scanTime = new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
            this.loading = true;
            this.assetData = null;
            this.scanError = null;

            let kodeAsset = decodedText;
            try {
                const url = new URL(decodedText);
                const parts = url.pathname.split('/');
                const scanIndex = parts.indexOf('scan');
                if (scanIndex !== -1 && parts[scanIndex + 1]) {
                    kodeAsset = parts[scanIndex + 1];
                }
            } catch (e) {}

            fetch(`/api/scan/${kodeAsset}`, { headers: { 'Accept': 'application/json' } })
            .then(res => {
                if (!res.ok) throw new Error('Not found');
                return res.json();
            })
            .then(data => {
                this.assetData = data.data || data;
                this.loading = false;
                this.scanHistory.unshift({
                    id: this.assetData.id,
                    code: this.assetData.kode_asset,
                    name: this.assetData.nama_asset,
                    category: this.assetData.category || '-',
                    kondisi: this.assetData.kondisi_label || '-',
                    lokasi: this.assetData.lokasi || '-',
                    merk: this.assetData.merk || '-',
                    model: this.assetData.model || '-',
                    status: this.assetData.status_label || '-',
                    time: this.scanTime
                });
                if (this.scanHistory.length > 50) this.scanHistory.pop();
                this.playBeep();
            })
            .catch(() => {
                this.scanError = `Kode "${kodeAsset}" tidak ditemukan.`;
                this.loading = false;
            });
        },

        playBeep() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.frequency.value = 880;
                gain.gain.value = 0.1;
                osc.start();
                setTimeout(() => osc.stop(), 100);
            } catch(e) {}
        },

        exportPDF() {
            if (this.scanHistory.length === 0) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.assets.scanner.export-pdf") }}';
            form.target = '_blank';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const dataInput = document.createElement('input');
            dataInput.type = 'hidden';
            dataInput.name = 'scan_data';
            dataInput.value = JSON.stringify(this.scanHistory);
            form.appendChild(dataInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    }
}
</script>
@endpush
@endsection
