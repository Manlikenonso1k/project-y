<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleAdminAuthAttempts
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isAdminAuthenticationAttempt($request)) {
            return $next($request);
        }

        $email = (string) $request->input('email', 'unknown');
        $key = sprintf('admin-auth:%s|%s', $request->ip(), strtolower($email));

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            Log::warning('Admin authentication throttled.', [
                'ip' => $request->ip(),
                'email' => $email,
                'user_agent' => (string) $request->userAgent(),
                'retry_after_seconds' => $seconds,
            ]);

            return response('Too many authentication attempts. Please wait before trying again.', 429)
                ->header('Retry-After', (string) $seconds);
        }

        RateLimiter::hit($key, 300);

        return $next($request);
    }

    private function isAdminAuthenticationAttempt(Request $request): bool
    {
        if ($request->isMethod('POST') && $request->routeIs('filament.admin.auth.login')) {
            return true;
        }

        if (! $request->isMethod('POST') || ! $request->routeIs('default-livewire.update')) {
            return false;
        }

        $referer = (string) $request->headers->get('referer');

        return str_contains($referer, '/admin/login');
    }
}
