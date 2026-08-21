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

        $submittedAmount = $this->submittedGiftCardAmount($order);
        $remainingAmount = max(0, (float) $order->total - $submittedAmount);

        return view('gift-card-payment', compact('order', 'submittedAmount', 'remainingAmount'));
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

        $cardCount = count($request->file('images'));
        $amountPerCard = round((float) $validated['card_amount'], 2);
        $submissionAmount = round($amountPerCard * $cardCount, 2);
        $remainingAmount = max(0, (float) $order->total - $this->submittedGiftCardAmount($order));

        if ($submissionAmount > $remainingAmount) {
            return back()
                ->withInput()
                ->withErrors(['card_amount' => 'The selected cards total $' . number_format($submissionAmount, 2) . ', which exceeds the remaining order balance of $' . number_format($remainingAmount, 2) . '.']);
        }

        $paths = [];
        foreach ($request->file('images') as $image) {
            $paths[] = $image->store("gift-card-submissions/{$order->id}", 'local');
        }

        $submission = GiftCardSubmission::create([
            'order_id' => $order->id,
            'card_type' => $validated['card_type'],
            'card_currency' => 'USD',
            'card_amount' => $submissionAmount,
            'card_value_per_image' => $amountPerCard,
            'image_count' => $cardCount,
            'image_paths' => $paths,
        ]);

        $notified = $telegram->sendGiftCardSubmissionNotification($submission, $order);

        Log::info('Gift card payment submission received.', [
            'submission_id' => $submission->id,
            'order_id' => $order->id,
            'telegram_notified' => $notified,
            'card_count' => $cardCount,
            'submission_amount' => $submissionAmount,
        ]);

        return redirect()
            ->route('order.success', $order)
            ->with('success', 'Your gift cards have been submitted for review. The submitted value is $' . number_format($submissionAmount, 2) . '.');
    }

    private function submittedGiftCardAmount(Order $order): float
    {
        return (float) $order->giftCardSubmissions()
            ->get(['card_amount', 'card_value_per_image', 'image_count', 'image_paths'])
            ->sum(function (GiftCardSubmission $submission): float {
                if ($submission->card_value_per_image !== null && $submission->image_count !== null) {
                    return (float) $submission->card_value_per_image * (int) $submission->image_count;
                }

                // Submissions created before per-card totals were introduced.
                return (float) $submission->card_amount * count($submission->image_paths ?? []);
            });
    }

    private function authorizeOrderAccess(Order $order): void
    {
        $isOwner = Auth::check() && $order->user_id === Auth::id();
        $isGuestOrderInSession = ! Auth::check() && (int) session('last_order_id') === $order->id;

        abort_unless($isOwner || $isGuestOrderInSession, 403);
    }
}