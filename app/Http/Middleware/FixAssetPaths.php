<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FixAssetPaths
{
    public function handle(Request $request, Closure $next)
    {
        // Force relative asset paths for Tor/local dev server access
        // This prevents absolute URLs like https://127.0.0.1:8001/ which fail over Tor
        if ($request->getHost() === '127.0.0.1:8001' || str_contains($request->getHost(), '.onion')) {
            config(['app.url' => 'http://localhost']);
        }
        
        return $next($request);
    }
}
