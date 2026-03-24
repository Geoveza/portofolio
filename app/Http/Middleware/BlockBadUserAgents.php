<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBadUserAgents
{
    /**
     * List of blocked user agent patterns
     */
    protected $blockedPatterns = [
        '/sqlmap/i',
        '/nikto/i',
        '/nmap/i',
        '/masscan/i',
        '/zgrab/i',
        '/gobuster/i',
        '/dirbuster/i',
        '/burp/i',
        '/metasploit/i',
        '/wpscan/i',
        '/joomscan/i',
        '/wfuzz/i',
        '/ffuf/i',
        '/guzzlehttp/i', // PHP HTTP client often used in attacks
        '/curl\/7\./i', // Only block suspicious curl versions
        '/python-requests/i',
        '/scrapy/i',
        '/bot\/0/i',
        '/crawler/i',
    ];

    /**
     * List of blocked IP patterns (CIDR notation can be used)
     */
    protected $blockedIps = [
        // Add known malicious IPs here
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent', '');
        $ip = $request->ip();

        // Check for blocked user agents
        foreach ($this->blockedPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                \Illuminate\Support\Facades\Log::warning('Blocked suspicious user agent', [
                    'ip' => $ip,
                    'user_agent' => $userAgent,
                    'pattern' => $pattern,
                    'url' => $request->url(),
                ]);
                abort(403, 'Access denied.');
            }
        }

        // Check for blocked IPs
        if (in_array($ip, $this->blockedIps)) {
            \Illuminate\Support\Facades\Log::warning('Blocked request from blacklisted IP', [
                'ip' => $ip,
                'url' => $request->url(),
            ]);
            abort(403, 'Access denied.');
        }

        // Check for suspicious request patterns
        if ($this->isSuspiciousRequest($request)) {
            \Illuminate\Support\Facades\Log::warning('Suspicious request pattern detected', [
                'ip' => $ip,
                'url' => $request->url(),
                'user_agent' => $userAgent,
            ]);
            abort(403, 'Access denied.');
        }

        return $next($request);
    }

    /**
     * Check if request has suspicious patterns
     */
    protected function isSuspiciousRequest(Request $request): bool
    {
        // Check for common attack patterns in URL
        $url = $request->url();
        $suspiciousPatterns = [
            '/\.\./', // Directory traversal
            '/%2e%2e/', // URL encoded directory traversal
            '/\.(git|svn|env|htaccess)/i', // Sensitive file access
            '/(php|jsp|asp)\\?/i', // Script injection attempts
            '/(cmd|exec|system|passthru|shell_exec|popen|proc_open)/i', // Command injection
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }
}
