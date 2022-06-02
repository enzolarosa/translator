<?php

namespace enzolarosa\Translator;

use enzolarosa\Translator\Commands\TranslateMissingStringCommand;
use enzolarosa\Translator\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class TranslatorServiceProvider extends ServiceProvider
{
    public array $packageCommands = [
        TranslateMissingStringCommand::class,
    ];

    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
            $this->horizon();
            $this->filesystems();
        });

        Nova::serving(function (ServingNova $event) {
            //
        });

        $this->registerResources();
        $this->registerCommands();
        $this->publishResources();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/translator.php', 'translator');
    }

    protected function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/translator.php' => config_path('translator.php'),
        ], 'translator-config');

        $this->publishes([
            __DIR__ . '/../lang/en.json' => lang_path('vendor/translator/en.json'),
        ], 'translator-lang');
    }

    protected function registerCommands()
    {
        $this->commands($this->packageCommands);
    }

    protected function registerResources()
    {
        $this->loadJsonTranslationsFrom(lang_path('vendor/translator'));
    }

    protected function filesystems()
    {
        config([
            'filesystems.disks.translator' => [
                'driver' => 'local',
                'root' => base_path('lang/vendor/translator'),
                'throw' => false,
            ],
        ]);
    }

    protected function horizon()
    {
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
        $queue = array_merge(config("horizon.defaults") ?? [], $queue);
        config(["horizon.defaults" => $queue]);
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authorize::class], 'translator')
            ->group(__DIR__ . '/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/translator')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
