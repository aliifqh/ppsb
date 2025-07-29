<?php

namespace App\Http\Controllers;

use App\Services\DripsenderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    protected $dripsender;

    public function __construct(DripsenderService $dripsender)
    {
        $this->dripsender = $dripsender;
    }

    public function sendTestMessage(Request $request)
    {
        $phone = $request->input('phone');
        $message = "Halo! Ini adalah pesan test dari sistem kami. 🚀\n\nJika kamu menerima pesan ini, berarti integrasi WhatsApp sudah berhasil! 🎉";

        $result = $this->dripsender->sendMessage($phone, $message);

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pesan berhasil dikirim!',
                'data' => $result
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengirim pesan. Cek log untuk detail.'
        ], 500);
    }
} 