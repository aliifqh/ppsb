<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembayaran';

    protected $fillable = [
        'santri_id',
        'biaya_administrasi',
        'biaya_daftar_ulang',
        'status_administrasi',
        'status_daftar_ulang',
        'bukti_biaya_administrasi',
        'bukti_biaya_daftar_ulang',
        'bukti_biaya_administrasi_admin',
        'bukti_biaya_daftar_ulang_admin',
        'tanggal_administrasi',
        'tanggal_daftar_ulang',
        'nominal_transfer_administrasi',
        'nominal_transfer_daftar_ulang',
        'keterangan_administrasi',
        'keterangan_daftar_ulang'
    ];

    protected $casts = [
        'biaya_administrasi' => 'decimal:2',
        'biaya_daftar_ulang' => 'decimal:2',
        'nominal_transfer_administrasi' => 'decimal:2',
        'nominal_transfer_daftar_ulang' => 'decimal:2',
        'tanggal_administrasi' => 'datetime',
        'tanggal_daftar_ulang' => 'datetime'
    ];

    // Status pembayaran yang tersedia
    const STATUS_BELUM_BAYAR = 'belum_bayar';
    const STATUS_SUDAH_BAYAR = 'sudah_bayar';
    const STATUS_DIVERIFIKASI = 'diverifikasi';

    // Relasi dengan tabel santri
    public function santri()
    {
        return $this->belongsTo(Santri::class, 'santri_id');
    }

    // Get path untuk bukti pembayaran (bukan URL lengkap)
    public function getBuktiAdministrasiPathAttribute()
    {
        return $this->bukti_biaya_administrasi;
    }

    public function getBuktiDaftarUlangPathAttribute()
    {
        return $this->bukti_biaya_daftar_ulang;
    }

    public function getBuktiAdministrasiAdminPathAttribute()
    {
        return $this->bukti_biaya_administrasi_admin;
    }

    public function getBuktiDaftarUlangAdminPathAttribute()
    {
        return $this->bukti_biaya_daftar_ulang_admin;
    }

    // Helper methods untuk cek status
    public function isAdministrasiBelumBayar()
    {
        return $this->status_administrasi === self::STATUS_BELUM_BAYAR;
    }

    public function isAdministrasiSudahBayar()
    {
        return $this->status_administrasi === self::STATUS_SUDAH_BAYAR;
    }

    public function isAdministrasiDiverifikasi()
    {
        return $this->status_administrasi === self::STATUS_DIVERIFIKASI;
    }

    public function isDaftarUlangBelumBayar()
    {
        return $this->status_daftar_ulang === self::STATUS_BELUM_BAYAR;
    }

    public function isDaftarUlangSudahBayar()
    {
        return $this->status_daftar_ulang === self::STATUS_SUDAH_BAYAR;
    }

    public function isDaftarUlangDiverifikasi()
    {
        return $this->status_daftar_ulang === self::STATUS_DIVERIFIKASI;
    }

    // Format currency
    public function getFormattedBiayaAdministrasiAttribute()
    {
        return 'Rp ' . number_format((float)$this->biaya_administrasi, 0, ',', '.');
    }

    public function getFormattedBiayaDaftarUlangAttribute()
    {
        return 'Rp ' . number_format((float)$this->biaya_daftar_ulang, 0, ',', '.');
    }

    public function getFormattedNominalTransferAdministrasiAttribute()
    {
        return $this->nominal_transfer_administrasi ? 'Rp ' . number_format((float)$this->nominal_transfer_administrasi, 0, ',', '.') : '-';
    }

    public function getFormattedNominalTransferDaftarUlangAttribute()
    {
        return $this->nominal_transfer_daftar_ulang ? 'Rp ' . number_format((float)$this->nominal_transfer_daftar_ulang, 0, ',', '.') : '-';
    }

    // Hapus file bukti pembayaran saat model dihapus
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pembayaran) {
            if ($pembayaran->bukti_biaya_administrasi) {
                Storage::delete($pembayaran->bukti_biaya_administrasi);
            }
            if ($pembayaran->bukti_biaya_daftar_ulang) {
                Storage::delete($pembayaran->bukti_biaya_daftar_ulang);
            }
        });

        // Set default values saat membuat record baru
        static::creating(function ($pembayaran) {
            if (!isset($pembayaran->status_administrasi)) {
                $pembayaran->status_administrasi = self::STATUS_BELUM_BAYAR;
            }
            if (!isset($pembayaran->status_daftar_ulang)) {
                $pembayaran->status_daftar_ulang = self::STATUS_BELUM_BAYAR;
            }
            if (!isset($pembayaran->biaya_administrasi)) {
                $pembayaran->biaya_administrasi = 0;
            }
            if (!isset($pembayaran->biaya_daftar_ulang)) {
                $pembayaran->biaya_daftar_ulang = 0;
            }
        });
    }
}
