<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR code for an asset (SVG format - no imagick needed).
     * ALWAYS uses current APP_URL so it auto-adapts to ngrok/production.
     */
    public function generate(Asset $asset): string
    {
        $url = $this->getScanUrl($asset);
        $filename = "qrcodes/{$asset->kode_asset}.svg";
        $path = storage_path("app/public/{$filename}");

        // Ensure directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Generate QR code as SVG (no imagick extension needed)
        $svg = QrCode::format('svg')
            ->size(config('app.qr_code_size', 300))
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);

        file_put_contents($path, $svg);

        return $filename;
    }

    /**
     * Generate QR code as inline SVG string (dynamic, always uses current URL).
     * This is the PREFERRED method - no file caching, always up-to-date.
     */
    public function generateInlineSvg(Asset $asset, int $size = 300): string
    {
        $url = $this->getScanUrl($asset);

        return QrCode::format('svg')
            ->size($size)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);
    }

    /**
     * Get the scan URL for an asset based on current APP_URL.
     * This ensures QR codes always match the current domain (ngrok, production, etc.)
     */
    public function getScanUrl(Asset $asset): string
    {
        return rtrim(config('app.url'), '/') . "/scan/{$asset->kode_asset}";
    }

    /**
     * Regenerate QR code for an asset.
     */
    public function regenerate(Asset $asset): string
    {
        // Delete old QR code if exists
        if ($asset->qr_code && Storage::disk('public')->exists($asset->qr_code)) {
            Storage::disk('public')->delete($asset->qr_code);
        }

        $filename = $this->generate($asset);
        $asset->update(['qr_code' => $filename]);

        return $filename;
    }

    /**
     * Regenerate ALL QR codes (useful when APP_URL changes, e.g. switching to ngrok).
     * Run: php artisan qr:regenerate-all
     */
    public function regenerateAll(): int
    {
        $assets = Asset::all();
        $count = 0;

        foreach ($assets as $asset) {
            $this->regenerate($asset);
            $count++;
        }

        return $count;
    }

    /**
     * Get the public URL for a QR code file.
     */
    public function getUrl(Asset $asset): ?string
    {
        if (!$asset->qr_code) return null;
        return Storage::disk('public')->url($asset->qr_code);
    }
}
