<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

class BlockonomicsService
{
    public function __construct(
        private readonly Client $client = new Client(),
        private readonly ?string $apiKey = null,
    ) {
    }

    /**
     * Generate a new Bitcoin payment address via Blockonomics.
     */
    public function createAddress(): string
    {
        $apiKey = $this->apiKey ?: config('services.blockonomics.api_key');

        if (! $apiKey) {
            throw new RuntimeException('Missing Blockonomics API key. Set BLOCKONOMICS_API_KEY in your environment.');
        }

        try {
            // Primary mode follows Blockonomics examples for /api/new_address.
            $response = $this->client->post('https://www.blockonomics.co/api/new_address', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'api_key' => $apiKey,
                ],
                'http_errors' => false,
                'timeout' => 15,
            ]);

            $status = $response->getStatusCode();
            $payload = json_decode((string) $response->getBody(), true);

            if ($this->hasAddress($status, $payload)) {
                return (string) $payload['address'];
            }

            // Fallback for environments/accounts configured for bearer-style auth.
            $fallbackResponse = $this->client->post('https://www.blockonomics.co/api/new_address', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
                'timeout' => 15,
            ]);

            $fallbackStatus = $fallbackResponse->getStatusCode();
            $fallbackPayload = json_decode((string) $fallbackResponse->getBody(), true);

            if ($this->hasAddress($fallbackStatus, $fallbackPayload)) {
                return (string) $fallbackPayload['address'];
            }

            $firstError = $this->extractErrorMessage($status, $payload);
            $fallbackError = $this->extractErrorMessage($fallbackStatus, $fallbackPayload);

            throw new RuntimeException("Blockonomics new_address failed. Primary: {$firstError}. Fallback: {$fallbackError}.");
        } catch (GuzzleException $exception) {
            throw new RuntimeException('Blockonomics request failed: ' . $exception->getMessage(), previous: $exception);
        }
    }

    private function hasAddress(int $status, mixed $payload): bool
    {
        return $status >= 200
            && $status < 300
            && is_array($payload)
            && ! empty($payload['address'])
            && is_string($payload['address']);
    }

    private function extractErrorMessage(int $status, mixed $payload): string
    {
        if (is_array($payload)) {
            foreach (['message', 'error', 'detail', 'response'] as $key) {
                if (! empty($payload[$key]) && is_string($payload[$key])) {
                    return "HTTP {$status}: {$payload[$key]}";
                }
            }

            return 'HTTP ' . $status . ': ' . json_encode($payload);
        }

        return 'HTTP ' . $status . ': Empty or non-JSON response from Blockonomics.';
    }

    /**
     * Build a QR code image URL for a Bitcoin address.
     */
    public function getQRCode(string $address): string
    {
        $bitcoinUri = 'bitcoin:' . $address;

        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($bitcoinUri);
    }
}
