<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Terapkan header keamanan HTTP standar industri (OWASP Mitigation).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Perlindungan terhadap Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Perlindungan terhadap MIME Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Filter proteksi XSS pada browser lama
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Kebijakan perujuk (Referrer Policy) yang aman
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Batasi akses sensor/fitur hardware yang tidak digunakan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}

