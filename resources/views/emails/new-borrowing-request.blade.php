<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Peminjaman Baru</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f4f8; -webkit-font-smoothing: antialiased;">
    <!-- Wrapper -->
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f0f4f8; padding: 48px 16px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table width="560" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2563eb; padding: 36px 40px; text-align: center;">
                            <table cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto;">
                                <tr>
                                    <td style="background-color: rgba(255,255,255,0.15); border-radius: 12px; padding: 10px; vertical-align: middle;">
                                        <img src="https://img.icons8.com/fluency/48/000000/box.png" alt="" width="28" height="28" style="display: block;">
                                    </td>
                                    <td style="padding-left: 14px; vertical-align: middle;">
                                        <p style="margin: 0; color: #ffffff; font-size: 18px; font-weight: 700; letter-spacing: -0.3px;">Permintaan Peminjaman Baru</p>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 12px 0 0; color: #bfdbfe; font-size: 13px;">Ada permintaan peminjaman baru yang perlu Anda review.</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 40px 20px;">
                            
                            <!-- Alert Badge -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 28px;">
                                <tr>
                                    <td style="background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 14px 18px;">
                                        <table cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="vertical-align: middle; padding-right: 10px; font-size: 18px;">&#9888;&#65039;</td>
                                                <td style="vertical-align: middle;">
                                                    <p style="margin: 0; color: #92400e; font-size: 13px; font-weight: 600;">Menunggu Persetujuan</p>
                                                    <p style="margin: 2px 0 0; color: #a16207; font-size: 12px;">Permintaan ini memerlukan tindakan dari admin.</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section: Asset Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                        <p style="margin: 0; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">INFORMASI ASSET</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 14px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding: 8px 0; width: 140px; color: #64748b; font-size: 13px; vertical-align: top;">Nama Asset</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $asset->nama_asset }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">Kode Asset</td>
                                                <td style="padding: 8px 0;">
                                                    <span style="display: inline-block; background-color: #eff6ff; color: #2563eb; font-size: 13px; font-weight: 700; font-family: 'Courier New', monospace; padding: 4px 10px; border-radius: 6px; border: 1px solid #bfdbfe;">{{ $asset->kode_asset }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">Kategori</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px;">{{ $asset->category?->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">Lokasi</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px;">{{ $asset->lokasi ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section: Peminjam -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                        <p style="margin: 0; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">DATA PEMINJAM</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 14px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding: 8px 0; width: 140px; color: #64748b; font-size: 13px; vertical-align: top;">Nama</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $borrowing->borrower_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">Email</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px;">
                                                    <a href="mailto:{{ $borrowing->borrower_email }}" style="color: #2563eb; text-decoration: none;">{{ $borrowing->borrower_email }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">No. Telepon</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px;">{{ $borrowing->borrower_phone ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Section: Detail Peminjaman -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-bottom: 28px;">
                                <tr>
                                    <td style="padding-bottom: 10px; border-bottom: 2px solid #e2e8f0;">
                                        <p style="margin: 0; color: #2563eb; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">DETAIL PEMINJAMAN</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 14px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                            <tr>
                                                <td style="padding: 8px 0; width: 140px; color: #64748b; font-size: 13px; vertical-align: top;">Tujuan</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px; line-height: 1.6;">{{ $borrowing->purpose }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">Tgl. Pinjam</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px; font-weight: 600;">{{ $borrowing->borrow_date->format('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #64748b; font-size: 13px; vertical-align: top;">Tgl. Kembali</td>
                                                <td style="padding: 8px 0; color: #1e293b; font-size: 13px; font-weight: 600;">{{ $borrowing->return_date ? $borrowing->return_date->format('d F Y') : 'Belum ditentukan' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Action Button -->
                    <tr>
                        <td style="padding: 0 40px 36px;" align="center">
                            <table cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="border-radius: 10px; background-color: #2563eb;" align="center">
                                        <a href="{{ url('/admin/borrowings') }}" target="_blank" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 10px; font-size: 14px; font-weight: 700; letter-spacing: 0.2px; border: 2px solid #2563eb;">
                                            Lihat di Admin Panel &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 14px 0 0; color: #94a3b8; font-size: 12px;">Klik tombol di atas untuk approve atau reject permintaan ini.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td style="text-align: center;">
                                        <p style="margin: 0; color: #64748b; font-size: 12px; font-weight: 600;">{{ config('app.name') }}</p>
                                        <p style="margin: 6px 0 0; color: #94a3b8; font-size: 11px;">Email ini dikirim otomatis. Harap tidak membalas email ini.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

                <!-- Sub-footer -->
                <table width="560" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: 24px;">
                    <tr>
                        <td align="center">
                            <p style="margin: 0; color: #94a3b8; font-size: 11px;">Dikirim pada {{ now()->format('d F Y, H:i') }} WIB</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
