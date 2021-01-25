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
            'bigmom-auth.packages' => array_merge([[
                'name' => 'Poll',
                'description' => 'Poll/Vote',
                'routes' => [
                    [
                        'title' => 'Question list',
                        'name' => 'bigmom-poll.question.getIndex',
                        'permission' => 'poll-manage',
                    ],
                    [
                        'title' => 'Import/Export',
                        'name' => 'bigmom-poll.question.getImport',
                        'permission' => 'poll-manage',
                    ],
                    [
                        'title' => 'Example widget',
                        'name' => 'bigmom-poll.getDebug',
                        'permission' => 'poll-manage',
                    ]
                ],
                'permissions' => [
                    'poll-manage',
                ]
            ]], config('bigmom-auth.packages', []))
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
                __DIR__.'/../public' => public_path('vendor/poll'),
            ], 'public');

            $this->publishes([
                __DIR__.'/../resources/views/components/widget/' => resource_path('views/components/vendor/bigmom/poll/widget'),
            ]);
        }

        $this->loadViewComponentsAs('vendor-bigmom-poll', [
            Main::class,
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bigmom-poll');
    }
}