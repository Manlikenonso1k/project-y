<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $host = strtolower((string) request()->getHost());
        $isOnionRequest = $host !== '' && str_ends_with($host, '.onion');

        Paginator::useBootstrapFive();

        if ($this->app->environment('production') && ! $isOnionRequest) {
            URL::forceScheme('https');
        }

        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(30)->by((string) ($request->user()?->id ?? $request->ip()));
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by((string) ($request->user()?->id ?? $request->ip()));
        });

        RateLimiter::for('payment', function (Request $request) {
            return Limit::perMinute(6)->by((string) ($request->user()?->id ?? $request->ip()));
        });

        View::composer('*', function ($view): void {
            static $resolvedCartCount = null;

            if ($resolvedCartCount !== null) {
                $view->with('cartCount', $resolvedCartCount);

                return;
            }

            $cartCount = 0;

            if (Auth::check()) {
                $cart = Cart::query()
                    ->where('user_id', Auth::id())
                    ->withSum('items', 'quantity')
                    ->first();

                $cartCount = (int) ($cart?->items_sum_quantity ?? 0);
            } elseif (request()->hasSession()) {
                $cart = Cart::query()
                    ->where('session_id', session()->getId())
                    ->withSum('items', 'quantity')
                    ->first();

                $cartCount = (int) ($cart?->items_sum_quantity ?? 0);
            }

            $resolvedCartCount = $cartCount;

            $view->with('cartCount', $resolvedCartCount);
        });
    }
}
