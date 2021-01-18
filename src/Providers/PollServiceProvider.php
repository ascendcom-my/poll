<?php

namespace Bigmom\Poll\Providers;

use Bigmom\Poll\Managers\VoteManager;
use Bigmom\Poll\View\Components\Widget\Main;
use Illuminate\Support\ServiceProvider;

class PollServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'auth.guards.poll' => array_merge([
                'driver' => config('poll.guard.driver', 'session'),
                'provider' => config('poll.guard.provider', 'users'),
            ], config('auth.guards.poll', [])),
        ]);

        $this->app->singleton('vote', function ($app) {
            return new VoteManager;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/poll.php' => config_path('poll.php'),
            ]);
    
            $this->publishes([
                __DIR__.'/../stubs/PollServiceProvider.stub' => app_path('Providers/PollServiceProvider.php'),
            ]);

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/poll'),
            ], 'public');

            $this->publishes([
                __DIR__.'/../resources/views/auth' => resource_path('views/vendor/bigmom/poll/auth'),
            ]);

            $this->publishes([
                __DIR__.'/../resources/views/components/widget/' => resource_path('views/components/vendor/bigmom/poll/widget'),
            ]);
        }

        $this->loadViewComponentsAs('vendor-bigmom-poll', [
            Main::class,
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'poll');
    }
}