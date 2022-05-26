<?php

namespace enzolarosa\Translator\Http\Controllers;

use enzolarosa\Translator\Http\Requests\Translator\StoreRequest;
use enzolarosa\Translator\Jobs\GitPush;
use Illuminate\Routing\Controller;

class ApiTranslatorController extends Controller
{
    public function store(StoreRequest $request)
    {
        $defaultLocale = config('translator.locale');

        $startKeys = json_decode(
            disk('translator')->get("$defaultLocale.json") ?? '[]',
            true
        );

        $targetLocale = "{$request->get('locale')}.json";

        $resultKeys = array_merge(
            $startKeys,
            collect($request->get('keys'))
                ->mapWithKeys(fn ($translate) => [$translate['key'] => $translate['str']])
                ->toArray()
        );

        disk('translator')->put($targetLocale, json_encode(array_unique($resultKeys)));

        if (config('translator.git.autoPush', false)) {
            GitPush::dispatch();
        }

        return response()->json('Updated');
    }
}
