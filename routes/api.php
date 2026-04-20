<?php

use App\Http\Controllers\BlockonomicsWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/blockonomics/callback', [BlockonomicsWebhookController::class, 'handle'])
    ->name('api.blockonomics.callback');
