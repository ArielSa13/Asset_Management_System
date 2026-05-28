<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BorrowingsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Borrowing::with('asset')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode Asset',
            'Nama Asset',
            'Nama Peminjam',
            'Email',
            'Telepon',
            'Tujuan',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Tanggal Dikembalikan',
            'Status',
        ];
    }

    public function map($borrowing): array
    {
        return [
            $borrowing->id,
            $borrowing->asset?->kode_asset,
            $borrowing->asset?->nama_asset,
            $borrowing->borrower_name,
            $borrowing->borrower_email,
            $borrowing->borrower_phone,
            $borrowing->purpose,
            $borrowing->borrow_date->format('Y-m-d'),
            $borrowing->return_date->format('Y-m-d'),
            $borrowing->actual_return_date?->format('Y-m-d'),
            $borrowing->status_label,
        ];
    }
}
