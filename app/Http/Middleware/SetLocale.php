<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\App;

class SetLocale {
    public function handle($request, Closure $next) {
        $locale = $request->header('Accept-Language');
        if ($locale && in_array($locale, ['en', 'es'])) {
            App::setLocale($locale);
        }
        return $next($request);
    }
}
