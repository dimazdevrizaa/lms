<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use App\Observers\NotificationObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ponytail: force HTTPS in production only
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Gunakan Bootstrap 5 pagination (sesuai tampilan proyek)
        // Mencegah SVG Tailwind (w-5 h-5) tampil besar karena proyek tidak memakai Tailwind
        Paginator::defaultView('pagination::bootstrap-5');

        RateLimiter::for('parent-access', function (Request $request) {
            return [
                Limit::perMinute(5)->by($this->parentIpThrottleKey($request, 'access')),
                Limit::perMinutes(10, 15)->by($this->parentCodeThrottleKey($request, 'parent_code', 'access')),
            ];
        });

        RateLimiter::for('parent-direct', function (Request $request) {
            return [
                Limit::perMinute(10)->by($this->parentIpThrottleKey($request, 'direct')),
                Limit::perMinutes(10, 20)->by($this->parentCodeThrottleKey($request, 'code', 'direct')),
            ];
        });

        RateLimiter::for('parent-confirm', function (Request $request) {
            return [
                Limit::perMinute(5)->by($this->parentIpThrottleKey($request, 'confirm')),
                Limit::perMinutes(10, 15)->by($this->parentCodeThrottleKey($request, 'parent_code', 'confirm')),
            ];
        });

        // Register notification observer for Web Push
        Notification::observe(NotificationObserver::class);
    }

    private function parentIpThrottleKey(Request $request, string $context): string
    {
        return 'parent:' . $context . ':ip:' . $request->ip();
    }

    private function parentCodeThrottleKey(Request $request, string $field, string $context): string
    {
        $code = strtoupper(trim((string) ($request->input($field) ?? $request->route($field) ?? '')));

        if ($code === '') {
            return 'parent:' . $context . ':code:empty';
        }

        return 'parent:' . $context . ':code:' . hash('sha256', $code);
    }
}
