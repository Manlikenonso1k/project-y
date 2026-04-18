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
            Log::error('Blockonomics payment address generation failed.', [
                'message' => $exception->getMessage(),
            ]);

            return view('payment', [
                'address' => null,
                'qrCodeUrl' => null,
                'errorMessage' => 'Unable to generate a payment address right now. Please try again in a moment.',
            ]);
        }
    }
}
