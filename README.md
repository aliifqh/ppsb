# 🏫 PPSB (Penerimaan Peserta Santri Baru) - AL-MUKMIN

Sistem informasi penerimaan peserta santri baru yang dikembangkan untuk pesantren AL-MUKMIN. Sistem ini mengelola seluruh proses pendaftaran dari formulir online hingga seleksi dan pembayaran.

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Project](#-struktur-project)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Penggunaan](#-penggunaan)
- [API Documentation](#-api-documentation)
- [Deployment](#-deployment)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

## ✨ Fitur Utama

### 🎯 Area Publik
- **Homepage** - Landing page dengan informasi pendaftaran
- **Formulir Pendaftaran** - Form pendaftaran 4 tahap dengan validasi real-time
- **Cek Status** - Login santri untuk melihat status pendaftaran
- **Pembayaran Publik** - Upload bukti pembayaran tanpa login

### 👨‍🎓 Area Santri
- **Dashboard Santri** - Overview status pendaftaran dan alur
- **Data Pribadi** - Lihat dan edit data pendaftaran
- **Pembayaran** - Status pembayaran dan upload bukti
- **Ujian** - Informasi jadwal dan hasil tes masuk
- **Print Dokumen** - Cetak formulir dan bukti pendaftaran
- **Magic Login** - Login otomatis via token

### 👨‍💼 Area Admin
- **Dashboard Admin** - Statistik pendaftaran dan monitoring
- **Manajemen Santri** - CRUD data santri dengan soft delete
- **Manajemen Pembayaran** - Verifikasi dan kelola pembayaran
- **Manajemen Gelombang** - Atur periode pendaftaran
- **Role & Permission** - Manajemen user dan hak akses
- **Notifikasi** - Sistem notifikasi real-time
- **WhatsApp Integration** - Kirim notifikasi otomatis
- **Export Data** - Export data ke Excel/PDF

### 🔧 Fitur Teknis
- **Multi-Provider WhatsApp** - Support 8 provider WhatsApp API
- **File Management** - Upload dan preview dokumen
- **Real-time Validation** - Validasi form dengan Alpine.js
- **Responsive Design** - Mobile-first dengan Tailwind CSS
- **Progressive Web App** - SPA dengan Vue.js dan Inertia
- **Activity Logging** - Audit trail untuk semua aktivitas

## 🛠 Teknologi yang Digunakan

### Backend
- **Laravel 12** - PHP Framework
- **PHP 8.2+** - Bahasa pemrograman
- **MySQL/PostgreSQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Socialite** - Google OAuth
- **Spatie Permission** - Role & Permission
- **Laravel Excel** - Import/Export data
- **Laravel DomPDF** - Generate PDF
- **Livewire 3** - Real-time components

### Frontend
- **Vue.js 3** - JavaScript Framework
- **Inertia.js** - SPA without API
- **Alpine.js** - Lightweight reactivity
- **Tailwind CSS** - Utility-first CSS
- **Vite** - Build tool
- **SweetAlert2** - Beautiful alerts
- **Chart.js** - Data visualization
- **FilePond** - File upload
- **Vue Router** - Client-side routing

### Third Party Services
- **Google OAuth** - Social login
- **WhatsApp API Providers**:
  - Fonnte
  - Wablas
  - Woowa
  - Dripsender
  - Watzap
  - Whacenter
  - Whapi
- **Jitsi Meet** - Video conference untuk ujian

## 📁 Struktur Project

```
ppsb/
├── app/
│   ├── Console/Commands/          # Artisan commands
│   ├── Events/                    # Event classes
│   ├── Exports/                   # Excel export classes
│   ├── Helpers/                   # Helper functions
│   ├── Http/
│   │   ├── Controllers/           # Controller classes
│   │   │   ├── Admin/            # Admin controllers
│   │   │   ├── Auth/             # Authentication
│   │   │   └── Santri/           # Santri controllers
│   │   └── Middleware/           # Custom middleware
│   ├── Models/                   # Eloquent models
│   ├── Observers/                # Model observers
│   ├── Providers/                # Service providers
│   ├── Services/                 # Business logic services
│   └── Traits/                   # Reusable traits
├── config/                       # Configuration files
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database migrations
│   └── seeders/                  # Database seeders
├── public/                       # Public assets
├── resources/
│   ├── js/
│   │   ├── components/           # Vue components
│   │   │   ├── admin/           # Admin components
│   │   │   ├── common/          # Shared components
│   │   │   └── santri/          # Santri components
│   │   ├── services/            # API services
│   │   └── router.js            # Vue router
│   ├── css/                     # Stylesheets
│   └── views/                   # Blade templates
├── routes/                       # Route definitions
├── storage/                      # File storage
└── tests/                       # Test files
```

## 🚀 Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js 18+ dan npm
- MySQL 8.0+ atau PostgreSQL 13+
- Web server (Apache/Nginx)

### Langkah Instalasi

1. **Clone repository**
```bash
git clone <repository-url>
cd ppsb
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Konfigurasi database di .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppsb_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Jalankan migration dan seeder**
```bash
php artisan migrate
php artisan db:seed
```

7. **Build assets**
```bash
npm run build
```

8. **Setup storage link**
```bash
php artisan storage:link
```

9. **Jalankan server**
```bash
php artisan serve
```

## ⚙️ Konfigurasi

### Environment Variables

#### Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppsb_db
DB_USERNAME=root
DB_PASSWORD=
```

#### Mail Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Google OAuth
```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

#### WhatsApp Configuration
```env
WHATSAPP_PROVIDER=fonnte
FONNTE_TOKEN=your_fonnte_token
FONNTE_DEVICE=your_device_id
```

### File Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 📖 Penggunaan

### Admin Panel

1. **Login Admin**
   - Akses `/admin`
   - Login dengan kredensial admin

2. **Dashboard**
   - Lihat statistik pendaftaran
   - Monitor pembayaran
   - Notifikasi real-time

3. **Manajemen Santri**
   - Tambah/edit data santri
   - Verifikasi dokumen
   - Export data

4. **Manajemen Pembayaran**
   - Verifikasi bukti pembayaran
   - Update status pembayaran
   - Generate invoice

5. **Manajemen Gelombang**
   - Buat periode pendaftaran
   - Set biaya administrasi
   - Aktifkan/nonaktifkan gelombang

### Santri Portal

1. **Pendaftaran**
   - Akses `/formulir`
   - Isi formulir 4 tahap
   - Upload dokumen

2. **Cek Status**
   - Akses `/cek-pendaftaran`
   - Login dengan nomor pendaftaran
   - Lihat status dan alur

3. **Dashboard Santri**
   - Overview status pendaftaran
   - Upload bukti pembayaran
   - Lihat jadwal ujian

### WhatsApp Integration

1. **Setup Provider**
   - Pilih provider WhatsApp
   - Masukkan API credentials
   - Test koneksi

2. **Template Management**
   - Buat template pesan
   - Set variabel dinamis
   - Aktifkan template

3. **Auto Notification**
   - Notifikasi pendaftaran baru
   - Reminder pembayaran
   - Update status ujian

## 🔌 API Documentation

### Authentication
```bash
# Login
POST /login
{
    "email": "admin@example.com",
    "password": "password"
}

# Google OAuth
GET /auth/google
GET /auth/google/callback
```

### Santri API
```bash
# Get santri data
GET /api/admin/santri/data

# Create santri
POST /api/admin/santri
{
    "nama": "Ahmad Santri",
    "nisn": "1234567890",
    "jenis_kelamin": "Laki-laki",
    // ... other fields
}

# Update santri
PUT /api/admin/santri/{id}
```

### Pembayaran API
```bash
# Get pembayaran data
GET /api/admin/pembayaran

# Verifikasi pembayaran
POST /api/admin/pembayaran/{id}/verifikasi
{
    "status": "diverifikasi",
    "keterangan": "Pembayaran valid"
}
```

### WhatsApp API
```bash
# Send message
POST /api/whatsapp/send
{
    "to": "6281234567890",
    "message": "Hello from PPSB",
    "template": "pendaftaran_baru"
}

# Test connection
GET /api/whatsapp/test
```

## 🚀 Deployment

### Production Setup

1. **Server Requirements**
   - Ubuntu 20.04+ / CentOS 8+
   - Nginx/Apache
   - PHP 8.2+ dengan extensions
   - MySQL 8.0+ / PostgreSQL 13+
   - Redis (optional, untuk cache)

2. **Deployment Steps**
```bash
# Clone repository
git clone <repository-url>
cd ppsb

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
# Edit .env dengan production settings

# Setup database
php artisan migrate --force
php artisan db:seed --force

# Setup permissions
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Setup queue worker
php artisan queue:work --daemon
```

3. **Nginx Configuration**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/ppsb/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

4. **SSL Certificate**
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d your-domain.com
```

### Monitoring & Maintenance

1. **Log Monitoring**
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View queue logs
tail -f storage/logs/queue.log
```

2. **Database Backup**
```bash
# Create backup
mysqldump -u username -p ppsb_db > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore backup
mysql -u username -p ppsb_db < backup_file.sql
```

3. **Cache Management**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Coding Standards
- Ikuti PSR-12 untuk PHP
- Gunakan ESLint untuk JavaScript
- Tulis unit tests untuk fitur baru
- Dokumentasikan API endpoints

## 📄 Lisensi

Project ini dilisensikan di bawah MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 📞 Support

Untuk pertanyaan dan dukungan:
- Email: support@ppsb-almukmin.com
- WhatsApp: +62 812-3456-7890
- Documentation: [docs.ppsb-almukmin.com](https://docs.ppsb-almukmin.com)

## 🙏 Acknowledgments

- Laravel Team untuk framework yang luar biasa
- Vue.js Team untuk reactive framework
- Tailwind CSS untuk utility-first CSS
- Semua contributor yang telah membantu pengembangan

---

**PPSB AL-MUKMIN** - Sistem Penerimaan Peserta Santri Baru yang Modern dan Terintegrasi 🏫✨