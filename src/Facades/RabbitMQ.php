<?php
namespace Alterindonesia\RabbitMQ\Facades;

class RabbitMQ
{
    public static function publish($data, $exchange = "", $routingKey = ""): void
    {
        $rabbitMQ = new \Alterindonesia\RabbitMQ\Services\RabbitMQPublisherService();
        $rabbitMQ->publish(
            $data,
            $exchange,
            $routingKey
        );
    }
}
