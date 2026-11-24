<?php

namespace Paymenter\Extensions\Others\Smm\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Paymenter\Extensions\Others\Smm\Models\SmmProvider;
use Paymenter\Extensions\Others\Smm\Models\SmmSyncLog;

class EasySmmpanelApi
{
    protected SmmProvider $provider;
    protected int $timeout = 30;

    public function __construct(SmmProvider $provider)
    {
        $this->provider = $provider;
    }

    public static function forDefault(): self
    {
        $provider = SmmProvider::where('is_default', true)->where('is_active', true)->first();
        if (!$provider) {
            throw new \RuntimeException('No active default EasySMMPanel provider configured.');
        }
        return new self($provider);
    }

    public function getProvider(): SmmProvider
    {
        return $this->provider;
    }

    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    protected function baseUrl(): string
    {
        return rtrim($this->provider->api_endpoint ?: 'https://easysmmpanel.com/api/v2', '/');
    }

    protected function apiKey(): string
    {
        return $this->provider->api_key ?: '';
    }

    public function log(string $action, bool $success, string $message = '', array $payload = []): void
    {
        try {
            SmmSyncLog::create([
                'provider' => $this->provider->code,
                'action' => $action,
                'success' => $success,
                'message' => $message,
                'payload' => $payload,
            ]);
        } catch (\Throwable $e) {
            Log::warning('SMM sync log failed: '.$e->getMessage());
        }
    }

    protected function providerLog(string $action, string $method, string $url, ?string $requestPayload, ?int $responseStatus, ?string $responseBody): void
    {
        try {
            \Paymenter\Extensions\Others\Smm\Models\SmmProviderLog::create([
                'provider_id' => $this->provider->id,
                'action' => $action,
                'request_method' => $method,
                'request_url' => $url,
                'request_payload' => $requestPayload,
                'response_status' => $responseStatus,
                'response_body' => $responseBody,
            ]);
        } catch (\Throwable $e) {
            Log::warning('SMM provider log failed: '.$e->getMessage());
        }
    }

    public function request(string $action, array $data = []): array
    {
        $url = $this->baseUrl();
        $payload = array_merge(['key' => $this->apiKey(), 'action' => $action], $data);
        $requestPayload = json_encode($payload);

        try {
            $response = Http::timeout($this->timeout)->post($url, $payload);
            $responseStatus = $response->status();
            $body = $response->body();

            $this->providerLog($action, 'POST', $url, $requestPayload, $responseStatus, $body);

            if ($response->failed()) {
                $this->log($action, false, 'HTTP '.$responseStatus, ['url' => $url, 'body' => $body]);
                return ['success' => false, 'error' => 'HTTP '.$responseStatus.': '.$body];
            }

            $decoded = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->log($action, false, 'Invalid JSON: '.$body, ['url' => $url]);
                return ['success' => false, 'error' => 'Invalid provider response'];
            }

            // EasySMMPanel returns an error object on failure
            if (is_array($decoded) && isset($decoded['error'])) {
                $this->log($action, false, $decoded['error'], ['url' => $url, 'payload' => $payload]);
                return ['success' => false, 'error' => $decoded['error']];
            }

            $this->log($action, true, 'OK', ['url' => $url]);
            return ['success' => true, 'data' => $decoded];
        } catch (\Exception $e) {
            $this->providerLog($action, 'POST', $url, $requestPayload, null, $e->getMessage());
            $this->log($action, false, $e->getMessage(), ['url' => $url]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function testConnection(): array
    {
        return $this->request('balance');
    }

    public function balance(): array
    {
        $result = $this->request('balance');

        if (!$result['success']) {
            return $result;
        }

        $data = $result['data'] ?? [];
        $balance = $data['balance'] ?? $data['Balance'] ?? null;

        try {
            $this->provider->update([
                'balance' => $balance !== null ? (float) $balance : null,
                'balance_currency' => $data['currency'] ?? 'INR',
                'last_balance_check_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // ignore
        }

        return [
            'success' => true,
            'balance' => $balance,
            'currency' => $data['currency'] ?? 'INR',
            'raw' => $data,
        ];
    }

    public function services(): array
    {
        $result = $this->request('services');

        if (!$result['success']) {
            return $result;
        }

        $data = $result['data'] ?? [];

        if (!is_array($data)) {
            return ['success' => false, 'error' => 'Unexpected provider response format'];
        }

        return ['success' => true, 'data' => $data];
    }
}
