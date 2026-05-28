<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Services\AssetCodeGeneratorService;
use App\Services\CategoryDetectorService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation
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
        // Try to get category from kategori column first
        $category = null;
        
        if (!empty($row['kategori'])) {
            $category = Category::where('name', $row['kategori'])
                ->orWhere('prefix', strtoupper($row['kategori'] ?? ''))
                ->first();
        }
        
        // If category not found or empty, try auto-detect from nama_asset
        if (!$category && !empty($row['nama_asset'])) {
            $category = $this->categoryDetector->detectCategory($row['nama_asset']);
        }

        // If still no category found, skip this row
        if (!$category) {
            return null;
        }

        // Check if kode_asset is provided, if not generate new one
        if (!empty($row['kode_asset'])) {
            // Check if asset with this code already exists
            $existingAsset = Asset::where('kode_asset', $row['kode_asset'])->first();
            if ($existingAsset) {
                // Skip this row if asset code already exists
                return null;
            }
            $kodeAsset = $row['kode_asset'];
        } else {
            $kodeAsset = $this->codeGenerator->generate($category);
        }

        // Normalize kondisi to lowercase and map non-standard values
        $kondisi = !empty($row['kondisi']) ? strtolower(trim($row['kondisi'])) : 'baik';
        $kondisi = $this->normalizeKondisi($kondisi);
        
        // Normalize status to lowercase and map non-standard values
        $status = !empty($row['status']) ? strtolower(trim($row['status'])) : 'available';
        $status = $this->normalizeStatus($status);

        return new Asset([
            'kode_asset' => $kodeAsset,
            'nama_asset' => $row['nama_asset'],
            'category_id' => $category->id,
            'serial_number' => $row['serial_number'] ?? null,
            'merk' => $row['merk'] ?? null,
            'model' => $row['model'] ?? null,
            'kondisi' => $kondisi,
            'status' => $status,
            'lokasi' => $row['lokasi'] ?? null,
            'deskripsi' => $row['deskripsi'] ?? null,
        ]);
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

    public function rules(): array
    {
        return [
            'nama_asset' => ['required', 'string'],
            // kategori is now optional since we have auto-detection
        ];
    }
}
