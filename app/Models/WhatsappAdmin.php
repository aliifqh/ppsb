<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappAdmin extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'is_active',
        'events',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'events' => 'array',
    ];

    /**
     * Mendapatkan semua admin yang aktif
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveAdmins()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Mendapatkan admin berdasarkan event
     *
     * @param string $event
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAdminsByEvent($event)
    {
        return self::where('is_active', true)
            ->where(function ($query) use ($event) {
                $query->whereJsonContains('events', $event)
                    ->orWhereJsonContains('events', '*');
            })
            ->get();
    }

    /**
     * Cek apakah admin menerima notifikasi untuk event tertentu
     *
     * @param string $event
     * @return bool
     */
    public function receivesEvent($event)
    {
        if (!$this->is_active) {
            return false;
        }

        if (!is_array($this->events)) {
            return false;
        }

        return in_array($event, $this->events) || in_array('*', $this->events);
    }

    /**
     * Memformat nomor telepon
     *
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        // Hapus karakter non-numerik
        $phone = preg_replace('/[^0-9]/', '', $value);
        
        // Hapus awalan 0 dan tambahkan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Pastikan diawali dengan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        $this->attributes['phone'] = $phone;
    }
}
