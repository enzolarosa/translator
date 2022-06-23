<?php

namespace enzolarosa\Translator;

use enzolarosa\Translator\Models\Translator as Model;
use Illuminate\Support\Facades\Http;

class Translator
{
    public static function translate(string $text, string $target)
    {
        return Http::asJson()->post('https://translate.enzolarosa.dev/translate', [
            'q' => $text,
            'source' => 'auto',
            'target' => $target,
            'format' => 'text',
        ])->object();
    }

    public static function checkMissingTranslation($key): void
    {
        match (config('translator.driver')) {
            'database' => self::handleDatabaseDriver($key),
            default => self::handleDefaultDriver($key),
        };
    }

    protected function handleDatabaseDriver($key)
    {
        Model::query()->firstOrCreate([
            'language' => config('translator.locale'),
            'original' => $key,
        ], [
            'translation' => $key,
        ]);
    }

    protected function handleDefaultDriver($key): void
    {
        $json = config('translator.locale') . '.json';
        $keys = json_decode(disk('translator')->get($json) ?? '[]', true);

        if (! isset($keys[$key])) {
            dispatch(function () use ($json, $key) {
                $keys = json_decode(disk('translator')->get($json) ?? '[]', true);
                $keys[$key] = $key;
                disk('translator')->put($json, json_encode(array_unique($keys)));
            })->onQueue('operational');
        }
    }
}
