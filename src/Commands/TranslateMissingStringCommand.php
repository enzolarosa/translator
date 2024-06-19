<?php

namespace enzolarosa\Translator\Commands;

use enzolarosa\Translator\Jobs\GitPush;
use enzolarosa\Translator\Translator;
use Illuminate\Console\Command;

class TranslateMissingStringCommand extends Command
{
    public $signature = 'translate:missing-string {--force}';

    protected $description = 'Translate the missing string';

    protected bool $force = false;

    public function handle(): int
    {
        $this->force = $this->option('force') ?? false;
        match (config('translator.driver')) {
            default => $this->handleDefaultDriver(),
        };

        return self::SUCCESS;
    }

    protected function handleDefaultDriver(): void
    {
        $json = config('app.locale') . '.json';
        $keys = json_decode(disk('translator')->get($json) ?? '[]', true);

        $targets = config('translator.supported_language', []);
        $count = [];
        foreach ($targets as $target) {
            if ($target == config('app.locale')) {
                continue;
            }
            $this->info(localize('translator.missing_command.target.title', [
                'target' => $target,
            ]));

            $targetKeys = json_decode(disk('translator')->get("$target.json") ?? '[]', true);
            $num = 0;

            $bar = $this->output->createProgressBar(count($keys));
            $bar->start();

            foreach ($keys as $key => $string) {
                if (array_key_exists($key, $targetKeys) && !$this->force) {
                    continue;
                }
                $translated = Translator::translate($string, $target, config('app.locale')) ?? $string;
                $targetKeys[$key] = $translated;
                $num++;
                $bar->advance();
            }
            $bar->finish();
            $this->info(localize('translator.missing_command.target.complete', [
                'target' => $target,
            ]));

            $count[] = [
                'target' => $target,
                'count' => $num,
            ];
            disk('translator')->put("$target.json", json_encode(array_unique($targetKeys)));
        }

        $this->line(localize('translator.missing_command.recap.table.title'));

        $this->table([
            'targets', 'count',
        ], $count);

        GitPush::dispatchIf(config('translator.git.autoPush'));
    }
}
