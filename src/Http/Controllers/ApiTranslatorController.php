<?php

namespace enzolarosa\Translator\Http\Controllers;

use enzolarosa\Translator\Http\Requests\Translator\StoreRequest;
use Illuminate\Routing\Controller;

class ApiTranslatorController extends Controller
{
    public function store(StoreRequest $request)
    {
        $defaultLocale = config('app.locale');

        $startKeys = json_decode(
            disk('translator')->get("$defaultLocale.json") ?? '[]', true
        );

        $targetLocale = "{$request->get('locale')}.json";

        $resultKeys = array_merge(
            $startKeys,
            collect($request->get('keys'))
                ->mapWithKeys(fn($translate) => [$translate['key'] => $translate['str']])
                ->toArray()
        );

        disk('translator')->put($targetLocale, json_encode(array_unique($resultKeys)));

        return response()->json('Updated');
    }
}
