<?php

namespace Alterindonesia\RabbitMQ\Console;

use Alterindonesia\RabbitMQ\Services\RabbitMQConsumerService;
use Illuminate\Console\Command;

class RabbitMQConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // using optional arguments
    protected $signature = 'alter:mq-consume {--routing=} {--exchange=} {--queue=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer for RabbitMQ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routing = $this->option('routing') ?? 'test';
        $exchange = $this->option('exchange') ?? 'test';
        $queue = $this->option('queue') ?? 'test';
        $rabbitMQConsumer = new RabbitMQConsumerService();
        $rabbitMQConsumer->listen($queue);

    }
}
