<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\BlockonomicsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function __construct(
        private readonly BlockonomicsService $blockonomicsService,
    ) {
    }

    public function createPayment(Request $request): View
    {
        return view('payment', [
            'order' => null,
            'address' => null,
            'expectedBtc' => null,
            'paymentStatus' => 'pending_address',
            'qrCodeUrl' => null,
            'errorMessage' => 'Place an order first, then open the Bitcoin payment page from the order success screen.',
        ]);
    }

    /**
     * Create or show secure BTC payment details for a specific order.
     */
    public function showOrderPayment(Order $order): View
    {
        $isOwner = Auth::check() && $order->user_id === Auth::id();
        $isGuestOrderInSession = ! Auth::check() && (int) session('last_order_id') === $order->id;

        abort_unless($isOwner || $isGuestOrderInSession, 403);

        try {
            if (! $order->btc_address) {
                $callbackUrl = URL::route('api.blockonomics.callback');
                $address = $this->blockonomicsService->createAddress($callbackUrl);
                $expectedBtc = $this->blockonomicsService->convertUsdToBtc((float) $order->total);

                $order->update([
                    'btc_address' => $address,
                    'expected_btc' => $expectedBtc,
                    'payment_status' => 'pending_confirmation',
                ]);
            }

            $order->refresh();

            $qrCodeUrl = $this->blockonomicsService->getQRCode($order->btc_address);

            return view('payment', [
                'order' => $order,
                'address' => $order->btc_address,
                'expectedBtc' => $order->expected_btc,
                'paymentStatus' => $order->payment_status,
                'qrCodeUrl' => $qrCodeUrl,
                'errorMessage' => null,
            ]);
        } catch (\Throwable $exception) {
            $message = $exception->getMessage();

            Log::error('Blockonomics payment address generation failed.', [
                'message' => $message,
            ]);

            $errorMessage = 'Unable to generate a payment address right now. Please try again in a moment.';

            if (str_contains($message, 'No store found') || str_contains($message, 'code":1040')) {
                $errorMessage = 'Blockonomics store is not configured yet. Create and activate a store in Blockonomics, attach your wallet/xPub, then use that store API key in BLOCKONOMICS_API_KEY.';
            }

            return view('payment', [
                'order' => $order,
                'address' => null,
                'expectedBtc' => null,
                'paymentStatus' => $order->payment_status,
                'qrCodeUrl' => null,
                'errorMessage' => $errorMessage,
            ]);
        }
    }
}
