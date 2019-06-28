<?php

namespace Sdrockdev\Newsletters;

use Illuminate\Support\ServiceProvider;

class NewsletterFactoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/newsletters.php', 'newsletters');

        $this->publishes([
            __DIR__.'/../config/newsletters.php' => config_path('newsletters.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(NewsletterFactory::class, function () {
            return new NewsletterFactory(config('newsletters'));
        });

        $this->app->alias(NewsletterFactory::class, 'newsletters');
    }
}
