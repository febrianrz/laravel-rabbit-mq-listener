<?php

namespace Alterindonesia\RabbitMQ\Services;

use Alterindonesia\Procurex\Facades\RabbitMQProducer;
use Alterindonesia\RabbitMQ\Contracts\IBrokerPublisher;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisherService implements IBrokerPublisher
{

    /**
     * @var AMQPStreamConnection
     */
    protected AMQPStreamConnection $connection;

    /**
     * @var AMQPChannel
     */
    protected AMQPChannel $channel;

    public function __construct ()
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

    public function test (): void
    {
        $exchange = 'test';
        $routingKey = 'test';
        $this->publish([
                'test' => 'test'
            ],
            $exchange,
            $routingKey
        );
    }

    public function publish(array $data, string $exchange = "", string $routingKey = "")
    {
        $exchange = $exchange ? $exchange : config('rabbit-mq.exchange');
        $routingKey = $routingKey ? $routingKey : config('rabbit-mq.routing_key');
        try {
            $this->channel = $this->connection->channel();

            $this->channel->exchange_declare(
                $exchange,
                AMQPExchangeType::DIRECT,
                false,
                true,
                false
            );
            $payload = [
                'data'    => $data,
            ];
            $payload = new AMQPMessage(
                json_encode($payload),
                array('content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
            );
            $this->channel->basic_publish(
                $payload,
                $exchange,
                $routingKey,
            );

            $this->channel->wait_for_pending_acks();
            // clear connection
            $this->channel->close();
            $this->connection->close();
            Log::info("RabbitMQProducer: publishTask success - ex: {$exchange} - routing: {$routingKey}".json_encode($data));
        } catch (\Exception $e){
            Log::error("RabbitMQProducer: publishTask error - ex: {$exchange} - routing: {$routingKey}");
        }
    }
}
