<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send an SMS message via Semaphore (PH) or fallback provider.
     *
     * @param  string  $phone   e.g. "09171234567" or "+639171234567"
     * @param  string  $message
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        $phone = $this->normalizePhone($phone);

        try {
            return $this->sendViaSemaphore($phone, $message);
        } catch (\Throwable $e) {
            Log::error('SmsService: Semaphore failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ── Semaphore API ─────────────────────────────────────────
    private function sendViaSemaphore(string $phone, string $message): bool
    {
        $apiKey     = config('services.semaphore.key');
        $senderName = config('services.semaphore.sender_name', 'MMSC');

        if (empty($apiKey)) {
            Log::warning('SmsService: SEMAPHORE_API_KEY not configured.');
            return false;
        }

        $response = Http::timeout(15)->post('https://api.semaphore.co/api/v4/messages', [
            'apikey'      => $apiKey,
            'number'      => $phone,
            'message'     => $message,
            'sendername'  => $senderName,
        ]);

        if ($response->successful()) {
            Log::info('SmsService: Sent via Semaphore', ['phone' => $phone]);
            return true;
        }

        Log::error('SmsService: Semaphore error', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);
        return false;
    }

    // ── Normalize Philippine phone number ─────────────────────
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // strip non-digits

        // Convert 09XXXXXXXXX → 639XXXXXXXXX
        if (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = '63' . substr($phone, 1);
        }

        return $phone;
    }
}
