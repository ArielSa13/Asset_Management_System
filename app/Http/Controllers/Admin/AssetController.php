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
        $assets = Asset::with(['category'])
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

    public function showImport()
    {
        return view('admin.assets.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'nama_asset',
            'kategori',
            'merk',
            'model',
            'serial_number',
            'lokasi',
            'kondisi',
            'deskripsi'
        ];

        $exampleData = [
            [
                'nama_asset' => 'Laptop Dell Latitude 5520',
                'kategori' => 'KOMPUTER',
                'merk' => 'Dell',
                'model' => 'Latitude 5520',
                'serial_number' => 'SN123456789',
                'lokasi' => 'Room 101, Floor 2',
                'kondisi' => 'baik',
                'deskripsi' => 'Laptop untuk staff IT'
            ],
            [
                'nama_asset' => 'Mouse Logitech MX Master',
                'kategori' => 'PERIPHERALS',
                'merk' => 'Logitech',
                'model' => 'MX Master 3',
                'serial_number' => 'SN987654321',
                'lokasi' => 'Room 102, Floor 2',
                'kondisi' => 'baik',
                'deskripsi' => 'Mouse wireless untuk staff'
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
        foreach (range('A', 'H') as $col) {
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
            ['- nama_asset', 'Asset name (required)'],
            ['- kategori', 'Category name or prefix (required)'],
            ['', ''],
            ['Optional Fields:', ''],
            ['- merk', 'Brand name'],
            ['- model', 'Model name'],
            ['- serial_number', 'Serial number'],
            ['- lokasi', 'Location (free text)'],
            ['- kondisi', 'Condition: baik, cukup, rusak_ringan, rusak_berat'],
            ['- deskripsi', 'Description'],
            ['', ''],
            ['Notes:', ''],
            ['1. Asset code (kode_asset) will be auto-generated'],
            ['2. Status will be set to "available" by default'],
            ['3. Make sure category exists in the system'],
            ['4. Remove example data before uploading'],
        ];

        $row = 2;
        foreach ($instructions as $instruction) {
            $notesSheet->setCellValue('A' . $row, $instruction[0]);
            if (isset($instruction[1])) {
                $notesSheet->setCellValue('B' . $row, $instruction[1]);
            }
            $row++;
        }

        $notesSheet->getColumnDimension('A')->setWidth(20);
        $notesSheet->getColumnDimension('B')->setWidth(50);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $fileName = 'assets-import-template.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}
