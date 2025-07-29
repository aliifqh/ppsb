<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'santri_id',
        'pasfoto',
        'akta_lahir',
        'kartu_keluarga',
        'ijazah',
        'dokumen_pendukung'
    ];

    protected $casts = [
        'dokumen_pendukung' => 'array'
    ];

    // Relasi dengan tabel santri
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
