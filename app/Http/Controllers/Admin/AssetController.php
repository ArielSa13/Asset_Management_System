<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Category;
use App\Services\AssetService;
use App\Services\AssetCodeGeneratorService;
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
        $assets = Asset::with(['category'])
            ->search($request->search)
            ->filterStatus($request->status)
            ->filterCategory($request->category_id ? (int) $request->category_id : null)
            ->filterKondisi($request->kondisi)
            ->when($request->lokasi, fn($q, $lokasi) => $q->where('lokasi', $lokasi))
            ->orderBy('kode_asset', 'asc')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::active()->get();
        $locations = Asset::whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->distinct()
            ->pluck('lokasi')
            ->sort()
            ->values();

        return view('admin.assets.index', compact('assets', 'categories', 'locations'));
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
        $asset->load(['category', 'borrowings' => function ($q) {
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

    public function printLabel(Asset $asset)
    {
        return view('admin.assets.print-label', compact('asset'));
    }

    public function showImport()
    {
        return view('admin.assets.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'nama_asset',
            'kode_asset',
            'kategori',
            'merk',
            'model',
            'serial_number',
            'kondisi',
            'status',
            'lokasi',
            'deskripsi'
        ];

        $exampleData = [
            [
                'nama_asset' => 'Laptop Dell Latitude 5520',
                'kode_asset' => '',  // Leave empty for auto-generate
                'kategori' => '',  // Leave empty for auto-detect (will detect as KOMPUTER/LAP)
                'merk' => 'Dell',
                'model' => 'Latitude 5520',
                'serial_number' => 'SN123456789',
                'kondisi' => 'baik',
                'status' => 'available',
                'lokasi' => 'Room 101, Floor 2',
                'deskripsi' => 'Laptop untuk staff IT'
            ],
            [
                'nama_asset' => 'Headset Logitech H390',
                'kode_asset' => '',  // Auto-generate
                'kategori' => '',  // Auto-detect (will detect as AUDIO/HEADSET)
                'merk' => 'Logitech',
                'model' => 'H390',
                'serial_number' => 'SN987654321',
                'kondisi' => 'baik',
                'status' => 'available',
                'lokasi' => 'Room 102',
                'deskripsi' => 'Headset USB untuk meeting'
            ],
            [
                'nama_asset' => 'ADAPTER NOTEBOOK',
                'kode_asset' => 'ADP0001061',  // Can provide existing code
                'kategori' => 'ADAPTER',  // Or specify exact category
                'merk' => 'HP',
                'model' => 'PPP009D',
                'serial_number' => 'WEBPE0BAR9T94L',
                'kondisi' => 'baik',
                'status' => 'available',
                'lokasi' => 'data warehouse',
                'deskripsi' => 'Adapter untuk notebook HP'
            ]
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4F46E5');
            $sheet->getStyle($col . '1')->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        // Add example data
        $row = 2;
        foreach ($exampleData as $data) {
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $row, $data[$header] ?? '');
                $col++;
            }
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add notes sheet
        $notesSheet = $spreadsheet->createSheet(1);
        $notesSheet->setTitle('Instructions');
        $notesSheet->setCellValue('A1', 'IMPORT INSTRUCTIONS');
        $notesSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        $instructions = [
            ['', ''],
            ['Required Fields:', ''],
            ['- nama_asset', 'Asset name (REQUIRED - must be filled)'],
            ['', ''],
            ['Optional Fields:', ''],
            ['- kategori', 'Category name/prefix (OPTIONAL - auto-detect if empty)'],
            ['- kode_asset', 'Asset code (OPTIONAL - auto-generate if empty)'],
            ['- merk', 'Brand name'],
            ['- model', 'Model name'],
            ['- serial_number', 'Serial number'],
            ['- kondisi', 'Condition: baik, cukup, rusak_ringan, rusak_berat'],
            ['- status', 'Status: available, maintenance, broken, lost'],
            ['- lokasi', 'Location (free text)'],
            ['- deskripsi', 'Description'],
            ['', ''],
            ['AUTO-CATEGORIZATION:', ''],
            ['System can auto-detect category from nama_asset if kategori is empty:'],
            ['- "Laptop Dell" → KOM/LAP (Komputer/Laptop)'],
            ['- "Headset Logitech" → AUD/HEADSET (Audio/Headset)'],
            ['- "Keyboard Mechanical" → PER/KEYBOARD (Peripherals)'],
            ['- "Mouse Wireless" → PER/MOUSE (Peripherals)'],
            ['- "Monitor LED" → MON (Monitor)'],
            ['- "Printer HP" → PRT (Printer)'],
            ['- "Adapter Notebook" → ADP/ADAPTER (Adapter)'],
            ['- "Router TP-Link" → NET/ROUTER (Network)'],
            ['+ and many more keywords...'],
            ['', ''],
            ['IMPORTANT NOTES:', ''],
            ['1. kategori field:', ''],
            ['   - Leave EMPTY: System will auto-detect from nama_asset'],
            ['   - Fill manually: Use specific category name or prefix'],
            ['', ''],
            ['2. kode_asset field:', ''],
            ['   - Leave EMPTY: System will auto-generate new code'],
            ['   - Fill with code: Use existing asset code (for migrating old data)'],
            ['   - If code exists: Row will be skipped to prevent duplicates'],
            ['', ''],
            ['3. Category must exist in the system before importing'],
            ['4. kondisi will default to "baik" if not specified'],
            ['5. status will default to "available" if not specified'],
            ['6. Remove example data before uploading your file'],
        ];

        $row = 2;
        foreach ($instructions as $instruction) {
            $notesSheet->setCellValue('A' . $row, $instruction[0]);
            if (isset($instruction[1])) {
                $notesSheet->setCellValue('B' . $row, $instruction[1]);
            }
            $row++;
        }

        $notesSheet->getColumnDimension('A')->setWidth(25);
        $notesSheet->getColumnDimension('B')->setWidth(60);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $fileName = 'assets-import-template.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    public function previewCode(Category $category)
    {
        $codeGenerator = app(AssetCodeGeneratorService::class);
        $nextCode = $codeGenerator->preview($category);
        
        return response()->json([
            'success' => true,
            'code' => $nextCode,
            'prefix' => $category->prefix,
            'category' => $category->name
        ]);
    }
}
