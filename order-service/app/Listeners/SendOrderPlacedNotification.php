<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class SendOrderPlacedNotification
{
    protected $connection;
    protected $channel;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'localhost'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest')
        );

        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare('order_placed', 'topic', false, true, false);
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $message = new AMQPMessage(json_encode([
            'type' => 'order_placed',
            'order' => $event->order
        ]));

        $this->channel->basic_publish($message, 'order_placed', 'product.update.stock');
    }

    /**
     * Close the connection.
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

}
