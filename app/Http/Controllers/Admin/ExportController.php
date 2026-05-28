<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AssetsExport;
use App\Exports\BorrowingsExport;
use App\Models\Asset;
use App\Models\Borrowing;
use App\Models\Category;
use App\Services\AssetCodeGeneratorService;
use App\Services\CategoryDetectorService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportAssets(Request $request)
    {
        $format = $request->get('format', 'xlsx');

        if ($format === 'pdf') {
            $assets = Asset::with(['category'])->orderBy('kode_asset')->get();
            $pdf = Pdf::loadView('exports.assets-pdf', compact('assets'));
            return $pdf->download('assets-report.pdf');
        }

        $extension = $format === 'csv' ? 'csv' : 'xlsx';
        return Excel::download(new AssetsExport(), "assets-report.{$extension}");
    }

    public function exportBorrowings(Request $request)
    {
        $format = $request->get('format', 'xlsx');

        if ($format === 'pdf') {
            $borrowings = Borrowing::with('asset')->get();
            $pdf = Pdf::loadView('exports.borrowings-pdf', compact('borrowings'));
            return $pdf->download('borrowings-report.pdf');
        }

        return Excel::download(new BorrowingsExport(), 'borrowings-report.xlsx');
    }

    /**
     * Preview import - show which rows will succeed/fail before importing
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $file = $request->file('file');
        $categoryDetector = app(CategoryDetectorService::class);

        // Read Excel data
        $rows = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\WithHeadingRow {}, $file)[0] ?? [];

        $preview = [];
        $validCount = 0;
        $skipCount = 0;

        foreach ($rows as $index => $row) {
            $data = $this->mapColumns($row);

            // Skip completely empty rows
            if (empty($data['nama_asset'])) {
                continue;
            }

            $status = 'valid';
            $reason = '';
            $isFallback = false;

            // Check category
            $category = null;
            if (!empty($data['kategori'])) {
                $category = Category::where('name', $data['kategori'])
                    ->orWhere('prefix', strtoupper($data['kategori']))
                    ->first();
            }
            if (!$category) {
                $category = $categoryDetector->detectCategory($data['nama_asset']);
                // Check if it fell back to "Perangkat Lainnya"
                if ($category && $category->prefix === 'OTH') {
                    $isFallback = true;
                    $reason = 'Auto → Perangkat Lainnya';
                }
            }

            // Check duplicate kode_asset
            if ($status === 'valid' && !empty($data['kode_asset'])) {
                $exists = Asset::where('kode_asset', $data['kode_asset'])->exists();
                if ($exists) {
                    $status = 'skip';
                    $reason = 'Kode asset sudah ada di database';
                    $skipCount++;
                }
            }

            if ($status === 'valid') {
                $validCount++;
            }

            $preview[] = [
                'row' => $index + 2,
                'nama_asset' => $data['nama_asset'],
                'kode_asset' => $data['kode_asset'] ?? '(auto-generate)',
                'kategori' => $data['kategori'] ?? '(auto-detect)',
                'category_found' => $category ? $category->name : null,
                'merk' => $data['merk'] ?? '-',
                'serial_number' => $data['serial_number'] ?? '-',
                'kondisi' => $data['kondisi'] ?? 'baik',
                'status_import' => $status,
                'is_fallback' => $isFallback,
                'reason' => $reason,
            ];
        }

        // Store file temporarily for confirm step
        $tempPath = $file->store('temp-imports');

        return view('admin.assets.import-preview', compact('preview', 'validCount', 'skipCount', 'tempPath'));
    }

    /**
     * Confirm and execute import after preview
     */
    public function confirmImport(Request $request)
    {
        $request->validate([
            'temp_path' => ['required', 'string'],
        ]);

        $tempPath = $request->input('temp_path');
        $fullPath = storage_path("app/{$tempPath}");

        if (!file_exists($fullPath)) {
            return redirect()->route('admin.assets.import')
                ->with('error', 'File sementara sudah expired. Silakan upload ulang.');
        }

        $categoryDetector = app(CategoryDetectorService::class);
        $codeGenerator = app(AssetCodeGeneratorService::class);
        $qrService = app(QrCodeService::class);

        // Read Excel data
        $rows = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\WithHeadingRow {}, $fullPath)[0] ?? [];

        $importedCount = 0;
        $skippedCount = 0;

        foreach ($rows as $row) {
            $data = $this->mapColumns($row);

            if (empty($data['nama_asset'])) {
                continue;
            }

            // Find category
            $category = null;
            if (!empty($data['kategori'])) {
                $category = Category::where('name', $data['kategori'])
                    ->orWhere('prefix', strtoupper($data['kategori']))
                    ->first();
            }
            if (!$category) {
                $category = $categoryDetector->detectCategory($data['nama_asset']);
            }
            if (!$category) {
                $skippedCount++;
                continue;
            }

            // Handle kode_asset
            if (!empty($data['kode_asset'])) {
                if (Asset::where('kode_asset', $data['kode_asset'])->exists()) {
                    $skippedCount++;
                    continue;
                }
                $kodeAsset = $data['kode_asset'];
            } else {
                $kodeAsset = $codeGenerator->generate($category);
            }

            // Normalize kondisi & status
            $kondisi = !empty($data['kondisi']) ? strtolower(trim($data['kondisi'])) : 'baik';
            $kondisi = $this->normalizeKondisi($kondisi);

            $assetStatus = !empty($data['status']) ? strtolower(trim($data['status'])) : 'available';
            $assetStatus = $this->normalizeStatus($assetStatus);

            // Create asset
            $asset = Asset::create([
                'kode_asset' => $kodeAsset,
                'nama_asset' => $data['nama_asset'],
                'category_id' => $category->id,
                'serial_number' => $data['serial_number'] ?? null,
                'merk' => $data['merk'] ?? null,
                'model' => $data['model'] ?? null,
                'kondisi' => $kondisi,
                'status' => $assetStatus,
                'lokasi' => $data['lokasi'] ?? null,
                'deskripsi' => $data['deskripsi'] ?? null,
            ]);

            // Generate QR Code
            $qrPath = $qrService->generate($asset);
            $asset->update(['qr_code' => $qrPath]);

            $importedCount++;
        }

        // Delete temp file
        @unlink($fullPath);

        return redirect()->route('admin.assets.index')
            ->with('success', "Import selesai! {$importedCount} asset berhasil diimport, {$skippedCount} di-skip.");
    }

    /**
     * Map flexible column names
     */
    private function mapColumns(array $row): array
    {
        return [
            'nama_asset' => $this->getColumnValue($row, ['nama_asset', 'nama', 'name', 'asset_name', 'nama_aset']),
            'kode_asset' => $this->getColumnValue($row, ['kode_asset', 'kode', 'code', 'asset_code', 'kode_aset']),
            'kategori' => $this->getColumnValue($row, ['kategori', 'category', 'kategory', 'cat', 'jenis']),
            'merk' => $this->getColumnValue($row, ['merk', 'merek', 'brand', 'merek_brand', 'merekbrand']),
            'model' => $this->getColumnValue($row, ['model', 'tipe', 'type', 'model_tipe', 'modeltipe']),
            'serial_number' => $this->getColumnValue($row, ['serial_number', 'serial', 'sn', 'serialnumber']),
            'kondisi' => $this->getColumnValue($row, ['kondisi', 'condition', 'kondisi_asset']),
            'status' => $this->getColumnValue($row, ['status', 'stat', 'status_asset']),
            'lokasi' => $this->getColumnValue($row, ['lokasi', 'location', 'loc', 'tempat', 'ruangan']),
            'deskripsi' => $this->getColumnValue($row, ['deskripsi', 'description', 'desc', 'keterangan', 'note', 'notes']),
        ];
    }

    private function getColumnValue(array $row, array $possibleKeys): ?string
    {
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && $row[$key] !== null && $row[$key] !== '') {
                return trim((string) $row[$key]);
            }
        }
        return null;
    }

    private function normalizeKondisi(string $kondisi): string
    {
        $mapping = [
            'baik' => 'baik', 'bagus' => 'baik', 'good' => 'baik', 'baru' => 'baik', 'new' => 'baik',
            'cukup' => 'cukup', 'sedang' => 'cukup', 'fair' => 'cukup', 'normal' => 'cukup',
            'rusak_ringan' => 'rusak_ringan', 'rusak ringan' => 'rusak_ringan', 'minor' => 'rusak_ringan', 'rusak' => 'rusak_ringan',
            'rusak_berat' => 'rusak_berat', 'rusak berat' => 'rusak_berat', 'major' => 'rusak_berat', 'broken' => 'rusak_berat', 'hancur' => 'rusak_berat',
        ];
        return $mapping[$kondisi] ?? 'baik';
    }

    private function normalizeStatus(string $status): string
    {
        $mapping = [
            'available' => 'available', 'tersedia' => 'available', 'aktif' => 'available', 'active' => 'available', 'ready' => 'available',
            'borrowed' => 'borrowed', 'dipinjam' => 'borrowed', 'pinjam' => 'borrowed', 'in use' => 'borrowed', 'inuse' => 'borrowed',
            'maintenance' => 'maintenance', 'perbaikan' => 'maintenance', 'repair' => 'maintenance', 'service' => 'maintenance',
            'broken' => 'broken', 'rusak' => 'broken', 'damaged' => 'broken',
            'lost' => 'lost', 'hilang' => 'lost', 'missing' => 'lost',
            'retired' => 'maintenance', 'disposed' => 'lost', 'scrap' => 'broken', 'tidak aktif' => 'maintenance', 'inactive' => 'maintenance',
        ];
        return $mapping[$status] ?? 'available';
    }
}
