@extends('layouts.admin')

@section('title', 'QR Scanner')

@section('breadcrumb')
<span class="text-gray-400 dark:text-gray-500 mx-2">/</span>
<span class="text-gray-700 dark:text-gray-300 text-sm font-medium">QR Scanner</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="qrScanner()">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">QR Code Scanner</h2>
        <p class="text-base text-gray-600 dark:text-gray-400 mt-2">Arahkan kamera ke QR Code asset untuk melihat detail langsung.</p>
    </div>

    <!-- Camera Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden mb-6">
        <!-- Camera Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full animate-pulse" :class="scanning ? 'bg-green-500' : 'bg-gray-300'"></div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300" x-text="scanning ? 'Scanning aktif...' : 'Camera Off'"></span>
            </div>
            <button @click="toggleCamera()" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200"
                    :class="scanning ? 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400' : 'bg-primary-100 text-primary-700 hover:bg-primary-200 dark:bg-primary-900/30 dark:text-primary-400'">
                <svg x-show="!scanning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <svg x-show="scanning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><rect x="9" y="9" width="6" height="6" rx="1"/></svg>
                <span x-text="scanning ? 'Stop' : 'Start Camera'"></span>
            </button>
        </div>

        <!-- Camera View -->
        <div class="relative bg-black" style="min-height: 300px;">
            <div id="qr-reader" class="w-full"></div>
            
            <div x-show="!scanning" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900" style="min-height: 300px;">
                <div class="w-20 h-20 rounded-2xl bg-gray-800 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-gray-400 text-sm font-medium">Klik "Start Camera" untuk mulai scan</p>
                <p class="text-gray-600 text-xs mt-1">Pastikan izin kamera sudah diberikan</p>
            </div>
        </div>
    </div>

    <!-- Scan Result Section -->
    <div x-show="lastScanned" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        
        <!-- Loading State -->
        <div x-show="loading" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-8 text-center mb-6">
            <div class="animate-spin w-8 h-8 border-4 border-primary-200 border-t-primary-600 rounded-full mx-auto"></div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Memuat detail asset...</p>
        </div>

        <!-- Asset Detail Card -->
        <div x-show="!loading && assetData" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 overflow-hidden mb-6">
            <!-- Result Header -->
            <div class="flex items-center justify-between px-6 py-3.5 border-b dark:border-gray-700 bg-green-50 dark:bg-green-900/20">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm font-bold text-green-800 dark:text-green-400">QR Code Terdeteksi!</span>
                </div>
                <span class="text-xs text-green-600 dark:text-green-500" x-text="scanTime"></span>
            </div>

            <!-- Asset Info -->
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-100 to-indigo-100 dark:from-primary-900/30 dark:to-indigo-900/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate" x-text="assetData?.nama_asset"></h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-md text-sm font-mono font-bold" x-text="assetData?.kode_asset"></span>
                            <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md text-xs font-medium" x-text="assetData?.category"></span>
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-md" 
                                  :class="{
                                      'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': assetData?.status === 'available',
                                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': assetData?.status === 'borrowed',
                                      'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': assetData?.status === 'maintenance',
                                      'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': assetData?.status === 'broken' || assetData?.status === 'lost'
                                  }" 
                                  x-text="assetData?.status_label"></span>
                        </div>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 pt-5 border-t dark:border-gray-700">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Brand</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="assetData?.merk || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Model</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="assetData?.model || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Kondisi</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="assetData?.kondisi_label || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-0.5">Lokasi</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="assetData?.lokasi || '-'"></p>
                    </div>
                </div>

                <!-- Borrower Info -->
                <div x-show="assetData?.borrower" class="mt-4 p-3.5 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                    <p class="text-xs font-bold text-amber-800 dark:text-amber-400">Dipinjam oleh: <span class="font-normal text-amber-700 dark:text-amber-300" x-text="assetData?.borrower"></span></p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2 mt-5 pt-4 border-t dark:border-gray-700">
                    <a :href="'/admin/assets/' + assetData?.id" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Detail
                    </a>
                    <a :href="'/admin/assets/' + assetData?.id + '/edit'" class="inline-flex items-center gap-1.5 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <a :href="'/admin/assets/' + assetData?.id + '/print-label'" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div x-show="!loading && scanError" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border-2 border-red-200 dark:border-red-800 p-5 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-800 dark:text-red-400">Asset tidak ditemukan</p>
                    <p class="text-xs text-red-600 dark:text-red-500 mt-0.5" x-text="scanError"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scan History -->
    <div x-show="scanHistory.length > 0" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Riwayat Scan</h3>
            <button @click="scanHistory = []" class="text-xs text-red-500 hover:text-red-700 font-semibold">Hapus</button>
        </div>
        <div class="space-y-2 max-h-60 overflow-y-auto">
            <template x-for="(item, index) in scanHistory" :key="index">
                <a :href="'/admin/assets/' + item.id" class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-xs font-mono font-bold text-primary-600 dark:text-primary-400 flex-shrink-0" x-text="item.code"></span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate" x-text="item.name"></span>
                    </div>
                    <span class="text-xs text-gray-400 flex-shrink-0 ml-2" x-text="item.time"></span>
                </a>
            </template>
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

            this.scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.333 },
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

            // Extract asset code from URL
            let kodeAsset = decodedText;
            try {
                const url = new URL(decodedText);
                const parts = url.pathname.split('/');
                const scanIndex = parts.indexOf('scan');
                if (scanIndex !== -1 && parts[scanIndex + 1]) {
                    kodeAsset = parts[scanIndex + 1];
                }
            } catch (e) {}

            // Fetch asset data via API
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
                    time: this.scanTime
                });
                if (this.scanHistory.length > 10) this.scanHistory.pop();
                this.playBeep();
            })
            .catch(() => {
                this.scanError = `Kode "${kodeAsset}" tidak ditemukan di database.`;
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
        }
    }
}
</script>
@endpush
@endsection
