<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionSecurityCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Warning: Check if debug mode is enabled in production
        if (config('app.debug') && config('app.env') === 'production') {
            \Illuminate\Support\Facades\Log::critical('SECURITY WARNING: Debug mode is enabled in production!');
        }

        // Check if APP_KEY is set (prevents using default/insecure key)
        $appKey = config('app.key');
        if (empty($appKey) || strpos($appKey, 'base64:') !== 0 || strlen($appKey) < 20) {
            \Illuminate\Support\Facades\Log::critical('SECURITY WARNING: APP_KEY is not properly set!');
        }

        $response = $next($request);

        // Add additional security headers
        $response->headers->set('X-Download-Options', 'noopen'); // For IE
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none'); // Prevent Flash/Acrobat from loading content
        $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        return $response;
    }
}
