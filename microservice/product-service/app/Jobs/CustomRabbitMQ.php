<?php

namespace App\Jobs;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob;
use App\Jobs\ProductStockUpdate;
use Illuminate\Support\Facades\Log;

class CustomRabbitMQ extends RabbitMQJob
{
    public function fire()
    {
        try {
            // Get the payload data
            $command = $this->payload()['data']['command'];
            $unserializedPayload = unserialize($command);
            $dataArray = (array) $unserializedPayload;
            $data = $dataArray['data'];

            $jobClass = $this->determineJobClass($data['type']);

            if ($jobClass) {
                $jobInstance = $this->resolve($jobClass, ['data' => $data]);
                $jobInstance->handle();
            } else {
                Log::warning("Unknown job type: {$data['type']}");
            }
            $this->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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
