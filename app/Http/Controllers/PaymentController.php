<?php

namespace App\Http\Controllers;

use App\Services\BlockonomicsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private readonly BlockonomicsService $blockonomicsService,
    ) {
    }

    /**
     * Create a BTC payment address and render a payment page with QR code.
     */
    public function createPayment(Request $request): View
    {
        try {
            $address = $this->blockonomicsService->createAddress();
            $qrCodeUrl = $this->blockonomicsService->getQRCode($address);

            return view('payment', [
                'address' => $address,
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
                'address' => null,
                'qrCodeUrl' => null,
                'errorMessage' => $errorMessage,
            ]);
        }
    }
}
