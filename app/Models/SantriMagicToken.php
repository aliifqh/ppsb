<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SantriMagicToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id', 'token', 'expired_at', 'used_at'
    ];

    protected $dates = ['expired_at', 'used_at'];

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    public static function generateFor($santriId, $expiredMinutes = 60)
    {
        $token = Str::random(40);
        $expired = Carbon::now()->addMinutes($expiredMinutes);
        return self::create([
            'santri_id' => $santriId,
            'token' => $token,
            'expired_at' => $expired
        ]);
    }
}
