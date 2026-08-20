<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class TelegramNotificationService
{
    private $botToken;
    private $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token') ?? env('BOT_TOKEN') ?? env('TELEGRAM_BOT_TOKEN') ?? '';
        $this->chatId = config('services.telegram.chat_id') ?? env('CHAT_ID') ?? env('TELEGRAM_CHAT_ID') ?? '';
    }

    public function sendOrderNotification(Order $order)
    {
        $items = $order->items()->with('product')->get();
        
        $itemsList = $items->map(function($item) {
            return "• {$item->product->name} x{$item->quantity} = \${$item->total}";
        })->join("\n");

        $message = "🎉 *NEW ORDER PLACED!*\n\n";
        $message .= "*Order #:* `{$order->order_number}`\n";
        $message .= "*Customer:* {$order->first_name} {$order->last_name}\n";
        $message .= "*Email:* {$order->email}\n";
        $message .= "*Phone:* {$order->phone}\n\n";
        $message .= "*Items:*\n{$itemsList}\n\n";
        $message .= "*Summary:*\n";
        $message .= "Subtotal: \${$order->subtotal}\n";
        $message .= "Tax (10%): \${$order->tax}\n";
        $message .= "Shipping: \${$order->shipping}\n";
        $message .= "*Total: \${$order->total}*\n\n";
        $message .= "*Address:* {$order->address}, {$order->city}, {$order->state} {$order->postal_code}, {$order->country}\n";
        $message .= "*Status:* " . ucfirst($order->status);

        return $this->sendMessage($message);
    }

    /**
     * Notify staff only after a Bitcoin payment has reached final confirmation.
     */
    public function sendBitcoinPaymentConfirmedNotification(Order $order, int $valueSatoshi, ?string $txid): bool
    {
        $amountBtc = number_format($valueSatoshi / 100_000_000, 8, '.', '');
        $message = "*BITCOIN PAYMENT CONFIRMED*\n\n";
        $message .= "*Order #:* `{$order->order_number}`\n";
        $message .= "*Customer:* {$order->first_name} {$order->last_name}\n";
        $message .= "*Amount:* {$amountBtc} BTC\n";
        $message .= "*Order Total:* \${$order->total}\n";

        if ($txid) {
            $message .= "*Transaction:* `{$txid}`\n";
        }

        $message .= "*Status:* Paid";

        return $this->sendMessage($message);
    }

    private function sendMessage($text)
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            \Log::info('Telegram credentials not configured; skipping notification.');
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Failed to send Telegram notification: ' . $e->getMessage());
            return false;
        }
    }
}
