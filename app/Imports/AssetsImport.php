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

        $kodeAsset = $this->codeGenerator->generate($category);

        return new Asset([
            'kode_asset' => $kodeAsset,
            'nama_asset' => $row['nama_asset'],
            'category_id' => $category->id,
            'serial_number' => $row['serial_number'] ?? null,
            'merk' => $row['merk'] ?? null,
            'model' => $row['model'] ?? null,
            'kondisi' => $row['kondisi'] ?? 'baik',
            'status' => 'available',
            'lokasi' => $row['lokasi'] ?? null,
            'tanggal_pembelian' => $row['tanggal_pembelian'] ?? null,
            'harga' => $row['harga'] ?? null,
            'supplier' => $row['supplier'] ?? null,
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
