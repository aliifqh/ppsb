<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'event',
        'content',
        'is_active',
        'variables',
        'description',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
    ];

    /**
     * Mendapatkan template berdasarkan event
     *
     * @param string $event
     * @return WhatsappTemplate|null
     */
    public static function findByEvent($event)
    {
        return self::where('event', $event)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Mendapatkan semua template yang aktif
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveTemplates()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Pesan yang menggunakan template ini
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class, 'template_id');
    }

    /**
     * Memproses template dengan variabel yang diberikan
     *
     * @param array $variables
     * @return string
     */
    public function processTemplate($variables = [])
    {
        $content = $this->content;
        
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        return $content;
    }
}
