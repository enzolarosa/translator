<?php

use enzolarosa\Translator\Translator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

if (!function_exists('disk')) {
    function disk(?string $name = null): Filesystem
    {
        return Storage::disk($name);
    }
}

if (!function_exists('localize')) {
    function localize($key = null, $replace = [], $locale = null)
    {
        Translator::checkMissingTranslation($key);
        return trans($key, $replace, $locale);
    }
}
