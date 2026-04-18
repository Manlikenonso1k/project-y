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
            // Blockonomics accepts bearer-auth for merchant APIs.
            $response = $this->client->post('https://www.blockonomics.co/api/new_address', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
                'timeout' => 15,
            ]);

            $status = $response->getStatusCode();
            $payload = json_decode((string) $response->getBody(), true);

            if ($status < 200 || $status >= 300 || ! is_array($payload) || empty($payload['address'])) {
                $message = is_array($payload) && isset($payload['message'])
                    ? (string) $payload['message']
                    : 'Unable to generate BTC address from Blockonomics.';

                throw new RuntimeException($message);
            }

            return (string) $payload['address'];
        } catch (GuzzleException $exception) {
            throw new RuntimeException('Blockonomics request failed: ' . $exception->getMessage(), previous: $exception);
        }
    }

    /**
     * Build a QR code image URL for a Bitcoin address.
     */
    public function getQRCode(string $address): string
    {
        $bitcoinUri = 'bitcoin:' . $address;

        return 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($bitcoinUri);
    }
}
