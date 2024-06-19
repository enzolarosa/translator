<?php

namespace enzolarosa\Translator;

use Aws\Exception\AwsException;
use Aws\Translate\TranslateClient;
use enzolarosa\Translator\Jobs\GitPush;

class Translator
{
    public static function translate(string $text, string $target, string $current = 'en'): ?string
    {
        if (strlen($text) == 0) {
            return $text;
        }

        $client = new TranslateClient([
            'credentials' => [
                'key' => config('translator.aws.key'),
                'secret' => config('translator.aws.secret'),
            ],
            'region' => config('translator.aws.region'),
            'version' => 'latest',
        ]);

        try {
            $result = $client->translateText([
                'SourceLanguageCode' => $current,
                'TargetLanguageCode' => $target,
                'Text' => $text,
            ]);

            return $result['TranslatedText'];
        } catch (AwsException $e) {
            report($e);

            return null;
        }
    }

    public static function checkMissingTranslation($key): void
    {
        self::handleDefaultDriver($key);
        /*
        match (config('translator.driver')) {
            default => self::handleDefaultDriver($key),
        };
        */
    }

    protected static function handleDefaultDriver($key): void
    {
        $json = config('app.locale').'.json';
        $keys = json_decode(disk('translator')->get($json) ?? '[]', true);

        if (! isset($keys[$key])) {
            $job = function () use ($json, $key) {
                $keys = json_decode(disk('translator')->get($json) ?? '[]', true);
                $keys[$key] = $key;
                disk('translator')->put($json, json_encode(array_unique($keys)));
                GitPush::dispatchIf(config('translator.git.autoPush'));
            };
            if (config('translator.horizon.enabled')) {
                dispatch($job)->onQueue(config('translator.horizon.queue', 'default'));
            } else {
                dispatch_sync($job);
            }
        }
    }
}
