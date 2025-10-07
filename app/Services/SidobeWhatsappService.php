<?php

namespace App\Services;

use Illuminate\Support\Facades\{Http, Log};
use Exception;

class SidobeWhatsappService
{
    protected $baseUrl = 'https://api.sidobe.com/wa/v1';
    protected $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.sidobe.secret_key');
    }

    /**
     * Format nomor HP ke format internasional (+62)
     */
    protected function formatPhone(string $phone): string
    {
        $phone = trim($phone);

        // Hapus semua karakter non-digit kecuali '+'
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Jika diawali '0' → ganti jadi '+62'
        if (substr($phone, 0, 1) === '0') {
            $phone = '+62' . substr($phone, 1);
        }

        // Jika diawali '62' tanpa '+' → tambahkan '+'
        if (substr($phone, 0, 2) === '62' && substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Check nomor WhatsApp valid atau tidak
     */
    public function checkNumber(string $phone)
    {
        try {
            $phone = $this->formatPhone($phone);

            $response = Http::withHeaders([
                'X-Secret-Key' => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/utilities/check-number", [
                'phone' => $phone,
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('Sidobe checkNumber error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Kirim pesan teks ke nomor WA
     */
    public function sendMessage(string $phone, string $message)
    {
        try {
            $phone = $this->formatPhone($phone);

            $check = $this->checkNumber($phone);
            if (!empty($check['error']) || empty($check['is_success']) || $check['is_success'] !== true) {
                return ['error' => true, 'message' => 'Nomor tidak valid atau tidak terdaftar di WhatsApp'];
            }

            $response = Http::withHeaders([
                'X-Secret-Key' => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/send-message", [
                'phone' => $phone,
                'message' => $message,
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('Sidobe sendMessage error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Kirim pesan ke grup WA
     */
    public function sendGroupMessage(string $groupId, string $message)
    {
        try {
            $response = Http::withHeaders([
                'X-Secret-Key' => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/send-message", [
                'group_id' => $groupId,
                'message' => $message,
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('Sidobe sendGroupMessage error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Kirim pesan dengan gambar
     */
    public function sendImage(string $phone, string $message, string $imageUrl)
    {
        try {
            $phone = $this->formatPhone($phone);

            $check = $this->checkNumber($phone);
            if (!empty($check['error']) || empty($check['is_success']) || $check['is_success'] !== true) {
                return ['error' => true, 'message' => 'Nomor tidak valid atau tidak terdaftar di WhatsApp'];
            }

            $response = Http::withHeaders([
                'X-Secret-Key' => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/send-message-image", [
                'phone' => $phone,
                'message' => $message,
                'image_url' => $imageUrl,
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('Sidobe sendImage error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Kirim pesan dengan dokumen
     */
    public function sendDocument(string $phone, string $message, string $documentUrl, string $documentName)
    {
        try {
            $phone = $this->formatPhone($phone);

            $check = $this->checkNumber($phone);
            if (!empty($check['error']) || empty($check['is_success']) || $check['is_success'] !== true) {
                return ['error' => true, 'message' => 'Nomor tidak valid atau tidak terdaftar di WhatsApp'];
            }

            $response = Http::withHeaders([
                'X-Secret-Key' => $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/send-message-doc", [
                'phone' => $phone,
                'message' => $message,
                'document_url' => $documentUrl,
                'document_name' => $documentName,
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('Sidobe sendDocument error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Cek status pesan berdasarkan message ID
     */
    public function checkMessageStatus(string $messageId)
    {
        try {
            $response = Http::withHeaders([
                'X-Secret-Key' => $this->secretKey,
            ])->get("{$this->baseUrl}/whatsapp-messages/{$messageId}");

            return $response->json();
        } catch (Exception $e) {
            Log::error('Sidobe checkMessageStatus error: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
