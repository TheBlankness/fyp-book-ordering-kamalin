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
     * The path to the "home" route for your application.
     *
     * This is only used as a fallback. You're using redirectByRole() for redirection.
     */
    public const HOME = '/dashboard'; // You can change this if needed

    /**
     * Redirect users based on their role after login.
     */
    public static function redirectByRole()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'support':
                return '/support-dashboard';
            case 'designer':
                return '/designer-dashboard';
            case 'planner':
                return '/planner-dashboard';
            case 'agent':
                return '/agent-dashboard';
            default:
                return self::HOME;
        }
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
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
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
