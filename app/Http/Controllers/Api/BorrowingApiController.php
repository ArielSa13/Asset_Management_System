<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BorrowingApiController extends Controller
{
    /**
     * List borrowings.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Borrowing::with(['asset']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $borrowings,
        ]);
    }

    /**
     * Show a single borrowing.
     */
    public function show(Borrowing $borrowing): JsonResponse
    {
        $borrowing->load(['asset.category', 'approvedBy']);

        return response()->json([
            'success' => true,
            'data' => $borrowing,
        ]);
    }
}
