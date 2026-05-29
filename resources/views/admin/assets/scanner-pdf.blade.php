<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Riwayat Scan QR Code</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
            padding: 10px;
        }
        .header {
            text-align: center;
            padding: 24px 0 20px;
            border-bottom: 3px solid #2563eb;
            margin-bottom: 24px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 6px;
        }
        .header p {
            font-size: 12px;
            color: #6b7280;
        }
        .meta-info {
            margin-bottom: 20px;
            padding: 14px 18px;
            background-color: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 4px 10px;
            font-size: 11px;
        }
        .meta-info .label {
            color: #6b7280;
            width: 130px;
        }
        .meta-info .value {
            color: #1f2937;
            font-weight: 600;
        }
        table.scan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        table.scan-table thead th {
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.scan-table tbody td {
            padding: 11px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            vertical-align: top;
        }
        table.scan-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .code {
            font-family: 'DejaVu Sans Mono', 'Courier New', monospace;
            font-weight: 700;
            color: #2563eb;
            font-size: 10px;
        }
        .asset-name {
            font-weight: 600;
            color: #111827;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 700;
        }
        .badge-available { background-color: #d1fae5; color: #065f46; }
        .badge-borrowed { background-color: #fef3c7; color: #92400e; }
        .badge-maintenance { background-color: #dbeafe; color: #1e40af; }
        .badge-broken { background-color: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Laporan Riwayat Scan QR Code</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Tanggal Export:</td>
                <td class="value">{{ $exportDate }}</td>
                <td class="label">Total Asset Discan:</td>
                <td class="value">{{ $totalScanned }} asset</td>
            </tr>
            <tr>
                <td class="label">Diexport oleh:</td>
                <td class="value">{{ auth()->user()->name ?? 'Admin' }}</td>
                <td class="label">Catatan:</td>
                <td class="value">Hasil scanning via QR Scanner</td>
            </tr>
        </table>
    </div>

    <table class="scan-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 80px;">Kode Asset</th>
                <th>Nama Asset</th>
                <th style="width: 65px;">Kategori</th>
                <th style="width: 55px;">Kondisi</th>
                <th style="width: 60px;">Status</th>
                <th style="width: 75px;">Lokasi</th>
                <th style="width: 45px;">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scanData as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="code">{{ $item['code'] ?? '-' }}</span></td>
                <td><span class="asset-name">{{ $item['name'] ?? '-' }}</span></td>
                <td>{{ $item['category'] ?? '-' }}</td>
                <td>{{ $item['kondisi'] ?? '-' }}</td>
                <td>
                    @php
                        $status = strtolower($item['status'] ?? 'available');
                        $badgeClass = match(true) {
                            str_contains($status, 'available') => 'badge-available',
                            str_contains($status, 'borrowed') => 'badge-borrowed',
                            str_contains($status, 'maintenance') => 'badge-maintenance',
                            default => 'badge-broken',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $item['status'] ?? '-' }}</span>
                </td>
                <td>{{ $item['lokasi'] ?? '-' }}</td>
                <td>{{ $item['time'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh {{ config('app.name') }} pada {{ $exportDate }}</p>
    </div>
</body>
</html>
