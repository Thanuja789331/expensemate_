<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->headers->set(
            'X-Frame-Options', 'SAMEORIGIN'
        );

        // Prevent MIME sniffing
        $response->headers->set(
            'X-Content-Type-Options', 'nosniff'
        );

        // XSS Protection
        $response->headers->set(
            'X-XSS-Protection', '1; mode=block'
        );

        // Referrer Policy
        $response->headers->set(
            'Referrer-Policy', 'strict-origin-when-cross-origin'
        );

        // Content Security Policy
        // Allow unsafe-eval for Alpine.js and Livewire
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
                "https://cdn.tailwindcss.com " .
                "https://cdn.jsdelivr.net " .
                "https://quickchart.io; " .
            "style-src 'self' 'unsafe-inline' " .
                "https://cdn.tailwindcss.com " .
                "https://fonts.bunny.net; " .
            "font-src 'self' https://fonts.bunny.net; " .
            "img-src 'self' data: https: blob:; " .
            "connect-src 'self' https: wss:; " .
            "frame-src 'none';"
        );

        // Permissions Policy
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=()'
        );

        return $response;
    }
}
