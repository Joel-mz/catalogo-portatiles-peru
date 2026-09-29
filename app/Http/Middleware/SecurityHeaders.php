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
        if (app()->isProduction() && !$request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // 2. IP Blocklist Enforcement
        try {
            $blockedIps = array_filter(array_map('trim', explode(',', \App\Models\Setting::where('key', 'security_blocked_ips')->value('value') ?? '')));
            if (!empty($blockedIps) && in_array($request->ip(), $blockedIps, true)) {
                abort(403, 'Acceso restringido por el cortafuegos de seguridad del sistema.');
            }
        } catch (\Throwable $e) {
            // Silently continue if database is migrating
        }

        // 3. Active WAF SQL Injection & Malicious Payload Inspection Filter
        try {
            $firewallEnabled = (\App\Models\Setting::where('key', 'security_firewall_enabled')->value('value') ?? '1') === '1';
            if ($firewallEnabled && !$request->is('admin/*') && !$request->is('api/checkout')) {
                $queryString = urldecode($request->getQueryString() ?? '');
                $rawPayload = json_encode($request->except(['_token', 'password', 'password_confirmation', 'image', 'images', 'pdf', 'excel_file', 'sale_note']));
                $contentToInspect = $queryString . ' ' . $rawPayload;

                $sqlPatterns = [
                    '/\bunion\b\s+(all\s+)?\bselect\b/i',
                    '/\bselect\b.+\bfrom\b.+\binformation_schema\b/i',
                    '/\b(drop|truncate|alter)\s+(table|database)\b/i',
                    '/\bexec(\s|\+)+(s|x)p\w+/i',
                    '/(\'|")\s*or\s*(\'|")?1(\'|")?\s*=\s*(\'|")?1/i',
                    '/(\'|")\s*or\s*1\s*=\s*1/i',
                    '/\b(benchmark|sleep)\s*\(\s*\d+\s*\)/i',
                ];

                foreach ($sqlPatterns as $pattern) {
                    if (preg_match($pattern, $contentToInspect)) {
                        \App\Models\AuditLog::create([
                            'user_id' => auth()->id(),
                            'action' => 'Ataque Bloqueado: Inyección SQL sospechosa',
                            'model' => 'WAF_Firewall',
                            'model_id' => null,
                            'details' => [
                                'ip' => $request->ip(),
                                'url' => $request->fullUrl(),
                                'user_agent' => substr($request->userAgent() ?? '', 0, 255),
                                'method' => $request->method(),
                            ],
                            'ip_address' => $request->ip(),
                        ]);

                        abort(403, 'Petición bloqueada por el Cortafuegos de Seguridad (WAF). Actividad sospechosa detectada.');
                    }
                }
            }
        } catch (\Throwable $e) {
            // Keep request flowing if database is not reachable during setup
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
        if (app()->isProduction() && $request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }


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
