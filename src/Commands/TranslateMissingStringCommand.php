<?php

namespace enzolarosa\Translator\Commands;

use enzolarosa\Translator\Jobs\GitPush;
use enzolarosa\Translator\Models\Translator as Model;
use enzolarosa\Translator\Translator;
use Illuminate\Console\Command;

class TranslateMissingStringCommand extends Command
{
    public $signature = 'translate:missing-string';
    protected $description = 'Translate the missing string';

    public function handle(): int
    {
        match (config('translator.driver')) {
            'database' => $this->handleDatabaseDriver(),
            default => $this->handleDefaultDriver(),
        };

        return self::SUCCESS;
    }

    protected function handleDefaultDriver(): void
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
                $translated = Translator::translate($string, $target, config('translator.locale')) ?? $string;
                $targetKeys[$key] = $translated;
                $count[$target]++;
                $this->line("[$target] ===> `$string` will be: `$translated`");
            }
            disk('translator')->put("$target.json", json_encode(array_unique($targetKeys)));
        }

        if (config('translator.git.autoPush', false)) {
            GitPush::dispatch();
        }
    }

    protected function handleDatabaseDriver(): void
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
                        'translation' => Translator::translate($translator->original, $target, config('translator.locale')) ?? $translator->original,
                    ]);
                }
            });
    }
}
