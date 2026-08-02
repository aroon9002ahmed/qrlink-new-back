<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check query parameter e.g. ?lang=ar
        $locale = $request->query('lang');

        // 2. If not in query, check Accept-Language header
        if (!$locale) {
            $locale = $request->header('Accept-Language');
        }

        // 3. Normalize locale (e.g., 'en-US,en;q=0.9' -> 'en')
        if ($locale) {
            $locale = substr($locale, 0, 2);
        }

        // 4. Validate locale against supported options, default to 'en'
        $supportedLocales = ['en', 'ar'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en';
        }

        // 5. Set Laravel application locale
        app()->setLocale($locale);

        return $next($request);
    }
}
