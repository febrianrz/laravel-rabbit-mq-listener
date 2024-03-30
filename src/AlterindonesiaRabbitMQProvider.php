<?php

namespace Alterindonesia\RabbitMQ;

use Alterindonesia\RabbitMQ\Console\RabbitMQConsumeCommand;
use Illuminate\Support\ServiceProvider;

class AlterindonesiaRabbitMQProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../config/rabbit-mq.php' => config_path('rabbit-mq.php')], 'config');
        $this->mergeConfigFrom(__DIR__.'/../config/rabbit-mq.php', 'rabbit-mq');

        if($this->app->runningInConsole()) {
            $this->commands([
                RabbitMQConsumeCommand::class
            ]);
        }
    }

    public function register()
    {

    }

}
