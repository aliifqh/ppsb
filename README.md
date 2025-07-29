<div align="center">

TES GIT BRANCH
# 🏫 PPSB AL-MUKMIN
### Sistem Penerimaan Peserta Santri Baru

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

**Sistem informasi penerimaan peserta santri baru yang modern dan terintegrasi untuk pesantren AL-MUKMIN** 🚀

[![GitHub stars](https://img.shields.io/github/stars/aliifqh/ppsb?style=social)](https://github.com/aliifqh/ppsb/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/aliifqh/ppsb?style=social)](https://github.com/aliifqh/ppsb/network)
[![GitHub issues](https://img.shields.io/github/issues/aliifqh/ppsb)](https://github.com/aliifqh/ppsb/issues)
[![GitHub pull requests](https://img.shields.io/github/issues-pr/aliifqh/ppsb)](https://github.com/aliifqh/ppsb/pulls)

</div>

---

## 📋 Table of Contents

- [✨ Features](#-features)
- [🛠 Tech Stack](#-tech-stack)
- [🚀 Quick Start](#-quick-start)
- [📱 Screenshots](#-screenshots)
- [🏗 Architecture](#-architecture)
- [🔧 Configuration](#-configuration)
- [📖 Documentation](#-documentation)
- [🤝 Contributing](#-contributing)
- [📄 License](#-license)

---

## ✨ Features

### 🎯 Public Area
| Feature | Description | Status |
|---------|-------------|--------|
| 🏠 **Homepage** | Landing page dengan informasi pendaftaran | ✅ |
| 📝 **Registration Form** | Form pendaftaran 4 tahap dengan validasi real-time | ✅ |
| 🔍 **Status Check** | Login santri untuk melihat status pendaftaran | ✅ |
| 💳 **Public Payment** | Upload bukti pembayaran tanpa login | ✅ |

### 👨‍🎓 Student Portal
| Feature | Description | Status |
|---------|-------------|--------|
| 📊 **Dashboard** | Overview status pendaftaran dan alur | ✅ |
| 👤 **Personal Data** | Lihat dan edit data pendaftaran | ✅ |
| 💰 **Payment** | Status pembayaran dan upload bukti | ✅ |
| 📚 **Exam** | Informasi jadwal dan hasil tes masuk | ✅ |
| 🖨️ **Print Documents** | Cetak formulir dan bukti pendaftaran | ✅ |
| 🔐 **Magic Login** | Login otomatis via token | ✅ |

### 👨‍💼 Admin Panel
| Feature | Description | Status |
|---------|-------------|--------|
| 📈 **Dashboard** | Statistik pendaftaran dan monitoring | ✅ |
| 👥 **Student Management** | CRUD data santri dengan soft delete | ✅ |
| 💳 **Payment Management** | Verifikasi dan kelola pembayaran | ✅ |
| 📅 **Wave Management** | Atur periode pendaftaran | ✅ |
| 🔐 **Role & Permission** | Manajemen user dan hak akses | ✅ |
| 🔔 **Notifications** | Sistem notifikasi real-time | ✅ |
| 📱 **WhatsApp Integration** | Kirim notifikasi otomatis | ✅ |
| 📊 **Data Export** | Export data ke Excel/PDF | ✅ |

### 🔧 Technical Features
| Feature | Description | Status |
|---------|-------------|--------|
| 📱 **Multi-Provider WhatsApp** | Support 8 provider WhatsApp API | ✅ |
| 📁 **File Management** | Upload dan preview dokumen | ✅ |
| ⚡ **Real-time Validation** | Validasi form dengan Alpine.js | ✅ |
| 📱 **Responsive Design** | Mobile-first dengan Tailwind CSS | ✅ |
| 🚀 **Progressive Web App** | SPA dengan Vue.js dan Inertia | ✅ |
| 📝 **Activity Logging** | Audit trail untuk semua aktivitas | ✅ |

---

## 🛠 Tech Stack

### Backend
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13+-336791?style=flat-square&logo=postgresql)

### Frontend
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat-square&logo=vue.js)
![Inertia.js](https://img.shields.io/badge/Inertia.js-2.x-000000?style=flat-square)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=flat-square&logo=tailwind-css)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square)
![Vite](https://img.shields.io/badge/Vite-6.x-646CFF?style=flat-square&logo=vite)

### Third Party Services
![Google OAuth](https://img.shields.io/badge/Google_OAuth-4285F4?style=flat-square&logo=google)
![WhatsApp API](https://img.shields.io/badge/WhatsApp_API-25D366?style=flat-square&logo=whatsapp)
![Jitsi Meet](https://img.shields.io/badge/Jitsi_Meet-97979A?style=flat-square&logo=jitsi)

### Development Tools
![Composer](https://img.shields.io/badge/Composer-885630?style=flat-square&logo=composer)
![npm](https://img.shields.io/badge/npm-CB3837?style=flat-square&logo=npm)
![Git](https://img.shields.io/badge/Git-F05032?style=flat-square&logo=git)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker)

---

## 🚀 Quick Start

### Prerequisites
- [PHP 8.2+](https://php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [Node.js 18+](https://nodejs.org/)
- [MySQL 8.0+](https://dev.mysql.com/downloads/) or [PostgreSQL 13+](https://www.postgresql.org/download/)

### Installation

```bash
# 1. Clone repository
git clone https://github.com/aliifqh/ppsb.git
cd ppsb

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppsb_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 6. Run migrations and seeders
php artisan migrate
php artisan db:seed

# 7. Build assets
npm run build

# 8. Setup storage link
php artisan storage:link

# 9. Start development server
php artisan serve
```

### Development Commands

```bash
# Start development environment
composer run dev

# Run tests
composer test

# Code formatting
composer format

# Static analysis
composer analyze
```

---

## 📱 Screenshots

<div align="center">

### 🏠 Homepage
![Homepage](https://via.placeholder.com/800x400/4F46E5/FFFFFF?text=Homepage+Screenshot)

### 👨‍💼 Admin Dashboard
![Admin Dashboard](https://via.placeholder.com/800x400/059669/FFFFFF?text=Admin+Dashboard)

### 👨‍🎓 Student Portal
![Student Portal](https://via.placeholder.com/800x400/DC2626/FFFFFF?text=Student+Portal)

### 📱 Mobile Responsive
![Mobile View](https://via.placeholder.com/400x600/7C3AED/FFFFFF?text=Mobile+View)

</div>

---

## 🏗 Architecture

```
ppsb/
├── 📁 app/
│   ├── 🎮 Console/Commands/     # Artisan commands
│   ├── 📡 Events/              # Event classes
│   ├── 📊 Exports/             # Excel export classes
│   ├── 🛠️ Helpers/             # Helper functions
│   ├── 🌐 Http/
│   │   ├── 🎯 Controllers/     # Controller classes
│   │   │   ├── 👨‍💼 Admin/     # Admin controllers
│   │   │   ├── 🔐 Auth/        # Authentication
│   │   │   └── 👨‍🎓 Santri/    # Student controllers
│   │   └── 🛡️ Middleware/      # Custom middleware
│   ├── 📋 Models/              # Eloquent models
│   ├── 👀 Observers/           # Model observers
│   ├── ⚙️ Providers/           # Service providers
│   ├── 🔧 Services/            # Business logic services
│   └── 🧬 Traits/              # Reusable traits
├── ⚙️ config/                  # Configuration files
├── 🗄️ database/
│   ├── 🏭 factories/           # Model factories
│   ├── 🚀 migrations/          # Database migrations
│   └── 🌱 seeders/             # Database seeders
├── 🌐 public/                  # Public assets
├── 📦 resources/
│   ├── 🎨 js/
│   │   ├── 🧩 components/      # Vue components
│   │   │   ├── 👨‍💼 admin/     # Admin components
│   │   │   ├── 🔧 common/      # Shared components
│   │   │   └── 👨‍🎓 santri/    # Student components
│   │   ├── 🔌 services/        # API services
│   │   └── 🛣️ router.js        # Vue router
│   ├── 🎨 css/                 # Stylesheets
│   └── 📄 views/               # Blade templates
├── 🛣️ routes/                  # Route definitions
├── 💾 storage/                 # File storage
└── 🧪 tests/                   # Test files
```

---

## 🔧 Configuration

### Environment Variables

<details>
<summary>📋 Database Configuration</summary>

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppsb_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

</details>

<details>
<summary>📧 Mail Configuration</summary>

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@ppsb-almukmin.com"
MAIL_FROM_NAME="PPSB AL-MUKMIN"
```

</details>

<details>
<summary>🔐 Google OAuth</summary>

```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

</details>

<details>
<summary>📱 WhatsApp Configuration</summary>

```env
WHATSAPP_PROVIDER=fonnte
FONNTE_TOKEN=your_fonnte_token
FONNTE_DEVICE=your_device_id
```

</details>

### File Permissions

```bash
# Set proper permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

## 📖 Documentation

### API Endpoints

<details>
<summary>🔐 Authentication</summary>

```bash
# Login
POST /api/auth/login
{
    "email": "admin@example.com",
    "password": "password"
}

# Google OAuth
GET /api/auth/google
GET /api/auth/google/callback
```

</details>

<details>
<summary>👥 Student Management</summary>

```bash
# Get students data
GET /api/admin/students

# Create student
POST /api/admin/students
{
    "name": "Ahmad Santri",
    "nisn": "1234567890",
    "gender": "male"
}

# Update student
PUT /api/admin/students/{id}
```

</details>

<details>
<summary>💳 Payment Management</summary>

```bash
# Get payments
GET /api/admin/payments

# Verify payment
POST /api/admin/payments/{id}/verify
{
    "status": "verified",
    "notes": "Payment valid"
}
```

</details>

<details>
<summary>📱 WhatsApp API</summary>

```bash
# Send message
POST /api/whatsapp/send
{
    "to": "6281234567890",
    "message": "Hello from PPSB",
    "template": "registration_new"
}

# Test connection
GET /api/whatsapp/test
```

</details>

### Usage Guide

<details>
<summary>👨‍💼 Admin Panel</summary>

1. **Login Admin**
   - Akses `/admin`
   - Login dengan kredensial admin

2. **Dashboard**
   - Lihat statistik pendaftaran
   - Monitor pembayaran
   - Notifikasi real-time

3. **Student Management**
   - Tambah/edit data santri
   - Verifikasi dokumen
   - Export data

4. **Payment Management**
   - Verifikasi bukti pembayaran
   - Update status pembayaran
   - Generate invoice

5. **Wave Management**
   - Buat periode pendaftaran
   - Set biaya administrasi
   - Aktifkan/nonaktifkan gelombang

</details>

<details>
<summary>👨‍🎓 Student Portal</summary>

1. **Registration**
   - Akses `/formulir`
   - Isi formulir 4 tahap
   - Upload dokumen

2. **Status Check**
   - Akses `/cek-pendaftaran`
   - Login dengan nomor pendaftaran
   - Lihat status dan alur

3. **Dashboard**
   - Overview status pendaftaran
   - Upload bukti pembayaran
   - Lihat jadwal ujian

</details>

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. 🍴 **Fork** the repository
2. 🌿 **Create** a feature branch (`git checkout -b feature/AmazingFeature`)
3. 💾 **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. 📤 **Push** to the branch (`git push origin feature/AmazingFeature`)
5. 🔄 **Open** a Pull Request

### Development Guidelines

- ✅ Follow PSR-12 for PHP code
- ✅ Use ESLint for JavaScript
- ✅ Write unit tests for new features
- ✅ Document API endpoints
- ✅ Keep commits atomic and meaningful

### Code of Conduct

Please read our [Code of Conduct](CODE_OF_CONDUCT.md) to keep our community approachable and respectable.

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 📞 Support & Contact

<div align="center">

### 📧 Email
[![Email](https://img.shields.io/badge/Email-support%40ppsb--almukmin.com-blue?style=for-the-badge&logo=gmail)](mailto:support@ppsb-almukmin.com)

### 📱 WhatsApp
[![WhatsApp](https://img.shields.io/badge/WhatsApp-+62%20812--3456--7890-green?style=for-the-badge&logo=whatsapp)](https://wa.me/6281234567890)

### 📚 Documentation
[![Documentation](https://img.shields.io/badge/Documentation-docs.ppsb--almukmin.com-orange?style=for-the-badge&logo=read-the-docs)](https://docs.ppsb-almukmin.com)

### 🐛 Issues
[![GitHub Issues](https://img.shields.io/badge/GitHub%20Issues-Report%20Bug-red?style=for-the-badge&logo=github)](https://github.com/aliifqh/ppsb/issues)

</div>

---

## 🙏 Acknowledgments

<div align="center">

**Special thanks to:**

- [Laravel Team](https://laravel.com) for the amazing framework
- [Vue.js Team](https://vuejs.org) for the reactive framework
- [Tailwind CSS](https://tailwindcss.com) for utility-first CSS
- [Inertia.js](https://inertiajs.com) for SPA without API
- All contributors who helped develop this project

</div>

---

<div align="center">

**Made with ❤️ for Pesantren AL-MUKMIN**

[![GitHub](https://img.shields.io/badge/GitHub-aliifqh%2Fppsb-black?style=for-the-badge&logo=github)](https://github.com/aliifqh/ppsb)

**PPSB AL-MUKMIN** - Modern & Integrated Student Registration System 🏫✨

</div>
