<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Assets Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        h1 { text-align: center; font-size: 20px; margin-bottom: 5px; color: #1e40af; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #2563eb; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { border-bottom: 1px solid #e5e7eb; padding: 7px 6px; font-size: 10px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-available { background-color: #dcfce7; color: #166534; }
        .badge-borrowed { background-color: #dbeafe; color: #1e40af; }
        .badge-maintenance { background-color: #fef3c7; color: #92400e; }
        .badge-broken { background-color: #fee2e2; color: #991b1b; }
        .badge-lost { background-color: #f3f4f6; color: #374151; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 9px; color: #666; }
        .summary { margin-top: 20px; padding: 12px; background-color: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; }
        .summary-title { font-weight: bold; margin-bottom: 5px; color: #0369a1; }
        .summary-grid { display: inline-block; margin-right: 20px; }
    </style>
</head>
<body>
    <h1>Assets Report</h1>
    <p class="subtitle">Generated: {{ now()->format('d M Y H:i') }} | Total: {{ $assets->count() }} assets</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 12%">Code</th>
                <th style="width: 20%">Name</th>
                <th style="width: 10%">Category</th>
                <th style="width: 13%">Brand / Model</th>
                <th style="width: 12%">Serial Number</th>
                <th style="width: 8%" class="text-center">Status</th>
                <th style="width: 8%" class="text-center">Condition</th>
                <th style="width: 12%">Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-family: monospace; font-size: 9px;">{{ $asset->kode_asset }}</td>
                <td>{{ $asset->nama_asset }}</td>
                <td>{{ $asset->category?->name ?? '-' }}</td>
                <td>{{ $asset->merk ?? '' }}{{ $asset->model ? ' / '.$asset->model : '' }}</td>
                <td style="font-family: monospace; font-size: 9px;">{{ $asset->serial_number ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ $asset->status }}">{{ $asset->status_label }}</span>
                </td>
                <td class="text-center">{{ $asset->kondisi_label }}</td>
                <td>{{ $asset->lokasi ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-title">Summary</div>
        <span class="summary-grid">Available: {{ $assets->where('status', 'available')->count() }}</span>
        <span class="summary-grid">Borrowed: {{ $assets->where('status', 'borrowed')->count() }}</span>
        <span class="summary-grid">Maintenance: {{ $assets->where('status', 'maintenance')->count() }}</span>
        <span class="summary-grid">Broken: {{ $assets->where('status', 'broken')->count() }}</span>
        <span class="summary-grid">Lost: {{ $assets->where('status', 'lost')->count() }}</span>
    </div>

    <div class="footer">
        <p>Asset Management System | Report generated automatically</p>
    </div>
</body>
</html>
