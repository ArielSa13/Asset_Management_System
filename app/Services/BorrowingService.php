<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Borrowing;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    public function __construct(
        private ActivityLogService $activityLog,
        private NotificationService $notificationService,
    ) {}

    /**
     * Create a new borrowing request (from public QR scan page).
     */
    public function createRequest(array $data): Borrowing
    {
        return DB::transaction(function () use ($data) {
            $borrowing = Borrowing::create([
                'asset_id' => $data['asset_id'],
                'borrower_name' => $data['borrower_name'],
                'borrower_email' => $data['borrower_email'],
                'borrower_phone' => $data['borrower_phone'],
                'purpose' => $data['purpose'],
                'borrow_date' => $data['borrow_date'],
                'return_date' => $data['return_date'],
                'status' => 'pending',
            ]);

            $this->notificationService->notifyNewBorrowingRequest($borrowing);

            return $borrowing;
        });
    }

    /**
     * Approve a borrowing request.
     */
    public function approve(Borrowing $borrowing, ?string $notes = null): Borrowing
    {
        return DB::transaction(function () use ($borrowing, $notes) {
            $borrowing->update([
                'status' => 'approved',
                'admin_notes' => $notes,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Update asset status
            $borrowing->asset->update(['status' => 'borrowed']);

            // Record asset history
            AssetHistory::create([
                'asset_id' => $borrowing->asset_id,
                'action' => 'borrowed',
                'description' => "Borrowed by {$borrowing->borrower_name}",
                'new_values' => [
                    'borrower' => $borrowing->borrower_name,
                    'borrow_date' => $borrowing->borrow_date->format('Y-m-d'),
                    'return_date' => $borrowing->return_date->format('Y-m-d'),
                ],
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
            ]);

            $this->activityLog->log('approve', 'borrowing', "Approved borrowing #{$borrowing->id} for {$borrowing->borrower_name}");
            $this->notificationService->notifyBorrowingApproved($borrowing);

            return $borrowing;
        });
    }

    /**
     * Reject a borrowing request.
     */
    public function reject(Borrowing $borrowing, ?string $notes = null): Borrowing
    {
        $borrowing->update([
            'status' => 'rejected',
            'admin_notes' => $notes,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $this->activityLog->log('reject', 'borrowing', "Rejected borrowing #{$borrowing->id} for {$borrowing->borrower_name}");

        return $borrowing;
    }

    /**
     * Mark a borrowing as returned.
     */
    public function markReturned(Borrowing $borrowing): Borrowing
    {
        return DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status' => 'returned',
                'actual_return_date' => now()->toDateString(),
                'returned_at' => now(),
            ]);

            // Update asset status back to available
            $borrowing->asset->update(['status' => 'available']);

            // Record asset history
            AssetHistory::create([
                'asset_id' => $borrowing->asset_id,
                'action' => 'returned',
                'description' => "Returned by {$borrowing->borrower_name}",
                'new_values' => ['returned_at' => now()->toDateTimeString()],
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
            ]);

            $this->activityLog->log('return', 'borrowing', "Marked borrowing #{$borrowing->id} as returned");

            return $borrowing;
        });
    }

    /**
     * Check and mark overdue borrowings.
     */
    public function checkOverdue(): int
    {
        $overdueBorrowings = Borrowing::whereIn('status', ['approved', 'borrowed'])
            ->where('return_date', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdueBorrowings as $borrowing) {
            $borrowing->update(['status' => 'overdue']);
            $this->notificationService->notifyOverdue($borrowing);
            $count++;
        }

        return $count;
    }
}
