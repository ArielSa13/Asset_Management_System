<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Borrowings Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <h1>Borrowings Report</h1>
    <p style="text-align: center; color: #666; margin-bottom: 15px;">Generated: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Asset</th>
                <th>Borrower</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing->id }}</td>
                <td>{{ $borrowing->asset?->kode_asset }} - {{ $borrowing->asset?->nama_asset }}</td>
                <td>{{ $borrowing->borrower_name }}</td>
                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                <td>{{ $borrowing->return_date->format('d M Y') }}</td>
                <td>{{ $borrowing->status_label }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Borrowings: {{ $borrowings->count() }} | Asset Management System</p>
    </div>
</body>
</html>
