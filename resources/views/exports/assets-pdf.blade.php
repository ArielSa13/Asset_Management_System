<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assets Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 10px; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <h1>Assets Report</h1>
    <p style="text-align: center; color: #666; margin-bottom: 15px;">Generated: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Condition</th>
                <th>Location</th>
                <th class="text-right">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->kode_asset }}</td>
                <td>{{ $asset->nama_asset }}</td>
                <td>{{ $asset->category?->name }}</td>
                <td>{{ $asset->status_label }}</td>
                <td>{{ $asset->kondisi_label }}</td>
                <td>{{ $asset->lokasi ?? $asset->location?->name }}</td>
                <td class="text-right">{{ $asset->harga ? 'Rp ' . number_format($asset->harga, 0, ',', '.') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Assets: {{ $assets->count() }} | Asset Management System</p>
    </div>
</body>
</html>
