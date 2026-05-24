<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    /**
     * Kirim pesan ke Telegram menggunakan HTML format.
     *
     * @param string $message
     * @return bool
     */
    public static function sendMessage(string $message): bool
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (empty($botToken) || empty($chatId)) {
            Log::warning('[Telegram] Konfigurasi token bot atau chat ID kosong. Pesan tidak dikirim.', [
                'message' => $message
            ]);
            return false;
        }

        try {
            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
            $response = Http::post($url, [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'HTML',
            ]);

            if ($response->successful()) {
                Log::info('[Telegram] Pesan notifikasi berhasil dikirim.');
                return true;
            }

            Log::error('[Telegram] Gagal mengirim pesan ke Telegram', [
                'status'   => $response->status(),
                'response' => $response->body()
            ]);
        } catch (\Throwable $e) {
            Log::error('[Telegram] Terjadi kesalahan saat mengirim pesan', [
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }
}
