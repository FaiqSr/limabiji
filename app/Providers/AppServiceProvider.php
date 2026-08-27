<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (
            app()->environment('production') ||
            str_starts_with((string) config('app.url'), 'https://') ||
            request()->header('X-Forwarded-Proto') === 'https'
        ) {
            URL::forceScheme('https');
        }

        View::composer('components.admin.sidebar', function ($view) {
            $view->with([
                'sidebarPendingCount' => Article::where('status', 'pending')->count(),
                'sidebarUnreadCount' => ContactMessage::where('is_read', false)->count(),
            ]);
        });
    }
}
