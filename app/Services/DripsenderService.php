<?php

namespace App\Services;

use App\Models\WhatsappConfig;
use Illuminate\Support\Facades\Http;

class DripsenderService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $config = WhatsappConfig::getActiveConfig();
        $this->apiKey = $config && $config->provider === 'dripsender' ? $config->dripsender_token : null;
        $this->baseUrl = $config && $config->provider === 'dripsender' && $config->dripsender_endpoint ? rtrim($config->dripsender_endpoint, '/') : 'https://api.dripsender.id';
    }

    public function sendMessage($phone, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/send-message', [
            'phone' => $phone,
            'message' => $message
        ]);

        return $response;
    }
} 