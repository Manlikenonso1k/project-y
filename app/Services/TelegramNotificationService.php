<?php

namespace App\Services;

use App\Models\Order;
use App\Models\GiftCardSubmission;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Send a review request and each private gift-card image to the configured group.
     */
    public function sendGiftCardSubmissionNotification(GiftCardSubmission $submission, Order $order): bool
    {
        if (empty($this->botToken) || empty($this->chatId)) {
            \Log::info('Telegram credentials not configured; skipping gift card submission notification.');
            return false;
        }

        $message = "*GIFT CARD PAYMENT SUBMITTED*\n\n";
        $message .= "*Order #:* `{$order->order_number}`\n";
        $message .= "*Submission #:* `{$submission->id}`\n";
        $message .= "*Card:* {$submission->card_type}\n";
        $message .= "*Card Value:* \${$submission->card_amount} {$submission->card_currency}\n";
        $message .= "*Order Total:* \${$order->total}\n";
        $message .= "*Customer:* {$order->first_name} {$order->last_name}\n";
        $message .= "*Status:* Pending review";

        $sent = $this->sendMessage($message);

        foreach ($submission->image_paths as $path) {
            $sent = $this->sendPhoto($path, "Order `{$order->order_number}` — gift card image") && $sent;
        }

        return $sent;
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

    private function sendPhoto(string $path, string $caption): bool
    {
        if (! Storage::disk('local')->exists($path)) {
            \Log::warning('Gift card image is missing before Telegram delivery.', ['path' => $path]);
            return false;
        }

        $stream = Storage::disk('local')->readStream($path);

        if ($stream === false) {
            \Log::warning('Gift card image could not be opened for Telegram delivery.', ['path' => $path]);
            return false;
        }

        try {
            $response = Http::attach('photo', $stream, basename($path))
                ->post("https://api.telegram.org/bot{$this->botToken}/sendPhoto", [
                    'chat_id' => $this->chatId,
                    'caption' => $caption,
                    'parse_mode' => 'Markdown',
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Failed to send gift card image to Telegram: ' . $e->getMessage());
            return false;
        } finally {
            fclose($stream);
        }
    }
}
