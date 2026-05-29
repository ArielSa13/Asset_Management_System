<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Borrowing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetApiController extends Controller
{
    /**
     * List all assets.
     */
    public function index(Request $request): JsonResponse
    {
        $assets = Asset::with(['category'])
            ->search($request->search)
            ->filterStatus($request->status)
            ->filterCategory($request->category_id ? (int) $request->category_id : null)
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $assets,
        ]);
    }

    /**
     * Show a single asset.
     */
    public function show(Asset $asset): JsonResponse
    {
        $asset->load(['category', 'activeBorrowing']);

        return response()->json([
            'success' => true,
            'data' => $asset,
        ]);
    }

    /**
     * Scan endpoint - get asset by code.
     */
    public function scan(string $kode): JsonResponse
    {
        $asset = Asset::with(['category', 'activeBorrowing'])
            ->where('kode_asset', $kode)
            ->first();

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $asset->id,
                'kode_asset' => $asset->kode_asset,
                'nama_asset' => $asset->nama_asset,
                'merk' => $asset->merk ?? '-',
                'model' => $asset->model ?? '-',
                'serial_number' => $asset->serial_number ?? '-',
                'kondisi' => $asset->kondisi,
                'kondisi_label' => $asset->kondisi_label,
                'status' => $asset->status,
                'status_label' => $asset->status_label,
                'status_badge' => $asset->status_badge,
                'lokasi' => $asset->lokasi ?? '-',
                'category' => $asset->category?->name ?? '-',
                'borrower' => $asset->activeBorrowing?->borrower_name,
            ],
        ]);
    }
}
