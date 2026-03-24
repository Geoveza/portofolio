<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     * SECURITY FIX: Added rate limiting for login and contact form
     */
    protected function configureRateLimiting(): void
    {
        // API rate limiting
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // SECURITY: Login attempts rate limiting (5 attempts per minute per IP)
        RateLimiter::for('login', function (Request $request) {
            $key = $request->ip() . ':' . $request->input('email', '');
            return Limit::perMinute(5)->by($key)->response(function () {
                return redirect()->back()->withErrors([
                    'email' => 'Too many login attempts. Please try again in 1 minute.',
                ]);
            });
        });

        // SECURITY: Contact form rate limiting (3 submissions per minute per IP)
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return redirect()->back()->withErrors([
                    'message' => 'Too many messages sent. Please try again later.',
                ]);
            });
        });

        // SECURITY: Admin panel rate limiting (30 requests per minute)
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });
    }
}
