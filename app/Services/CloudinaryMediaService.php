<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class CloudinaryMediaService
{
    public function upload(UploadedFile $file): array
    {
        if (! $this->isConfigured()) {
            return $this->uploadLocally($file);
        }

        try {
            return $this->uploadToCloudinary($file);
        } catch (Throwable) {
            return $this->uploadLocally($file);
        }
    }

    private function uploadToCloudinary(UploadedFile $file): array
    {
        $cloudName = $this->cloudName();
        $resourceType = str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image';
        $timestamp = time();
        $params = array_filter([
            'timestamp' => $timestamp,
            'folder' => config('services.cloudinary.folder'),
        ]);

        $response = Http::attach('file', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
            ->post("https://api.cloudinary.com/v1_1/{$cloudName}/{$resourceType}/upload", [
                ...$params,
                'api_key' => $this->apiKey(),
                'signature' => $this->signature($params),
            ])
            ->throw()
            ->json();

        return [
            'media_url' => $response['secure_url'] ?? $response['url'] ?? null,
            'media_public_id' => $response['public_id'] ?? null,
            'media_type' => $response['resource_type'] ?? $resourceType,
        ];
    }

    public function delete(?string $publicId, ?string $resourceType): void
    {
        if (! $publicId) {
            return;
        }

        if (str_starts_with($publicId, 'local:')) {
            Storage::disk('public')->delete(substr($publicId, 6));
            return;
        }

        if (! $this->isConfigured()) {
            return;
        }

        $cloudName = $this->cloudName();
        $resourceType = $resourceType === 'video' ? 'video' : 'image';
        $params = [
            'public_id' => $publicId,
            'timestamp' => time(),
            'invalidate' => 'true',
        ];

        Http::post("https://api.cloudinary.com/v1_1/{$cloudName}/{$resourceType}/destroy", [
            ...$params,
            'api_key' => $this->apiKey(),
            'signature' => $this->signature($params),
        ])->throw();
    }

    private function uploadLocally(UploadedFile $file): array
    {
        $resourceType = str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image';
        $path = $file->store('items', 'public');

        return [
            'media_url' => Storage::disk('public')->url($path),
            'media_public_id' => 'local:'.$path,
            'media_type' => $resourceType,
        ];
    }

    private function isConfigured(): bool
    {
        foreach (['cloud_name', 'api_key', 'api_secret'] as $key) {
            $value = config("services.cloudinary.{$key}");

            if (! is_string($value) || $value === '') {
                return false;
            }
        }

        return true;
    }

    private function cloudName(): string
    {
        return $this->requiredConfig('cloud_name');
    }

    private function apiKey(): string
    {
        return $this->requiredConfig('api_key');
    }

    private function apiSecret(): string
    {
        return $this->requiredConfig('api_secret');
    }

    private function requiredConfig(string $key): string
    {
        $value = config("services.cloudinary.{$key}");

        if (! is_string($value) || $value === '') {
            throw new RuntimeException("Konfigurasi Cloudinary {$key} belum diisi.");
        }

        return $value;
    }

    private function signature(array $params): string
    {
        ksort($params);

        $payload = collect($params)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        return sha1($payload.$this->apiSecret());
    }
}
