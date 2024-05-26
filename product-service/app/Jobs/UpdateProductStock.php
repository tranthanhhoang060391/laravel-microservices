<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductStockUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;

    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = $this->payload['order'];
        $orderItems = $order->details;

        foreach ($orderItems as $orderItem) {
            $product = Product::find($orderItem['product_id']);

            if ($product) {
                if ($orderItem['action'] === 'increase') {
                    $product->stock += $orderItem['quantity'];
                } elseif ($orderItem['action'] === 'decrease') {
                    $product->stock -= $orderItem['quantity'];
                }

                $product->save();
            } else {
                Log::warning("Product with ID {$orderItem['product_id']} not found");
            }
        }
    }
}
