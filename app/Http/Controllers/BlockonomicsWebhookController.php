<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlockonomicsWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $this->assertCallbackIsAuthorized($request);

        $payload = $request->validate([
            'addr' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer'],
            'value' => ['nullable', 'integer', 'min:0'],
            'txid' => ['nullable', 'string', 'max:255'],
            'crypto' => ['nullable', 'string', 'max:16'],
        ]);

        $order = Order::query()->where('btc_address', $payload['addr'])->first();

        if (! $order) {
            Log::warning('Blockonomics callback received for unknown address.', [
                'addr' => $payload['addr'],
                'txid' => $payload['txid'] ?? null,
            ]);

            return response()->json(['ok' => true], 202);
        }

        Log::info('Blockonomics callback received.', [
            'order_id' => $order->id,
            'address' => $payload['addr'],
            'status' => $payload['status'],
            'value' => $payload['value'] ?? null,
            'txid' => $payload['txid'] ?? null,
            'crypto' => $payload['crypto'] ?? 'BTC',
        ]);

        $payloadHash = hash('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        if (DB::table('blockonomics_callbacks')->where('payload_hash', $payloadHash)->exists()) {
            return response()->json(['ok' => true, 'replayed' => true]);
        }

        DB::transaction(function () use ($order, $payload, $payloadHash): void {
            DB::table('blockonomics_callbacks')->insert([
                'order_id' => $order->id,
                'payload_hash' => $payloadHash,
                'address' => $payload['addr'],
                'txid' => $payload['txid'] ?? null,
                'value_satoshi' => (int) ($payload['value'] ?? 0),
                'status_code' => (int) $payload['status'],
                'payload' => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $expectedSatoshi = (int) round((float) $order->expected_btc * 100_000_000);
            $paidSatoshi = (int) ($payload['value'] ?? 0);
            $statusCode = (int) $payload['status'];

            if ($order->payment_status === 'paid') {
                return;
            }

            if ($payload['txid'] ?? null) {
                $order->txid = $payload['txid'];
            }

            if ($statusCode < 0) {
                $order->payment_status = 'failed';
            } elseif ($statusCode >= 2) {
                if ($paidSatoshi < $expectedSatoshi) {
                    $order->payment_status = 'underpaid';
                } else {
                    $order->payment_status = 'paid';

                    if ($order->status === 'pending') {
                        $order->status = 'processing';
                    }
                }
            } else {
                $order->payment_status = 'pending_confirmation';
            }

            $order->save();
        });

        return response()->json(['ok' => true]);
    }

    private function assertCallbackIsAuthorized(Request $request): void
    {
        $expectedSecret = (string) config('services.blockonomics.callback_secret');

        if ($expectedSecret === '') {
            abort(500, 'Blockonomics callback secret is not configured.');
        }

        $providedSecret = (string) ($request->header('X-Blockonomics-Secret') ?: $request->input('secret', ''));

        if (! hash_equals($expectedSecret, $providedSecret)) {
            Log::warning('Rejected Blockonomics callback due to invalid secret.', [
                'ip' => $request->ip(),
            ]);

            abort(403, 'Invalid callback secret.');
        }

        $allowedIpsRaw = trim((string) config('services.blockonomics.callback_ips'));

        if ($allowedIpsRaw === '') {
            return;
        }

        $allowedIps = array_values(array_filter(array_map('trim', explode(',', $allowedIpsRaw))));

        if (! in_array($request->ip(), $allowedIps, true)) {
            Log::warning('Rejected Blockonomics callback due to disallowed IP.', [
                'ip' => $request->ip(),
                'allowed_ips' => $allowedIps,
            ]);

            abort(403, 'Callback IP not allowed.');
        }
    }
}
