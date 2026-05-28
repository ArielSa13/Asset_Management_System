<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Services\AssetCodeGeneratorService;
use App\Services\CategoryDetectorService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetsImport implements ToModel, WithHeadingRow
{
    private AssetCodeGeneratorService $codeGenerator;
    private CategoryDetectorService $categoryDetector;

    public function __construct()
    {
        $this->codeGenerator = app(AssetCodeGeneratorService::class);
        $this->categoryDetector = app(CategoryDetectorService::class);
    }

    public function model(array $row)
    {
        // Map flexible column names to standard fields
        $data = $this->mapColumns($row);

        // Skip empty rows
        if (empty($data['nama_asset'])) {
            return null;
        }

        // Try to get category from kategori column first
        $category = null;
        
        if (!empty($data['kategori'])) {
            $category = Category::where('name', $data['kategori'])
                ->orWhere('prefix', strtoupper($data['kategori']))
                ->first();
        }
        
        // If category not found or empty, try auto-detect from nama_asset
        if (!$category) {
            $category = $this->categoryDetector->detectCategory($data['nama_asset']);
        }

        // If still no category found, skip this row
        if (!$category) {
            return null;
        }

        // Check if kode_asset is provided, if not generate new one
        if (!empty($data['kode_asset'])) {
            // Check if asset with this code already exists
            $existingAsset = Asset::where('kode_asset', $data['kode_asset'])->first();
            if ($existingAsset) {
                return null;
            }
            $kodeAsset = $data['kode_asset'];
        } else {
            $kodeAsset = $this->codeGenerator->generate($category);
        }

        // Normalize kondisi and status
        $kondisi = !empty($data['kondisi']) ? strtolower(trim($data['kondisi'])) : 'baik';
        $kondisi = $this->normalizeKondisi($kondisi);
        
        $status = !empty($data['status']) ? strtolower(trim($data['status'])) : 'available';
        $status = $this->normalizeStatus($status);

        return new Asset([
            'kode_asset' => $kodeAsset,
            'nama_asset' => $data['nama_asset'],
            'category_id' => $category->id,
            'serial_number' => $data['serial_number'] ?? null,
            'merk' => $data['merk'] ?? null,
            'model' => $data['model'] ?? null,
            'kondisi' => $kondisi,
            'status' => $status,
            'lokasi' => $data['lokasi'] ?? null,
            'deskripsi' => $data['deskripsi'] ?? null,
        ]);
    }

    /**
     * Map flexible column names to standard field names
     * Supports multiple header formats
     */
    private function mapColumns(array $row): array
    {
        return [
            'nama_asset' => $this->getColumnValue($row, [
                'nama_asset', 'nama', 'name', 'asset_name', 'nama_aset',
            ]),
            'kode_asset' => $this->getColumnValue($row, [
                'kode_asset', 'kode', 'code', 'asset_code', 'kode_aset',
            ]),
            'kategori' => $this->getColumnValue($row, [
                'kategori', 'category', 'kategory', 'cat', 'jenis',
            ]),
            'merk' => $this->getColumnValue($row, [
                'merk', 'merek', 'brand', 'merek_brand', 'merekbrand',
            ]),
            'model' => $this->getColumnValue($row, [
                'model', 'tipe', 'type', 'model_tipe', 'modeltipe',
            ]),
            'serial_number' => $this->getColumnValue($row, [
                'serial_number', 'serial', 'sn', 'serialnumber',
            ]),
            'kondisi' => $this->getColumnValue($row, [
                'kondisi', 'condition', 'kondisi_asset',
            ]),
            'status' => $this->getColumnValue($row, [
                'status', 'stat', 'status_asset',
            ]),
            'lokasi' => $this->getColumnValue($row, [
                'lokasi', 'location', 'loc', 'tempat', 'ruangan',
            ]),
            'deskripsi' => $this->getColumnValue($row, [
                'deskripsi', 'description', 'desc', 'keterangan', 'note', 'notes',
            ]),
        ];
    }

    /**
     * Get value from row by trying multiple possible column names
     */
    private function getColumnValue(array $row, array $possibleKeys): ?string
    {
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && $row[$key] !== null && $row[$key] !== '') {
                return trim((string) $row[$key]);
            }
        }
        return null;
    }

    /**
     * Normalize kondisi value to match ENUM in database
     * Valid: baik, cukup, rusak_ringan, rusak_berat
     */
    private function normalizeKondisi(string $kondisi): string
    {
        $mapping = [
            'baik' => 'baik',
            'bagus' => 'baik',
            'good' => 'baik',
            'baru' => 'baik',
            'new' => 'baik',
            
            'cukup' => 'cukup',
            'sedang' => 'cukup',
            'fair' => 'cukup',
            'normal' => 'cukup',
            
            'rusak_ringan' => 'rusak_ringan',
            'rusak ringan' => 'rusak_ringan',
            'minor' => 'rusak_ringan',
            'rusak' => 'rusak_ringan',
            
            'rusak_berat' => 'rusak_berat',
            'rusak berat' => 'rusak_berat',
            'major' => 'rusak_berat',
            'broken' => 'rusak_berat',
            'hancur' => 'rusak_berat',
        ];

        return $mapping[$kondisi] ?? 'baik';
    }

    /**
     * Normalize status value to match ENUM in database
     * Valid: available, borrowed, maintenance, broken, lost
     */
    private function normalizeStatus(string $status): string
    {
        $mapping = [
            'available' => 'available',
            'tersedia' => 'available',
            'aktif' => 'available',
            'active' => 'available',
            'ready' => 'available',
            
            'borrowed' => 'borrowed',
            'dipinjam' => 'borrowed',
            'pinjam' => 'borrowed',
            'in use' => 'borrowed',
            'inuse' => 'borrowed',
            
            'maintenance' => 'maintenance',
            'perbaikan' => 'maintenance',
            'repair' => 'maintenance',
            'service' => 'maintenance',
            
            'broken' => 'broken',
            'rusak' => 'broken',
            'damaged' => 'broken',
            
            'lost' => 'lost',
            'hilang' => 'lost',
            'missing' => 'lost',
            
            // Non-standard values
            'retired' => 'maintenance',
            'disposed' => 'lost',
            'scrap' => 'broken',
            'tidak aktif' => 'maintenance',
            'inactive' => 'maintenance',
            'off' => 'maintenance',
        ];

        return $mapping[$status] ?? 'available';
    }

}
