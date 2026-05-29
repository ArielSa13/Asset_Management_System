<?php

namespace App\Console\Commands;

use App\Services\QrCodeService;
use Illuminate\Console\Command;

class RegenerateQrCodes extends Command
{
    protected $signature = 'qr:regenerate-all';
    protected $description = 'Regenerate all QR codes with current APP_URL (use after changing domain/ngrok)';

    public function handle(QrCodeService $qrCodeService): int
    {
        $this->info('Regenerating all QR codes with URL: ' . config('app.url'));
        $this->newLine();

        $count = $qrCodeService->regenerateAll();

        $this->newLine();
        $this->info("✅ Done! {$count} QR code(s) regenerated.");
        $this->info("QR codes now point to: " . config('app.url') . "/scan/{code}");

        return Command::SUCCESS;
    }
}
