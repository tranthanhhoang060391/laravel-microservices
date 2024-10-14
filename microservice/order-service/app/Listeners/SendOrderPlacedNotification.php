<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\RabbitMQService;

class SendOrderPlacedNotification
{
    protected $rabbitMQService;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->rabbitMQService = new RabbitMQService();
        $this->rabbitMQService->exchangeDeclare('inter_service_communication', 'topic');
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $this->rabbitMQService->publish('product.update.stock', [
            'type' => 'order_placed',
            'order' => $event->order,
            'action' => 'decrease_product_stock'
        ]);
    }

    /**
     * Close the connection.
     */
    public function __destruct()
    {
        //
    }
}
