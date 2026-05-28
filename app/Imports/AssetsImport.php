<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Category;
use App\Services\AssetCodeGeneratorService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel, WithHeadingRow, WithValidation
{
    private AssetCodeGeneratorService $codeGenerator;

    public function __construct()
    {
        $this->codeGenerator = app(AssetCodeGeneratorService::class);
    }

    public function model(array $row)
    {
        $category = Category::where('name', $row['kategori'])
            ->orWhere('prefix', strtoupper($row['kategori'] ?? ''))
            ->first();

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

        // Normalize kondisi to lowercase
        $kondisi = !empty($row['kondisi']) ? strtolower(trim($row['kondisi'])) : 'baik';
        
        // Normalize status to lowercase
        $status = !empty($row['status']) ? strtolower(trim($row['status'])) : 'available';

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

    public function rules(): array
    {
        return [
            'nama_asset' => ['required', 'string'],
            'kategori' => ['required', 'string'],
        ];
    }
}
