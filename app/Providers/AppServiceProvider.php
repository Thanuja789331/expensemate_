<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Rate limiting for API routes
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () {
                    return response()->json([
                        'error'   => 'Too many requests.',
                        'message' => 'Please wait before retrying.',
                        'retry_after' => '60 seconds'
                    ], 429);
                });
        });

        // Rate limiting for login
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(function () {
                    return response()->json([
                        'error'   => 'Too many login attempts.',
                        'message' => 'Please wait 1 minute before retrying.',
                    ], 429);
                });
        });

        // Rate limiting for web routes
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(100)
                ->by($request->user()?->id ?: $request->ip());
        });
    }
}
