<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach enterprise security headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Remove identifying PHP engine headers
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        /** @var Response $response */
        $response = $next($request);

        // Remove server and language signatures from response
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // 1. Clickjacking Protection
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. MIME Sniffing Prevention
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. XSS Filter for legacy browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 4. Safe Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Restrict Unneeded Browser Permissions
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // 6. HTTP Strict Transport Security (HSTS)
        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https' || app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // 7. Content Security Policy (CSP)
        $csp = implode('; ', [
            "default-src 'self' https: data: blob:",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https: http: blob:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://cdn.tailwindcss.com https: http:",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https: http:",
            "img-src 'self' data: blob: https: http:",
            "media-src 'self' data: blob: https: http:",
            "connect-src 'self' https: http: ws: wss:",
            "worker-src 'self' blob:",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self' https://wa.me",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
