<?php

namespace App\Jobs;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;
use App\Jobs\ProductStockUpdate;

class CustomRabbitMQ extends RabbitMQJob
{
    public function fire()
    {
        $payload = $this->payload();

        $class = ProductStockUpdate::class;
        $method = 'handle';

        ($this->instance = $this->resolve($class))->{$method}($this, $payload);

        $this->delete();
    }
}
