<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class Gelombang extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'biaya_administrasi',
        'biaya_daftar_ulang',
        'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'status' => 'boolean',
        'biaya_administrasi' => 'integer',
        'biaya_daftar_ulang' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    // Relasi ke model Santri
    public function santri()
    {
        return $this->hasMany(Santri::class);
    }

    public function hasUnpaidSantri()
    {
        return $this->santri()
            ->whereHas('pembayaran', function ($query) {
                $query->where(function ($q) {
                    $q->where('status_administrasi', '!=', 'diverifikasi')
                      ->orWhere('status_daftar_ulang', '!=', 'diverifikasi');
                });
            })
            ->exists();
    }

    public function canBeDeleted()
    {
        return !$this->hasUnpaidSantri();
    }

    public function isActive()
    {
        $now = now();
        /** @var \Carbon\Carbon $mulai */
        $mulai = $this->tanggal_mulai instanceof \Carbon\Carbon ? $this->tanggal_mulai : \Carbon\Carbon::parse($this->tanggal_mulai);
        /** @var \Carbon\Carbon $selesai */
        $selesai = $this->tanggal_selesai instanceof \Carbon\Carbon ? $this->tanggal_selesai : \Carbon\Carbon::parse($this->tanggal_selesai);
        return $this->status &&
               $now->greaterThanOrEqualTo($mulai) &&
               $now->lessThanOrEqualTo($selesai);
    }

    public function isExpired()
    {
        /** @var \Carbon\Carbon $selesai */
        $selesai = $this->tanggal_selesai instanceof \Carbon\Carbon ? $this->tanggal_selesai : \Carbon\Carbon::parse($this->tanggal_selesai);
        return now()->greaterThan($selesai);
    }

    public function isNotStarted()
    {
        /** @var \Carbon\Carbon $mulai */
        $mulai = $this->tanggal_mulai instanceof \Carbon\Carbon ? $this->tanggal_mulai : \Carbon\Carbon::parse($this->tanggal_mulai);
        return now()->lessThan($mulai);
    }

    public function getStatusText()
    {
        if ($this->isExpired()) {
            return 'Kadaluarsa';
        } elseif ($this->isNotStarted()) {
            return 'Belum Dimulai';
        } elseif ($this->isActive()) {
            return 'Aktif';
        } else {
            return 'Nonaktif';
        }
    }

    public function getStatusColor()
    {
        if ($this->isExpired()) {
            return 'red';
        } elseif ($this->isNotStarted()) {
            return 'yellow';
        } elseif ($this->isActive()) {
            return 'emerald';
        } else {
            return 'gray';
        }
    }
}
