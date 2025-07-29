# Wireframe PPSB (Penerimaan Peserta Santri Baru)

Kumpulan wireframe untuk sistem pendaftaran santri baru di pesantren. Wireframe ini dibuat sebagai panduan visual untuk pengembangan sistem PPSB.

## Struktur Wireframe

Wireframe dibagi menjadi 3 area utama:

### 1. Area Publik
- **publik-homepage.html** - Halaman utama website
- **publik-formulir.html** - Form pendaftaran santri baru (3 tahap)
- **publik-konfirmasi.html** - Halaman setelah berhasil mendaftar
- **publik-cek-status.html** - Halaman untuk login dan cek status pendaftaran
- **publik-pembayaran.html** - Halaman untuk upload bukti pembayaran

### 2. Area Santri (User)
- **santri-dashboard.html** - Dashboard utama santri setelah login
- **santri-data.html** - Halaman detail data pribadi santri
- **santri-pembayaran.html** - Halaman informasi dan manajemen pembayaran
- **santri-ujian.html** - Halaman informasi ujian dan hasil tes
- **santri-cetak.html** - Halaman untuk mencetak berbagai dokumen

### 3. Area Admin
- **admin-dashboard.html** - Dashboard utama admin dengan statistik
- **admin-santri.html** - Halaman kelola data santri
- **admin-detail-santri.html** - Halaman detail data santri
- **admin-pembayaran.html** - Halaman kelola pembayaran santri
- **admin-detail-pembayaran.html** - Halaman detail dan verifikasi pembayaran
- **admin-gelombang.html** - Halaman kelola gelombang pendaftaran
- **admin-form-gelombang.html** - Form tambah/edit gelombang
- **admin-whatsapp-config.html** - Konfigurasi WhatsApp API
- **admin-whatsapp-template.html** - Manajemen template pesan WhatsApp
- **admin-whatsapp-admin.html** - Manajemen admin penerima notifikasi
- **admin-whatsapp-broadcast.html** - Pengiriman pesan broadcast

## Cara Menggunakan

1. Buka file `index.html` untuk melihat daftar semua wireframe
2. Klik pada wireframe yang ingin dilihat
3. Gunakan tombol "Kembali ke Daftar Wireframe" untuk kembali ke halaman index

## Fitur Utama

- **Pendaftaran Online**: Form pendaftaran multi-tahap dengan validasi
- **Manajemen Pembayaran**: Upload bukti dan verifikasi pembayaran
- **Notifikasi WhatsApp**: Integrasi dengan WhatsApp untuk notifikasi otomatis
- **Panel Admin**: Manajemen data santri, pembayaran, dan gelombang pendaftaran
- **Dashboard**: Statistik dan informasi ringkas untuk admin dan santri

## Rekomendasi Langkah Selanjutnya

1. **Implementasi UI Sesungguhnya**: Mengembangkan UI sesungguhnya berdasarkan wireframe dengan Laravel + Tailwind CSS
2. **Pengembangan Backend**: Membuat struktur database, model, controller, dan API sesuai dengan fitur yang digambarkan dalam wireframe
3. **Integrasi WhatsApp API**: Mengimplementasikan sistem notifikasi WhatsApp sesuai dengan wireframe
4. **User Testing**: Melakukan pengujian dengan pengguna untuk mendapatkan feedback
5. **Dokumentasi Flow Penggunaan**: Membuat dokumentasi lengkap tentang alur penggunaan sistem

## Catatan

Wireframe ini hanya panduan visual dan tidak mencakup logika bisnis atau implementasi teknis. Pengembang perlu menyesuaikan implementasi dengan kebutuhan spesifik sistem PPSB. 