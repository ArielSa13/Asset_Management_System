# Asset Management System

A modern, production-ready Asset Management System built with Laravel 12, featuring Smart QR Code tracking, Smart Asset Code Generation with gap detection, and a public borrowing system.

## Features

- **Smart Asset Code Generator** - Auto-generates unique codes (PREFIX + 6 digits) with gap detection
- **QR Code Tracking** - Every asset gets a unique QR code for quick scanning
- **Public QR Scan Page** - No login required to view asset details and submit borrowing requests
- **Borrowing System** - Full workflow: Request → Approve/Reject → Borrowed → Returned
- **Overdue Detection** - Automatic overdue marking with notification architecture
- **Modern Dashboard** - SaaS-style dashboard with charts and real-time stats
- **Export/Import** - Excel, CSV, and PDF export; Excel import
- **Audit Log** - Complete activity tracking with IP and user agent
- **Dark Mode** - Full dark mode support
- **REST API** - Sanctum-protected API endpoints
- **Mobile Responsive** - Works on all devices

## Tech Stack

- **Backend:** Laravel 12, PHP 8.3+
- **Database:** MySQL
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates
- **Auth:** Laravel Breeze (admin only, no public registration)
- **Packages:** simplesoftwareio/simple-qrcode, maatwebsite/excel, barryvdh/laravel-dompdf
- **API:** Laravel Sanctum

## Installation

```bash
# Clone the repository
git clone https://github.com/projectarielsa/Asset_Management_System.git
cd Asset_Management_System

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# DB_DATABASE=asset_management
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database (creates admin user + categories + locations)
php artisan db:seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start the development server
php artisan serve
```

## Default Admin Credentials

```
Email: admin@assetmanagement.com
Password: password123
```

**Important:** Change the password after first login!

## Architecture

```
app/
├── Console/Commands/     # Artisan commands (overdue check)
├── Exports/              # Excel/PDF exports
├── Http/
│   ├── Controllers/
│   │   ├── Admin/        # Admin controllers (thin)
│   │   ├── Api/          # REST API controllers
│   │   ├── Auth/         # Authentication
│   │   └── Public/       # Public QR scan
│   └── Requests/         # Form Request validation
├── Imports/              # Excel imports
├── Models/               # Eloquent models
├── Providers/            # Service providers
└── Services/             # Business logic (clean architecture)
    ├── ActivityLogService.php
    ├── AssetCodeGeneratorService.php  # Smart code generation
    ├── AssetService.php
    ├── BorrowingService.php
    ├── DashboardService.php
    ├── NotificationService.php        # Future email/WhatsApp
    └── QrCodeService.php
```

## Smart Asset Code Generator

Asset codes are auto-generated based on category prefix:

| Category  | Prefix | Example Code |
|-----------|--------|-------------|
| Komputer  | KOM    | KOM000001   |
| Laptop    | LAP    | LAP000001   |
| Printer   | PRT    | PRT000001   |

**Gap Detection:** If KOM000002 is deleted, the next asset in the same category will be assigned KOM000002 (not KOM000004).

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | /api/assets | Sanctum | List all assets |
| GET | /api/assets/{id} | Sanctum | Get single asset |
| GET | /api/borrowings | Sanctum | List borrowings |
| GET | /api/scan/{kode} | Public | Get asset by code |

## Scheduled Commands

```bash
# Check and mark overdue borrowings (runs daily at 8AM)
php artisan borrowings:check-overdue

# Add to crontab:
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## Future Scalability

The architecture is prepared for:
- Multi-admin / role-based access
- Multi-branch support
- Mobile app (REST API ready)
- WhatsApp notification gateway
- Asset depreciation tracking
- Maintenance scheduler
- QR label printing
- PWA support

## License

MIT License
