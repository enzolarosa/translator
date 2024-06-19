<?php

namespace enzolarosa\Translator;

use enzolarosa\Translator\Commands\TranslateInstallCommand;
use enzolarosa\Translator\Commands\TranslateMissingStringCommand;
use Illuminate\Support\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    public array $packageCommands = [
        TranslateInstallCommand::class,
        TranslateMissingStringCommand::class,
    ];

    public function boot()
    {
        $this->app->booted(function () {
            $this->horizon();
            $this->filesystems();
        });

        $this->registerResources();
        $this->registerCommands();
        $this->publishResources();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/translator.php', 'translator');
    }

    protected function publishResources(): void
    {
        $this->publishes([
            __DIR__.'/../config/translator.php' => config_path('translator.php'),
        ], 'translator-config');

        $this->publishes([
            __DIR__.'/../lang/en.json' => lang_path('vendor/translator/en.json'),
        ], 'translator-lang');
    }

    protected function registerCommands(): void
    {
        $this->commands($this->packageCommands);
    }

    protected function registerResources(): void
    {
        $this->loadJsonTranslationsFrom(lang_path('vendor/translator'));
    }

    protected function filesystems(): void
    {
        config([
            'filesystems.disks.translator' => [
                'driver' => 'local',
                'root' => base_path('lang/vendor/translator'),
                'throw' => false,
            ],
        ]);
    }

    protected function horizon(): void
    {
        if (! config('translator.horizon.enabled')) {
            return;
        }

        $queue = [
            'translator' => [
                'connection' => 'redis',
                'queue' => [config('translator.horizon.queue')],
                'balance' => config('translator.horizon.balance'),
                'tries' => config('translator.horizon.tries'),
                'timeout' => config('translator.horizon.timeout'),
                'maxProcesses' => config('translator.horizon.maxProcesses'),
                'minProcesses' => config('translator.horizon.minProcesses'),
                'balanceCooldown' => config('translator.horizon.balanceCooldown'),
                'balanceMaxShift' => config('translator.horizon.balanceMaxShift'),
            ],
        ];
        $queue = array_merge(config('horizon.defaults') ?? [], $queue);
        config(['horizon.defaults' => $queue]);
    }
}
