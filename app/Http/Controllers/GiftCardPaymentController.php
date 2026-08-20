<?php

namespace App\Http\Controllers;

use App\Models\GiftCardSubmission;
use App\Models\Order;
use App\Services\TelegramNotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GiftCardPaymentController extends Controller
{
    public function show(Order $order): View
    {
        $this->authorizeOrderAccess($order);

        return view('gift-card-payment', compact('order'));
    }

    public function submit(Request $request, Order $order, TelegramNotificationService $telegram): RedirectResponse
    {
        $this->authorizeOrderAccess($order);

        $validated = $request->validate([
            'card_type' => ['required', 'string', 'in:Apple Gift Card,Amazon Gift Card,Google Play Gift Card,Steam Gift Card'],
            'card_amount' => ['required', 'numeric', 'min:1', 'max:10000'],
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $paths = [];
        foreach ($request->file('images') as $image) {
            $paths[] = $image->store("gift-card-submissions/{$order->id}", 'local');
        }

        $submission = GiftCardSubmission::create([
            'order_id' => $order->id,
            'card_type' => $validated['card_type'],
            'card_currency' => 'USD',
            'card_amount' => $validated['card_amount'],
            'image_paths' => $paths,
        ]);

        $notified = $telegram->sendGiftCardSubmissionNotification($submission, $order);

        Log::info('Gift card payment submission received.', [
            'submission_id' => $submission->id,
            'order_id' => $order->id,
            'telegram_notified' => $notified,
        ]);

        return redirect()
            ->route('order.success', $order)
            ->with('success', 'Your gift card payment has been submitted for review. We will contact you after verification.');
    }

    private function authorizeOrderAccess(Order $order): void
    {
        $isOwner = Auth::check() && $order->user_id === Auth::id();
        $isGuestOrderInSession = ! Auth::check() && (int) session('last_order_id') === $order->id;

        abort_unless($isOwner || $isGuestOrderInSession, 403);
    }
}
