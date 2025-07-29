<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappConfig extends Model
{
    use HasFactory;

    /**
     * Tabel yang terkait dengan model ini
     *
     * @var string
     */
    protected $table = 'whatsapp_configs';

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'provider',
        'is_active',
        'default_sender',
        'sender_name',
        'enable_read_receipt',
        'daily_limit',
        'queue_limit',
        'fonnte_token',
        'fonnte_device',
        'wablas_token',
        'wablas_domain',
        'woowa_api_key',
        'woowa_instance_id',
        'dripsender_token',
        'dripsender_endpoint',
        'watzap_api_key',
        'watzap_device_id',
        'whacenter_api_key',
        'whacenter_device',
        'whapi_token',
        'whapi_instance',
    ];

    /**
     * Atribut yang harus dikonversi ke tipe data tertentu
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'enable_read_receipt' => 'boolean',
        'daily_limit' => 'integer',
        'queue_limit' => 'integer',
    ];

    /**
     * Mendapatkan konfigurasi yang aktif
     *
     * @return WhatsappConfig|null
     */
    public static function getActiveConfig()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Mendapatkan konfigurasi berdasarkan provider
     *
     * @param string $provider
     * @return array
     */
    public function getConfigByProvider()
    {
        $config = [];
        
        switch ($this->provider) {
            case 'fonnte':
                $config = [
                    'token' => $this->fonnte_token,
                    'device' => $this->fonnte_device,
                ];
                break;
            case 'wablas':
                $config = [
                    'token' => $this->wablas_token,
                    'domain' => $this->wablas_domain,
                ];
                break;
            case 'woowa':
                $config = [
                    'api_key' => $this->woowa_api_key,
                    'instance_id' => $this->woowa_instance_id,
                ];
                break;
            case 'dripsender':
                $config = [
                    'token' => $this->dripsender_token,
                    'endpoint' => $this->dripsender_endpoint,
                ];
                break;
            case 'watzap':
                $config = [
                    'api_key' => $this->watzap_api_key,
                    'device_id' => $this->watzap_device_id,
                ];
                break;
            case 'whacenter':
                $config = [
                    'api_key' => $this->whacenter_api_key,
                    'device' => $this->whacenter_device,
                ];
                break;
            case 'whapi':
                $config = [
                    'token' => $this->whapi_token,
                    'instance' => $this->whapi_instance,
                ];
                break;
        }
        
        return $config;
    }
}
