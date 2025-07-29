<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Helpers\DateHelper;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class Santri extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'santri';

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
        'status_tes',
        'gelombang_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'anak_ke' => 'integer',
        'jumlah_saudara' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::forceDeleted(function($santri) {
            if ($santri->pasfoto) {
                Storage::delete($santri->pasfoto);
            }
            if ($santri->akta_lahir) {
                Storage::delete($santri->akta_lahir);
            }
            if ($santri->kartu_keluarga) {
                Storage::delete($santri->kartu_keluarga);
            }
            if ($santri->ijazah) {
                Storage::delete($santri->ijazah);
            }
            if ($santri->dokumen_pendukung) {
                foreach ($santri->dokumen_pendukung as $dokumen) {
                    Storage::delete($dokumen);
                }
            }
            if ($santri->bukti_pembayaran) {
                Storage::delete($santri->bukti_pembayaran);
            }

            // Hapus file bukti pembayaran jika ada
            if ($santri->pembayaran) {
                if ($santri->pembayaran->bukti_biaya_administrasi) {
                    Storage::delete($santri->pembayaran->bukti_biaya_administrasi);
                }
                if ($santri->pembayaran->bukti_biaya_daftar_ulang) {
                    Storage::delete($santri->pembayaran->bukti_biaya_daftar_ulang);
                }
            }
        });

        static::deleting(function($santri) {
            // Hapus data pembayaran terkait
            $santri->pembayaran()->delete();
        });

        static::creating(function ($santri) {
            // Set default values if not set
            if (!$santri->status_tes) {
                $santri->status_tes = 'Menunggu Tes';
            }
        });
    }

    /**
     * Get formatted tanggal lahir khusus untuk tampilan
     */
    public function getTanggalLahirFormattedAttribute()
    {
        return $this->tanggal_lahir ? DateHelper::toIndonesianFormat($this->tanggal_lahir) : null;
    }

    /**
     * Validation rules untuk pendaftaran santri
     */
    public static function rules()
    {
        return [
            'unit' => 'required|in:ppim,tks',
            'nama' => 'required|string|max:100',
            'nisn' => 'required|string|size:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'asal_sekolah' => 'required|string|max:100',
            'anak_ke' => 'required|integer|min:1',
            'jumlah_saudara' => 'required|integer|min:0',
            'nama_ayah' => 'required|string|max:100',
            'pekerjaan_ayah' => 'required|string',
            'pekerjaan_ayah_lainnya' => 'nullable|string|max:100',
            'pendidikan_ayah' => 'required|in:SD,SLTP,SLTA,Diploma,S1,S2,S3',
            'wa_ayah' => 'required|string|regex:/^[0-9]{10,15}$/',
            'nama_ibu' => 'required|string|max:100',
            'pekerjaan_ibu' => 'required|string',
            'pekerjaan_ibu_lainnya' => 'nullable|string|max:100',
            'pendidikan_ibu' => 'required|in:SD,SLTP,SLTA,Diploma,S1,S2,S3',
            'wa_ibu' => 'required|string|regex:/^[0-9]{10,15}$/',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kode_pos' => 'required|string|size:5',
            'pasfoto' => 'required|image|max:3072|mimes:jpg,jpeg,png',
            'akta_lahir' => 'required|max:3072|mimes:jpg,jpeg,png,pdf',
            'kartu_keluarga' => 'required|max:3072|mimes:jpg,jpeg,png,pdf',
            'ijazah' => 'required|max:3072|mimes:jpg,jpeg,png,pdf',
            'dokumen_pendukung.*' => 'nullable|max:3072|mimes:jpg,jpeg,png,pdf'
        ];
    }

    /**
     * Custom messages untuk validasi
     */
    public static function messages()
    {
        return [
            'required' => ':attribute wajib diisi',
            'in' => ':attribute tidak valid',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min',
            'size' => ':attribute harus :size karakter',
            'integer' => ':attribute harus berupa angka',
            'string' => ':attribute harus berupa teks',
            'date' => ':attribute harus berupa tanggal yang valid',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berformat :values',
            'regex' => ':attribute tidak valid',
            'wa_ayah.regex' => 'Nomor WhatsApp Ayah tidak valid',
            'wa_ibu.regex' => 'Nomor WhatsApp Ibu tidak valid',
            'pasfoto.max' => 'Ukuran pasfoto maksimal 3MB',
            'akta_lahir.max' => 'Ukuran akta lahir maksimal 3MB',
            'kartu_keluarga.max' => 'Ukuran kartu keluarga maksimal 3MB',
            'ijazah.max' => 'Ukuran ijazah maksimal 3MB',
            'dokumen_pendukung.*.max' => 'Ukuran dokumen pendukung maksimal 3MB'
        ];
    }

    /**
     * Get file URL attributes
     */
    public function getPasfotoUrlAttribute()
    {
        return $this->pasfoto ? Storage::url($this->pasfoto) : null;
    }

    public function getAktaLahirUrlAttribute()
    {
        return $this->akta_lahir ? Storage::url($this->akta_lahir) : null;
    }

    public function getKartuKeluargaUrlAttribute()
    {
        return $this->kartu_keluarga ? Storage::url($this->kartu_keluarga) : null;
    }

    public function getIjazahUrlAttribute()
    {
        return $this->ijazah ? Storage::url($this->ijazah) : null;
    }

    public function getDokumenPendukungUrlsAttribute()
    {
        if (!$this->dokumen_pendukung) return [];
        return array_map(function($path) {
            return Storage::url($path);
        }, $this->dokumen_pendukung);
    }

    // Relasi dengan tabel orang_tua
    public function orangTua()
    {
        return $this->hasOne(OrangTua::class);
    }

    // Relasi dengan tabel dokumen
    public function dokumen()
    {
        return $this->hasOne(Dokumen::class);
    }

    // Relasi dengan tabel pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    // Relasi dengan tabel gelombang
    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function magicTokens()
    {
        return $this->hasMany(\App\Models\SantriMagicToken::class);
    }
}
