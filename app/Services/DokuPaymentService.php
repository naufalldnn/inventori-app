<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class DokuPaymentService
{
    private ?string $clientId;
    private ?string $secretKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->clientId = config('services.doku.mall_id');
        $this->secretKey = config('services.doku.shared_key');
        $this->apiUrl = rtrim(config('services.doku.api_url') ?: 'https://api-sandbox.doku.com', '/');
    }

    public function isConfigured(): bool
    {
        return filled($this->clientId) && filled($this->secretKey) && filled($this->apiUrl);
    }

    public function createCheckoutUrl(
        string $invoiceNo,
        float $amount,
        string $customerName,
        string $customerEmail,
        string $redirectUrl,
        ?string $description = null,
        array $lineItems = []
    ): string {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Doku belum dikonfigurasi. Pesanan tetap bisa dibuat, tetapi pembayaran online belum aktif.');
        }

        $amount = (int) round($amount);

        if ($amount < 1000) {
            throw new RuntimeException('Nominal pembayaran Doku minimal Rp 1.000.');
        }

        $targetPath = '/checkout/v1/payment';
        $lineItems = $lineItems ?: [[
            'name' => $description ?: 'Pesanan inventori',
            'quantity' => 1,
            'price' => $amount,
            'category' => 'retail',
        ]];

        $body = [
            'order' => [
                'amount' => $amount,
                'invoice_number' => $this->sanitizeInvoiceNumber($invoiceNo),
                'currency' => 'IDR',
                'callback_url' => $redirectUrl,
                'callback_url_result' => $redirectUrl,
                'language' => 'ID',
                'auto_redirect' => true,
                'line_items' => $lineItems,
            ],
            'payment' => [
                'payment_due_date' => 60,
            ],
            'customer' => [
                'id' => 'USER-'.$this->sanitizeText((string) auth()->id(), 40),
                'name' => $this->sanitizeText($customerName, 120),
                'email' => $customerEmail,
            ],
            'additional_info' => [
                'override_notification_url' => config('services.doku.notification_url') ?: route('doku.callback'),
            ],
        ];

        $headers = $this->headers('POST', $targetPath, $body);

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->withHeaders($headers)
                ->timeout(20)
                ->post($this->apiUrl.$targetPath, $body)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            $message = $exception->response?->json('error_messages.0')
                ?? $exception->response?->body()
                ?? $exception->getMessage();

            Log::error('Doku checkout request failed', [
                'invoice_number' => $invoiceNo,
                'status' => $exception->response?->status(),
                'message' => $message,
            ]);

            throw new RuntimeException('Doku menolak checkout: '.(is_scalar($message) ? $message : json_encode($message)));
        }

        $paymentUrl = data_get($response, 'response.payment.url');

        if (! is_string($paymentUrl) || $paymentUrl === '') {
            Log::error('Doku checkout response missing payment URL', [
                'invoice_number' => $invoiceNo,
                'response' => $response,
            ]);

            throw new RuntimeException('Doku tidak mengembalikan URL pembayaran.');
        }

        return $paymentUrl;
    }

    public function queryTransactionStatus(string $invoiceNo): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $targetPath = '/orders/v1/status/'.$this->sanitizeInvoiceNumber($invoiceNo);

        try {
            $response = Http::acceptJson()
                ->withHeaders($this->headers('GET', $targetPath))
                ->timeout(15)
                ->get($this->apiUrl.$targetPath);

            return $response->successful() ? $response->json() : null;
        } catch (Throwable $exception) {
            Log::warning('Doku query status error', [
                'invoice_number' => $invoiceNo,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    public function handleCallback(array $data): array
    {
        try {
            $invoiceNumber = data_get($data, 'order.invoice_number')
                ?? data_get($data, 'order.invoice_number.value')
                ?? data_get($data, 'INVOICE_NUMBER')
                ?? data_get($data, 'invoice_number');

            $status = strtoupper((string) (
                data_get($data, 'transaction.status')
                ?? data_get($data, 'transaction_status')
                ?? data_get($data, 'STATUS_CODE')
                ?? data_get($data, 'status')
            ));

            if (! $invoiceNumber || ! $status) {
                throw new RuntimeException('Missing required callback parameters');
            }

            $orderStatus = match ($status) {
                'SUCCESS', 'SUCCEEDED', 'SETTLEMENT', 'CAPTURED', '0' => 'paid',
                'PENDING', '99', 'REDIRECT', 'TIMEOUT' => 'pending',
                'EXPIRED', 'ORDER_EXPIRED' => 'expired',
                default => 'failed',
            };

            return [
                'success' => true,
                'invoice_number' => $invoiceNumber,
                'status' => $orderStatus,
                'transaction_id' => data_get($data, 'transaction.original_request_id')
                    ?? data_get($data, 'transaction.id')
                    ?? data_get($data, 'REFERENCE'),
                'response' => $data,
            ];
        } catch (Throwable $e) {
            Log::error('Doku callback error: ' . $e->getMessage(), $data);
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function mapStatusResponse(array $data): ?string
    {
        $status = strtoupper((string) (
            data_get($data, 'transaction.status')
            ?? data_get($data, 'order.status')
            ?? data_get($data, 'transaction_status')
            ?? ''
        ));

        return match ($status) {
            'SUCCESS', 'SUCCEEDED', 'SETTLEMENT', 'CAPTURED' => 'paid',
            'EXPIRED', 'ORDER_EXPIRED' => 'expired',
            'FAILED' => 'failed',
            'PENDING', 'REDIRECT', 'ORDER_GENERATED', 'ORDER_GENERATE' => 'pending',
            default => null,
        };
    }

    private function headers(string $method, string $targetPath, ?array $body = null): array
    {
        $requestId = (string) str()->uuid();
        $timestamp = $this->requestTimestamp();
        $clientId = (string) $this->clientId;
        $secretKey = (string) $this->secretKey;

        $component = "Client-Id:{$clientId}\n"
            ."Request-Id:{$requestId}\n"
            ."Request-Timestamp:{$timestamp}\n"
            ."Request-Target:{$targetPath}";

        $headers = [
            'Client-Id' => $clientId,
            'Request-Id' => $requestId,
            'Request-Timestamp' => $timestamp,
        ];

        if ($method !== 'GET' && $body !== null) {
            $digest = base64_encode(hash('sha256', json_encode($body), true));
            $component .= "\nDigest:{$digest}";
            $headers['Digest'] = $digest;
        }

        $signature = base64_encode(hash_hmac('sha256', $component, $secretKey, true));
        $headers['Signature'] = 'HMACSHA256='.$signature;

        return $headers;
    }

    private function sanitizeInvoiceNumber(string $invoiceNo): string
    {
        return substr(preg_replace('/[^A-Za-z0-9]/', '', $invoiceNo) ?: $invoiceNo, 0, 30);
    }

    private function sanitizeText(string $value, int $maxLength): string
    {
        $value = preg_replace('/[^A-Za-z0-9 @._-]/', '', $value) ?: $value;

        return substr($value, 0, $maxLength);
    }

    private function requestTimestamp(): string
    {
        try {
            $dateHeader = Http::timeout(5)->head($this->apiUrl)->header('Date');

            if (is_string($dateHeader) && $dateHeader !== '') {
                return CarbonImmutable::parse($dateHeader)->utc()->format('Y-m-d\TH:i:s\Z');
            }
        } catch (Throwable $exception) {
            Log::warning('Doku server time lookup failed', ['message' => $exception->getMessage()]);
        }

        return gmdate('Y-m-d\TH:i:s\Z');
    }
}
