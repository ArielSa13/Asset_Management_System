<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Category;
use App\Services\AssetService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function __construct(
        private AssetService $assetService,
        private QrCodeService $qrCodeService,
    ) {}

    public function index(Request $request)
    {
        $assets = Asset::with(['category', 'location'])
            ->search($request->search)
            ->filterStatus($request->status)
            ->filterCategory($request->category_id ? (int) $request->category_id : null)
            ->filterKondisi($request->kondisi)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::active()->get();

        return view('admin.assets.index', compact('assets', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();

        return view('admin.assets.create', compact('categories'));
    }

    public function store(StoreAssetRequest $request)
    {
        $asset = $this->assetService->create(
            $request->validated(),
            $request->file('foto_asset')
        );

        // Generate QR code
        $this->qrCodeService->generate($asset);

        return redirect()->route('admin.assets.show', $asset)
            ->with('success', "Asset {$asset->kode_asset} berhasil dibuat.");
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'location', 'borrowings' => function ($q) {
            $q->orderByDesc('created_at')->limit(10);
        }, 'histories' => function ($q) {
            $q->orderByDesc('created_at')->limit(20);
        }]);

        return view('admin.assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $categories = Category::active()->get();

        return view('admin.assets.edit', compact('asset', 'categories'));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $this->assetService->update(
            $asset,
            $request->validated(),
            $request->file('foto_asset')
        );

        return redirect()->route('admin.assets.show', $asset)
            ->with('success', 'Asset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $this->assetService->delete($asset);

        return redirect()->route('admin.assets.index')
            ->with('success', 'Asset berhasil dihapus.');
    }

    public function regenerateQr(Asset $asset)
    {
        $this->qrCodeService->regenerate($asset);

        return back()->with('success', 'QR Code berhasil di-regenerate.');
    }
}
