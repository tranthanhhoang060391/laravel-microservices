<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProductStockUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle($data)
    {
        $action = $data['action'];
        $products = $data['products'];

        foreach ($products as $item) {
            $product = Product::find($item['product_id']);

            if ($product) {
                if ($action === 'increase_product_stock') {
                    $product->stock += $item['quantity'];
                } elseif ($action === 'decrease_product_stock') {
                    $product->stock -= $item['quantity'];
                }

                $product->save();
            } else {
                Log::warning("Product with ID {$item['product_id']} not found");
            }
        }
    }
}
