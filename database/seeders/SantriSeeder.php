<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Santri;
use App\Models\OrangTua;
use App\Models\Dokumen;
use App\Models\Pembayaran;
use App\Models\Gelombang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SantriSeeder extends Seeder
{
    public function run(): void
    {
        // Buat gelombang aktif jika belum ada
        $gelombang = Gelombang::firstOrCreate(
            ['status' => true],
            [
                'nama' => 'Gelombang 1',
                'tanggal_mulai' => Carbon::now()->subDays(30),
                'tanggal_selesai' => Carbon::now()->addDays(30),
                'biaya_administrasi' => 500000,
                'biaya_daftar_ulang' => 2000000,
            ]
        );

        $dataSantri = [
            [
                'nama' => 'Ahmad Fadillah',
                'nisn' => '1234567890',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-05-15',
                'asal_sekolah' => 'SDN 01 Jakarta',
                'anak_ke' => 1,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Budi Santoso',
                'pekerjaan_ayah' => 'Pedagang / Wirausaha',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567890',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567891',
                'alamat' => 'Jl. Merdeka No. 123',
                'provinsi_id' => '31',      // DKI JAKARTA
                'kabupaten_id' => '3174',   // KOTA JAKARTA SELATAN
                'kecamatan_id' => '3174070', // KEBAYORAN BARU
                'desa_id' => '3174070001',   // SELONG
                'kode_pos' => '12110'
            ],
            [
                'nama' => 'Nabila Putri',
                'nisn' => '2345678901',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-06-20',
                'asal_sekolah' => 'SDN 02 Bandung',
                'anak_ke' => 2,
                'jumlah_saudara' => 3,
                'nama_ayah' => 'Rudi Hartono',
                'pekerjaan_ayah' => 'PNS / ASN',
                'pendidikan_ayah' => 'S2',
                'wa_ayah' => '081234567892',
                'nama_ibu' => 'Dewi Lestari',
                'pekerjaan_ibu' => 'Dokter',
                'pendidikan_ibu' => 'S2',
                'wa_ibu' => '081234567893',
                'alamat' => 'Jl. Asia Afrika No. 45',
                'provinsi_id' => '32',      // JAWA BARAT
                'kabupaten_id' => '3273',   // KOTA BANDUNG
                'kecamatan_id' => '3273200', // BATUNUNGGAL
                'desa_id' => '3273200003',   // KEBON WARU
                'kode_pos' => '40272'
            ],
            [
                'nama' => 'Muhammad Rizki',
                'nisn' => '3456789012',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2008-07-10',
                'asal_sekolah' => 'SDN 03 Surabaya',
                'anak_ke' => 1,
                'jumlah_saudara' => 1,
                'nama_ayah' => 'Ahmad Hidayat',
                'pekerjaan_ayah' => 'Pedagang / Wirausaha',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567894',
                'nama_ibu' => 'Fatimah Azzahra',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'pendidikan_ibu' => 'SLTA',
                'wa_ibu' => '081234567895',
                'alamat' => 'Jl. Basuki Rahmat No. 67',
                'provinsi_id' => '35',      // JAWA TIMUR
                'kabupaten_id' => '3578',   // KOTA SURABAYA
                'kecamatan_id' => '3578190', // GENTENG
                'desa_id' => '3578190001',   // KETABANG
                'kode_pos' => '60272'
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nisn' => '4567890123',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2008-08-25',
                'asal_sekolah' => 'SDN 04 Yogyakarta',
                'anak_ke' => 3,
                'jumlah_saudara' => 4,
                'nama_ayah' => 'Haji Abdul Rahman',
                'pekerjaan_ayah' => 'Pedagang / Wirausaha',
                'pendidikan_ayah' => 'SLTA',
                'wa_ayah' => '081234567896',
                'nama_ibu' => 'Hajjah Aminah',
                'pekerjaan_ibu' => 'Pedagang / Wirausaha',
                'pendidikan_ibu' => 'SLTA',
                'wa_ibu' => '081234567897',
                'alamat' => 'Jl. Malioboro No. 89',
                'provinsi_id' => '34',      // DI YOGYAKARTA
                'kabupaten_id' => '3471',   // KOTA YOGYAKARTA
                'kecamatan_id' => '3471030', // GEDONG TENGEN
                'desa_id' => '3471030002',   // SOSROMENDURAN
                'kode_pos' => '55271'
            ],
            [
                'nama' => 'Rizki Ramadhan',
                'nisn' => '5678901234',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2008-09-05',
                'asal_sekolah' => 'SDN 05 Medan',
                'anak_ke' => 2,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Suharto',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'pendidikan_ayah' => 'D3',
                'wa_ayah' => '081234567898',
                'nama_ibu' => 'Siti Aisyah',
                'pekerjaan_ibu' => 'Karyawan Swasta',
                'pendidikan_ibu' => 'D3',
                'wa_ibu' => '081234567899',
                'alamat' => 'Jl. Diponegoro No. 12',
                'provinsi_id' => '12',      // SUMATERA UTARA
                'kabupaten_id' => '1275',   // KOTA MEDAN
                'kecamatan_id' => '1275040', // MEDAN BARAT
                'desa_id' => '1275040001',   // KESAWAN
                'kode_pos' => '20111'
            ],
            [
                'nama' => 'Aisyah Putri',
                'nisn' => '6789012345',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Makassar',
                'tanggal_lahir' => '2008-10-15',
                'asal_sekolah' => 'SDN 06 Makassar',
                'anak_ke' => 1,
                'jumlah_saudara' => 1,
                'nama_ayah' => 'Andi Muhammad',
                'pekerjaan_ayah' => 'Dokter',
                'pendidikan_ayah' => 'S2',
                'wa_ayah' => '081234567900',
                'nama_ibu' => 'Andi Fatimah',
                'pekerjaan_ibu' => 'Dokter',
                'pendidikan_ibu' => 'S2',
                'wa_ibu' => '081234567901',
                'alamat' => 'Jl. Pengayoman No. 34',
                'provinsi_id' => '73',      // SULAWESI SELATAN
                'kabupaten_id' => '7371',   // KOTA MAKASSAR
                'kecamatan_id' => '7371010', // MARISO
                'desa_id' => '7371010009',   // MARISO
                'kode_pos' => '90125'
            ],
            [
                'nama' => 'Fadli Rahman',
                'nisn' => '7890123456',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Palembang',
                'tanggal_lahir' => '2008-11-20',
                'asal_sekolah' => 'SDN 07 Palembang',
                'anak_ke' => 2,
                'jumlah_saudara' => 3,
                'nama_ayah' => 'Rahmat Hidayat',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567902',
                'nama_ibu' => 'Nurul Hidayah',
                'pekerjaan_ibu' => 'Guru',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567903',
                'alamat' => 'Jl. Merdeka No. 56',
                'provinsi_id' => '16',      // SUMATERA SELATAN
                'kabupaten_id' => '1671',   // KOTA PALEMBANG
                'kecamatan_id' => '1671040', // ILIR TIMUR SATU
                'desa_id' => '1671040003',   // SUNGAI PANGERAN
                'kode_pos' => '30114'
            ],
            [
                'nama' => 'Nurul Hidayah',
                'nisn' => '8901234567',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2008-12-10',
                'asal_sekolah' => 'SDN 08 Semarang',
                'anak_ke' => 1,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Bambang Sutrisno',
                'pekerjaan_ayah' => 'PNS',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567904',
                'nama_ibu' => 'Sri Mulyani',
                'pekerjaan_ibu' => 'PNS',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567905',
                'alamat' => 'Jl. Pemuda No. 78',
                'provinsi_id' => '33',      // JAWA TENGAH
                'kabupaten_id' => '3374',   // KOTA SEMARANG
                'kecamatan_id' => '3374070', // SEMARANG TENGAH
                'desa_id' => '3374070005',   // PENDRINKAN KIDUL
                'kode_pos' => '50131'
            ],
            [
                'nama' => 'Muhammad Alif',
                'nisn' => '9012345678',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Malang',
                'tanggal_lahir' => '2009-01-15',
                'asal_sekolah' => 'SDN 09 Malang',
                'anak_ke' => 3,
                'jumlah_saudara' => 4,
                'nama_ayah' => 'Ahmad Syafiq',
                'pekerjaan_ayah' => 'Dosen',
                'pendidikan_ayah' => 'S3',
                'wa_ayah' => '081234567906',
                'nama_ibu' => 'Nurul Aini',
                'pekerjaan_ibu' => 'Dosen',
                'pendidikan_ibu' => 'S2',
                'wa_ibu' => '081234567907',
                'alamat' => 'Jl. Veteran No. 90',
                'provinsi_id' => '35',      // JAWA TIMUR
                'kabupaten_id' => '3573',   // KOTA MALANG
                'kecamatan_id' => '3573050', // KLOJEN
                'desa_id' => '3573050006',   // KLOJEN
                'kode_pos' => '65111'
            ],
            [
                'nama' => 'Zahra Aisyah',
                'nisn' => '0123456789',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Denpasar',
                'tanggal_lahir' => '2009-02-20',
                'asal_sekolah' => 'SDN 10 Denpasar',
                'anak_ke' => 2,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'I Wayan Sujana',
                'pekerjaan_ayah' => 'Pengusaha',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567908',
                'nama_ibu' => 'Ni Made Sari',
                'pekerjaan_ibu' => 'Pengusaha',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567909',
                'alamat' => 'Jl. Gatot Subroto No. 23',
                'provinsi_id' => '51',      // BALI
                'kabupaten_id' => '5171',   // KOTA DENPASAR
                'kecamatan_id' => '5171010', // DENPASAR SELATAN
                'desa_id' => '5171010006',   // RENON
                'kode_pos' => '80226'
            ],
            [
                'nama' => 'Rafi Akbar',
                'nisn' => '1122334455',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Padang',
                'tanggal_lahir' => '2009-03-25',
                'asal_sekolah' => 'SDN 11 Padang',
                'anak_ke' => 1,
                'jumlah_saudara' => 1,
                'nama_ayah' => 'Rizal Fadillah',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567910',
                'nama_ibu' => 'Siti Fatimah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'pendidikan_ibu' => 'SMA',
                'wa_ibu' => '081234567911',
                'alamat' => 'Jl. Imam Bonjol No. 45',
                'provinsi_id' => '13',      // SUMATERA BARAT
                'kabupaten_id' => '1371',   // KOTA PADANG
                'kecamatan_id' => '1371020', // PADANG BARAT
                'desa_id' => '1371020005',   // BELAKANG TANGSI
                'kode_pos' => '25112'
            ],
            [
                'nama' => 'Nabila Zahra',
                'nisn' => '2233445566',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Pekanbaru',
                'tanggal_lahir' => '2009-04-30',
                'asal_sekolah' => 'SDN 12 Pekanbaru',
                'anak_ke' => 2,
                'jumlah_saudara' => 3,
                'nama_ayah' => 'Ahmad Rizal',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567912',
                'nama_ibu' => 'Nurul Hidayah',
                'pekerjaan_ibu' => 'Guru',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567913',
                'alamat' => 'Jl. Sudirman No. 67',
                'provinsi_id' => '14',      // RIAU
                'kabupaten_id' => '1471',   // KOTA PEKANBARU
                'kecamatan_id' => '1471061', // BINAWIDYA (Dulu TAMPAN)
                'desa_id' => '1471061001',   // SIMPANG BARU
                'kode_pos' => '28292'
            ],
            [
                'nama' => 'Muhammad Fajar',
                'nisn' => '3344556677',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Manado',
                'tanggal_lahir' => '2009-05-15',
                'asal_sekolah' => 'SDN 13 Manado',
                'anak_ke' => 1,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Johan Kaseger',
                'pekerjaan_ayah' => 'Dokter',
                'pendidikan_ayah' => 'S2',
                'wa_ayah' => '081234567914',
                'nama_ibu' => 'Martha Tilaar',
                'pekerjaan_ibu' => 'Dokter',
                'pendidikan_ibu' => 'S2',
                'wa_ibu' => '081234567915',
                'alamat' => 'Jl. Sam Ratulangi No. 89',
                'provinsi_id' => '71',      // SULAWESI UTARA
                'kabupaten_id' => '7171',   // KOTA MANADO
                'kecamatan_id' => '7171020', // WENANG
                'desa_id' => '7171020002',   // WENANG SELATAN
                'kode_pos' => '95111'
            ],
            [
                'nama' => 'Aisyah Nurul',
                'nisn' => '4455667788',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Pontianak',
                'tanggal_lahir' => '2009-06-20',
                'asal_sekolah' => 'SDN 14 Pontianak',
                'anak_ke' => 3,
                'jumlah_saudara' => 4,
                'nama_ayah' => 'Budi Santoso',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567916',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'pendidikan_ibu' => 'SMA',
                'wa_ibu' => '081234567917',
                'alamat' => 'Jl. Gajah Mada No. 12',
                'provinsi_id' => '61',      // KALIMANTAN BARAT
                'kabupaten_id' => '6171',   // KOTA PONTIANAK
                'kecamatan_id' => '6171030', // PONTIANAK KOTA
                'desa_id' => '6171030005',   // BENUA MELAYU DARAT
                'kode_pos' => '78122'
            ],
            [
                'nama' => 'Rizki Pratama',
                'nisn' => '5566778899',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Balikpapan',
                'tanggal_lahir' => '2009-07-25',
                'asal_sekolah' => 'SDN 15 Balikpapan',
                'anak_ke' => 2,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Ahmad Hidayat',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567918',
                'nama_ibu' => 'Nurul Hidayah',
                'pekerjaan_ibu' => 'Guru',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567919',
                'alamat' => 'Jl. Sudirman No. 34',
                'provinsi_id' => '64',      // KALIMANTAN TIMUR
                'kabupaten_id' => '6472',   // KOTA SAMARINDA
                'kecamatan_id' => '6472010', // SUNGAI PINANG
                'desa_id' => '6472010001',   // SUNGAI PINANG DALAM
                'kode_pos' => '75117'
            ],
            [
                'nama' => 'Nabila Putri',
                'nisn' => '6677889900',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Samarinda',
                'tanggal_lahir' => '2009-08-30',
                'asal_sekolah' => 'SDN 16 Samarinda',
                'anak_ke' => 1,
                'jumlah_saudara' => 1,
                'nama_ayah' => 'Rudi Hartono',
                'pekerjaan_ayah' => 'PNS',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567920',
                'nama_ibu' => 'Dewi Lestari',
                'pekerjaan_ibu' => 'PNS',
                'pendidikan_ibu' => 'S1',
                'wa_ibu' => '081234567921',
                'alamat' => 'Jl. Pangeran Antasari No. 56',
                'provinsi_id' => '64',      // KALIMANTAN TIMUR
                'kabupaten_id' => '6472',   // KOTA SAMARINDA
                'kecamatan_id' => '6472010', // SUNGAI PINANG
                'desa_id' => '6472010001',   // SUNGAI PINANG DALAM
                'kode_pos' => '75117'
            ],
            [
                'nama' => 'Muhammad Rizki',
                'nisn' => '7788990011',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Palangkaraya',
                'tanggal_lahir' => '2009-09-05',
                'asal_sekolah' => 'SDN 17 Palangkaraya',
                'anak_ke' => 2,
                'jumlah_saudara' => 3,
                'nama_ayah' => 'Budi Santoso',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pendidikan_ayah' => 'S1',
                'wa_ayah' => '081234567922',
                'nama_ibu' => 'Siti Aminah',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'pendidikan_ibu' => 'SMA',
                'wa_ibu' => '081234567923',
                'alamat' => 'Jl. Tjilik Riwut No. 78',
                'provinsi_id' => '62',      // KALIMANTAN TENGAH
                'kabupaten_id' => '6271',   // KOTA PALANGKARAYA
                'kecamatan_id' => '6271020', // JEKAN RAYA
                'desa_id' => '6271020005',   // MENTENG
                'kode_pos' => '73111'
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nisn' => '8899001122',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Banjarmasin',
                'tanggal_lahir' => '2009-10-10',
                'asal_sekolah' => 'SDN 18 Banjarmasin',
                'anak_ke' => 3,
                'jumlah_saudara' => 4,
                'nama_ayah' => 'Haji Abdul Rahman',
                'pekerjaan_ayah' => 'Pedagang',
                'pendidikan_ayah' => 'SMA',
                'wa_ayah' => '081234567924',
                'nama_ibu' => 'Hajjah Aminah',
                'pekerjaan_ibu' => 'Pedagang',
                'pendidikan_ibu' => 'SMA',
                'wa_ibu' => '081234567925',
                'alamat' => 'Jl. Pangeran Antasari No. 90',
                'provinsi_id' => '63',      // KALIMANTAN SELATAN
                'kabupaten_id' => '6371',   // KOTA BANJARMASIN
                'kecamatan_id' => '6371030', // BANJARMASIN TENGAH
                'desa_id' => '6371030005',   // PEKAPURAN LAUT
                'kode_pos' => '70231'
            ],
            [
                'nama' => 'Rizki Ramadhan',
                'nisn' => '9900112233',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Mataram',
                'tanggal_lahir' => '2009-11-15',
                'asal_sekolah' => 'SDN 19 Mataram',
                'anak_ke' => 1,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Suharto',
                'pekerjaan_ayah' => 'Karyawan Swasta',
                'pendidikan_ayah' => 'D3',
                'wa_ayah' => '081234567926',
                'nama_ibu' => 'Siti Aisyah',
                'pekerjaan_ibu' => 'Karyawan Swasta',
                'pendidikan_ibu' => 'D3',
                'wa_ibu' => '081234567927',
                'alamat' => 'Jl. Pejanggik No. 12',
                'provinsi_id' => '52',      // NUSA TENGGARA BARAT
                'kabupaten_id' => '5271',   // KOTA MATARAM
                'kecamatan_id' => '5271030', // SELAPARANG
                'desa_id' => '5271030001',   // REMBIGA
                'kode_pos' => '83124'
            ],
            [
                'nama' => 'Aisyah Putri',
                'nisn' => '0011223344',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Kupang',
                'tanggal_lahir' => '2009-12-20',
                'asal_sekolah' => 'SDN 20 Kupang',
                'anak_ke' => 2,
                'jumlah_saudara' => 2,
                'nama_ayah' => 'Andi Muhammad',
                'pekerjaan_ayah' => 'Dokter',
                'pendidikan_ayah' => 'S2',
                'wa_ayah' => '081234567928',
                'nama_ibu' => 'Andi Fatimah',
                'pekerjaan_ibu' => 'Dokter',
                'pendidikan_ibu' => 'S2',
                'wa_ibu' => '081234567929',
                'alamat' => 'Jl. Soeharto No. 34',
                'provinsi_id' => '53',      // NUSA TENGGARA TIMUR
                'kabupaten_id' => '5371',   // KOTA KUPANG
                'kecamatan_id' => '5371040', // KELAPA LIMA
                'desa_id' => '5371040001',   // OESAPA
                'kode_pos' => '85228'
            ]
        ];

        // Format nomor WhatsApp ayah & ibu ke 62...
        foreach ($dataSantri as &$data) {
            if (isset($data['wa_ayah']) && strpos($data['wa_ayah'], '08') === 0) {
                $data['wa_ayah'] = '62' . substr($data['wa_ayah'], 1);
            }
            if (isset($data['wa_ibu']) && strpos($data['wa_ibu'], '08') === 0) {
                $data['wa_ibu'] = '62' . substr($data['wa_ibu'], 1);
            }
        }
        unset($data);

        foreach ($dataSantri as $index => $data) {
            // Generate nomor pendaftaran
            $tahun = date('Y');
            $count = $index + 1;
            $randomString = strtoupper(Str::random(4));
            $nomorPendaftaran = "PPSB-PPIN-{$tahun}-" . str_pad($count, 4, '0', STR_PAD_LEFT) . "-{$randomString}";

            // Buat data santri
            $santri = Santri::create([
                'nomor_pendaftaran' => $nomorPendaftaran,
                'unit' => 'ppim',
                'nama' => $data['nama'],
                'nisn' => $data['nisn'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'asal_sekolah' => $data['asal_sekolah'],
                'anak_ke' => $data['anak_ke'],
                'jumlah_saudara' => $data['jumlah_saudara'],
                'status_tes' => 'Menunggu Tes',
                'gelombang_id' => $gelombang->id,
            ]);

            // Buat data orang tua
            OrangTua::create([
                'santri_id' => $santri->id,
                'nama_ayah' => $data['nama_ayah'],
                'nama_ibu' => $data['nama_ibu'],
                'pekerjaan_ayah' => $data['pekerjaan_ayah'],
                'pekerjaan_ibu' => $data['pekerjaan_ibu'],
                'pendidikan_ayah' => $data['pendidikan_ayah'],
                'pendidikan_ibu' => $data['pendidikan_ibu'],
                'wa_ayah' => $data['wa_ayah'],
                'wa_ibu' => $data['wa_ibu'],
                'alamat' => $data['alamat'],
                'provinsi_id' => $data['provinsi_id'],
                'kabupaten_id' => $data['kabupaten_id'],
                'kecamatan_id' => $data['kecamatan_id'],
                'desa_id' => $data['desa_id'],
                'kode_pos' => $data['kode_pos'],
            ]);

            // Buat data pembayaran
            Pembayaran::create([
                'santri_id' => $santri->id,
                'biaya_administrasi' => $gelombang->biaya_administrasi,
                'biaya_daftar_ulang' => $gelombang->biaya_daftar_ulang,
                'status_administrasi' => 'belum_bayar',
                'status_daftar_ulang' => 'belum_bayar',
            ]);

            // Buat dokumen dummy untuk santri
            $this->createDummyDocuments($santri->id);
        }
    }

    /**
     * Membuat dokumen dummy untuk santri
     */
    private function createDummyDocuments($santriId)
    {
        // Copy logo PPIN sebagai dokumen dummy
        $sourceLogo = 'public/img/logo-ppin.png';

        // Buat direktori jika belum ada
        $directories = [
            'public/storage/pasfoto',
            'public/storage/akta_lahir',
            'public/storage/kartu_keluarga',
            'public/storage/ijazah',
            'public/storage/dokumen_pendukung',
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        // Copy logo ke masing-masing direktori dokumen
        $pasfoto = 'pasfoto/pasfoto_' . $santriId . '.png';
        $aktaLahir = 'akta_lahir/akta_lahir_' . $santriId . '.png';
        $kartuKeluarga = 'kartu_keluarga/kartu_keluarga_' . $santriId . '.png';
        $ijazah = 'ijazah/ijazah_' . $santriId . '.png';

        // Copy file logo ke direktori dokumen
        if (File::exists($sourceLogo)) {
            File::copy($sourceLogo, 'public/storage/' . $pasfoto);
            File::copy($sourceLogo, 'public/storage/' . $aktaLahir);
            File::copy($sourceLogo, 'public/storage/' . $kartuKeluarga);
            File::copy($sourceLogo, 'public/storage/' . $ijazah);
        }

        // Simpan data dokumen ke database
        Dokumen::create([
            'santri_id' => $santriId,
            'pasfoto' => $pasfoto,
            'akta_lahir' => $aktaLahir,
            'kartu_keluarga' => $kartuKeluarga,
            'ijazah' => $ijazah,
            'dokumen_pendukung' => null
        ]);
    }
}
