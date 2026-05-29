<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label - {{ $asset->kode_asset }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .screen-controls {
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #4f46e5;
            color: #fff;
        }

        .btn-print:hover {
            background: #4338ca;
        }

        .btn-back {
            background: #6b7280;
            color: #fff;
        }

        .btn-back:hover {
            background: #4b5563;
        }

        .label-preview {
            background: #fff;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 16px;
        }

        .label {
            width: 5cm;
            height: 3cm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3mm;
            border: 1px solid #000;
            background: #fff;
        }

        .label .qr-code {
            width: 1.8cm;
            height: 1.8cm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .label .qr-code svg {
            width: 100%;
            height: 100%;
        }

        .label .asset-code {
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            font-weight: 700;
            text-align: center;
            margin-top: 2mm;
            letter-spacing: 0.5px;
        }

        .label .asset-name {
            font-size: 6pt;
            text-align: center;
            margin-top: 1mm;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #333;
        }

        .label .company-name {
            font-size: 5pt;
            text-align: center;
            margin-top: 1mm;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Print Styles */
        @page {
            size: 5cm 3cm;
            margin: 0;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
                display: block;
            }

            .screen-controls {
                display: none !important;
            }

            .label-preview {
                border: none;
                padding: 0;
                border-radius: 0;
                background: none;
            }

            .label {
                width: 5cm;
                height: 3cm;
                border: none;
                margin: 0;
                padding: 3mm;
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Screen Controls (hidden on print) -->
    <div class="screen-controls">
        <button class="btn btn-print" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Label
        </button>
        <a href="{{ route('admin.assets.show', $asset) }}" class="btn btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back
        </a>
    </div>

    <!-- Label Preview -->
    <div class="label-preview">
        <div class="label">
            <div class="qr-code">
                {!! QrCode::format('svg')->size(150)->errorCorrection('H')->margin(0)->generate(url("/scan/{$asset->kode_asset}")) !!}
            </div>
            <div class="asset-code">{{ $asset->kode_asset }}</div>
            <div class="asset-name">{{ Str::limit($asset->nama_asset, 30) }}</div>
            <div class="company-name">{{ config('app.name') }}</div>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog on load
        window.addEventListener('load', function() {
            // Small delay to ensure QR code is rendered
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
