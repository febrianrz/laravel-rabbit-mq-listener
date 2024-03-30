<?php
namespace Alterindonesia\RabbitMQ\Services;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConsumerService
{
    protected AMQPStreamConnection $connection;
    protected \PhpAmqpLib\Channel\AMQPChannel $channel;
    public function __construct()
    {
        // set connection
        try {
            $this->connection = new AMQPStreamConnection(
                config("rabbit-mq.host"),
                config("rabbit-mq.port"),
                config("rabbit-mq.user"),
                config("rabbit-mq.password"),
                config("rabbit-mq.vhost")
            );
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            throw new \Exception("Failed to connect to RabbitMQ: " . $e->getMessage());
        }
    }

    public function listen(string $queue, string $routingKey="", string $exchange=""): void
    {
        // declare the queue
//        $this->channel->queue_declare(
//            $queue,
//            false,
//            true,
//            false,
//            false
//        );

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            echo " [x] Done\n";
            $msg->ack();
        };

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
