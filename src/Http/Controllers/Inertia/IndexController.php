<?php

namespace enzolarosa\Translator\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $locale = 'en';

        $keys = collect(json_decode(
            disk('translator')->get("$locale.json") ?? '[]', true
        ));

        return inertia('Translator', [
            'locale'  => config('app.locale'),
            'locales' => config('translator.supported_language'),
            'keys'    => $keys->toArray(),
        ]);
    }
}
