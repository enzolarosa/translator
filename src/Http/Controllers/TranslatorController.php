<?php

namespace enzolarosa\Translator\Http\Controllers;

use enzolarosa\Translator\Exceptions\TranslatorException;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class TranslatorController extends Controller
{
    public function index(NovaRequest $request, string $locale = 'en')
    {
        $supported = config('translator.supported_language');

        throw_if(!in_array($locale, $supported), TranslatorException::localeNotSupported($locale));

        $keys = collect(json_decode(
            disk('translator')->get("$locale.json") ?? '[]', true
        ));

        return inertia('Translator', [
            'locale'  => $locale,
            'locales' => $supported,
            'keys'    => $keys->map(fn($str, $key) => ['key' => $key, 'str' => $str])->toArray(),
        ]);
    }
}
