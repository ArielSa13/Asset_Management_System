<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print All QR Labels ({{ $assets->count() }} assets)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }

        /* Screen Controls */
        .screen-controls {
            max-width: 900px;
            margin: 0 auto 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 20px 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .controls-left {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .controls-info {
            font-size: 14px;
            color: #6b7280;
        }

        .controls-info strong {
            color: #1f2937;
            font-size: 16px;
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
            background: #e5e7eb;
            color: #374151;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        /* Labels Grid */
        .labels-grid {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, 5cm);
            gap: 8px;
            justify-content: center;
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .label {
            width: 5cm;
            height: 3cm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3mm;
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 4px;
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
            font-size: 5.5pt;
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
            size: A4;
            margin: 10mm;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }

            .screen-controls {
                display: none !important;
            }

            .labels-grid {
                max-width: 100%;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                display: grid;
                grid-template-columns: repeat(3, 5cm);
                gap: 4mm;
                justify-content: center;
            }

            .label {
                border: 1px solid #000;
                border-radius: 0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Screen Controls (hidden on print) -->
    <div class="screen-controls">
        <div class="controls-left">
            <a href="{{ route('admin.assets.index') }}" class="btn btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>
            <div class="controls-info">
                <strong>{{ $assets->count() }}</strong> label siap dicetak
            </div>
        </div>
        <button class="btn btn-print" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Semua ({{ $assets->count() }})
        </button>
    </div>

    <!-- Labels Grid -->
    <div class="labels-grid">
        @foreach($assets as $asset)
        <div class="label">
            <div class="qr-code">
                {!! QrCode::format('svg')->size(150)->errorCorrection('H')->margin(0)->generate(url("/scan/{$asset->kode_asset}")) !!}
            </div>
            <div class="asset-code">{{ $asset->kode_asset }}</div>
            <div class="asset-name">{{ Str::limit($asset->nama_asset, 28) }}</div>
            <div class="company-name">{{ config('app.name') }}</div>
        </div>
        @endforeach
    </div>
</body>
</html>
