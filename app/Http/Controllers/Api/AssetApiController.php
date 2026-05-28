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
        $assets = Asset::with(['category', 'location'])
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
        $asset->load(['category', 'location', 'activeBorrowing']);

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
        $asset = Asset::with(['category', 'location', 'activeBorrowing'])
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
            'data' => $asset,
        ]);
    }
}
