<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class TelegramNotificationService
{
    private $botToken = '8553841666:AAFvLOLdcV4JvQAwUPKAyAFB2_fr0TBOB9U';
    private $chatId = '1963161428';

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

    private function sendMessage($text)
    {
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
