<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $sessionLocale = $request->hasSession() ? $request->session()->get('locale') : null;
        $locale = $request->query('locale', $sessionLocale ?? $request->getPreferredLanguage(['en', 'ar']) ?? 'en');
        if (! in_array($locale, ['en', 'ar'], true)) {
            $locale = 'en';
        } App::setLocale($locale);

        return $next($request);
    }
}
