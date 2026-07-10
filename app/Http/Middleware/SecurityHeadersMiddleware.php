<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
        $csp = "default-src 'self' https: data: blob:; ".
            "script-src 'self' 'unsafe-inline' https:; ".
            "style-src 'self' 'unsafe-inline' https:; ".
            "img-src 'self' https: data: blob:; ".
            "font-src 'self' https: data:; ".
            "connect-src 'self' https:; ".
            "frame-ancestors 'self'; ".
            "base-uri 'self'; ".
            "form-action 'self';";

        if (app()->environment('production')) {
            $csp .= ' upgrade-insecure-requests;';
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
