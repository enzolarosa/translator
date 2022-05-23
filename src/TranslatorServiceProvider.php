<?php

namespace enzolarosa\Translator;

use enzolarosa\Translator\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class TranslatorServiceProvider extends ServiceProvider
{
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
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/translator.php',
            'translator'
        );
    }

    protected function filesystems()
    {
        config([
            'filesystems.disks.translator' => [
                'driver' => 'local',
                'root'   => base_path('lang/vendor/translator'),
                'throw'  => false,
            ],
        ]);
    }

    protected function horizon()
    {
        $queue = [
            'translator' => [
                'connection'      => 'redis',
                'queue'           => [config('translator.horizon.queue')],
                'balance'         => config('translator.horizon.balance'),
                'tries'           => config('translator.horizon.tries'),
                'timeout'         => config('translator.horizon.timeout'),
                'maxProcesses'    => config('translator.horizon.maxProcesses'),
                'minProcesses'    => config('translator.horizon.minProcesses'),
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
