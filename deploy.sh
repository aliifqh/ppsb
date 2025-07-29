#!/bin/bash

# 🚀 PPSB AL-MUKMIN Deployment Script
# Usage: ./deploy.sh

echo "🚀 Starting PPSB AL-MUKMIN Deployment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel project root directory"
    exit 1
fi

print_status "Checking prerequisites..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
if [[ $(echo "$PHP_VERSION 8.2" | tr " " "\n" | sort -V | head -n 1) != "8.2" ]]; then
    print_error "PHP 8.2 or higher is required. Current version: $PHP_VERSION"
    exit 1
fi

print_status "PHP version: $PHP_VERSION ✓"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed"
    exit 1
fi

print_status "Composer is installed ✓"

# Check if .env exists
if [ ! -f ".env" ]; then
    print_warning ".env file not found. Creating from .env.example..."
    cp .env.example .env
    print_status "Please configure your .env file before continuing"
    exit 1
fi

print_status ".env file exists ✓"

# Install/Update dependencies
print_status "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

if [ $? -ne 0 ]; then
    print_error "Failed to install PHP dependencies"
    exit 1
fi

print_status "PHP dependencies installed ✓"

# Check if Node.js is available
if command -v npm &> /dev/null; then
    print_status "Installing Node.js dependencies..."
    npm install --silent
    
    if [ $? -eq 0 ]; then
        print_status "Building assets..."
        npm run build --silent
        
        if [ $? -eq 0 ]; then
            print_status "Assets built successfully ✓"
        else
            print_warning "Failed to build assets"
        fi
    else
        print_warning "Failed to install Node.js dependencies"
    fi
else
    print_warning "Node.js/npm not found. Skipping asset building..."
fi

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    print_status "Generating application key..."
    php artisan key:generate --no-interaction
fi

# Clear all caches
print_status "Clearing caches..."
php artisan config:clear --no-interaction
php artisan route:clear --no-interaction
php artisan view:clear --no-interaction
php artisan cache:clear --no-interaction

# Run database migrations
print_status "Running database migrations..."
php artisan migrate --force --no-interaction

if [ $? -ne 0 ]; then
    print_error "Failed to run migrations"
    exit 1
fi

print_status "Database migrations completed ✓"

# Create storage link
if [ ! -L "public/storage" ]; then
    print_status "Creating storage link..."
    php artisan storage:link --no-interaction
fi

# Set proper permissions
print_status "Setting file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/

# Optimize for production
print_status "Optimizing for production..."
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

# Check if optimization was successful
if [ $? -eq 0 ]; then
    print_status "Application optimized ✓"
else
    print_warning "Some optimizations failed"
fi

# Final checks
print_status "Performing final checks..."

# Check if storage is writable
if [ -w "storage" ]; then
    print_status "Storage is writable ✓"
else
    print_error "Storage is not writable"
    exit 1
fi

# Check if bootstrap/cache is writable
if [ -w "bootstrap/cache" ]; then
    print_status "Bootstrap cache is writable ✓"
else
    print_error "Bootstrap cache is not writable"
    exit 1
fi

# Test database connection
print_status "Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo();" --no-interaction > /dev/null 2>&1

if [ $? -eq 0 ]; then
    print_status "Database connection successful ✓"
else
    print_error "Database connection failed"
    exit 1
fi

echo ""
echo "🎉 Deployment completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Configure your web server to point to the 'public' directory"
echo "2. Set up SSL certificate"
echo "3. Configure cron jobs for scheduled tasks"
echo "4. Test the application"
echo ""
echo "📞 For support, check the DEPLOYMENT.md file"
echo "" 