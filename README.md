# Laravel Rabbit MQ Provider

This package provides a simple way to publish and consume messages from RabbitMQ.

## Installation
composer require alterindonesia/rabbit-mq

## Configuration
Add the following to your .env file:
```
RABBITMQ_HOST=
RABBITMQ_PORT=
RABBITMQ_USER=
RABBITMQ_PASSWORD=
RABBITMQ_VHOST=
RABBITMQ_EXCHANGE=
```

## Usage
### Test Publisher
```php
$rabbit = new Alterindonesia\RabbitMQ\Services\RabbitMQPublisherService();
$rabbit->test();
```
it will send to exchange name `test` with routing key `test` and message `test`

### Publishing a message
```php
$rabbit = new Alterindonesia\RabbitMQ\Services\RabbitMQPublisherService();
$rabbit->publish(
    ['test'=>'test'], 
    'exchange_name',
    'routing_key'
);
```

### Consuming a message
```php
$listener = new Alterindonesia\RabbitMQ\Services\RabbitMQConsumerService();
$listener->listen('queue_name');
```

or with command
```php
php artisan alter:mq-consume {--routing=} {--exchange=} {--queue=}
```

## License
This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
```
