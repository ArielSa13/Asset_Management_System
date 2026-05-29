<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Borrowing Request</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7fa;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 700;">New Borrowing Request</h1>
                            <p style="margin: 8px 0 0; color: #e0e7ff; font-size: 14px;">A new borrowing request has been submitted</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Asset Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 12px; color: #1e293b; font-size: 16px; font-weight: 600;">Asset Information</h2>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; width: 120px;">Asset Name</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px; font-weight: 600;">{{ $asset->nama_asset }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px;">Asset Code</td>
                                                <td style="padding: 6px 0; color: #4f46e5; font-size: 13px; font-weight: 700; font-family: 'Courier New', monospace;">{{ $asset->kode_asset }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Borrower Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 12px; color: #1e293b; font-size: 16px; font-weight: 600;">Borrower Details</h2>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; width: 120px;">Name</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px; font-weight: 600;">{{ $borrowing->borrower_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px;">Email</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px;">{{ $borrowing->borrower_email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px;">Phone</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px;">{{ $borrowing->borrower_phone ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Borrowing Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 32px; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 12px; color: #1e293b; font-size: 16px; font-weight: 600;">Borrowing Details</h2>
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px; width: 120px;">Purpose</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px;">{{ $borrowing->purpose }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px;">Borrow Date</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px; font-weight: 600;">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 13px;">Return Date</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 13px; font-weight: 600;">{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'Not specified' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/admin/borrowings') }}" style="display: inline-block; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 14px; font-weight: 600; letter-spacing: 0.3px;">
                                            View in Admin Panel
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px;">This is an automated notification from {{ config('app.name') }}.</p>
                            <p style="margin: 4px 0 0; color: #94a3b8; font-size: 12px;">Please review and respond to this request promptly.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
