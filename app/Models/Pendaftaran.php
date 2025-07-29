<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'nomor_pendaftaran',
        'unit',
        'nama',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_sekolah',
        'anak_ke',
        'jumlah_saudara',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ayah_lainnya',
        'pekerjaan_ibu',
        'pekerjaan_ibu_lainnya',
        'pendidikan_ayah',
        'pendidikan_ibu',
        'wa_ayah',
        'wa_ibu',
        'alamat',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'kode_pos',
        'pasfoto',
        'akta_lahir',
        'kartu_keluarga',
        'ijazah',
        'dokumen_pendukung'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'anak_ke' => 'integer',
        'jumlah_saudara' => 'integer',
        'dokumen_pendukung' => 'array'
    ];
}
