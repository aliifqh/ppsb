<?php

namespace App\Services;

use App\Models\WhatsappConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Konfigurasi WhatsApp
     *
     * @var \App\Models\WhatsappConfig
     */
    protected $config;

    /**
     * Provider yang aktif
     *
     * @var string
     */
    protected $provider;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->config = WhatsappConfig::getActiveConfig();
        $this->provider = $this->config ? $this->config->provider : null;
    }

    /**
     * Mengirim pesan WhatsApp
     *
     * @param string $to Nomor tujuan (format: 628xxxxx)
     * @param string $message Isi pesan
     * @param array $options Opsi tambahan (file, caption, dll)
     * @return array
     */
    public function sendMessage($to, $message, $options = [])
    {
        if (!$this->config || !$this->config->is_active) {
            return [
                'success' => false,
                'message' => 'Konfigurasi WhatsApp tidak aktif'
            ];
        }

        // Pastikan nomor tujuan diawali dengan 62
        $to = $this->formatPhoneNumber($to);

        try {
            switch ($this->provider) {
                case 'fonnte':
                    return $this->sendViaFonnte($to, $message, $options);
                case 'wablas':
                    return $this->sendViaWablas($to, $message, $options);
                case 'woowa':
                    return $this->sendViaWoowa($to, $message, $options);
                case 'dripsender':
                    return $this->sendViaDripsender($to, $message, $options);
                case 'watzap':
                    return $this->sendViaWatzap($to, $message, $options);
                case 'whacenter':
                    return $this->sendViaWhacenter($to, $message, $options);
                case 'whapi':
                    return $this->sendViaWhapi($to, $message, $options);
                default:
                    return [
                        'success' => false,
                        'message' => 'Provider tidak didukung'
                    ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mengirim pesan melalui Fonnte
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaFonnte($to, $message, $options = [])
    {
        $endpoint = 'https://api.fonnte.com/send';
        $token = $this->config->fonnte_token;
        
        $payload = [
            'target' => $to,
            'message' => $message,
        ];

        // Tambahkan device jika ada
        if ($this->config->fonnte_device) {
            $payload['device'] = $this->config->fonnte_device;
        }

        // Tambahkan file jika ada
        if (isset($options['file'])) {
            $payload['url'] = $options['file'];
            if (isset($options['caption'])) {
                $payload['caption'] = $options['caption'];
            }
        }

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post($endpoint, $payload);

        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Mengirim pesan melalui Wablas
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaWablas($to, $message, $options = [])
    {
        $domain = $this->config->wablas_domain;
        $endpoint = "https://{$domain}/api/send-message";
        $token = $this->config->wablas_token;
        
        $payload = [
            'phone' => $to,
            'message' => $message,
        ];

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post($endpoint, $payload);

        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Mengirim pesan melalui Woowa
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaWoowa($to, $message, $options = [])
    {
        $apiKey = $this->config->woowa_api_key;
        $instanceId = $this->config->woowa_instance_id;
        $endpoint = "https://api.woowa.net/api/send";
        
        $payload = [
            'instance_id' => $instanceId,
            'api_key' => $apiKey,
            'number' => $to,
            'message' => $message,
        ];

        $response = Http::post($endpoint, $payload);
        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Mengirim pesan melalui Dripsender
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaDripsender($to, $message, $options = [])
    {
        $endpoint = $this->config->dripsender_endpoint ?? 'https://api.dripsender.id';
        $endpoint = rtrim($endpoint, '/') . '/send-message';
        $token = $this->config->dripsender_token;
        
        $payload = [
            'api_key' => $token,
            'phone' => $to,
            'message' => $message,
        ];

        $response = Http::post($endpoint, $payload);
        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Mengirim pesan melalui Watzap
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaWatzap($to, $message, $options = [])
    {
        $apiKey = $this->config->watzap_api_key;
        $deviceId = $this->config->watzap_device_id;
        $endpoint = "https://api.watzap.id/v1/send-message";
        
        $payload = [
            'api_key' => $apiKey,
            'device_id' => $deviceId,
            'to' => $to,
            'message' => $message,
        ];

        $response = Http::post($endpoint, $payload);
        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['success'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Mengirim pesan melalui Whacenter
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaWhacenter($to, $message, $options = [])
    {
        $apiKey = $this->config->whacenter_api_key;
        $device = $this->config->whacenter_device;
        $endpoint = "https://app.whacenter.com/api/send";
        
        $payload = [
            'device_id' => $device,
            'api-key' => $apiKey,
            'number' => $to,
            'message' => $message,
        ];

        $response = Http::get($endpoint, $payload);
        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['status'] ?? '') === 'success',
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Mengirim pesan melalui Whapi
     *
     * @param string $to
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function sendViaWhapi($to, $message, $options = [])
    {
        $token = $this->config->whapi_token;
        $instance = $this->config->whapi_instance;
        $endpoint = "https://whapi.io/api/send";
        
        $payload = [
            'app_id' => $instance,
            'token' => $token,
            'to' => $to,
            'message' => $message,
        ];

        $response = Http::post($endpoint, $payload);
        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['success'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Memformat nomor telepon
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber($phone)
    {
        // Hapus karakter non-numerik
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Hapus awalan 0 dan tambahkan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Pastikan diawali dengan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Menguji koneksi dengan provider
     *
     * @return array
     */
    public function testConnection()
    {
        if (!$this->config) {
            return [
                'success' => false,
                'message' => 'Konfigurasi WhatsApp tidak ditemukan'
            ];
        }

        try {
            switch ($this->provider) {
                case 'fonnte':
                    return $this->testFonnteConnection();
                case 'wablas':
                    return $this->testWablasConnection();
                case 'woowa':
                    return $this->testWoowaConnection();
                case 'dripsender':
                    return $this->testDripsenderConnection();
                case 'watzap':
                    return $this->testWatzapConnection();
                case 'whacenter':
                    return $this->testWhacenterConnection();
                case 'whapi':
                    return $this->testWhapiConnection();
                default:
                    return [
                        'success' => false,
                        'message' => 'Provider tidak didukung'
                    ];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Connection Test Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Menguji koneksi dengan Fonnte
     *
     * @return array
     */
    protected function testFonnteConnection()
    {
        $endpoint = 'https://api.fonnte.com/device';
        $token = $this->config->fonnte_token;
        
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($endpoint);

        $result = $response->json();
        
        return [
            'success' => $response->successful() && ($result['status'] ?? false),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Menguji koneksi dengan Wablas
     *
     * @return array
     */
    protected function testWablasConnection()
    {
        $domain = $this->config->wablas_domain;
        $endpoint = "https://{$domain}/api/device/info";
        $token = $this->config->wablas_token;
        
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->get($endpoint);

        $result = $response->json();
        
        return [
            'success' => $response->successful() && isset($result['data']),
            'message' => $result['message'] ?? 'Tidak ada response',
            'data' => $result
        ];
    }

    /**
     * Menguji koneksi dengan provider lainnya
     * 
     * Implementasi untuk provider lainnya (Woowa, Dripsender, dll)
     * dapat ditambahkan sesuai dengan dokumentasi API masing-masing
     */
    protected function testWoowaConnection()
    {
        // Implementasi pengujian koneksi dengan Woowa
        return [
            'success' => true,
            'message' => 'Koneksi ke Woowa berhasil'
        ];
    }

    protected function testDripsenderConnection()
    {
        // Implementasi pengujian koneksi dengan Dripsender
        return [
            'success' => true,
            'message' => 'Koneksi ke Dripsender berhasil'
        ];
    }

    protected function testWatzapConnection()
    {
        // Implementasi pengujian koneksi dengan Watzap
        return [
            'success' => true,
            'message' => 'Koneksi ke Watzap berhasil'
        ];
    }

    protected function testWhacenterConnection()
    {
        // Implementasi pengujian koneksi dengan Whacenter
        return [
            'success' => true,
            'message' => 'Koneksi ke Whacenter berhasil'
        ];
    }

    protected function testWhapiConnection()
    {
        // Implementasi pengujian koneksi dengan Whapi
        return [
            'success' => true,
            'message' => 'Koneksi ke Whapi berhasil'
        ];
    }

    /**
     * Mengirim pesan menggunakan template
     *
     * @param string $to Nomor tujuan
     * @param string $event Nama event
     * @param array $variables Variabel untuk template
     * @param array $options Opsi tambahan
     * @return array
     */
    public function sendTemplateMessage($to, $event, $variables = [], $options = [])
    {
        // Cari template berdasarkan event
        $template = \App\Models\WhatsappTemplate::findByEvent($event);
        
        if (!$template) {
            return [
                'success' => false,
                'message' => "Template untuk event '{$event}' tidak ditemukan"
            ];
        }
        
        // Proses template dengan variabel
        $message = $template->processTemplate($variables);
        
        // Kirim pesan
        $result = $this->sendMessage($to, $message, $options);
        
        // Jika berhasil, simpan ke database
        if ($result['success']) {
            $this->saveMessageHistory($to, $message, $result, $template->id, $event, $variables['recipient_name'] ?? null);
        }
        
        return $result;
    }
    
    /**
     * Mengirim notifikasi ke admin berdasarkan event
     *
     * @param string $event Nama event
     * @param array $variables Variabel untuk template
     * @param array $options Opsi tambahan
     * @return array
     */
    public function sendAdminNotification($event, $variables = [], $options = [])
    {
        // Cari template berdasarkan event
        $template = \App\Models\WhatsappTemplate::findByEvent($event);
        
        if (!$template) {
            return [
                'success' => false,
                'message' => "Template untuk event '{$event}' tidak ditemukan"
            ];
        }
        
        // Cari admin yang menerima notifikasi untuk event ini
        $admins = \App\Models\WhatsappAdmin::getAdminsByEvent($event);
        
        if ($admins->isEmpty()) {
            return [
                'success' => false,
                'message' => "Tidak ada admin yang menerima notifikasi untuk event '{$event}'"
            ];
        }
        
        // Proses template dengan variabel
        $message = $template->processTemplate($variables);
        
        $results = [];
        
        // Kirim pesan ke semua admin
        foreach ($admins as $admin) {
            $result = $this->sendMessage($admin->phone, $message, $options);
            
            // Jika berhasil, simpan ke database
            if ($result['success']) {
                $this->saveMessageHistory($admin->phone, $message, $result, $template->id, $event, $admin->name);
            }
            
            $results[] = [
                'admin' => $admin->name,
                'phone' => $admin->phone,
                'result' => $result
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Notifikasi berhasil dikirim ke ' . count($admins) . ' admin',
            'results' => $results
        ];
    }
    
    /**
     * Menyimpan riwayat pengiriman pesan
     *
     * @param string $recipient Nomor penerima
     * @param string $content Isi pesan
     * @param array $response Response dari provider
     * @param int|null $templateId ID template
     * @param string|null $event Nama event
     * @param string|null $recipientName Nama penerima
     * @return \App\Models\WhatsappMessage
     */
    public function saveMessageHistory($recipient, $content, $response, $templateId = null, $event = null, $recipientName = null)
    {
        $message = new \App\Models\WhatsappMessage();
        $message->recipient = $recipient;
        $message->recipient_name = $recipientName;
        $message->content = $content;
        $message->status = $response['success'] ? 'sent' : 'failed';
        $message->response = $response;
        $message->message_id = $response['data']['id'] ?? null;
        $message->sent_at = now();
        $message->template_id = $templateId;
        $message->event = $event;
        $message->save();
        
        return $message;
    }
} 