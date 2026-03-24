<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // SECURITY: Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // SECURITY: Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // SECURITY: Enable XSS protection in browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // SECURITY: Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // SECURITY: Permissions policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // SECURITY: Content Security Policy (CSP)
        // More restrictive policy for better security
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tailwindcss.com; " .
               "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; " .
               "img-src 'self' data: https: blob:; " .
               "font-src 'self' https://cdnjs.cloudflare.com; " .
               "connect-src 'self'; " .
               "frame-ancestors 'none'; " .
               "base-uri 'self'; " .
               "form-action 'self';";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // SECURITY: Remove server identification headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        
        // SECURITY: HTTP Strict Transport Security (HSTS)
        // Forces browsers to use HTTPS for all requests
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        return $response;
    }
}
