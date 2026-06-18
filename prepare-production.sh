#!/bin/bash

# Asset Management System - Production Preparation Script
# Usage: bash prepare-production.sh

set -e

echo "🚀 Asset Management System - Production Preparation"
echo "=================================================="
echo ""

# Colors for output
RED='\033[0;31m'
YELLOW='\033[1;33m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

# Check if .env exists
if [ ! -f .env ]; then
    echo -e "${RED}❌ .env file not found!${NC}"
    echo "Creating from .env.example..."
    cp .env.example .env
    echo -e "${YELLOW}⚠️  Please edit .env with production values before continuing${NC}"
    exit 1
fi

echo "📋 Checking Environment Configuration..."
echo ""

# Check critical environment variables
WARNINGS=0

if grep -q "APP_DEBUG=true" .env; then
    echo -e "${RED}❌ CRITICAL: APP_DEBUG=true${NC}"
    echo "   Change to: APP_DEBUG=false"
    WARNINGS=$((WARNINGS + 1))
else
    echo -e "${GREEN}✅ APP_DEBUG is false${NC}"
fi

if grep -q "APP_ENV=local" .env; then
    echo -e "${RED}❌ CRITICAL: APP_ENV=local${NC}"
    echo "   Change to: APP_ENV=production"
    WARNINGS=$((WARNINGS + 1))
else
    echo -e "${GREEN}✅ APP_ENV is production${NC}"
fi

if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${RED}❌ CRITICAL: APP_KEY not set${NC}"
    echo "   Run: php artisan key:generate"
    WARNINGS=$((WARNINGS + 1))
else
    echo -e "${GREEN}✅ APP_KEY is set${NC}"
fi

if grep -q "DB_PASSWORD=$" .env || grep -q "DB_PASSWORD=\"\"" .env; then
    echo -e "${YELLOW}⚠️  WARNING: Database password is empty${NC}"
    WARNINGS=$((WARNINGS + 1))
else
    echo -e "${GREEN}✅ Database password is set${NC}"
fi

if grep -q "MAIL_HOST=mailpit" .env; then
    echo -e "${YELLOW}⚠️  WARNING: Using mailpit (development mail server)${NC}"
    echo "   Change to production SMTP server"
    WARNINGS=$((WARNINGS + 1))
fi

echo ""
echo "=================================================="

if [ $WARNINGS -gt 0 ]; then
    echo -e "${RED}Found $WARNINGS warning(s)!${NC}"
    echo ""
    read -p "Do you want to continue anyway? (y/N) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Aborted."
        exit 1
    fi
else
    echo -e "${GREEN}✅ Environment configuration looks good!${NC}"
fi

echo ""
echo "📦 Step 1: Installing Composer Dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "📦 Step 2: Installing NPM Dependencies..."
npm ci

echo ""
echo "🏗️  Step 3: Building Frontend Assets..."
npm run build

echo ""
echo "💾 Step 4: Running Database Migrations..."
read -p "Run migrations? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate --force
    echo -e "${GREEN}✅ Migrations completed${NC}"
else
    echo -e "${YELLOW}⚠️  Skipped migrations${NC}"
fi

echo ""
echo "🔗 Step 5: Creating Storage Symlink..."
if [ -L public/storage ]; then
    echo "Storage link already exists"
else
    php artisan storage:link
    echo -e "${GREEN}✅ Storage link created${NC}"
fi

echo ""
echo "⚡ Step 6: Optimizing Application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo -e "${GREEN}✅ Application optimized${NC}"

echo ""
echo "🔒 Step 7: Setting Permissions..."
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✅ Permissions set${NC}"

echo ""
echo "=================================================="
echo -e "${GREEN}✅ Production preparation completed!${NC}"
echo ""
echo "📋 Next Steps:"
echo "   1. Review PRODUCTION_CHECKLIST.md"
echo "   2. Setup cron job for scheduled tasks"
echo "   3. Configure web server (nginx/apache)"
echo "   4. Setup SSL certificate"
echo "   5. Create admin user (DON'T use default password!)"
echo "   6. Setup monitoring & logging"
echo "   7. Configure backup system"
echo ""
echo "🔧 Cron Job Setup:"
echo "   crontab -e"
echo "   Add: * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
echo ""
echo "👤 Create Admin User:"
echo "   php artisan tinker"
echo "   User::create(['name'=>'Admin','email'=>'admin@yourdomain.com','password'=>Hash::make('YourStrongPassword'),'role'=>'admin','email_verified_at'=>now()]);"
echo ""
echo "=================================================="
