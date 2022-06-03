<?php

namespace enzolarosa\Translator\Commands;

use enzolarosa\Translator\Translator;
use Illuminate\Console\Command;

class TranslateMissingStringCommand extends Command
{
    public $signature = 'translate-missing-string';
    protected $description = 'Translate the missing string';

    public function handle(): int
    {
        $count = [];
        $json = config('translator.locale') . '.json';
        $keys = json_decode(disk('translator')->get($json) ?? '[]', true);

        foreach (config('translator.supported_language', []) as $target) {
            $targetKeys = json_decode(disk('translator')->get("$target.json") ?? '[]', true);
            $count[$target] = 0;
            foreach ($keys as $key => $string) {
                if (!blank($targetKeys[$key])) {
                    continue;
                }
                $targetKeys[$key] = Translator::translate($string, $target)->translatedText ?? $string;
                $count[$target]++;
            }
            disk('translator')->put("$target.json", json_encode(array_unique($keys)));
        }

        return self::SUCCESS;
    }
}
