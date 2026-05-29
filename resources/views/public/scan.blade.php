<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asset->nama_asset }} - {{ $asset->kode_asset }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-xl mx-auto px-5 py-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-indigo-600 rounded-2xl mb-4 shadow-lg shadow-primary-200">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Asset Tracking</h1>
            <p class="text-sm text-gray-500 mt-1">Scan & Borrow System</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-8 p-5 bg-green-50 border-2 border-green-200 rounded-2xl">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-green-800">Berhasil!</p>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
        <div class="mb-8 p-5 bg-red-50 border-2 border-red-200 rounded-2xl">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-800">Terjadi kesalahan</p>
                    @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Asset Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden mb-8">
            <!-- Asset Photo / Banner -->
            @if($asset->foto_asset)
            <div class="w-full h-56 bg-gray-200 relative">
                <img src="{{ Storage::url($asset->foto_asset) }}" alt="{{ $asset->nama_asset }}" class="w-full h-full object-cover">
                <div class="absolute top-4 right-4">
                    <span class="px-4 py-1.5 text-sm font-bold rounded-full shadow-lg {{ $asset->status_badge }}">{{ $asset->status_label }}</span>
                </div>
            </div>
            @else
            <div class="w-full h-40 bg-gradient-to-br from-primary-100 via-blue-50 to-indigo-100 flex items-center justify-center relative">
                <svg class="w-20 h-20 text-primary-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <div class="absolute top-4 right-4">
                    <span class="px-4 py-1.5 text-sm font-bold rounded-full shadow-lg {{ $asset->status_badge }}">{{ $asset->status_label }}</span>
                </div>
            </div>
            @endif

            <!-- Asset Info -->
            <div class="p-7">
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-gray-900 leading-tight">{{ $asset->nama_asset }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center px-3 py-1 bg-primary-50 text-primary-700 rounded-lg text-sm font-mono font-bold">
                            {{ $asset->kode_asset }}
                        </span>
                        @if($asset->category)
                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium">
                            {{ $asset->category->name }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Asset Details Grid -->
                <div class="space-y-0 divide-y divide-gray-100">
                    @if($asset->merk || $asset->model)
                    <div class="flex items-center justify-between py-3.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span class="text-sm text-gray-500">Brand / Model</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $asset->merk }} {{ $asset->model }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between py-3.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-gray-500">Condition</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $asset->kondisi_label }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm text-gray-500">Location</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $asset->lokasi ?? '-' }}</span>
                    </div>
                    @if($asset->serial_number)
                    <div class="flex items-center justify-between py-3.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            <span class="text-sm text-gray-500">Serial Number</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900 font-mono">{{ $asset->serial_number }}</span>
                    </div>
                    @endif
                </div>

                <!-- Current Borrower Info -->
                @if($asset->activeBorrowing)
                <div class="mt-5 p-5 bg-amber-50 rounded-2xl border border-amber-200">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-sm font-bold text-amber-800">Sedang Dipinjam</h3>
                    </div>
                    <div class="space-y-1.5">
                        <p class="text-sm text-amber-700"><span class="font-medium">Peminjam:</span> {{ $asset->activeBorrowing->borrower_name }}</p>
                        <p class="text-sm text-amber-700"><span class="font-medium">Tanggal kembali:</span> {{ $asset->activeBorrowing->return_date ? $asset->activeBorrowing->return_date->format('d M Y') : 'Belum ditentukan' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Borrowing History -->
        @if($asset->borrowings->count() > 0)
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-7 mb-8">
            <div class="flex items-center gap-2 mb-5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-base font-bold text-gray-900">Riwayat Peminjaman</h3>
            </div>
            <div class="space-y-3">
                @foreach($asset->borrowings->take(3) as $borrowing)
                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $borrowing->borrower_name }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Borrow Request Form -->
        @if($asset->isAvailable())
        <div x-data="{ showForm: false }" class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-7">
            <button @click="showForm = !showForm" 
                    class="w-full py-4 text-lg font-bold rounded-2xl transition-all duration-300 shadow-lg"
                    :class="showForm ? 'bg-gray-100 text-gray-700 hover:bg-gray-200 shadow-none' : 'bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-700 hover:to-indigo-700 text-white shadow-primary-200'">
                <span x-show="!showForm" class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"/></svg>
                    Ajukan Peminjaman
                </span>
                <span x-show="showForm" class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </span>
            </button>

            <div x-show="showForm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-7">
                <div class="mb-6 pb-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Form Peminjaman</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi data di bawah untuk mengajukan peminjaman asset ini.</p>
                </div>

                <form action="{{ route('scan.borrow') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="borrower_name" value="{{ old('borrower_name') }}" required 
                               class="w-full rounded-xl border-gray-300 text-base py-3.5 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400" 
                               placeholder="Masukkan nama lengkap Anda">
                        @error('borrower_name') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="borrower_email" value="{{ old('borrower_email') }}" required 
                               class="w-full rounded-xl border-gray-300 text-base py-3.5 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400" 
                               placeholder="email@example.com">
                        @error('borrower_email') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="borrower_phone" value="{{ old('borrower_phone') }}" required 
                               class="w-full rounded-xl border-gray-300 text-base py-3.5 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 placeholder-gray-400" 
                               placeholder="08xxxxxxxxxx">
                        @error('borrower_phone') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Purpose -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Peminjaman <span class="text-red-500">*</span></label>
                        <textarea name="purpose" rows="3" required 
                                  class="w-full rounded-xl border-gray-300 text-base py-3.5 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none placeholder-gray-400" 
                                  placeholder="Jelaskan mengapa Anda perlu meminjam asset ini...">{{ old('purpose') }}</textarea>
                        @error('purpose') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                            <input type="date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}" 
                                   class="w-full rounded-xl border-gray-300 text-base py-3.5 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            @error('borrow_date') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kembali <span class="text-gray-400 text-xs">(opsional)</span></label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" 
                                   class="w-full rounded-xl border-gray-300 text-base py-3.5 px-4 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            @error('return_date') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-3">
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-lg font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-green-200 transform hover:scale-[1.02]">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kirim Permintaan
                            </span>
                        </button>
                        <p class="text-xs text-center text-gray-500 mt-3">Permintaan Anda akan direview oleh admin.</p>
                    </div>
                </form>
            </div>
        </div>
        @elseif($asset->isBorrowed())
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Asset Sedang Dipinjam</h3>
            <p class="text-sm text-gray-500">Asset ini sedang dalam peminjaman. Silakan cek kembali nanti.</p>
        </div>
        @else
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak Tersedia</h3>
            <p class="text-sm text-gray-500">Asset ini tidak tersedia untuk peminjaman.</p>
            <p class="text-xs text-gray-400 mt-1">Status: {{ $asset->status_label }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center mt-10">
            <p class="text-xs text-gray-400">Powered by <span class="font-semibold">Asset Management System</span></p>
        </div>
    </div>
</body>
</html>
