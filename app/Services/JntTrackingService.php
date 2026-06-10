<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class JntTrackingService
{
    private ?string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.binderbyte.api_key');
        $this->baseUrl = rtrim(config('services.binderbyte.base_url') ?: 'http://api.binderbyte.com/v1', '/');
    }

    public function isConfigured(): bool
    {
        return filled($this->apiKey) && filled($this->baseUrl);
    }

    public function track(string $awb, ?string $courier = null): ?array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Binderbyte belum dikonfigurasi. Isi BINDERBYTE_API_KEY untuk mengaktifkan tracking pengiriman.');
        }

        $courier ??= config('services.binderbyte.courier', 'jnt');

        try {
            $response = Http::acceptJson()
                ->timeout(20)
                ->get($this->baseUrl.'/track', [
                    'api_key' => $this->apiKey,
                    'courier' => $courier,
                    'awb' => $awb,
                ])
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            Log::warning('Binderbyte tracking request failed', [
                'awb' => $awb,
                'courier' => $courier,
                'status' => $exception->response?->status(),
                'message' => $exception->response?->body() ?? $exception->getMessage(),
            ]);

            return null;
        } catch (Throwable $exception) {
            Log::warning('Binderbyte tracking error', [
                'awb' => $awb,
                'courier' => $courier,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }

        if (! is_array($response)) {
            return null;
        }

        if (data_get($response, 'status') === 200 || data_get($response, 'status') === true || data_get($response, 'success') === true) {
            return $this->normalizeTrackingData(data_get($response, 'data', []), $response);
        }

        if (data_get($response, 'data')) {
            return $this->normalizeTrackingData(data_get($response, 'data'), $response);
        }

        Log::warning('Binderbyte tracking response unavailable', [
            'awb' => $awb,
            'courier' => $courier,
            'response' => $response,
        ]);

        return null;
    }

    private function normalizeTrackingData(mixed $data, array $rawResponse): array
    {
        $history = data_get($data, 'history')
            ?? data_get($data, 'details')
            ?? data_get($data, 'track')
            ?? data_get($data, 'traces')
            ?? (is_array($data) && array_is_list($data) ? $data : []);

        $summary = data_get($data, 'summary', []);

        return [
            'summary' => [
                'awb' => data_get($summary, 'awb')
                    ?? data_get($data, 'awb')
                    ?? data_get($data, 'waybill')
                    ?? data_get($rawResponse, 'awb'),
                'status' => data_get($summary, 'status')
                    ?? data_get($data, 'status')
                    ?? data_get($data, 'last_status')
                    ?? data_get($rawResponse, 'message'),
                'courier' => data_get($summary, 'courier')
                    ?? data_get($data, 'courier'),
                'receiver' => data_get($summary, 'receiver')
                    ?? data_get($summary, 'receiver_name')
                    ?? data_get($data, 'receiver')
                    ?? data_get($data, 'recipient'),
            ],
            'history' => is_array($history) ? $history : [],
            'raw' => $rawResponse,
        ];
    }
}
