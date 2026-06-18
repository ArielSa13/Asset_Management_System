<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Borrowing\StoreBorrowingRequest;
use App\Mail\NewBorrowingRequestMail;
use App\Models\Asset;
use App\Services\BorrowingService;
use Illuminate\Support\Facades\Mail;

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

        // Send email notification to admin
        try {
            $borrowing->load('asset');
            $adminEmail = config('app.admin_email', config('mail.from.address'));
            Mail::to($adminEmail)->send(new NewBorrowingRequestMail($borrowing));
        } catch (\Exception $e) {
            // Log the error but don't break the borrowing flow
            \Log::error('Failed to send borrowing notification email: ' . $e->getMessage());
        }

        return redirect()->route('scan.show', $borrowing->asset->kode_asset)
            ->with('success', 'Permintaan peminjaman berhasil dikirim. Silakan tunggu konfirmasi admin.');
    }
}
