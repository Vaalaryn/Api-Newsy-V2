<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;

class Locale
{
    public function handle($request, Closure $next)
    {
        $segment = $request->segment(1);

        if (!in_array($segment, config('app.locales'))) {
            $segments = $request->segments();
            $fallback = session('locale') ?: config('app.fallback_locale');
            $segments = Arr::prepend($segments, $fallback);
            return abort(404);
        }

        session(['locale' => $segment]);
        app()->setLocale($segment);
        return $next($request);
    }
}