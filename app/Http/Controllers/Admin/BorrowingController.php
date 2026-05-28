<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\BorrowingService;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct(
        private BorrowingService $borrowingService
    ) {}

    public function index(Request $request)
    {
        $query = Borrowing::with(['asset', 'approvedBy']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('borrower_name', 'like', "%{$request->search}%")
                  ->orWhere('borrower_email', 'like', "%{$request->search}%")
                  ->orWhereHas('asset', function ($aq) use ($request) {
                      $aq->where('kode_asset', 'like', "%{$request->search}%")
                         ->orWhere('nama_asset', 'like', "%{$request->search}%");
                  });
            });
        }

        $borrowings = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['asset.category', 'approvedBy']);

        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function approve(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan pending yang dapat di-approve.');
        }

        $this->borrowingService->approve($borrowing, $request->admin_notes);

        return back()->with('success', 'Permintaan peminjaman berhasil di-approve.');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya permintaan pending yang dapat di-reject.');
        }

        $this->borrowingService->reject($borrowing, $request->admin_notes);

        return back()->with('success', 'Permintaan peminjaman ditolak.');
    }

    public function markReturned(Borrowing $borrowing)
    {
        if (!in_array($borrowing->status, ['approved', 'borrowed', 'overdue'])) {
            return back()->with('error', 'Status peminjaman tidak valid untuk pengembalian.');
        }

        $this->borrowingService->markReturned($borrowing);

        return back()->with('success', 'Asset berhasil dikembalikan.');
    }
}
