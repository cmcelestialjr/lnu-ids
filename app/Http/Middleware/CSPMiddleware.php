<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CSPMiddleware
{
    public function handle($request, Closure $next)
    {
        // Generate a nonce for the CSP header
        $nonce = bin2hex(random_bytes(16)); // Generate a random nonce
        Log::info("Generated nonce: $nonce");
        // Set the nonce in the Laravel app container so it's accessible in views
        app()->singleton('csp-nonce', function () use ($nonce) {
            return $nonce;
        });

        // Set the CSP header with the nonce
        $response = $next($request);
        $response->header('Content-Security-Policy', "script-src 'self' 'unsafe-inline' 'nonce-$nonce'");
        
        return $response;
    }
}

?>