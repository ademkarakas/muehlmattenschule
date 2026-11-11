<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Setzt strikte HTTP-Sicherheitsheader (CSP, HSTS, Clickjacking-, MIME-Sniffing- & DSGVO-Schutz).
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()'
        );
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        if ($request->isSecure() || app()->isProduction()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $this->buildContentSecurityPolicy($request));
        }

        return $response;
    }

    /**
     * Erstellt die Content-Security-Policy-Direktiven (DSGVO-konform ohne externe CDNs/Tracker).
     */
    private function buildContentSecurityPolicy(Request $request): string
    {
        $isAdminOrLivewire = $request->is('admin*') || $request->is('livewire*');
        $viteDevOrigins = is_file(public_path('hot'))
            ? ' http://localhost:5173 ws://localhost:5173 http://127.0.0.1:5173 ws://127.0.0.1:5173'
            : '';

        $scriptSrc = $isAdminOrLivewire
            ? "'self' 'unsafe-inline' 'unsafe-eval'".$viteDevOrigins
            : "'self' 'unsafe-inline'".$viteDevOrigins;

        $directives = [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "style-src 'self' 'unsafe-inline'".$viteDevOrigins,
            "img-src 'self' data: blob:",
            "font-src 'self' data:",
            "connect-src 'self'".$viteDevOrigins,
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ];

        return implode('; ', $directives);
    }
}
