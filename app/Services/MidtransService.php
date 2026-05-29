<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * MidtransService
 *
 * Mengenkapsulasi semua komunikasi dengan Midtrans Snap API.
 * Menggunakan HTTP client bawaan Laravel (Guzzle) agar tidak perlu
 * install package Midtrans PHP SDK secara terpisah.
 *
 * Referensi: https://docs.midtrans.com/reference/snap-api
 */
class MidtransService
{
    private string $serverKey;
    private string $clientKey;
    private string $apiUrl;
    private string $snapUrl;
    private bool   $isProduction;

    public function __construct()
    {
        $this->serverKey    = config('midtrans.server_key');
        $this->clientKey    = config('midtrans.client_key');
        $this->apiUrl       = config('midtrans.api_url');
        $this->snapUrl      = config('midtrans.snap_url');
        $this->isProduction = config('midtrans.is_production');
    }

    // =========================================================================
    // PUBLIC METHODS
    // =========================================================================

    /**
     * Membuat Snap Transaction dan mendapatkan snap_token + redirect_url.
     *
     * @param  Order $order  Record order yang sudah tersimpan di DB
     * @param  array $items  Array item dari session cart
     * @return array         ['snap_token' => '...', 'redirect_url' => '...']
     *
     * @throws RuntimeException Jika request ke Midtrans gagal
     */
    public function createSnapTransaction(Order $order, array $items): array
    {
        $payload = $this->buildPayload($order, $items);

        Log::info('[Midtrans] Sending Snap request', [
            'order_code'   => $order->order_code,
            'total_price'  => $order->total_price,
        ]);

        // Di production, hapus ->withoutVerifying() ini.
        // Hanya untuk lokal (Windows/Laragon) yang sertifikat SSL-nya bermasalah.
        $httpClient = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders(['Content-Type' => 'application/json'])
            ->timeout(30);

        if (!$this->isProduction) {
            $httpClient = $httpClient->withoutVerifying();
        }

        $response = $httpClient->post($this->apiUrl, $payload);

        if ($response->failed()) {
            $errorBody = $response->json() ?? ['error_messages' => ['Unknown error']];
            $errorMsg  = implode(', ', $errorBody['error_messages'] ?? ['Midtrans API error']);

            Log::error('[Midtrans] Snap request failed', [
                'order_code' => $order->order_code,
                'status'     => $response->status(),
                'body'       => $errorBody,
            ]);

            throw new RuntimeException("Midtrans gagal memproses transaksi: {$errorMsg}");
        }

        $data = $response->json();

        Log::info('[Midtrans] Snap token received', [
            'order_code' => $order->order_code,
            'token'      => $data['token'] ?? null,
        ]);

        return [
            'snap_token'   => $data['token'],
            'redirect_url' => $data['redirect_url'],
        ];
    }

    /**
     * Verifikasi tanda tangan (signature key) dari notifikasi webhook Midtrans.
     * Mencegah notifikasi palsu dari pihak luar.
     *
     * Formula: SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignatureKey(
        string $orderId,
        string $statusCode,
        string $grossAmount,
        string $incomingSignatureKey
    ): bool {
        $hash = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($hash, $incomingSignatureKey);
    }

    /**
     * Mengembalikan Client Key (dipakai di frontend untuk Snap.js)
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Mengembalikan URL Snap.js CDN (sandbox vs production)
     */
    public function getSnapJsUrl(): string
    {
        return $this->snapUrl;
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Membangun payload JSON sesuai spesifikasi Midtrans Snap API.
     */
    private function buildPayload(Order $order, array $cartItems): array
    {
        // Normalisasi nomor HP ke format internasional (+62xxx)
        $phone = $this->normalizePhone($order->customer_phone);

        // Item details — Midtrans mewajibkan gross_amount = sum(price * qty)
        $itemDetails = array_values(array_map(fn($item) => [
            'id'       => 'PROD-' . $item['id'],
            'price'    => (int) $item['price'],
            'quantity' => (int) $item['quantity'],
            'name'     => mb_substr($item['name'], 0, 50), // Max 50 karakter
        ], $cartItems));

        return [
            'transaction_details' => [
                'order_id'     => $order->order_code,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone'      => $phone,
                'billing_address' => [
                    'address'     => $order->shipping_address ?? 'Takeaway',
                    'country_code'=> 'IDN',
                ],
                'shipping_address' => [
                    'first_name'  => $order->customer_name,
                    'phone'       => $phone,
                    'address'     => $order->shipping_address ?? 'Takeaway',
                    'country_code'=> 'IDN',
                ],
            ],
            'item_details' => $itemDetails,

            // Tampilkan metode pembayaran yang umum di UMKM
            'enabled_payments' => [
                'qris',
                'gopay',
                'shopeepay',
                'other_qris',
                'bank_transfer',
            ],

            // Konfigurasi Snap UI
            'credit_card' => [
                'secure' => config('midtrans.is_3ds', true),
            ],

            // Callback URL setelah pembayaran
            'callbacks' => [
                'finish' => route('checkout.finish'),
            ],
        ];
    }

    /**
     * Normalisasi nomor HP Indonesia ke format +62xxx.
     * Midtrans membutuhkan format internasional.
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // hapus semua non-digit

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return '+' . $phone;
    }
}
