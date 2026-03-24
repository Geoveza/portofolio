<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InputSanitization
{
    /**
     * Patterns that indicate potential SQL injection attempts
     */
    protected $sqlInjectionPatterns = [
        '/(\%27)|(\')|(\-\-)|(\%23)|(#)/i',
        '/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i',
        '/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i',
        '/((\%27)|(\'))union/i',
        '/exec(\s|\+)+(s|x)p\w+/i',
        '/UNION\s+SELECT/i',
        '/INSERT\s+INTO/i',
        '/DELETE\s+FROM/i',
        '/DROP\s+TABLE/i',
    ];

    /**
     * Patterns that indicate potential XSS attempts
     */
    protected $xssPatterns = [
        '/<script[^>]*>[\s\S]*?<\/script>/i',
        '/javascript:/i',
        '/on\w+\s*=/i',
        '/<iframe/i',
        '/<object/i',
        '/<embed/i',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for SQL injection patterns in query parameters
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                if ($this->containsSqlInjection($value)) {
                    \Illuminate\Support\Facades\Log::warning('Potential SQL injection attempt detected', [
                        'ip' => $request->ip(),
                        'url' => $request->url(),
                        'input' => substr($value, 0, 100),
                    ]);
                    abort(400, 'Invalid input detected.');
                }

                if ($this->containsXss($value)) {
                    \Illuminate\Support\Facades\Log::warning('Potential XSS attempt detected', [
                        'ip' => $request->ip(),
                        'url' => $request->url(),
                        'input' => substr($value, 0, 100),
                    ]);
                    abort(400, 'Invalid input detected.');
                }
            }
        }

        // Sanitize input
        $input = $request->all();
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                // Remove null bytes
                $value = str_replace(chr(0), '', $value);
                // Trim whitespace
                $value = trim($value);
            }
        });
        $request->merge($input);

        return $next($request);
    }

    /**
     * Check if string contains SQL injection patterns
     */
    protected function containsSqlInjection(string $value): bool
    {
        foreach ($this->sqlInjectionPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if string contains XSS patterns
     */
    protected function containsXss(string $value): bool
    {
        foreach ($this->xssPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        return false;
    }
}
