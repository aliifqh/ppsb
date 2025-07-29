<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'recipient',
        'recipient_name',
        'content',
        'status',
        'response',
        'message_id',
        'sent_at',
        'delivered_at',
        'read_at',
        'template_id',
        'event',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu
     *
     * @var array
     */
    protected $casts = [
        'response' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Template yang digunakan untuk pesan ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(WhatsappTemplate::class);
    }

    /**
     * Scope untuk pesan yang sedang menunggu
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk pesan yang berhasil terkirim
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope untuk pesan yang gagal terkirim
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope untuk pesan yang sudah dibaca
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope untuk pesan yang sudah terkirim
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDelivered($query)
    {
        return $query->whereNotNull('delivered_at');
    }

    /**
     * Mendapatkan status pesan dalam bahasa Indonesia
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Menunggu';
            case 'sent':
                return 'Terkirim';
            case 'delivered':
                return 'Diterima';
            case 'read':
                return 'Dibaca';
            case 'failed':
                return 'Gagal';
            default:
                return ucfirst($this->status);
        }
    }

    /**
     * Mendapatkan pesan berdasarkan status
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByStatus($status)
    {
        return self::where('status', $status)->get();
    }

    /**
     * Mendapatkan pesan berdasarkan event
     *
     * @param string $event
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByEvent($event)
    {
        return self::where('event', $event)->get();
    }

    /**
     * Mendapatkan statistik pengiriman pesan
     *
     * @param string $startDate Format: Y-m-d
     * @param string $endDate Format: Y-m-d
     * @return array
     */
    public static function getStats($startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        $total = $query->count();
        $sent = $query->where('status', 'sent')->count();
        $failed = $query->where('status', 'failed')->count();
        $pending = $query->where('status', 'pending')->count();
        
        return [
            'total' => $total,
            'sent' => $sent,
            'failed' => $failed,
            'pending' => $pending,
            'success_rate' => $total > 0 ? round(($sent / $total) * 100, 2) : 0,
        ];
    }
}
