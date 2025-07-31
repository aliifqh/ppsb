# Migrasi ke Single Page Application (SPA)

## Perubahan yang Telah Dilakukan

### 1. Struktur Router Vue
- **File**: `resources/js/router.js`
- **Perubahan**: Menggunakan nested routes dengan layout wrapper
- **Layout**: 
  - `AppLayout.vue` untuk halaman publik dan santri
  - `AdminLayout.vue` untuk halaman admin

### 2. Layout Vue
- **AppLayout.vue**: Layout utama dengan navigasi publik
- **AdminLayout.vue**: Layout admin dengan sidebar dan navigasi admin

### 3. Route Laravel
- **File**: `routes/web.php`
- **Perubahan**: Menghapus semua route view, hanya menyisakan API routes
- **Catch-all**: Semua request diarahkan ke SPA

### 4. App.js
- **File**: `resources/js/app.js`
- **Perubahan**: Menyederhanakan menjadi SPA tunggal
- **Mount**: Hanya mount ke element `#app`

### 5. Layout Blade
- **File**: `resources/views/layouts/app.blade.php`
- **Perubahan**: Hanya menampilkan container untuk Vue SPA

## Struktur SPA

```
/ (AppLayout)
├── / (Home)
├── /formulir (Formulir)
├── /login (Login Admin)
└── /login-santri (Login Santri)

/admin (AdminLayout)
├── /admin/dashboard
├── /admin/santri
├── /admin/pembayaran
├── /admin/gelombang
├── /admin/roles
└── /admin/roles-permissions

/santri (AppLayout)
├── /santri/data/:nomor_pendaftaran
├── /santri/pembayaran/:nomor_pendaftaran
└── /santri/ujian/:nomor_pendaftaran
```

## Keuntungan SPA

1. **Navigasi Cepat**: Tidak perlu reload halaman
2. **UX Lebih Baik**: Transisi halus antar halaman
3. **State Management**: State aplikasi terjaga
4. **Offline Capability**: Bisa ditambahkan PWA features
5. **Code Splitting**: Bisa dioptimasi dengan lazy loading

## API Routes yang Tetap Berfungsi

Semua API routes tetap berfungsi seperti sebelumnya:
- `/api/admin/*` - API untuk admin
- `/santri/*` - API untuk santri
- `/payment/*` - API untuk pembayaran
- `/formulir/store` - API untuk formulir

## Cara Menjalankan

1. **Development**: `npm run dev`
2. **Production**: `npm run build`
3. **Server**: `php artisan serve`

## Catatan Penting

- Semua halaman sekarang dihandle oleh Vue Router
- Backend hanya menyediakan API
- Authentication tetap menggunakan Laravel session
- CSRF token tetap diperlukan untuk POST requests 
