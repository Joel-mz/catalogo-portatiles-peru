<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and attach enterprise security & anti-XSS headers.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Force HTTPS 301 Redirect in Production if accessed via insecure HTTP
        if ((app()->isProduction() || config('app.env') === 'production') && 
            !$request->isSecure() && 
            $request->header('X-Forwarded-Proto') !== 'https' &&
            $request->header('X-Forwarded-SSL') !== 'on') {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Remove identifying PHP engine headers
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }

        /** @var Response $response */
        $response = $next($request);

        // Remove server and language signatures from response
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // 2. Clickjacking Protection
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. MIME Sniffing Prevention
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. Anti-XSS Protection (Cross-Site Scripting filter)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 5. Safe Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 6. Restrict Sensitive Browser Permissions (Allow camera for QR/Barcode scanner)
        $response->headers->set('Permissions-Policy', 'camera=*, microphone=(), geolocation=(), payment=(), usb=()');

        // 7. HTTP Strict Transport Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // 8. Robust Content Security Policy (CSP) with XSS defense and auto HTTPS upgrade
        $csp = implode('; ', [
            "default-src 'self' https: data: blob:",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://unpkg.com https: blob:",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://cdn.tailwindcss.com https:",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https:",
            "img-src 'self' data: blob: https: http:",
            "media-src 'self' data: blob: https:",
            "connect-src 'self' https: ws: wss:",
            "worker-src 'self' blob:",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self' https:",
            "upgrade-insecure-requests",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
