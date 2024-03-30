<?php

namespace Alterindonesia\RabbitMQ\Contracts;

interface IBrokerPublisher
{
    public function publish(array $data, string $exchange="", string $routingKey="");
}
