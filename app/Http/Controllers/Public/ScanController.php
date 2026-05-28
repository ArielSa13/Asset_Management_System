<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Borrowing\StoreBorrowingRequest;
use App\Models\Asset;
use App\Services\BorrowingService;

class ScanController extends Controller
{
    public function __construct(
        private BorrowingService $borrowingService
    ) {}

    /**
     * Public QR scan page - no authentication required.
     */
    public function show(string $kode)
    {
        $asset = Asset::with(['category', 'activeBorrowing', 'borrowings' => function ($q) {
            $q->orderByDesc('created_at')->limit(5);
        }])->where('kode_asset', $kode)->firstOrFail();

        return view('public.scan', compact('asset'));
    }

    /**
     * Submit borrowing request from public page.
     */
    public function requestBorrow(StoreBorrowingRequest $request)
    {
        $borrowing = $this->borrowingService->createRequest($request->validated());

        return redirect()->route('scan.show', $borrowing->asset->kode_asset)
            ->with('success', 'Permintaan peminjaman berhasil dikirim. Silakan tunggu konfirmasi admin.');
    }
}
