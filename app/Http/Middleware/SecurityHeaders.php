<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $isSecureProductionRequest = $request->isSecure() && app()->environment('production');

        $contentSecurityPolicy = [
            "default-src 'self'",
            "base-uri 'self'",
            $isSecureProductionRequest ? "form-action 'self' https:" : "form-action 'self' http:",
            "frame-ancestors 'self'",
            $isSecureProductionRequest ? "img-src 'self' data: https:" : "img-src 'self' data: https: http:",
            $isSecureProductionRequest ? "media-src 'self' data: blob: https:" : "media-src 'self' data: blob: https: http:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://www.blockonomics.co http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173",
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            $isSecureProductionRequest ? "connect-src 'self' https:" : "connect-src 'self' https: http: ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173 wss://localhost:5173 wss://127.0.0.1:5173 wss://[::1]:5173",
            "object-src 'none'",
        ];

        if ($isSecureProductionRequest) {
            $contentSecurityPolicy[] = 'upgrade-insecure-requests';
            $contentSecurityPolicy[] = 'block-all-mixed-content';
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        }

        if (! app()->environment('local')) {
            $response->headers->set('Content-Security-Policy', implode('; ', $contentSecurityPolicy));
        }

        return $response;
    }
}
