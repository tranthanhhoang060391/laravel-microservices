<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;
    protected $exchange;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'localhost'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $this->channel = $this->connection->channel();
    }

    public function exchangeDeclare(string $exchangeName, string $exchangeType): void
    {
        $this->exchange = $exchangeName;

        $this->channel->exchange_declare($exchangeName, $exchangeType, false, true, false);
    }

    public function publish(string $routingKey, array $data): void
    {
        $message = new AMQPMessage(json_encode($data));

        $this->channel->basic_publish($message, $this->exchange, $routingKey);
        $this->channel->close();
        $this->connection->close();
    }

    public function consume(string $queueName, string $routingKey, callable $callback): void
    {
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->channel->queue_bind($queueName, $this->exchange, $routingKey);
        $this->channel->basic_consume($queueName, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}
