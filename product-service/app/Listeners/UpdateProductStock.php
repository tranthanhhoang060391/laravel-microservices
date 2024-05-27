<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\Log;

class UpdateProductStock
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

        list($queue_name,,) = $this->channel->queue_declare("", false, true, true, false);
        $this->channel->queue_bind($queue_name, 'order_placed', 'product.update.stock');

        $this->channel->basic_consume($queue_name, '', false, true, false, false, [$this, 'processMessage']);
    }

    public function processMessage(AMQPMessage $msg)
    {
        $payload = json_decode($msg->body, true);

        $order = $payload['order'];
        $action = $payload['action'];
        $orderItems = $order['details'];

        foreach ($orderItems as $orderItem) {
            $product = Product::find($orderItem['product_id']);

            if ($product) {
                if ($action === 'increase_product_stock') {
                    $product->stock += $orderItem['quantity'];
                } elseif ($action === 'decrease_product_stock') {
                    $product->stock -= $orderItem['quantity'];
                }

                $product->save();
            } else {
                Log::warning("Product with ID {$orderItem['product_id']} not found");
            }
        }
    }

    public function listen()
    {
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
