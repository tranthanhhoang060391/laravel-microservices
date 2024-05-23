<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_accounts')->insert([
            'name' => 'API Gateway',
            'service_id' => env('API_GATEWAY_SERVICE_ID'),
            'service_secret' => env('API_GATEWAY_SERVICE_SECRET'),
        ]);

        DB::table('service_accounts')->insert([
            'name' => 'Product Service',
            'service_id' => env('PRODUCT_SERVICE_ID'),
            'service_secret' => env('PRODUCT_SERVICE_SECRET'),
        ]);

        DB::table('service_accounts')->insert([
            'name' => 'Order Service',
            'service_id' => env('ORDER_SERVICE_ID'),
            'service_secret' => env('ORDER_SERVICE_SECRET'),
        ]);
    }
}
