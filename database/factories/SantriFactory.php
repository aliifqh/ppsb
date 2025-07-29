<?php

namespace Database\Factories;

use App\Models\Santri;
use App\Models\Gelombang;
use App\Models\OrangTua;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SantriFactory extends Factory
{
    protected $model = Santri::class;

    public function definition(): array
    {
        $jenisKelamin = $this->faker->randomElement(['Laki-laki', 'Perempuan']);
        $nama = $jenisKelamin === 'Laki-laki' 
            ? $this->faker->firstNameMale() . ' ' . $this->faker->lastName()
            : $this->faker->firstNameFemale() . ' ' . $this->faker->lastName();

        $tahun = date('Y');
        $count = Santri::whereYear('created_at', $tahun)->count() + 1;
        $randomString = strtoupper(Str::random(4));
        $nomorPendaftaran = "PPSB-PPIN-{$tahun}-" . str_pad($count, 4, '0', STR_PAD_LEFT) . "-{$randomString}";

        // Data untuk OrangTua
        $pendidikan = ['SD', 'SLTP', 'SLTA', 'Diploma', 'S1', 'S2', 'S3'];
        $pekerjaan = ['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Dokter', 'Guru', 'Pedagang', 'Dosen', 'Ibu Rumah Tangga'];

        // Data untuk Pembayaran
        $statusPembayaran = ['belum_bayar', 'sudah_bayar', 'verifikasi'];
        $statusDaftarUlang = ['belum_bayar', 'sudah_bayar', 'verifikasi'];

        // Ambil gelombang yang aktif
        $gelombang = Gelombang::where('status', true)->first();
        if (!$gelombang) {
            // Jika tidak ada gelombang aktif, buat gelombang baru
            $gelombang = Gelombang::create([
                'nama' => 'Gelombang 1',
                'tanggal_mulai' => Carbon::now()->subDays(30),
                'tanggal_selesai' => Carbon::now()->addDays(30),
                'biaya_administrasi' => 500000,
                'biaya_daftar_ulang' => 2000000,
                'status' => true,
            ]);
        }

        return [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'unit' => $this->faker->randomElement(['ppim', 'tks']),
            'nama' => $nama,
            'nisn' => $this->faker->unique()->numerify('##########'),
            'jenis_kelamin' => $jenisKelamin,
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-15 years', '-10 years'),
            'asal_sekolah' => 'SDN ' . $this->faker->numberBetween(1, 100) . ' ' . $this->faker->city(),
            'anak_ke' => $this->faker->numberBetween(1, 5),
            'jumlah_saudara' => $this->faker->numberBetween(0, 4),
            'status_tes' => $this->faker->randomElement(['Menunggu Tes', 'Lulus', 'Tidak Lulus']),
            'gelombang_id' => $gelombang->id,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Santri $santri) {
            // Buat data OrangTua
            OrangTua::create([
                'santri_id' => $santri->id,
                'nama_ayah' => $this->faker->name('male'),
                'nama_ibu' => $this->faker->name('female'),
                'pekerjaan_ayah' => $this->faker->randomElement(['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Dokter', 'Guru', 'Pedagang', 'Dosen', 'Ibu Rumah Tangga']),
                'pekerjaan_ibu' => $this->faker->randomElement(['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Dokter', 'Guru', 'Pedagang', 'Dosen', 'Ibu Rumah Tangga']),
                'pekerjaan_ayah_lainnya' => null,
                'pekerjaan_ibu_lainnya' => null,
                'pendidikan_ayah' => $this->faker->randomElement(['SD', 'SLTP', 'SLTA', 'Diploma', 'S1', 'S2', 'S3']),
                'pendidikan_ibu' => $this->faker->randomElement(['SD', 'SLTP', 'SLTA', 'Diploma', 'S1', 'S2', 'S3']),
                'wa_ayah' => $this->faker->numerify('08##########'),
                'wa_ibu' => $this->faker->numerify('08##########'),
                'alamat' => $this->faker->address(),
                'provinsi_id' => $this->faker->state(),
                'kabupaten_id' => $this->faker->city(),
                'kecamatan_id' => $this->faker->city(),
                'desa_id' => $this->faker->city(),
                'kode_pos' => $this->faker->numerify('#####'),
            ]);

            // Buat data Pembayaran
            Pembayaran::create([
                'santri_id' => $santri->id,
                'biaya_administrasi' => $santri->gelombang->biaya_administrasi,
                'biaya_daftar_ulang' => $santri->gelombang->biaya_daftar_ulang,
                'status_administrasi' => $this->faker->randomElement(['belum_bayar', 'sudah_bayar', 'verifikasi']),
                'status_daftar_ulang' => $this->faker->randomElement(['belum_bayar', 'sudah_bayar', 'verifikasi']),
                'bukti_administrasi' => null,
                'bukti_daftar_ulang' => null,
                'keterangan_administrasi' => null,
                'keterangan_daftar_ulang' => null,
            ]);
        });
    }
} 