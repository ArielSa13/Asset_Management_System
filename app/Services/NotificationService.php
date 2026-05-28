<?php

namespace App\Services;

use App\Models\Borrowing;
use Illuminate\Support\Facades\Log;

/**
 * Notification service architecture.
 * Prepared for future Email and WhatsApp Gateway integration.
 */
class NotificationService
{
    /**
     * Notify admin about a new borrowing request.
     */
    public function notifyNewBorrowingRequest(Borrowing $borrowing): void
    {
        // Future: Send email to admin
        // Future: Send WhatsApp notification

        Log::info("New borrowing request #{$borrowing->id} from {$borrowing->borrower_name}");
    }

    /**
     * Notify borrower that their request was approved.
     */
    public function notifyBorrowingApproved(Borrowing $borrowing): void
    {
        // Future: Send email to borrower
        // Future: Send WhatsApp notification

        Log::info("Borrowing #{$borrowing->id} approved for {$borrowing->borrower_name}");
    }

    /**
     * Notify about overdue borrowing.
     */
    public function notifyOverdue(Borrowing $borrowing): void
    {
        // Future: Send email to borrower
        // Future: Send WhatsApp notification to admin

        Log::warning("Borrowing #{$borrowing->id} is overdue. Borrower: {$borrowing->borrower_name}");
    }
}
