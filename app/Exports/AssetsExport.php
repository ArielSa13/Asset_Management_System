<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Asset::with(['category'])->get();
    }

    public function headings(): array
    {
        return [
            'Kode Asset',
            'Nama Asset',
            'Kategori',
            'Lokasi',
            'Serial Number',
            'Merk',
            'Model',
            'Kondisi',
            'Status',
            'Tanggal Pembelian',
            'Harga',
            'Supplier',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->kode_asset,
            $asset->nama_asset,
            $asset->category?->name,
            $asset->location?->name ?? $asset->lokasi,
            $asset->serial_number,
            $asset->merk,
            $asset->model,
            $asset->kondisi_label,
            $asset->status_label,
            $asset->tanggal_pembelian?->format('Y-m-d'),
            $asset->harga,
            $asset->supplier,
        ];
    }
}
