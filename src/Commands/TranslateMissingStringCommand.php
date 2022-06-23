<?php

namespace enzolarosa\Translator\Commands;

use enzolarosa\Translator\Models\Translator as Model;
use enzolarosa\Translator\Translator;
use Illuminate\Console\Command;

class TranslateMissingStringCommand extends Command
{
    public $signature = 'translate:missing-string';
    protected $description = 'Translate the missing string';

    public function handle(): int
    {
        $count = [];

        match (config('translator.driver')) {
            'database' => $this->handleDatabaseDriver(),
            default => $this->handleDefaultDriver(),
        };

        return self::SUCCESS;
    }

    protected function handleDefaultDriver()
    {
        $json = config('translator.locale') . '.json';
        $keys = json_decode(disk('translator')->get($json) ?? '[]', true);

        $targets = config('translator.supported_language', []);

        foreach ($targets as $target) {
            $targetKeys = json_decode(disk('translator')->get("$target.json") ?? '[]', true);
            $count[$target] = 0;
            foreach ($keys as $key => $string) {
                if (key_exists($key, $targetKeys)) {
                    continue;
                }
                $translated = Translator::translate($string, $target)->translatedText ?? $string;
                $targetKeys[$key] = $translated;
                $count[$target]++;
                $this->line("`$string` will be: `$translated` for `$target` target");
            }
            disk('translator')->put("$target.json", json_encode(array_unique($targetKeys)));
        }
    }

    protected function handleDatabaseDriver()
    {
        $keys = Model::query()
            ->where('language', config('translator.locale'))
            ->cursor()
            ->each(function (Model $translator) {
                foreach (config('translator.supported_language', []) as $target) {
                    Model::query()->firstOrCreate([
                        'language' => $target,
                        'original' => $translator->original,
                    ], [
                        'translation' => Translator::translate($translator->original, $target)->translatedText ?? $translator->original,
                    ]);
                }
            });
    }
}
