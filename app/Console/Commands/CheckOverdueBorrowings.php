<?php

namespace App\Console\Commands;

use App\Services\BorrowingService;
use Illuminate\Console\Command;

class CheckOverdueBorrowings extends Command
{
    protected $signature = 'borrowings:check-overdue';
    protected $description = 'Check and mark overdue borrowings';

    public function handle(BorrowingService $borrowingService): int
    {
        $count = $borrowingService->checkOverdue();

        $this->info("Marked {$count} borrowing(s) as overdue.");

        return Command::SUCCESS;
    }
}
