<?php

namespace enzolarosa\Translator\Http\Middleware;

use Arr;
use Closure;

class TranslatorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (
            !blank($lang = $request->header('accept-language')) &&
            blank($request->header('X-Request-Language'))
        ) {
            $lang = explode('-', $lang);
            $request->headers->set('X-Request-Language', strtolower(Arr::first($lang)));
        }

        if (!blank($lang = $request->header('X-Request-Language'))) {
            $lang = explode('-', $lang);
            $request->headers->set('X-Request-Language', strtolower(Arr::first($lang)));
        }

        if (
            !blank($lang = $request->header('X-Request-Language'))
            && in_array($lang, config('translator.supported_locale'))
        ) {
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
