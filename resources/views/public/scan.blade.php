<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asset->nama_asset }} - {{ $asset->kode_asset }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-lg mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Asset Tracking</h1>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Asset Card -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-6">
            <!-- Asset Photo -->
            @if($asset->foto_asset)
            <div class="w-full h-48 bg-gray-200">
                <img src="{{ Storage::url($asset->foto_asset) }}" alt="{{ $asset->nama_asset }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="w-full h-32 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            @endif

            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $asset->nama_asset }}</h2>
                        <p class="text-sm font-mono text-blue-600 font-semibold">{{ $asset->kode_asset }}</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $asset->status_badge }}">{{ $asset->status_label }}</span>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Category</span>
                        <span class="text-sm font-medium text-gray-900">{{ $asset->category?->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Condition</span>
                        <span class="text-sm font-medium text-gray-900">{{ $asset->kondisi_label }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Location</span>
                        <span class="text-sm font-medium text-gray-900">{{ $asset->lokasi ?? '-' }}</span>
                    </div>
                    @if($asset->merk)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Brand/Model</span>
                        <span class="text-sm font-medium text-gray-900">{{ $asset->merk }} {{ $asset->model }}</span>
                    </div>
                    @endif
                </div>

                <!-- Current Borrower Info -->
                @if($asset->activeBorrowing)
                <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <h3 class="text-sm font-semibold text-yellow-800 mb-2">Currently Borrowed</h3>
                    <p class="text-sm text-yellow-700">By: {{ $asset->activeBorrowing->borrower_name }}</p>
                    <p class="text-xs text-yellow-600">Return date: {{ $asset->activeBorrowing->return_date ? $asset->activeBorrowing->return_date->format('d M Y') : 'Belum ditentukan' }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Borrowing History -->
        @if($asset->borrowings->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Borrowing History</h3>
            <div class="space-y-2">
                @foreach($asset->borrowings->take(3) as $borrowing)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm text-gray-700">{{ $borrowing->borrower_name }}</p>
                        <p class="text-xs text-gray-400">{{ $borrowing->borrow_date->format('d M Y') }}</p>
                    </div>
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $borrowing->status_badge }}">{{ $borrowing->status_label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Borrow Request Form -->
        @if($asset->isAvailable())
        <div x-data="{ showForm: false }" class="bg-white rounded-2xl shadow-sm border p-6">
            <button @click="showForm = !showForm" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                <span x-text="showForm ? 'Cancel' : 'Request Borrowing'"></span>
            </button>

            <div x-show="showForm" x-transition class="mt-6">
                <form action="{{ route('scan.borrow') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="borrower_name" value="{{ old('borrower_name') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Your full name">
                        @error('borrower_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="borrower_email" value="{{ old('borrower_email') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="your@email.com">
                        @error('borrower_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input type="text" name="borrower_phone" value="{{ old('borrower_phone') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="08xxxxxxxxxx">
                        @error('borrower_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose *</label>
                        <textarea name="purpose" rows="3" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Why do you need to borrow this asset?">{{ old('purpose') }}</textarea>
                        @error('purpose') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Borrow Date *</label>
                            <input type="date" name="borrow_date" value="{{ old('borrow_date') }}" required min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('borrow_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Return Date <span class="text-gray-400 text-xs">(optional)</span></label>
                            <input type="date" name="return_date" value="{{ old('return_date') }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('return_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors">
                        Submit Request
                    </button>
                    <p class="text-xs text-center text-gray-500">Your request will be reviewed by the admin.</p>
                </form>
            </div>
        </div>
        @elseif($asset->isBorrowed())
        <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-sm text-gray-700 font-medium">This asset is currently borrowed.</p>
            <p class="text-xs text-gray-500 mt-1">Please check back later.</p>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border p-6 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
            <p class="text-sm text-gray-700 font-medium">This asset is not available for borrowing.</p>
            <p class="text-xs text-gray-500 mt-1">Status: {{ $asset->status_label }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-400">Powered by Asset Management System</p>
        </div>
    </div>
</body>
</html>
