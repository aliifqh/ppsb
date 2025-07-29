# 🚀 Deployment Guide - PPSB AL-MUKMIN

## 📋 Daftar Isi
- [Prerequisites](#-prerequisites)
- [Metode 1: Git Deployment (Recommended)](#metode-1-git-deployment-recommended)
- [Metode 2: Manual Upload](#metode-2-manual-upload)
- [Konfigurasi cPanel](#-konfigurasi-cpanel)
- [Troubleshooting](#-troubleshooting)

---

## ⚙️ Prerequisites

### Server Requirements
- **PHP**: 8.2 atau lebih tinggi
- **MySQL**: 8.0 atau lebih tinggi
- **Composer**: Terinstall di server
- **Node.js**: 18+ (untuk build assets)
- **Git**: Terinstall di server

### cPanel Features
- **Git Version Control** (biasanya tersedia)
- **SSH Access** (opsional, untuk deployment otomatis)
- **Cron Jobs** (untuk scheduled tasks)

---

## 🎯 Metode 1: Git Deployment (Recommended)

### Step 1: Setup Git di cPanel

1. **Login ke cPanel**
2. **Buka "Git Version Control"**
3. **Klik "Create"**
4. **Isi form:**
   ```
   Repository URL: https://github.com/aliifqh/ppsb.git
   Repository Branch: master
   Directory: public_html/ppsb
   ```

### Step 2: Clone Repository

```bash
# Di cPanel Terminal atau SSH
cd public_html
git clone https://github.com/aliifqh/ppsb.git
cd ppsb
```

### Step 3: Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies (jika tersedia)
npm install
npm run build
```

### Step 4: Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Konfigurasi Database

Edit file `.env`:
```env
APP_NAME="PPSB AL-MUKMIN"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com/ppsb

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 6: Setup Database

```bash
# Run migrations
php artisan migrate --force

# Run seeders (opsional)
php artisan db:seed --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Setup File Permissions

```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/

# Create storage link
php artisan storage:link
```

### Step 8: Setup .htaccess

Buat file `.htaccess` di root folder:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 📤 Metode 2: Manual Upload

### Step 1: Prepare Files

```bash
# Di local machine
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Remove unnecessary files
rm -rf node_modules/
rm -rf .git/
rm -rf tests/
rm -rf .github/
rm -rf wireframe/
```

### Step 2: Upload via File Manager

1. **Buka cPanel File Manager**
2. **Upload semua file ke folder `public_html/ppsb/`**
3. **Extract file jika dalam format ZIP**

### Step 3: Setup Environment

Ikuti langkah yang sama seperti Metode 1 (Step 4-8)

---

## ⚙️ Konfigurasi cPanel

### 1. Database Setup

1. **Buka "MySQL Databases"**
2. **Buat database baru**
3. **Buat user database**
4. **Assign user ke database**
5. **Update `.env` dengan kredensial database**

### 2. Domain/Subdomain Setup

#### Option A: Subdomain
```
ppsb.yourdomain.com -> public_html/ppsb/public
```

#### Option B: Subfolder
```
yourdomain.com/ppsb -> public_html/ppsb/public
```

### 3. SSL Certificate

1. **Buka "SSL/TLS"**
2. **Install SSL certificate**
3. **Force HTTPS redirect**

### 4. Cron Jobs

Buat cron job untuk scheduled tasks:
```bash
# Laravel scheduler
* * * * * cd /home/username/public_html/ppsb && php artisan schedule:run >> /dev/null 2>&1

# Queue worker (jika menggunakan queue)
* * * * * cd /home/username/public_html/ppsb && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

---

## 🔧 Post-Deployment Setup

### 1. Test Application

```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();

# Test storage
php artisan storage:link
```

### 2. Setup Admin User

```bash
# Create admin user via tinker
php artisan tinker
User::create([
    'name' => 'Admin',
    'email' => 'admin@yourdomain.com',
    'password' => Hash::make('your_password'),
    'email_verified_at' => now()
]);
```

### 3. Configure WhatsApp

1. **Login ke admin panel**
2. **Buka "Pengaturan WhatsApp"**
3. **Setup provider dan credentials**
4. **Test koneksi**

### 4. Setup Email

Edit `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="PPSB AL-MUKMIN"
```

---

## 🐛 Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check error logs
tail -f storage/logs/laravel.log

# Check permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### 2. Database Connection Error
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();

# Check .env configuration
php artisan config:clear
```

#### 3. File Upload Issues
```bash
# Check storage permissions
chmod -R 755 storage/
php artisan storage:link

# Check upload limits in php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

#### 4. Asset Loading Issues
```bash
# Rebuild assets
npm run build

# Check public folder permissions
chmod -R 755 public/
```

### Performance Optimization

#### 1. Enable Caching
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2. Optimize Composer
```bash
composer install --optimize-autoloader --no-dev
```

#### 3. Enable OPcache
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
```

---

## 📞 Support

Jika mengalami masalah:

1. **Check error logs**: `storage/logs/laravel.log`
2. **Check cPanel error logs**
3. **Test database connection**
4. **Verify file permissions**
5. **Contact hosting provider**

---

## 🔄 Update Deployment

### Via Git (Recommended)
```bash
cd public_html/ppsb
git pull origin master
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Manual Update
1. **Backup current files**
2. **Upload new files**
3. **Run migrations**: `php artisan migrate --force`
4. **Clear caches**: `php artisan config:clear`

---

**🎉 Selamat! PPSB AL-MUKMIN sudah berhasil di-deploy ke cPanel!** 