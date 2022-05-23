<?php

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\Storage;

if (!function_exists('disk')) {
    function disk(?string $name = null): Filesystem
    {
        return Storage::disk($name);
    }
}

if (!function_exists('localize')) {
    function localize($key = null, $replace = [], $locale = null): array|string|Translator|Application|null
    {
        $json = config('app.locale') . '.json';
        $keys = json_decode(disk('translator')->get($json) ?? '[]', true);

        if (!isset($keys[$key])) {
            dispatch(function () use ($json, $key) {
                $keys = json_decode(disk('translator')->get($json) ?? [], true);
                $keys[$key] = $key;
                disk('translator')->put($json, json_encode(array_unique($keys)));
            })->onQueue('translator');
        }

        return trans($key, $replace, $locale);
    }
}
