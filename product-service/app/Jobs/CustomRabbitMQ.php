<?php

namespace App\Jobs;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;
use Illuminate\Support\Facades\Log;

class CustomRabbitMQ extends RabbitMQJob
{
    public function fire()
    {
        $message = $this->getRawBody();
        $payload = json_decode($message, true);
        $jobClass = $this->determineJobClass($payload['type']);

        if ($jobClass) {
            $jobInstance = new $jobClass($payload);
            $jobInstance->handle();
        } else {
            Log::warning("Unknown job type: {$payload['type']}");
        }
    }

    protected function determineJobClass($type)
    {
        $jobClasses = [
            'product.update.stock' => ProductStockUpdate::class,
        ];

        return $jobClasses[$type] ?? null;
    }
}
