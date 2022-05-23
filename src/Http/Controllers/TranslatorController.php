<?php

namespace enzolarosa\Translator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TranslatorController extends Controller
{
    public function currentLocale(Request $request)
    {
        return config('app.locale');
    }

    public function availableLocales(Request $request)
    {
        return config('translator.supported_language');
    }

    public function receive(Request $request, string $locale)
    {
        $keys = collect(
            json_decode(
                disk('translator')->get("$locale.json") ?? '[]', true
            )
        );

        if ($search = $request->get('search')) {
            $keys = $keys->where($search);
        }

        return $keys->toArray();
    }

    public function write(Request $request, string $locale)
    {

    }
}
