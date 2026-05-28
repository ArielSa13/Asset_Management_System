<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AssetsExport;
use App\Exports\BorrowingsExport;
use App\Imports\AssetsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Asset;
use App\Models\Borrowing;

class ExportController extends Controller
{
    public function exportAssets(Request $request)
    {
        $format = $request->get('format', 'xlsx');

        if ($format === 'pdf') {
            $assets = Asset::with(['category', 'location'])->get();
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

    public function importAssets(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        Excel::import(new AssetsImport(), $request->file('file'));

        return back()->with('success', 'Assets berhasil diimport.');
    }
}
