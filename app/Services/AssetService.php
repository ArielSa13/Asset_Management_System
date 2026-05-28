<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AssetService
{
    public function __construct(
        private AssetCodeGeneratorService $codeGenerator,
        private ActivityLogService $activityLog,
    ) {}

    public function create(array $data, ?UploadedFile $photo = null): Asset
    {
        $category = Category::findOrFail($data['category_id']);
        $data['kode_asset'] = $this->codeGenerator->generate($category);

        if ($photo) {
            $data['foto_asset'] = $photo->store('assets', 'public');
        }

        $asset = Asset::create($data);

        // Generate QR code path
        $asset->update(['qr_code' => "qrcodes/{$asset->kode_asset}.png"]);

        // Record history
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'created',
            'description' => "Asset {$asset->kode_asset} created",
            'new_values' => $asset->toArray(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        // Log activity
        $this->activityLog->log('create', 'asset', "Created asset: {$asset->kode_asset} - {$asset->nama_asset}");

        return $asset;
    }

    public function update(Asset $asset, array $data, ?UploadedFile $photo = null): Asset
    {
        $oldValues = $asset->toArray();

        if ($photo) {
            // Delete old photo
            if ($asset->foto_asset) {
                Storage::disk('public')->delete($asset->foto_asset);
            }
            $data['foto_asset'] = $photo->store('assets', 'public');
        }

        $asset->update($data);

        // Record history
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'updated',
            'description' => "Asset {$asset->kode_asset} updated",
            'old_values' => $oldValues,
            'new_values' => $asset->fresh()->toArray(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        $this->activityLog->log('update', 'asset', "Updated asset: {$asset->kode_asset}");

        return $asset->fresh();
    }

    public function delete(Asset $asset): bool
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'deleted',
            'description' => "Asset {$asset->kode_asset} deleted",
            'old_values' => $asset->toArray(),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        $this->activityLog->log('delete', 'asset', "Deleted asset: {$asset->kode_asset} - {$asset->nama_asset}");

        return $asset->delete();
    }

    public function updateStatus(Asset $asset, string $status): Asset
    {
        $oldStatus = $asset->status;
        $asset->update(['status' => $status]);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'status_changed',
            'description' => "Status changed from {$oldStatus} to {$status}",
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $status],
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
        ]);

        return $asset;
    }
}
