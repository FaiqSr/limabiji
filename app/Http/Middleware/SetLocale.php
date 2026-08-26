<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority: session > cookie > browser preference > default
        $locale = session('locale')
                  ?? $request->cookie('locale')
                  ?? $this->detectBrowserLocale($request)
                  ?? config('app.fallback_locale', 'en');

        // Only allow configured locales
        $allowedLocales = config('app.available_locales', ['en', 'id']);
        if (! in_array($locale, $allowedLocales)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }

    private function detectBrowserLocale(Request $request): ?string
    {
        $lang = $request->getPreferredLanguage();
        if ($lang) {
            $primary = substr($lang, 0, 2);
            if (in_array($primary, ['en', 'id'])) {
                return $primary;
            }
        }

        return null;
    }
}
