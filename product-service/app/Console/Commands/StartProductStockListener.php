<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Listeners\UpdateProductStock;

class StartProductStockListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to RabbitMQ messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting RabbitMQ listener...');
        $listener = new UpdateProductStock();
        $listener->listen();
    }
}
