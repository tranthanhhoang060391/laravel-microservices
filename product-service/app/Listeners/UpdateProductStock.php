<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Support\Facades\Log;
use App\Services\RabbitMQService;

class UpdateProductStock
{
    protected $rabbitMQService;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->rabbitMQService = new RabbitMQService();
        $this->rabbitMQService->exchangeDeclare('inter_service_communication', 'topic');

        $this->rabbitMQService->consume('', 'product.update.stock', [$this, 'processMessage']);
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
        //
    }

    public function __destruct()
    {
        //
    }
}
