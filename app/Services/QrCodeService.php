<?php

namespace App\Services;

use App\Models\Asset;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Generate QR code for an asset.
     */
    public function generate(Asset $asset): string
    {
        $url = url("/scan/{$asset->kode_asset}");
        $filename = "qrcodes/{$asset->kode_asset}.png";
        $path = storage_path("app/public/{$filename}");

        // Ensure directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Generate QR code
        QrCode::format('png')
            ->size(config('app.qr_code_size', 300))
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url, $path);

        return $filename;
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
     * Get the public URL for a QR code.
     */
    public function getUrl(Asset $asset): ?string
    {
        if (!$asset->qr_code) return null;
        return Storage::disk('public')->url($asset->qr_code);
    }
}
