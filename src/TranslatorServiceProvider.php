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
                'connection'   => 'redis',
                'queue'        => ['translator'],
                'balance'      => 'simple',
                'maxProcesses' => 1,
                'memory'       => 128,
                'tries'        => 1,
                'nice'         => 0,
            ],
        ];
        $env = config('app.env');
        $queue = array_merge(config("horizon.environments.$env"), $queue);
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
